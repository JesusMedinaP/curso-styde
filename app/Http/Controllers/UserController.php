<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        if(request()->has('empty')){
            $user = [];
        }else {
            $user = [
                'Joel', 'Ellie', 'Tess', 'Tommy', 'Bill'
            ];
        }
        return view('users')->with([
            'users' => $user,
            'title' => 'Listado de Usuarios',
        ]);
    }

    public function show($id)
    {
        return "Mostrando detalle del usuario: {$id}";
    }

    public function create()
    {
        return 'Crear nuevo usuario';
    }
}
