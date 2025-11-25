<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmpGoalRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'goal_id' => 'required|exists:goals,id',
            'score' => 'required|integer|min:0|max:100',
            'description' => 'nullable|string|max:1000',
            'years' => 'required|string|max:10', // ممكن تخليها numeric لو سنة
        ];
    }
}
