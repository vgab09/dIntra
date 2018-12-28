<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class editProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (bool)Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password' => [
                'required',
                'string',
                'min:6',
                function ($attribute, $value, $fail) {
                    if ($this->has($attribute) && !Hash::check($value, Auth::user()->password)) {
                        $fail($attribute.' is not match.');
                    }
                },
            ],
            'password' => 'required|string|confirmed|min:6'

        ];
    }
}
