<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCarRequest;
use App\Http\Requests\GetCarRequest;
use App\Http\Requests\StoreCarRequest;
use App\Http\Resources\CarCollection;
use App\Http\Resources\CarResource;
use App\ORM\Repositories\CarRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

final class CarController extends Controller
{
    public function __construct(private CarRepository $carRepository)
    {
    }

    public function index(): CarCollection
    {
        return new CarCollection($this->carRepository->findByUserId((int) auth()->id()));
    }

    public function show(GetCarRequest $request): CarResource
    {
        return new CarResource($this->carRepository->find($request->car));
    }

    public function store(StoreCarRequest $request): JsonResponse
    {
        $this->carRepository->create((int) auth()->id(), $request);

        return response()->json([], 201);
    }

    public function destroy(DeleteCarRequest $request): Response
    {
        $this->carRepository->delete($request->car);

        return response('OK', 200);
    }
}
