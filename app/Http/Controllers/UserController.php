<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Skill;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\UserProfiles;
use App\Profession;

class UserController extends Controller
{
    public function index()
    {
        // Constructor de consultas $users = DB::table('users')->get();

        // Utilizando Eloquent

        $users = User::all();
        $title = 'Listado de Usuarios';

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

    public function update(User $user)
    {
        $data = request()->validate([
            'name' => 'required',
            'email' =>
                ['required','email', Rule::unique('users')->ignore($user->id)
                ],
            'password' => '',
            'role' => '',
            'bio' => '',
            'profession_id' => '',
            'twitter' => '',
            'skills' => '',

        ]);

        if($data['password'] != null)
        {
            $data['password'] = bcrypt(($data['password']));
        }else{
            unset($data['password']);
        }

        $user->fill($data);
        $user->role = $data['role'];
        $user->save();

        $user->profile->update($data);

        $user->skills()->sync($data['skills'] ?? []);

        return redirect()->route('users.show', ['user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect('usuarios');
    }
}
