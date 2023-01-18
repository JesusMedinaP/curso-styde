@extends('layout')

@section('title', "Detalles de Usuario");

@section('content')
<div class="card mt-5 m-lg-5">
    <h4 class="card-header">Detalles de Usuario</h4>
    <div class="card-body">
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
             <div class="mb-3 m-lg-3">
                 <label for="name" class="form-label">Nombre:</label>
                 <input type="text" class="form-control" name="name" id="name" placeholder="Introduce tu nombre" value="{{old('name', $user->name)}}">
             </div>
             <div class="mb-3 m-lg-3">
                 <label for="email" class="form-label">Email:</label>
                 <input type="email" class="form-control" name="email" id="email" placeholder="Introduce tu Email" value="{{old('email', $user->email)}}">
             </div>
             <div class="mb-3 m-lg-3" class="form-label">
                 <label for="password">Contraseña:</label>
                 <input type="password" class="form-control" name="password" id="password" placeholder="Mayor a 6 caracteres">
             </div>
             <button type="submit" class="btn btn-primary m-lg-3">Actualizar Usuario</button>
             <a href="{{route('users.index')}}" class="btn btn-light m-lg-3">Volver al listado de usuarios</a>
        </form>
    </div>
</div>
@endsection