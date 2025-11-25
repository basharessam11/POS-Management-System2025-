<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
       public function rules(): array
    {
         return [
            'name' => 'required|string|max:255',
            'unit_id' => 'required|exists:units,id',
             'manager_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم القسم مطلوب',
            'name.string'   => 'اسم القسم يجب أن يكون نص',
            'name.max'      => 'اسم القسم يجب ألا يزيد عن 255 حرف',
            'unit_id.required' => 'يجب اختيار مستخدم',
            'unit_id.exists'   => 'المستخدم المختار غير موجود',
        ];
    }
}
