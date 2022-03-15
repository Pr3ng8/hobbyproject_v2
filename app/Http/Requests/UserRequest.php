<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'first_name' => 'required|alpha|string|max:20',
                    'last_name' => 'required|alpha|string|max:20',
                    'email' => 'required|email|unique:users,email',
                    'birthdate' => 'required|date_format:"Y-m-d"',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'first_name' => 'required|alpha|string|max:20',
                    'last_name' => 'required|alpha|string|max:20',
                    'email' => 'required|email|'. Rule::unique('users')->ignore(Auth::id(), 'id'),
                    'birthdate' => 'required|date_format:"Y-m-d"',
                    'file' => 'nullable|image|mimes:jpg,jpeg,bmp,png',
                ];
            }
            default:break;
        }

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'birthdate' => '',
        ];
    }
}
