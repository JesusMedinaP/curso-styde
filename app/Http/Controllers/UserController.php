<?php

namespace App\Http\Controllers;

use App\Http\Requests\{CreateUserRequest, UpdateUserRequest};
use Illuminate\Pagination\Paginator;
use App\{Skill, Sortable, User, UserFilter, UserProfiles, Profession, UserQuery};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request, UserFilter $filters, Sortable $sortable)
    {
        // Constructor de consultas $users = DB::table('users')->get();

        // Utilizando Eloquent

        $users = User::query()
            ->with('team', 'skills', 'profile.profession')
            ->filterBy($filters, $request->only(['state', 'role', 'search', 'skills', 'from', 'to']))
            ->when(request('order'), function ($q){
                $q->orderBy(request('order'), request('direction', 'asc'));
            }, function ($q){
                $q->orderByDesc('created_at');
            })
            ->paginate();

        $users->appends($filters->valid());

//        $users = $q->paginate(15)
//            ->appends(request(['search']));
//
//        $users->load('team', 'skills');

        $sortable->setCurrentOrder(request('order'), request('direction'));

        return view('users.index', [
            'users' => $users,
            'view' => 'index',
            'skills' => Skill::orderBy('name')->get(),
            'showFilters' => true,
            'checkedSkills' => collect(request('skills')),
            'sortable' => $sortable,
        ]);
    }

    public function trashed(Sortable $sortable)
    {
        $users = User::onlyTrashed()
            ->when(request('order'), function ($q){
                $q->orderBy(request('order'), request('direction', 'asc'));
            }, function ($q){
                $q->orderByDesc('created_at');
            })
            ->paginate();

        return view('users.index', [
            'users' => $users,
            'view' => 'trashed',
            'sortable' => $sortable
        ]);

    }

    public function show(User $user)
    {
        if($user == null){
            return response()->view('errors.404',[],404);
        }
        return view('users.show', compact('user'));

    }

    public function create()
    {
        $user = new User;
        return view('users.create', compact('user'));
    }

    public function store(CreateUserRequest $request)
    {
        $request->createUser();

        /// Forma antigua sin gestor de excepciones/errores de Laravel

    //    if(empty($data['name'])){
    //        return redirect('/usuarios/nuevo')->withErrors([
    //            'name' => 'The name field is required',
    //        ]);
    //    }

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {

        $request->updateUser($user);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function trash(User $user)
    {
        $user->delete();
        $user->profile()->delete();
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::onlyTrashed()->where('id', $id)->firstOrFail();

        $user->forceDelete();

        return redirect()->route('users.trashed');
    }
}
