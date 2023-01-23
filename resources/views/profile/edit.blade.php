@extends('layout')

@section('title', "Crear usuario");

@section('content')
    @card
    @slot('header')
        Editar perfil
    @endslot
    @slot('content')
        @include('shared._errors')
        <form method="POST" action="{{url("/editar-perfil")}}">
            {{ method_field('PUT') }}

            {{csrf_field()}}

            <div class="form-group">
                <label for="name">Nombre:</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="Pedro Pérez" value="{{old('name', $user->name)}}">
            </div>

            <div class="form-group">
                <label for="email">Correo:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="pedro@example.com" value="{{old('email', $user->email)}}">
            </div>

            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea name="bio" class="form-control" id="bio">{{old('bio', $user->profile->bio)}}</textarea>
            </div>

            <div class="form-group">
                <label for="profession">Profesión:</label>
                <select name="profession_id" id="profession_id" class="form-control">
                    <option value="">Selecciona una profesión</option>
                    @foreach($professions as $profession)
                        <option value="{{$profession->id}}">{{$profession->title}}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="twitter">Twitter:</label>
                <input type="url" name="twitter" class="form-control" id="twitter" placeholder="https://twitter.com/pedroperez"
                        value="{{old('twitter', $user->profile->twitter)}}">
            </div>

            <div class="form-group mt-4">
                <button type="submit" class="btn btn-primary">Actualizar perfil</button>
            </div>
        </form>
    @endslot
    @endcard
@endsection
