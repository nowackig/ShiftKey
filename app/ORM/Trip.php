<?php

declare(strict_types=1);

namespace App\ORM;

use Database\Factories\TripFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

final class Trip extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'date',
        'car_uuid',
        'miles',
    ];

    protected $casts = [
        'date' => 'datetime:Y-m-d\TH:i:s.u\Z',
    ];

    public function setDateAttribute(string $value): void
    {
        $this->attributes['date'] = Carbon::parse($value);
    }

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class, 'car_uuid', 'uuid');
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

    protected static function newFactory(): TripFactory
    {
        return TripFactory::new();
    }
}
