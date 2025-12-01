<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string|min:5',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required' => 'Komentar wajib diisi',
            'content.min' => 'Komentar minimal 5 karakter',
        ];
    }
}
