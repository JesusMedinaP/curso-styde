@extends('layout')

@section('content')
    <h1>{{ $title }}</h1>
    <hr>
    <ul>
        @empty($users)
            Listado de Usuarios vacio
        @else
            @foreach ($users as $user)
                <li>{{ $user->name }} {{ $user->email }}</li>
            @endforeach
        @endempty
    </ul>
@endsection

@section('siderbar')
    <h2>Barra lateral Personalizada</h2>
@endsection
