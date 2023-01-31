<?php

namespace App;

use Illuminate\Support\Facades\DB;

class UserFilter extends QueryFilter
{
    public function rules()
    {
        return [
            'search' => 'filled',
            'state' => 'in:active,inactive',
            'role' => 'in:admin,user',
            'skills' => 'array|exists:skills,id'
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

    public function filterBySkills($query, $skills)
    {

        $subquery = DB::table('user_skill AS s')
            ->selectRaw('COUNT(`s`.`id`)')
            ->whereRaw('`s`.`user_id` = `users`.`id`')
            ->whereIn('skill_id',$skills);

        $query->whereQuery($subquery, count($skills));


//        $query->whereHas('skills', function ($q) use ($skills){
//            $q->whereIn('skills.id', $skills)
//                ->havingRaw('COUNT(skills.id) = ?', [count($skills)]);
//        });
//
//        return $query;
    }
}