<?php

declare(strict_types=1);

namespace App\ORM\Repositories;

use App\Http\Requests\StoreCarRequest;
use App\ORM\Car;
use Illuminate\Database\Eloquent\Collection;

final class CarRepository
{
    public function findByUserId(int $id): Collection
    {
        return Car::where('user_id', '=', $id)->get();
    }

    public function find(string $uuid): ?Car
    {
        return Car::where('uuid', $uuid)->first();
    }

    public function create(int $userId, StoreCarRequest $request): void
    {
        $car = new Car();
        $car->fill($request->toArray());

        $car->user_id = $userId;
        $car->save();
    }

    public function delete(string $uuid): void
    {
        Car::where('uuid', $uuid)->delete();
    }
}
