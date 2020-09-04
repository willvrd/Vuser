<?php

namespace Modules\Vuser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'criteriaId' => 'required|int',
            'criteriaType' => 'required|in:user,role',
            'permissions' => 'required|array',
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
            'criteriaId.required' => 'Criteria Id is required',
            'criteriaType.required' => 'Criteria Type is required',
            'permission.required' => 'Permission is required'
        ];
    }
}
