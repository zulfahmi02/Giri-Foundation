<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:5'],
            'payment_method' => ['required', 'string', 'max:100'],
            'payment_channel' => ['nullable', 'string', 'max:100'],
            'message' => ['nullable', 'string', 'max:1000'],
            'is_anonymous' => ['nullable', 'boolean'],
        ];
    }
}
