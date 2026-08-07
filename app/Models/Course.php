<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'title',
        'description',
        'price',
        'thumbnail',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function isExternalThumbnail(): bool
    {
        return is_string($this->thumbnail)
            && ($this->thumbnail !== '')
            && (str_starts_with($this->thumbnail, 'http://') || str_starts_with($this->thumbnail, 'https://'));
    }

    public function getThumbnailUrlAttribute(): ?string
    {
        if (! $this->thumbnail) {
            return null;
        }
        if ($this->isExternalThumbnail()) {
            return $this->normalizeExternalThumbnailUrl($this->thumbnail);
        }

        return asset('storage/'.$this->thumbnail);
    }

    private function normalizeExternalThumbnailUrl(string $url): string
    {
        $id = $this->extractGoogleDriveId($url);
        if ($id) {
            // Convierte links "compartir" de Drive a URL apta para <img>
            return "https://drive.google.com/thumbnail?id={$id}&sz=w1600";
        }

        return $url;
    }

    private function extractGoogleDriveId(string $url): ?string
    {
        if (preg_match('#drive\\.google\\.com/file/d/([^/]+)#', $url, $m)) {
            return $m[1];
        }
        if (preg_match('#drive\\.google\\.com/open\\?id=([^&]+)#', $url, $m)) {
            return $m[1];
        }
        if (preg_match('#drive\\.google\\.com/uc\\?id=([^&]+)#', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user')
            ->withTimestamps();
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}
