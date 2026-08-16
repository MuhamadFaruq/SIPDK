<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DispositionHistory extends Model
{
    protected $fillable = [
        'disposition_id',
        'user_id',
        'action',
        'notes',
    ];

    public function disposition()
    {
        return $this->belongsTo(Disposition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
