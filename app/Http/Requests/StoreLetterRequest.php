<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'agenda_number' => 'required|string|unique:letters,agenda_number',
            'reference_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
            'sender' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'category_id' => 'required|exists:letter_categories,id',
            'degree' => 'required|in:Biasa,Penting,Rahasia,Sangat Segera',
            'letter_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }
}
