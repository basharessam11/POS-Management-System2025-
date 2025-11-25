<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnitRequest extends FormRequest
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
            'manager_id' => 'required|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الوحدة مطلوب',
            'name.string'   => 'اسم الوحدة يجب أن يكون نص',
            'name.max'      => 'اسم الوحدة يجب ألا يزيد عن 255 حرف',
            'manager_id.required' => 'يجب اختيار مستخدم',
            'manager_id.exists'   => 'المستخدم المختار غير موجود',
        ];
    }
}
