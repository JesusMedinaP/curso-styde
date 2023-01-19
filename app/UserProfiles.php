<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserProfiles extends Model
{
    protected $fillable = ['bio', 'twitter', 'profession_id'];
}
