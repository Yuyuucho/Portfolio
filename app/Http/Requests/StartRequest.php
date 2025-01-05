<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    

    /**
     * Get the validation rules that apply to the request.
     *
     *  \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gamepass' => 'required|string|max:10',
        ];
    }
}
