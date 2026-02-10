<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    protected $fillable = [
        'course_id',
        'title',
        'order',
        'file_type', // video | pdf
        'file_path',
        'is_locked', // boolean, por defecto true
    ];

    protected $casts = [
        'order' => 'integer',
        'is_locked' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'pdf';
    }
}
