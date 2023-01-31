<?php

namespace App;

class UserFilter extends QueryFilter
{
    public function rules()
    {
        return [
            'search' => 'filled',
            'state' => 'in:active,inactive',
            'role' => 'in:admin,user',
        ];
    }

    public function filterBySearch($query, $search)
    {
        //$query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$search}%")

        return $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('team', function ($query) use ($search){
                $query->where('name', 'like', "%{$search}%");
            });

    }

    public function filterByState($query, $state)
    {
        return $query->where('active', $state == 'active');
    }
}