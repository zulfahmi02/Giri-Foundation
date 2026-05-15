<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreDonationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $phone = trim((string) $this->input('phone', ''));
        $paymentChannel = trim((string) $this->input('payment_channel', ''));
        $message = trim((string) $this->input('message', ''));

        $this->merge([
            'full_name' => trim((string) $this->input('full_name', '')),
            'email' => Str::lower(trim((string) $this->input('email', ''))),
            'phone' => $phone !== '' ? $phone : null,
            'payment_channel' => $paymentChannel !== '' ? $paymentChannel : null,
            'message' => $message !== '' ? $message : null,
            'is_anonymous' => $this->boolean('is_anonymous'),
        ]);
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
