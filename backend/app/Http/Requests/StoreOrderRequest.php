<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'billing_address' => 'required|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_postal_code' => ['required', 'string', 'regex:/^\d{4}$/'],
            'delivery_address' => 'required|string|max:255',
            'delivery_city' => 'required|string|max:255',
            'delivery_postal_code' => ['required', 'string', 'regex:/^\d{4}$/'],
            'phone' => ['required', 'string', 'max:20', 'regex:/^(?:\+36|06)\d{8,9}$/'],
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1'
        ];
    }
}
