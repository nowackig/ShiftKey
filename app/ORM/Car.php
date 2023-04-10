<?php

declare(strict_types=1);

namespace App\ORM;

use Database\Factories\CarFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Uuid;

final class Car extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'make',
        'model',
        'year',
    ];

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'uuid', 'car_uuid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(static function ($model): void {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    protected static function newFactory(): CarFactory
    {
        return CarFactory::new();
    }
}
