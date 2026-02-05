<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relaciones
    public function courses()
    {
        return $this->belongsToMany(Course::class)
            ->withPivot('access_type', 'is_unlocked', 'unlocked_at', 'payment_id', 'access_key_id', 'progress')
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function accessKeys()
    {
        return $this->hasMany(AccessKey::class);
    }

    public function taughtCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Helpers
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isTeacher()
    {
        return $this->hasRole('profesor');
    }

    public function isStudent()
    {
        return $this->hasRole('alumno');
    }

    public function hasAccessToCourse($courseId)
    {
        return $this->courses()
            ->where('courses.id', $courseId)
            ->wherePivot('is_unlocked', true)
            ->exists();
    }
}
