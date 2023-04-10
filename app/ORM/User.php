<?php

declare(strict_types=1);

namespace App\ORM;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

final class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
