<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseFile extends Model
{
    protected $fillable = [
        'course_id',
        'module_id',
        'name',
        'file_path',
        'file_type',
        'mime_type',
        'file_size',
        'order',
        'is_locked',
        'description',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'order' => 'integer',
        'is_locked' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function isVideo(): bool
    {
        return $this->file_type === 'video';
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'pdf';
    }

    public function isImage(): bool
    {
        return $this->file_type === 'image';
    }
}
