<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasAccess(['administrator','author']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:150|string',
            'body' => 'required|max:30000|string',
            'file' => 'image|mimes:jpg,jpeg,bmp,png'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'A title is required!',
            'title.unique' => 'The title has already been taken!',
            'title.max' => 'The title can be only 70 character long!',
            'body.required'  => 'A content is required!',
            'body.max'  => 'A content is required!',
            'file.mimes'  => 'Not allowed file format! Only:jpg, jpeg, bmp, png!',
        ];
    }
}
