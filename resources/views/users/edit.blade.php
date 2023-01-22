@extends('layout')

@section('title', "Detalles de Usuario");

@section('content')
<div class="card mt-5 m-lg-5">
    <h4 class="card-header">Detalles de Usuario</h4>
    <div class="card-body">
        @include('shared._errors')
            <form method="POST" action="{{url("usuarios/$user->id")}}">
                {{ method_field('PUT') }}
               @include('users._fields')
             <button type="submit" class="btn btn-primary m-lg-3">Actualizar Usuario</button>
             <a href="{{route('users.index')}}" class="btn btn-light m-lg-3">Volver al listado de usuarios</a>
        </form>
    </div>
</div>
@endsection