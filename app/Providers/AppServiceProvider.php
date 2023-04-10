<?php

declare(strict_types=1);

namespace App\Providers;

use App\Rules\CarBelongsToUser;
use App\Rules\CarExists;
use App\Rules\CarNotExists;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Validator::extend('car_belongs_to_user', static function ($attribute, $value, $parameters, $validator): bool {
            return (new CarBelongsToUser())->passes($attribute, $value);
        });

        Validator::extend('car_exists', static function ($attribute, $value, $parameters, $validator): bool {
            return (new CarExists())->passes($attribute, $value);
        });

        Validator::extend('car_not_exists', static function ($attribute, $value, $parameters, $validator): bool {
            return (new CarNotExists())->passes($attribute, $value);
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
}
