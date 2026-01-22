<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FormatsUserData
{
    /**
     * Format user photo URL
     *
     * @param string|null $photoPath
     * @return string|null
     */
    protected function formatUserPhoto(?string $photoPath): ?string
    {
        // Use helper function that handles both S3 URLs and local storage
        return getImageUrl($photoPath);
    }

    /**
     * Check if user has a photo
     *
     * @param string|null $photoPath
     * @return bool
     */
    protected function hasUserPhoto(?string $photoPath): bool
    {
        if (!$photoPath) {
            return false;
        }

        // If it's an S3 URL, assume it exists
        if (str_starts_with($photoPath, 'http://') || str_starts_with($photoPath, 'https://')) {
            return true;
        }

        // Check local storage (backward compatibility)
        return Storage::disk('public')->exists($photoPath);
    }

    /**
     * Get user initials from first and last name
     *
     * @param string $firstName
     * @param string|null $lastName
     * @return string
     */
    protected function getUserInitials(string $firstName, ?string $lastName = ''): string
    {
        return strtoupper(
            substr($firstName, 0, 1) .
            substr($lastName ?? '', 0, 1)
        );
    }

    /**
     * Format complete user data with photo and initials
     *
     * @param object $user
     * @return array
     */
    protected function formatUserData($user): array
    {
        $photoPath = $user->photo ?? null;
        $hasPhoto = $this->hasUserPhoto($photoPath);

        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'slug' => $user->slug ?? null,
            'photo' => $this->formatUserPhoto($photoPath),
            'user_has_photo' => $hasPhoto,
            'user_initials' => $this->getUserInitials($user->first_name, $user->last_name ?? ''),
            'city' => $user->city ?? null,
            'state' => $user->state ?? null,
        ];
    }
}
