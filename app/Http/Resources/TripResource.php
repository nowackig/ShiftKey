<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class TripResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->resource->uuid,
            'date' => $this->resource->date->format('m/d/Y'),
            'miles' => $this->resource->miles,
            'total' => $this->resource->total,
            'car' => [
                'uuid' => $this->resource->car_uuid,
                'make' => $this->resource->make,
                'model' => $this->resource->model,
                'year' => $this->resource->year,
            ],
        ];
    }
}
