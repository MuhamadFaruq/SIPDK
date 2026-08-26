<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForwardDispositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'instruction' => 'required|string|max:1000',
            'urgency' => 'nullable|in:Biasa,Penting,Rahasia,Sangat Segera',
            'due_date' => 'nullable|date',
        ];
    }
}
