<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class AccessKey extends Model
{
    protected $fillable = [
        'course_id',
        'user_id',
        'key',
        'is_used',
        'used_at',
        'is_single_use',
        'notes',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'is_single_use' => 'boolean',
        'used_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($accessKey) {
            if (empty($accessKey->key)) {
                $accessKey->key = strtoupper(Str::random(16));
            }
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function use(): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
        ]);
    }

    public function canBeUsed(): bool
    {
        if ($this->is_single_use && $this->is_used) {
            return false;
        }
        return true;
    }
}
