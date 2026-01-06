<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Service
{
    /**
     * Upload media file to S3 with folder separation
     * Images go to 'media/images' folder, videos go to 'media/videos' folder
     * Category parameter allows subfolder organization (profile, product, service, blog, event, posts, company)
     *
     * @param UploadedFile $file
     * @param string|null $category Optional category folder (profile, product, service, blog, event, posts, company)
     * @return array Returns array with 'path', 'url', 'type', and 'folder'
     * @throws \Exception
     */
    public function uploadMedia(UploadedFile $file, ?string $category = null): array
    {
        // Determine if file is image or video
        $mimeType = $file->getMimeType();
        $isImage = str_starts_with($mimeType, 'image/');
        $isVideo = str_starts_with($mimeType, 'video/');

        if (!$isImage && !$isVideo) {
            throw new \Exception('File must be an image or video. Received: ' . $mimeType);
        }

        // Determine base folder based on file type
        $baseFolder = $isImage ? 'media/images' : 'media/videos';

        // Build the path with category if provided
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $category ? "{$baseFolder}/{$category}/{$fileName}" : "{$baseFolder}/{$fileName}";

        // Use AWS SDK directly to avoid PortableVisibilityConverter issues
        $storedPath = $this->uploadWithAwsSdk($file, $path, $mimeType);
        $path = $storedPath;
        
        // Build the URL using custom URL if set, otherwise construct from bucket/region
        $url = $this->getUrl($path);

        return [
            'path' => $path,
            'url' => $url,
            'type' => $isImage ? 'image' : 'video',
            'folder' => $baseFolder, // This will be 'media/images' or 'media/videos'
            'category' => $category,
            'mime_type' => $mimeType,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
        ];
    }

    /**
     * Delete a file from S3
     *
     * @param string $path The path to delete
     * @return bool
     */
    public function deleteMedia(string $path): bool
    {
        try {
            $s3Client = $this->getS3Client();
            $s3Client->deleteObject([
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $path,
            ]);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check if a file exists in S3
     *
     * @param string $path
     * @return bool
     */
    public function fileExists(string $path): bool
    {
        try {
            $s3Client = $this->getS3Client();
            return $s3Client->doesObjectExist(
                config('filesystems.disks.s3.bucket'),
                $path
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get URL for a file in S3
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        $bucket = config('filesystems.disks.s3.bucket');
        $region = config('filesystems.disks.s3.region');
        $customUrl = config('filesystems.disks.s3.url');
        
        if ($customUrl) {
            return rtrim($customUrl, '/') . '/' . $path;
        }
        
        return "https://{$bucket}.s3.{$region}.amazonaws.com/{$path}";
    }

    /**
     * Extract S3 path from a full S3 URL
     *
     * @param string $url
     * @return string|null
     */
    public function extractPathFromUrl(string $url): ?string
    {
        // If it's not a URL, return as is (might already be a path)
        if (!str_starts_with($url, 'http')) {
            return $url;
        }

        $bucket = config('filesystems.disks.s3.bucket');
        $region = config('filesystems.disks.s3.region');
        $customUrl = config('filesystems.disks.s3.url');

        // Try custom URL pattern
        if ($customUrl) {
            $customUrlBase = rtrim($customUrl, '/');
            if (str_starts_with($url, $customUrlBase)) {
                return str_replace($customUrlBase . '/', '', $url);
            }
        }

        // Try standard S3 URL pattern
        $s3UrlPattern = "https://{$bucket}.s3.{$region}.amazonaws.com/";
        if (str_starts_with($url, $s3UrlPattern)) {
            return str_replace($s3UrlPattern, '', $url);
        }

        // Try alternative S3 URL pattern (without region)
        $s3UrlPatternAlt = "https://{$bucket}.s3.amazonaws.com/";
        if (str_starts_with($url, $s3UrlPatternAlt)) {
            return str_replace($s3UrlPatternAlt, '', $url);
        }

        return null;
    }

    /**
     * Get S3 Client instance
     *
     * @return S3Client
     * @throws \Exception
     */
    private function getS3Client(): S3Client
    {
        // Ensure AWS SDK classes are loaded
        if (!class_exists('\Aws\S3\S3Client')) {
            // Check if vendor directory exists
            if (!file_exists(base_path('vendor/aws/aws-sdk-php'))) {
                throw new \Exception('AWS SDK is not installed. Please run: composer install');
            }
            
            // The class should be autoloaded, if not, web server needs restart
            throw new \Exception('AWS SDK classes not found. Please restart your Apache server in XAMPP Control Panel, then try again.');
        }

        $key = config('filesystems.disks.s3.key');
        $secret = config('filesystems.disks.s3.secret');
        $region = config('filesystems.disks.s3.region');

        if (empty($key) || empty($secret) || empty($region)) {
            throw new \Exception('AWS credentials not configured. Please check your .env file.');
        }

        return new S3Client([
            'version' => 'latest',
            'region' => $region,
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
        ]);
    }

    /**
     * Upload file directly using AWS SDK
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string $mimeType
     * @return string
     * @throws \Exception
     */
    private function uploadWithAwsSdk(UploadedFile $file, string $path, string $mimeType): string
    {
        $s3Client = $this->getS3Client();

        try {
            // Remove ACL if bucket doesn't support it (newer S3 buckets)
            $putObjectParams = [
                'Bucket' => config('filesystems.disks.s3.bucket'),
                'Key' => $path,
                'Body' => file_get_contents($file->getRealPath()),
                'ContentType' => $mimeType,
            ];
            
            // Only add ACL if bucket supports it (older buckets)
            // Newer buckets use bucket policies for public access instead
            // Uncomment the line below if your bucket supports ACLs
            // $putObjectParams['ACL'] = 'public-read';
            
            $s3Client->putObject($putObjectParams);

            return $path;
        } catch (AwsException $e) {
            throw new \Exception('AWS S3 upload failed: ' . $e->getMessage());
        }
    }
}

