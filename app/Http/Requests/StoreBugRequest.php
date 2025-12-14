<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBugRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'              => 'required|string|min:1|max:255',
            'description'        => 'required|string',
            'reproduction_steps' => 'required|string',
            'severity'           => 'required|in:LOW,MEDIUM,HIGH,CRITICAL',
            'assignee_id'        => 'nullable|integer|exists:users,id',
        ];
    }


    public function messages(): array
    {
        return [
            'title.required' => 'Judul bug wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'reproduction_steps.required' => 'Langkah reproduksi wajib diisi',
            'severity.required' => 'Severity wajib dipilih',
            'severity.in' => 'Severity harus: LOW, MEDIUM, HIGH, atau CRITICAL',
            'assignee_id.exists' => 'User assignee tidak ditemukan',
        ];
    }
}
