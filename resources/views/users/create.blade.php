@extends('layout')

@section('title', "Crear usuario");

@section('content')
        <h1>Crear nuevo Usuario</h1>

        <form method="post" action="{{url('usuarios')}}">
                {{ csrf_field() }}

                <label for="name">Nombre:</label>
                <input type="text" name="name" id="name" placeholder="Introduce tu nombre">
                <br>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Introduce tu Email">
                <br>
                <label for="password">Contraseña:</label>
                <input type="password" name="password" id="password" placeholder="Mayor a 6 caracteres">
                <br>
                <button type="submit">Crear usuario</button>
        </form>
        <p>
                <a href="{{route('users.index')}}">Volver al listado de usuarios</a>
        </p>
@endsection
