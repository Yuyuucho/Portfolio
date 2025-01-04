<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    

    /**
     * Get the validation rules that apply to the request.
     *
     * \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'roompass' => 'required|string|max:10',
        ];
    }
}
