@extends('layout')

@section('content')
    <h1>{{ $title }}</h1>
    <hr>
    <ul>
        @empty($users)
            <p>No se ha registrado ningún usuario</p>
        @else
            @foreach ($users as $user)
                <li>{{ $user }}</li>
            @endforeach
        @endempty
    </ul>
@endsection

@section('siderbar')
    <h2>Barra lateral Personalizada</h2>
@endsection
