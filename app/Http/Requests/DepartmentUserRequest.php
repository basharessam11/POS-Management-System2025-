<?php

namespace App\Http\Requests;

use App\Models\DepartmentUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DepartmentUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // رقم السجل (في حالة التعديل)
        $departmentUserId = $this->route('department_user');

        // رقم القسم (لو موجود في الـ route مباشرة)
        $departmentId = $this->route('id');

        if (!$departmentId && $departmentUserId) {
            $departmentUser = DepartmentUser::find($departmentUserId);
            $departmentId   = $departmentUser?->department_id; // null safe
        }

        return [
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('department_users')
                    ->where(fn($query) => $query->where('department_id', $departmentId))
                    ->ignore($departmentUserId),
            ],
        ];
    }


    public function messages(): array
    {
        return [
            'user_id.required' => 'من فضلك اختر موظف.',
            'user_id.exists'   => 'الموظف غير موجود.',
            'user_id.unique'   => 'هذا الموظف مسجل بالفعل في هذا القسم.',
        ];
    }
}
