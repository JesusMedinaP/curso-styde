<?php

namespace App\Http\Controllers;

use App\Http\Requests\{CreateUserRequest, UpdateUserRequest};
use Illuminate\Pagination\Paginator;
use App\{Skill, User, UserProfiles, Profession};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // Constructor de consultas $users = DB::table('users')->get();

        // Utilizando Eloquent

        $users = User::query()
            ->with('team', 'skills', 'profile.profession')
            ->byState(request('state'))
            ->search(request('search'))
            ->orderByDesc('created_at')
            ->paginate();

        $users->appends(request(['search']));

//        $users = $q->paginate(15)
//            ->appends(request(['search']));
//
//        $users->load('team', 'skills');


        return view('users.index', [
            'users' => $users,
            'title' => 'Listado de Usuarios',
            'roles' => trans('users.filters.roles'),
            'skills' => Skill::orderBy('name')->get(),
            'states' => trans('users.filters.states'),
            'checkedSkills' => collect(request('skills')),
        ]);
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->paginate();
        $title = 'Listado de Usuarios Eliminados';

        return view('users.index', compact('title', 'users'));

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
