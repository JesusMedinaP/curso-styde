<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;

class UserQuery extends Builder
{

    public static function findByEmail($email)
    {
        return static::where(compact('email'))->first();
    }

    public function search( $search)
    {
        if(empty($search)){
            return $this;
        }
        //$query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$search}%")

        return $this->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('team', function ($query) use ($search){
                $query->where('name', 'like', "%{$search}%");
            });

    }

    public function byState($state)
    {
        if($state == 'active')
        {
            return $this->where('active', true);
        }
        if ($state == 'inactive')
        {
            return $this->where('active', false);
        }

        return $this;
    }

    public function byRole($role)
    {
        if(in_array($role, ['admin', 'user'])){
            return $this->where('role', $role);
        }

        return $this;
    }

}