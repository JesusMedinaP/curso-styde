<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserFilter extends QueryFilter
{
    protected $aliases = [
        'date' => 'created_at',
        'login' => 'last_login_at',
    ];

    public function rules() : array
    {
        return [
            'search' => 'filled',
            'state' => 'in:active,inactive',
            'role' => 'in:admin,user',
            'skills' => 'array|exists:skills,id',
            'from' => 'date_format:d/m/Y',
            'to' => 'date_format:d/m/Y',
            'order' => 'in:first_name,email,created_at',
            'direction' => 'in:asc,desc',
            'trashed' => 'accepted'
        ];
    }

    public function search($query, $search)
    {
        //$query->where(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$search}%")

        return $query->where(function ($query) use ($search){
            $query->whereRaw('CONCAT(first_name, " ", last_name) like ?', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('team', function ($query) use ($search){
                    $query->where('name', 'like', "%{$search}%");
                });
        });
    }

    public function state($query, $state)
    {
        return $query->where('active', $state == 'active');
    }

    public function skills($query, $skills)
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

    public function from($query, $date)
    {
        $date = Carbon::createFromFormat('d/m/Y', $date);

        $query->whereDate('created_at', '>=', $date);
    }

    public function to($query, $date)
    {
        $date = Carbon::createFromFormat('d/m/Y', $date);

        $query->whereDate('created_at', '<=', $date);
    }

    public function order($query, $value)
    {
        $query->orderBy($value, $this->valid['direction'] ?? 'asc');

    }
    public function direction($query, $value)
    {

    }

    public function trashed($query, $value)
    {
        $query->onlyTrashed();
    }
}















