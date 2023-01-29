<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use Searchable;

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

    public function team(){
        return $this->belongsTo(Team::class)->withDefault();
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
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
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

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'team' => $this->team->name,

        ];
    }

    public function scopeSearch($query, $search)
    {
        if(empty($search)){
            return;
        }
        //$query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$search}%")

        $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('team', function ($query) use ($search){
                $query->where('name', 'like', "%{$search}%");
            });
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
