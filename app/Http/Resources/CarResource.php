<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class CarResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'uuid' => $this->resource->uuid,
            'make' => $this->resource->make,
            'model' => $this->resource->model,
            'year' => $this->resource->year,
            'trip_count' => $this->resource->trips->count(),
            'trip_miles' => $this->resource->total,
        ];
    }
}
