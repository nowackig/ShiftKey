<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\ORM\Car;
use App\ORM\Trip;
use App\ORM\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class TripControllerTest extends TestCase
{
    use DatabaseMigrations;
    use RefreshDatabase;

    private const USER_ID = 1;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create and authenticate a new user for the test
        $this->user = User::factory()->create(['id' => self::USER_ID]);
    }

    public function test_authorize_get_all_trips_fail()
    {
        // act
        $response = $this->get('/api/trips');

        // assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_get_all_trips_return_empty_array()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        Trip::factory(3)
            ->for(User::factory()->create(['id' => self::USER_ID + 1]))
            ->create()
        ;

        // act
        $response = $this->get('/api/trips');

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment(
            ['data' => []]
        );
    }

    public function test_get_all_trips()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()->for($this->user)->create();

        $tripCollection = Trip::factory(3)
            ->for($this->user)
            ->create(['car_uuid' => $car->uuid])
        ;

        $expectedResponse = [];

        foreach ($tripCollection as $trip) {
            $expectedResponse[] = [
                'uuid' => $trip->uuid,
                'date' => $trip->date->format('m/d/Y'),
                'miles' => $trip->miles,
                'total' => $car->total,
                'car' => [
                    'uuid' => $car->uuid,
                    'make' => $car->make,
                    'model' => $car->model,
                    'year' => $car->year,
                ],
            ];
        }

        // act
        $response = $this->get('/api/trips');

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment(
            ['data' => $expectedResponse]
        );
    }

    public function test_store_unauthorize()
    {
        // act
        $response = $this->post('/api/trips');

        // assert
        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_store()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()
            ->for($this->user)
            ->create()
        ;

        $date = Carbon::now()->subDays(1);
        $miles = 123456;

        // act
        $response = $this->post(
            '/api/trips',
            [
                'date' => $date->format('Y-m-d\TH:i:s.u\Z'),
                'car_uuid' => $car->uuid,
                'miles' => $miles,
            ]
        );

        // assert
        $response->assertStatus(Response::HTTP_CREATED);

        $this->assertDatabaseHas('trips', [
            'date' => $date,
            'car_uuid' => $car->uuid,
            'miles' => $miles,
            'user_id' => self::USER_ID,
        ]);
    }

    public function test_store_fail_for_unexists_car()
    {
        // arrange
        $this->actingAs($this->user, 'api');
        $fakeCarUuid = 'aaaaaaaa-bbbb-1111-2222-1234567890ab';
        $date = Carbon::now()->subDays(1)->format('Y-m-d\TH:i:s.u\Z');

        $miles = 123456;

        // act
        $response = $this->post(
            '/api/trips',
            [
                'date' => $date,
                'car_uuid' => $fakeCarUuid,
                'miles' => $miles,
            ]
        );

        // assert
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('trips', [
            'date' => $date,
            'car_uuid' => $fakeCarUuid,
            'miles' => $miles,
            'user_id' => self::USER_ID,
        ]);
    }

    public function test_store_fail_for_car_belongs_to_different_user()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $user2 = User::factory()->create(['id' => self::USER_ID + 1]);
        $car = Car::factory()
            ->for($user2)
            ->create()
        ;

        $date = Carbon::now()->subDays(1)->format('Y-m-d\TH:i:s.u\Z');

        $miles = 123456;

        // act
        $response = $this->post(
            '/api/trips',
            [
                'date' => $date,
                'car_uuid' => $car->uuid,
                'miles' => $miles,
            ]
        );

        // assert
        $response->assertStatus(Response::HTTP_FOUND);

        $this->assertDatabaseMissing('trips', [
            'date' => $date,
            'car_uuid' => $car->uuid,
            'miles' => $miles,
            'user_id' => self::USER_ID,
        ]);
    }

    public function test_get_all_trips_after_deleting_one_car()
    {
        // arrange
        $this->actingAs($this->user, 'api');

        $car = Car::factory()->for($this->user)->create();

        $tripCollection = Trip::factory(3)
            ->for($this->user)
            ->create(['car_uuid' => $car->uuid])
        ;

        $expectedResponse = [];

        foreach ($tripCollection as $trip) {
            $expectedResponse[] = [
                'uuid' => $trip->uuid,
                'date' => $trip->date->format('m/d/Y'),
                'miles' => $trip->miles,
                'total' => $car->total,
                'car' => [
                    'uuid' => $car->uuid,
                    'make' => $car->make,
                    'model' => $car->model,
                    'year' => $car->year,
                ],
            ];
        }

        $car->delete();

        // act
        $response = $this->get('/api/trips');

        // assert
        $response->assertStatus(Response::HTTP_OK);
        $response->assertHeader('content-type', 'application/json');
        $response->assertJsonFragment(
            ['data' => $expectedResponse]
        );
    }
}
