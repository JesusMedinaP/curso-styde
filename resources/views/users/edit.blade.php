@extends('layout')

@section('title', "Detalles de Usuario");

@section('content')
    @card
        @slot('header')
            Editar Usuario
        @endslot
        @slot('content')
            @include('shared._errors')
            <form method="POST" action="{{url("usuarios/$user->id")}}">
                {{ method_field('PUT') }}
                @render('UserFields', ['user' => $user])
                <button type="submit" class="btn btn-primary m-lg-3">Actualizar Usuario</button>
                <a href="{{route('users.index')}}" class="btn btn-light m-lg-3">Volver al listado de usuarios</a>
            </form>
        @endslot
    @endcard
@endsection