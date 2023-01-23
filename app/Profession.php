<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profession extends Model
{
    protected $fillable = ['title'];

    public function profiles()
    {
        return $this->hasMany(UserProfiles::class);
    }
}
