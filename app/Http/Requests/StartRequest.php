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
            'room.gamepass' => 'required|string|max:10',
            'room.number_of_winners' => 'required|min:1|max:99',
            'room.max_win' => 'required|min:1|max:99',
        ];
    }
}
