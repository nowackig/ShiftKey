<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\ORM\Car;
use App\ORM\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class CarControllerTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    private const USER_ID = 1;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->travelTo(date: Carbon::create(2023, 1, 1, 15, 30, 0));
        $this->freezeTime();

        // Create and authenticate a new user for the test
        $this->user = User::factory()->create(['id' => self::USER_ID]);
    }

    public function test_authorize_to_get_all_cars_should_fail()
    {
        // act
        $response = $this->get('/api/cars');

        // assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_get_all_cars_return_empty_array()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        Car::factory(3)
            ->for(User::factory()->create(['id' => self::USER_ID + 1]))
            ->create()
        ;

        // act
        $response = $this->get('/api/cars', ['content-type' => 'application/json']);

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment(
            ['data' => []]
        );
    }

    public function test_get_all_cars()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $carCollection = Car::factory(3)
            ->for($this->user)
            ->create()
        ;

        $expectedResponse = [];

        foreach ($carCollection as $car) {
            $expectedResponse[] = [
                'uuid' => $car->uuid,
                'make' => $car->make,
                'model' => $car->model,
                'year' => $car->year,
                'trip_count' => 0,
                'trip_miles' => $car->total,
            ];
        }

        // act
        $response = $this->get('/api/cars', ['content-type' => 'application/json']);

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment(
            ['data' => $expectedResponse]
        );
    }

    public function test_get_a_car_return_not_found()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        // act
        $response = $this->get('/api/cars/fake_car_uuid');

        // assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_get_a_car()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()
            ->for($this->user)
            ->create()
        ;

        $expectedResponse = [
            'uuid' => $car->uuid,
            'make' => $car->make,
            'model' => $car->model,
            'year' => $car->year,
            'trip_count' => 0,
            'trip_miles' => $car->total,
        ];

        // act
        $response = $this->get(sprintf('/api/cars/%s', $car->uuid));

        // assert
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment(
            ['data' => $expectedResponse]
        );
    }

    public function test_get_using_other_user_uuid_return_not_found()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()
            ->for(User::factory()->create(['id' => self::USER_ID + 1]))
            ->create()
        ;

        // act
        $response = $this->get(sprintf('/api/cars/%s', $car->uuid));

        // assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_store_unauthorize()
    {
        // act
        $response = $this->post('/api/cars');

        // assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_store()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        // act
        $response = $this->post(
            '/api/cars',
            [
                'year' => 2023,
                'make' => 'CarCo',
                'model' => 'Pulu',
            ]
        );

        // assert
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('cars', [
            'year' => 2023,
            'make' => 'CarCo',
            'model' => 'Pulu',
        ]);
    }

    public function test_store_the_same_twice_fail()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()
            ->for($this->user)
            ->create()
        ;

        $car->delete();

        // act
        $response = $this->post(
            '/api/cars',
            [
                'year' => $car->year,
                'make' => $car->make,
                'model' => $car->model,
            ]
        );

        // assert
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas(
            'cars',
            [
                'uuid' => $car->uuid,
                'user_id' => $this->user->id,
                'deleted_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function test_delete_unauthorize()
    {
        // act
        $response = $this->delete('/api/cars/fake-car-uuid');

        // assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_delete()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()
            ->for($this->user)
            ->create()
        ;

        // act
        $response = $this->delete('/api/cars/'.$car->uuid);

        // assert
        $response->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('cars', [
            'year' => $car->year,
            'make' => $car->make,
            'model' => $car->model,
            'deleted_at' => Carbon::now(),
        ]);
    }

    public function test_delete_other_user_uuid_fail()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()
            ->for(User::factory()->create(['id' => self::USER_ID + 1]))
            ->create()
        ;

        // act
        $response = $this->delete('/api/cars/'.$car->uuid);

        // assert
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseHas('cars', [
            'year' => $car->year,
            'make' => $car->make,
            'model' => $car->model,
            'deleted_at' => null,
        ]);
    }
}
