<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


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

    public function show($id)
    {
        $user = User::find($id);
        if($user == null){
            return response()->view('errors.404',[],404);
        }
        return view('users.show', compact('user'));

    }

    public function create()
    {
        return 'Crear nuevo usuario';
    }
}
