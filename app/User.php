<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

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

    public function profession() //profession_id
    {
        return $this->belongsTo(Profession::class);
    }

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
