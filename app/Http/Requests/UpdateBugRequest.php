<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBugRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'reproduction_steps' => 'sometimes|string',
            'severity' => 'sometimes|in:LOW,MEDIUM,HIGH,CRITICAL',
            'assignee_id' => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'severity.in' => 'Severity harus: LOW, MEDIUM, HIGH, atau CRITICAL',
            'assignee_id.exists' => 'User assignee tidak ditemukan',
        ];
    }
}
