<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpensesRequest extends FormRequest
{
    /**
     * تحديد إذا كان المستخدم مخولًا لإجراء هذا الطلب.
     */
    public function authorize(): bool
    {
        return true; // السماح بتنفيذ الطلب
    }

    /**
     * تحديد قواعد التحقق من صحة البيانات المدخلة.
     */
    public function rules(): array
    {
        return [
            'note' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
        ];
    }
}
