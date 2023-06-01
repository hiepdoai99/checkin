<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseRequest
{

    public function rules()
    {

        return [

            'email' => [
                'required',
                'email' => 'unique:users,email,'
            ],
            'employee_id' => 'required|min:3|unique:profiles,employee_id',
            'department_id' => 'nullable|integer',
            'password' => 'required|confirmed|min:6',
            'first_name' => 'required',
            'last_name' => 'required',
            'designation_id' => 'nullable|integer',
            'employment_status_id' => 'nullable|integer',
            'work_shift_id' => 'nullable|integer',
            'roles' => 'required',
            'gender' => 'required'
        ];
    }
}
