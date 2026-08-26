<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutgoingLetter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'agenda_number',
        'reference_number',
        'letter_date',
        'destination',
        'subject',
        'summary',
        'category_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'status',
        'degree',
        'created_by',
    ];

    protected $casts = [
        'letter_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(LetterCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
