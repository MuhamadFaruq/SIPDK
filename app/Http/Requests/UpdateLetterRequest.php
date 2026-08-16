<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLetterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'reference_number' => 'required|string|max:255',
            'letter_date' => 'required|date',
            'received_date' => 'required|date',
            'sender' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'category_id' => 'required|exists:letter_categories,id',
            'degree' => 'required|in:Biasa,Penting,Rahasia,Sangat Segera',
            'status' => 'required|in:Baru,Dibaca,Didisposisi,Diproses,Selesai,Arsip,Pending,Ditolak',
            'letter_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }
}
