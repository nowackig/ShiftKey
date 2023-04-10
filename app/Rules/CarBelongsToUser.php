<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

final class CarBelongsToUser implements Rule
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
        $user = Auth::user();

        if (!$user instanceof Authenticatable) {
            return false;
        }

        return $user->cars()->where('uuid', $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The car does not belong to the logged in user.';
    }
}
