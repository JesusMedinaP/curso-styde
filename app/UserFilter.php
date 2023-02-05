<?php

namespace App;

use App\Rules\SortableColumn;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserFilter extends QueryFilter
{
    protected $aliases = [
        'date' => 'created_at',
        'login' => 'last_login_at',
        'name' => 'first_name',
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
            'order' => [new SortableColumn(['name', 'email', 'date'])], //in:name,email,date,name-desc,email-desc,date-desc
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
        [$column, $direction] = Sortable::info($value);

            $query->orderBy($this->getColumnName($column), $direction);

    }
    protected function getColumnName($alias)
    {
        return $this->aliases[$alias] ?? $alias;
    }

    public function trashed($query, $value)
    {
        $query->onlyTrashed();
    }
}















