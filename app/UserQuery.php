<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class UserQuery extends Builder
{
    use FiltersQueries;

    public static function findByEmail($email)
    {
        return static::where(compact('email'))->first();
    }


    protected function filterRules() : array
    {
        return $rules = [
            'search' => 'filled',
            'state' => 'in:active,inactive',
            'role' => 'in:admin,user',
        ];
    }


    public function filterBySearch( $search)
    {
        //$query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$search}%")

        return $this->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhereHas('team', function ($query) use ($search){
                $query->where('name', 'like', "%{$search}%");
            });

    }

    public function filterByState($state)
    {
        return $this->where('active', $state == 'active');
    }


}