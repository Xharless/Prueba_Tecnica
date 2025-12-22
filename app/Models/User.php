<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'username', 
        'email',
        'password',
        'bio',      
        'avatar',   
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relación: Un usuario tiene muchos Links
    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    // Relación: Un usuario recibe muchos Apoyos
    public function supports(): HasMany
    {
        return $this->hasMany(Support::class)->latest(); // Los más recientes primero
    }
}