<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDispositionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'letter_id' => 'required|exists:letters,id',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'exists:users,id',
            'instruction' => 'required|string',
            'urgency' => 'required|in:Biasa,Penting,Rahasia,Sangat Segera',
            'due_date' => 'nullable|date',
        ];
    }
}
