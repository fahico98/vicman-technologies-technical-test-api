<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var list<string>
     * @author Fahibram Cárcamo
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var list<string>
     * @author Fahibram Cárcamo
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Obtiene los atributos que deben ser casteados.
     *
     * @return array<string, string>
     * @author Fahibram Cárcamo
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
