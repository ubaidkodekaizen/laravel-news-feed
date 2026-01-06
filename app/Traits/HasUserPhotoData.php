<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use App\Services\S3Service;

trait HasUserPhotoData
{
    /**
     * Add photo and initials data to a user instance
     *
     * @param \App\Models\User $user
     * @return \App\Models\User
     */
    public function addPhotoData($user)
    {
        if (!$user) {
            return $user;
        }

        $photoPath = $user->photo ?? null;

        // Check if photo exists - handle both S3 URLs and local storage
        $hasPhoto = false;
        if ($photoPath) {
            if (str_starts_with($photoPath, 'http')) {
                // S3 URL - assume it exists (we store full URLs)
                $hasPhoto = true;
            } else {
                // Local storage - check file existence
                $hasPhoto = Storage::disk('public')->exists($photoPath);
            }
        }

        // Generate initials
        $initials = strtoupper(
            substr($user->first_name ?? '', 0, 1) .
            substr($user->last_name ?? '', 0, 1)
        );

        // Add computed properties
        $user->user_has_photo = $hasPhoto;
        $user->user_initials = $initials;

        return $user;
    }

    /**
     * Add photo and initials data to a collection of users or nested structures
     *
     * @param \Illuminate\Support\Collection $items
     * @return \Illuminate\Support\Collection
     */
    public function addPhotoDataToCollection($items)
    {
        return $items->map(function ($item) {
            // Handle array structure with 'user' key
            if (is_array($item) && array_key_exists('user', $item)) {
                // Modify the user object directly
                $this->addPhotoData($item['user']);
                return $item;
            }

            // Handle direct user object
            return $this->addPhotoData($item);
        });
    }
}
