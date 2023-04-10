<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Resources\TripCollection;
use App\ORM\Repositories\TripRepository;
use Illuminate\Http\JsonResponse;

final class TripController extends Controller
{
    public function __construct(private TripRepository $tripRepository)
    {
    }

    public function index(): TripCollection
    {
        return new TripCollection($this->tripRepository->findByUserId((int) auth()->id()));
    }

    public function store(StoreTripRequest $request): JsonResponse
    {
        $this->tripRepository->create((int) auth()->id(), $request);

        return response()->json([], 201);
    }
}
