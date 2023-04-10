<?php

declare(strict_types=1);

namespace App\Rules;

use App\ORM\Car;
use Illuminate\Contracts\Validation\Rule;

final class CarExists implements Rule
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
        return Car::where('uuid', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The car does not exist.';
    }
}
