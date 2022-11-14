<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateAgentRequest extends FormRequest
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
            'data.firstName' => 'required|string|min:3|max:255',
            'data.lastName' => 'required|string|min:3|max:255',
            'data.email' => 'required|email|max:255|unique:agents,email',
            'data.phone' => 'required|string|max:15|unique:agents,phone',
            'data.address' => 'required|string|max:1000',
        ];
    }
}
