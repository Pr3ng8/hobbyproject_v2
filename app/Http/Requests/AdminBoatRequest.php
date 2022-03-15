<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminBoatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasAccess(['administrator']);
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
                    'name' => 'required|min:1|max:30|string|unique:boats,name,',
                    'limit' => 'required|numeric|min:1|max:20',
                    'file' => 'mimes:jpg,jpeg,bmp,png',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|min:1|max:30|string|unique:boats,id,'.$this->id,
                    'limit' => 'required|numeric|min:1|max:20',
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
            'name.required' => 'The Name of the Boat is required!',
            'limit.required'  => 'The Capacity of the boat is required!',
        ];
    }
}
