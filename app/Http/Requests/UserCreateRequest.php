<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'account_handle'    =>  'required|string|unique:users,account_handle', 
            'display_name'      =>  'required',
            'biography'         =>  'nullable|min:1|max:160',
            'email'             =>  'required|unique:users,email',
            'password'          =>  'required|string|min:8', 
            'confirm_password'  =>  'required',
        ];
    }
}
