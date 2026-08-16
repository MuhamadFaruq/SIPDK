<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LetterCategory extends Model
{
    protected $fillable = ['code', 'name', 'description'];

    public function letters()
    {
        return $this->hasMany(Letter::class, 'category_id');
    }
}
