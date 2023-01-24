<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserProfiles extends Model
{

    use SoftDeletes;

    protected $guarded = [];

    public function profession() //profession_id
    {
        return $this->belongsTo(Profession::class)->withDefault([
            'title' => 'Sin profesión',
        ]);
    }

}
