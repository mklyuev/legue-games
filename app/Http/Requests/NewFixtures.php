<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewFixtures extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'teams' => 'required|array|between:4,4',
            'teams.*.name' => 'required|string',
            'teams.*.attack' => 'required|integer',
            'teams.*.defence' => 'required|integer',
            'teams.*.accuracy' => 'required|integer',
            'teams.*.goalkeeper' => 'required|integer',
        ];
    }
}
