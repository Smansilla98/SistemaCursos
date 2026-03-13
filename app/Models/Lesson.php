<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lesson extends Model
{
    public const TYPE_VIDEO = 'video';
    public const TYPE_PDF = 'pdf';
    public const TYPE_DOCUMENT = 'document';
    public const TYPE_IMAGE = 'image';
    public const TYPE_LINK = 'link';

    public static function typeLabels(): array
    {
        return [
            self::TYPE_VIDEO => 'Video',
            self::TYPE_PDF => 'PDF',
            self::TYPE_DOCUMENT => 'Documento (Word, etc.)',
            self::TYPE_IMAGE => 'Imagen',
            self::TYPE_LINK => 'Enlace (URL)',
        ];
    }

    public static function fileTypes(): array
    {
        return [self::TYPE_VIDEO, self::TYPE_PDF, self::TYPE_DOCUMENT, self::TYPE_IMAGE, self::TYPE_LINK];
    }

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
        'file_type',
        'file_path',
        'is_locked',
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
        return $this->file_type === self::TYPE_VIDEO;
    }

    public function isPdf(): bool
    {
        return $this->file_type === self::TYPE_PDF;
    }

    public function isDocument(): bool
    {
        return $this->file_type === self::TYPE_DOCUMENT;
    }

    public function isImage(): bool
    {
        return $this->file_type === self::TYPE_IMAGE;
    }

    public function isLink(): bool
    {
        return $this->file_type === self::TYPE_LINK;
    }

    /** URL para que el estudiante abra el recurso (archivo en storage o enlace externo) */
    public function getResourceUrlAttribute(): string
    {
        if ($this->isLink() && $this->file_path) {
            return str_starts_with($this->file_path, 'http') ? $this->file_path : 'https://' . $this->file_path;
        }
        return $this->file_path ? asset('storage/' . $this->file_path) : '#';
    }
}
