<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['code', 'name', 'head_title'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
