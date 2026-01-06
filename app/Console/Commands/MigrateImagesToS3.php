<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Services\S3Service;
use App\Models\User;
use App\Models\Business\Product;
use App\Models\Business\Service;
use App\Models\Business\Company;
use App\Models\Content\Blog;
use App\Models\Content\Event;
use App\Models\Feed\PostMedia;
use Illuminate\Http\UploadedFile;

class MigrateImagesToS3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:migrate-to-s3 
                            {--dry-run : Run without making changes}
                            {--table= : Migrate specific table (users, products, services, companies, blogs, events, post_media)}
                            {--force : Skip confirmation prompts}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate all local storage images to S3 and update database records';

    protected $s3Service;
    protected $stats = [
        'total' => 0,
        'processed' => 0,
        'uploaded' => 0,
        'skipped' => 0,
        'errors' => 0,
        'already_s3' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->s3Service = app(S3Service::class);
        
        $this->info('🚀 Starting image migration to S3...');
        $this->newLine();

        // Check S3 configuration
        if (!$this->checkS3Config()) {
            return 1;
        }

        $isDryRun = $this->option('dry-run');
        $specificTable = $this->option('table');
        $force = $this->option('force');

        if ($isDryRun) {
            $this->warn('⚠️  DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        if (!$force && !$isDryRun) {
            if (!$this->confirm('This will upload images to S3 and update database records. Continue?')) {
                $this->info('Migration cancelled.');
                return 0;
            }
        }

        $tables = $specificTable ? [$specificTable] : [
            'users',
            'products',
            'services',
            'companies',
            'blogs',
            'events',
            'post_media',
        ];

        foreach ($tables as $table) {
            $this->migrateTable($table, $isDryRun);
        }

        $this->displaySummary();
        return 0;
    }

    protected function checkS3Config(): bool
    {
        $bucket = config('filesystems.disks.s3.bucket');
        $key = config('filesystems.disks.s3.key');
        $secret = config('filesystems.disks.s3.secret');

        if (empty($bucket) || empty($key) || empty($secret)) {
            $this->error('❌ S3 configuration is missing! Please check your .env file.');
            $this->error('Required: AWS_BUCKET, AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY');
            return false;
        }

        $this->info("✅ S3 Configuration: Bucket = {$bucket}");
        return true;
    }

    protected function migrateTable(string $table, bool $isDryRun): void
    {
        $this->info("📋 Processing table: {$table}");
        $this->line(str_repeat('-', 60));

        switch ($table) {
            case 'users':
                $this->migrateUsers($isDryRun);
                break;
            case 'products':
                $this->migrateProducts($isDryRun);
                break;
            case 'services':
                $this->migrateServices($isDryRun);
                break;
            case 'companies':
                $this->migrateCompanies($isDryRun);
                break;
            case 'blogs':
                $this->migrateBlogs($isDryRun);
                break;
            case 'events':
                $this->migrateEvents($isDryRun);
                break;
            case 'post_media':
                $this->migratePostMedia($isDryRun);
                break;
            default:
                $this->warn("⚠️  Unknown table: {$table}");
        }

        $this->newLine();
    }

    protected function migrateUsers(bool $isDryRun): void
    {
        $users = User::whereNotNull('photo')
            ->where('photo', '!=', '')
            ->get();

        $this->info("Found {$users->count()} users with photos");

        foreach ($users as $user) {
            $this->stats['total']++;
            $this->processImage(
                $user,
                'photo',
                'profile',
                'users',
                $user->id,
                $isDryRun
            );
        }
    }

    protected function migrateProducts(bool $isDryRun): void
    {
        $products = Product::whereNotNull('product_image')
            ->where('product_image', '!=', '')
            ->get();

        $this->info("Found {$products->count()} products with images");

        foreach ($products as $product) {
            $this->stats['total']++;
            $this->processImage(
                $product,
                'product_image',
                'product',
                'products',
                $product->id,
                $isDryRun
            );
        }
    }

    protected function migrateServices(bool $isDryRun): void
    {
        $services = Service::whereNotNull('service_image')
            ->where('service_image', '!=', '')
            ->get();

        $this->info("Found {$services->count()} services with images");

        foreach ($services as $service) {
            $this->stats['total']++;
            $this->processImage(
                $service,
                'service_image',
                'service',
                'services',
                $service->id,
                $isDryRun
            );
        }
    }

    protected function migrateCompanies(bool $isDryRun): void
    {
        $companies = Company::whereNotNull('company_logo')
            ->where('company_logo', '!=', '')
            ->get();

        $this->info("Found {$companies->count()} companies with logos");

        foreach ($companies as $company) {
            $this->stats['total']++;
            $this->processImage(
                $company,
                'company_logo',
                'company',
                'companies',
                $company->id,
                $isDryRun
            );
        }
    }

    protected function migrateBlogs(bool $isDryRun): void
    {
        $blogs = Blog::whereNotNull('image')
            ->where('image', '!=', '')
            ->get();

        $this->info("Found {$blogs->count()} blogs with images");

        foreach ($blogs as $blog) {
            $this->stats['total']++;
            $this->processImage(
                $blog,
                'image',
                'blog',
                'blogs',
                $blog->id,
                $isDryRun
            );
        }
    }

    protected function migrateEvents(bool $isDryRun): void
    {
        $events = Event::whereNotNull('image')
            ->where('image', '!=', '')
            ->get();

        $this->info("Found {$events->count()} events with images");

        foreach ($events as $event) {
            $this->stats['total']++;
            $this->processImage(
                $event,
                'image',
                'event',
                'events',
                $event->id,
                $isDryRun
            );
        }
    }

    protected function migratePostMedia(bool $isDryRun): void
    {
        $mediaItems = PostMedia::whereNotNull('media_path')
            ->where('media_path', '!=', '')
            ->get();

        $this->info("Found {$mediaItems->count()} post media items");

        foreach ($mediaItems as $media) {
            $this->stats['total']++;
            
            // Skip if already has S3 URL
            if ($media->media_url && (str_starts_with($media->media_url, 'http://') || str_starts_with($media->media_url, 'https://'))) {
                $this->stats['already_s3']++;
                $this->stats['skipped']++;
                $this->line("  ⏭️  [{$media->id}] Already S3 URL");
                continue;
            }

            $this->processImage(
                $media,
                'media_path',
                'posts',
                'post_media',
                $media->id,
                $isDryRun,
                'media_url' // Update this field with S3 URL
            );
        }
    }

    protected function processImage(
        $model,
        string $fieldName,
        string $category,
        string $tableName,
        int $recordId,
        bool $isDryRun,
        ?string $updateField = null
    ): void {
        $updateField = $updateField ?? $fieldName;
        $imagePath = $model->$fieldName;

        // Skip if already S3 URL
        if (str_starts_with($imagePath, 'http://') || str_starts_with($imagePath, 'https://')) {
            $this->stats['already_s3']++;
            $this->stats['skipped']++;
            $this->line("  ⏭️  [ID:{$recordId}] Already S3 URL: " . substr($imagePath, 0, 50) . '...');
            return;
        }

        // Check if file exists locally
        $localPath = $this->getLocalFilePath($imagePath);
        
        if (!$localPath) {
            $this->stats['errors']++;
            $this->stats['skipped']++;
            $this->error("  ❌ [ID:{$recordId}] Invalid path: {$imagePath}");
            return;
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($localPath)) {
            $this->stats['errors']++;
            $this->stats['skipped']++;
            $fullPath = Storage::disk('public')->path($localPath);
            $this->error("  ❌ [ID:{$recordId}] File not found: {$imagePath}");
            $this->error("      Expected at: {$fullPath}");
            return;
        }

        $fullLocalPath = Storage::disk('public')->path($localPath);
        
        // Verify the full path actually exists
        if (!file_exists($fullLocalPath)) {
            $this->stats['errors']++;
            $this->stats['skipped']++;
            $this->error("  ❌ [ID:{$recordId}] File path exists in Storage but not on filesystem: {$fullLocalPath}");
            return;
        }

        try {
            $this->stats['processed']++;

            if ($isDryRun) {
                $this->line("  🔍 [ID:{$recordId}] Would upload: {$imagePath} → S3 ({$category}/)");
                return;
            }

            // Read file contents and create a temporary file for UploadedFile
            $fileContents = file_get_contents($fullLocalPath);
            $tempPath = sys_get_temp_dir() . '/' . basename($localPath);
            file_put_contents($tempPath, $fileContents);

            // Create UploadedFile instance from local file
            $file = new UploadedFile(
                $tempPath,
                basename($localPath),
                mime_content_type($fullLocalPath),
                null,
                true
            );

            // Upload to S3
            $uploadResult = $this->s3Service->uploadMedia($file, $category);
            
            // Clean up temp file
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
            $s3Url = $uploadResult['url'];

            // Update database
            $model->$updateField = $s3Url;
            $model->save();

            $this->stats['uploaded']++;
            $this->line("  ✅ [ID:{$recordId}] Uploaded: {$imagePath} → {$s3Url}");

        } catch (\Exception $e) {
            $this->stats['errors']++;
            $this->error("  ❌ [ID:{$recordId}] Error: " . $e->getMessage());
        }
    }

    protected function getLocalFilePath(string $path): ?string
    {
        // Remove any leading slashes or storage prefixes
        $path = ltrim($path, '/');
        $path = str_replace('storage/', '', $path);
        $path = str_replace('public/', '', $path);

        // Based on your database structure, paths are already in correct format:
        // - profile_photos/xxx.jpg (for users and companies)
        // - products/xxx.jpg
        // - services/xxx.jpg
        // - blogs/xxx.png
        // - event_images/xxx.png
        // - post_media/xxx.jpg (if exists)

        // Return path as-is since they're already relative to storage/app/public/
        return $path;
    }

    protected function displaySummary(): void
    {
        $this->newLine();
        $this->info('📊 Migration Summary:');
        $this->line(str_repeat('=', 60));
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Records Found', $this->stats['total']],
                ['Processed', $this->stats['processed']],
                ['Successfully Uploaded', $this->stats['uploaded']],
                ['Already on S3', $this->stats['already_s3']],
                ['Skipped (File not found)', $this->stats['skipped'] - $this->stats['already_s3']],
                ['Errors', $this->stats['errors']],
            ]
        );

        if ($this->stats['uploaded'] > 0) {
            $this->info("✅ Successfully migrated {$this->stats['uploaded']} images to S3!");
        }

        if ($this->stats['errors'] > 0) {
            $this->warn("⚠️  {$this->stats['errors']} errors occurred during migration.");
        }
    }
}

