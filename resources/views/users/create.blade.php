@extends('layout')

@section('title', "Crear usuario");

@section('content')

<div class="card mt-5 m-lg-5">
                <h4 class="card-header">Crear nuevo Usuario</h4>
                <div class="card-body">
                @include('shared._errors')
                <form method="post" action="{{url('usuarios')}}">
                        @include('users._fields')
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary m-lg-3">Crear Usuario</button>
                        <a href="{{route('users.index')}}" class="btn btn-light m-lg-3">Volver al listado de usuarios</a>
                    </div>
                </form>
         </div>
</div>

@endsection
