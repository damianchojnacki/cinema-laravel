<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'seats' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $seats_taken = $this->showing->reservations->pluck('seats')->flatten(1);

                    foreach ($value as $seat) {
                        if ($seats_taken->contains($seat)) {
                            $fail('Jedno lub więcej wybranych miejsc jest już zarezerwowane.');
                        }
                    }
                },
            ],
            'seats.*' => ['required', 'array'],
            'seats.*.0' => ['required', 'integer', 'min:0'],
            'seats.*.1' => ['required', 'integer', 'min:0'],
        ];
    }
}
