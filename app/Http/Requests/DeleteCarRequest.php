<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class DeleteCarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'car_uuid' => 'required|car_exists|car_belongs_to_user',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['car_uuid' => $this->car]);
    }
}
