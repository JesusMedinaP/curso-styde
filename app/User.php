<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        
    ];

    public function profile()
    {
        return $this->hasOne(UserProfiles::class)->withDefault();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skill');
    }

    public function updateUser($data)
    {
        DB::transaction(function () use ($data){
            $user = ([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            $user->profile->update([
                'bio' => $data['bio'],
                'twitter' => $data['twitter'],
            ]);
        });
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
