<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    /** Usuarios que completaron esta lección */
    public function completedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'lesson_user')
            ->withPivot('completed_at')
            ->withTimestamps();
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

    /** True si el recurso se guarda como URL externa (p.ej. Drive) */
    public function isExternal(): bool
    {
        return is_string($this->file_path)
            && ($this->file_path !== '')
            && (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://'));
    }

    /** URL “cruda” (storage o URL externa). Útil para descargas/abrir en nueva pestaña. */
    public function getResourceUrlAttribute(): string
    {
        if ($this->file_path && $this->isExternal()) {
            return $this->file_path;
        }
        // Compat: antes 'link' aceptaba sin esquema (ej: www...)
        if ($this->isLink() && $this->file_path) {
            return str_starts_with($this->file_path, 'http') ? $this->file_path : 'https://'.$this->file_path;
        }

        return $this->file_path ? asset('storage/'.$this->file_path) : '#';
    }

    /** Si es un link de Google Drive/Docs, lo convertimos a un embed/preview */
    public function isGoogleDrive(): bool
    {
        $url = $this->getResourceUrlAttribute();

        return str_contains($url, 'drive.google.com') || str_contains($url, 'docs.google.com');
    }

    /** URL para embeber en iframe (Drive preview / Docs preview / o URL cruda) */
    public function getEmbedUrlAttribute(): string
    {
        $url = $this->getResourceUrlAttribute();
        if (! $this->isExternal()) {
            return $url;
        }

        $id = $this->extractGoogleDriveId($url);
        if ($id) {
            // Archivos (drive): /file/d/{id}/preview
            if (str_contains($url, 'drive.google.com')) {
                return "https://drive.google.com/file/d/{$id}/preview";
            }
            // Docs/Sheets/Slides: /d/{id}/preview
            if (preg_match('#docs\\.google\\.com/(document|spreadsheets|presentation)/d/([^/]+)#', $url, $m)) {
                $type = $m[1];

                return "https://docs.google.com/{$type}/d/{$id}/preview";
            }
        }

        return $url;
    }

    /** URL directa de descarga para Drive (si aplica) */
    public function getDownloadUrlAttribute(): ?string
    {
        $url = $this->getResourceUrlAttribute();
        $id = $this->extractGoogleDriveId($url);

        return $id ? "https://drive.google.com/uc?export=download&id={$id}" : null;
    }

    private function extractGoogleDriveId(string $url): ?string
    {
        // https://drive.google.com/file/d/{id}/view
        if (preg_match('#drive\\.google\\.com/file/d/([^/]+)#', $url, $m)) {
            return $m[1];
        }
        // https://drive.google.com/open?id={id}
        if (preg_match('#drive\\.google\\.com/open\\?id=([^&]+)#', $url, $m)) {
            return $m[1];
        }
        // https://drive.google.com/uc?id={id}&export=download
        if (preg_match('#drive\\.google\\.com/uc\\?id=([^&]+)#', $url, $m)) {
            return $m[1];
        }
        // https://docs.google.com/{type}/d/{id}/edit
        if (preg_match('#docs\\.google\\.com/(document|spreadsheets|presentation)/d/([^/]+)#', $url, $m)) {
            return $m[2];
        }

        return null;
    }
}
