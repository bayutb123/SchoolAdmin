<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddInitialInventoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'room_id' => 'required|integer',
            'category' => 'required|string',
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'quantity_unit' => 'required|string',
            'status' => 'required|string',
            'last_author_id' => 'required|string',
        ];
    }
}
