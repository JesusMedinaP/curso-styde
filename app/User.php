<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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


    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new UserQuery($query);
    }


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

    public function scopeFilterBy($query, QueryFilter $filters, array $data)
    {
        return $filters->applyTo($query, $data);
    }


    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'team' => $this->team->name,

        ];
    }

    public function getNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }



    public function setStateAttribute($value)
    {
        $this->active = $value == 'active';
    }

    public function getStateAttribute()
    {
        return $this->active ? 'active' : 'inactive';
    }


}
