@extends('layout')

@section('title', "Detalles de Usuario");

@section('content')
    <h1>Detalles de Usuario</h1>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>

    @endif
    <form method="POST" action="{{url("usuarios/$user-id")}}">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <label for="name">Nombre:</label>
        <input type="text" name="name" id="name" placeholder="Introduce tu nombre" value="{{old('name', $user->name)}}">
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" placeholder="Introduce tu Email" value="{{old('email', $user->email)}}">
        <br>
        <label for="password">Contraseña:</label>
        <input type="password" name="password" id="password" placeholder="Mayor a 6 caracteres">
        <br>
        <button type="submit">Actualizar Usuario</button>
    </form>
    <p>
        <a href="{{route('users.index')}}">Volver al listado de usuarios</a>
    </p>
@endsection