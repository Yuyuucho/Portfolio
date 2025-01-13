<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */

    /**
     * Get the validation rules that apply to the request.
     *
     *
     */
    public function rules(): array
    {
        return [
            'room.roomname' => 'required|string|max:10',
            'room.roompass' => 'required|string|max:10',
            'room.gamepass' => 'nullable|string',
            'room.number_of_winners' => 'required|min:1|max:99',
            'room.max_win' => 'required|min:1|max:99',
        ];
    }
}
