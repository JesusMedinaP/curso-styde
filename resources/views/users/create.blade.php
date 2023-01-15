@extends('layout')

@section('title', "Crear usuario");

@section('content')
        <h1>Crear Usuario</h1>

        <form method="post" action="{{url('usuarios/crear')}}">
                {!! csrf_field() !!}
                <button type="submit">Crear usuario</button>
        </form>
        <p>
                <a href="{{route('users')}}">Volver al listado</a>
        </p>
@endsection
