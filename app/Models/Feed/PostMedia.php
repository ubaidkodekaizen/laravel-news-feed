<?php

namespace App\Models\Feed;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Feed\Post;

class PostMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'media_type',
        'media_path',
        'media_url',
        'thumbnail_path',
        'file_name',
        'file_size',
        'mime_type',
        'duration',
        'order',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'duration' => 'integer',
        'order' => 'integer',
    ];

    /**
     * Get the post that owns this media.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Check if media is an image.
     */
    public function isImage(): bool
    {
        return $this->media_type === 'image';
    }

    /**
     * Check if media is a video.
     */
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }
}

