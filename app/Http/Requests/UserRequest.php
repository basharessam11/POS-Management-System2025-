<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        // جلب المستخدم اللي بنحدّثه من الـ route
        $userId = $this->route('user') ? $this->route('user') : null;


        return [
            'name' => ['required', 'string', 'max:255'],

            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($userId),
            ],

            'country_id' => ['required', 'exists:countries,id'],

            'salary' => ['nullable', 'numeric', 'min:0'],

            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],

            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    /**
     * Customize the error messages (اختياري)
     */
    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'phone.unique' => 'رقم الهاتف مستخدم من قبل',
            'country_id.required' => 'اختر الدولة',
            'country_id.exists' => 'الدولة المختارة غير صحيحة',
            'salary.numeric' => 'الراتب يجب أن يكون رقم',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم من قبل',
            'photo.image' => 'الصورة يجب أن تكون ملف صورة',
            'photo.mimes' => 'الصورة يجب أن تكون jpg, jpeg, png',
            'photo.max' => 'حجم الصورة يجب ألا يزيد عن 2 ميجا',
        ];
    }
}
