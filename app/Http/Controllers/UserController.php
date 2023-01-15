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
        return view('users.show', compact('id'));

    }

    public function create()
    {
        return 'Crear nuevo usuario';
    }
}
