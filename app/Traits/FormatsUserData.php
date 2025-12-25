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
        if (!$photoPath) {
            return null;
        }

        $hasPhoto = Storage::disk('public')->exists('profile_photos/' . basename($photoPath));

        if (!$hasPhoto) {
            return null;
        }

        return str_starts_with($photoPath, 'http')
            ? $photoPath
            : asset('storage/profile_photos/' . basename($photoPath));
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

        return Storage::disk('public')->exists('profile_photos/' . basename($photoPath));
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
        ];
    }
}
