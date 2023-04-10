<?php

declare(strict_types=1);

namespace App\ORM\Repositories;

use App\Http\Requests\StoreTripRequest;
use App\ORM\Trip;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class TripRepository
{
    public function findByUserId(int $id): Collection
    {
        $trips = DB::table('trips')
            ->select([
                'uuid' => 'trips.uuid', 'car_uuid' => 'trips.car_uuid', 'date' => 'trips.date', 'miles' => 'trips.miles',
                'total' => 'cars.total', 'make' => 'cars.make', 'model' => 'cars.model', 'year' => 'cars.year',
            ])
            ->join('cars', 'trips.car_uuid', '=', 'cars.uuid')
            ->where('trips.user_id', $id)
            ->get()
        ;

        foreach ($trips as $trip) {
            $trip->date = Carbon::parse($trip->date);
        }

        return $trips;
    }

    public function create(int $userId, StoreTripRequest $request): void
    {
        $trip = new Trip();
        $trip->fill($request->toArray());

        $trip->user_id = $userId;

        $car = $trip->car;

        if ($car) {
            $car->total += $request->miles;
            $trip->save();
            $car->save();
        }
    }
}
