@extends('layout')

@section('content')
    <h1>{{ $title }}</h1>

    <ul>
            @forelse($users as $user)
                <li>{{ $user->name }}, ({{ $user->email }})</li>
                <a href="{{ route('users.show', ['id' => $user->id]) }}">Ver detalles</a>
            @empty
            Listado de Usuarios vacio
            @endforelse
    </ul>
@endsection

@section('siderbar')
    @parent
@endsection
