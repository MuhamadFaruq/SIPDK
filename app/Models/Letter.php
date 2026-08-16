<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letter extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'agenda_number',
        'reference_number',
        'letter_date',
        'received_date',
        'sender',
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
        'received_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(LetterCategory::class, 'category_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function dispositions()
    {
        return $this->hasMany(Disposition::class, 'letter_id');
    }

    public function latestDisposition()
    {
        return $this->hasOne(Disposition::class, 'letter_id')->latestOfMany();
    }
}
