<?php

declare(strict_types=1);

namespace App\Rules;

use App\ORM\Car;
use Illuminate\Contracts\Validation\Rule;

final class CarNotExists implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (null == request()->input('year')) {
            return true;
        }

        if (null == request()->input('model')) {
            return true;
        }

        if (null == request()->input('make')) {
            return true;
        }

        return !Car::withTrashed()
            ->where('year', request()->input('year'))
            ->where('model', request()->input('model'))
            ->where('make', request()->input('make'))->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The car exists.';
    }
}
