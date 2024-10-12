<?php

namespace App\Http\Requests;

class CreateOrderRequest extends APIRequest
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
            'product_name' => 'required|min:2|max:150',
            'quantity' => 'required|integer',
            'price' => 'required|decimal:0,8'
        ];
    }
}
