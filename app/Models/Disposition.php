<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Disposition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'letter_id',
        'parent_id',
        'sender_user_id',
        'recipient_user_id',
        'recipient_department_id',
        'instruction',
        'urgency',
        'due_date',
        'status',
        'follow_up_notes',
        'followed_up_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'followed_up_at' => 'datetime',
    ];

    public function letter()
    {
        return $this->belongsTo(Letter::class);
    }

    public function parent()
    {
        return $this->belongsTo(Disposition::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Disposition::class, 'parent_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    public function recipientDepartment()
    {
        return $this->belongsTo(Department::class, 'recipient_department_id');
    }

    public function histories()
    {
        return $this->hasMany(DispositionHistory::class);
    }
}
