<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'prodi'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
            'role' => UserRole::class,
        ];
    }

    public function isMahasiswa(): bool
    {
        return $this->role === UserRole::Mahasiswa;
    }

    public function isPMB(): bool
    {
        return $this->role === UserRole::PMB;
    }

    public function isKaprodi(): bool
    {
        return $this->role === UserRole::Kaprodi;
    }

    public function isDekan(): bool
    {
        return $this->role === UserRole::Dekan;
    }

    public function isRektor(): bool
    {
        return $this->role === UserRole::Rektor;
    }

    /**
     * Get the student details associated with the user.
     */
    public function studentDetail(): HasOne
    {
        return $this->hasOne(StudentDetail::class);
    }

    /**
     * Get the conversion request for the student.
     */
    public function conversion(): HasOne
    {
        return $this->hasOne(Conversion::class);
    }
}
