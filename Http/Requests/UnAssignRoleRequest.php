<?php

namespace Modules\Vuser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnAssignRoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'userId' => 'required|int',
            'role' => 'required|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'userId.required' => 'UserId is required',
            'role.required' => 'Role is required'
        ];
    }
}
