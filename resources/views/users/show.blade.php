@extends('layout')

@section('title', "Usuario {$user->id} ")

@section('content')
        <div class="card mt-5 m-lg-5">
                <h3 class="card-header">Usuario #{{$user->id}}</h3>
                <div class="card-body">
                        <p>Nombre del usuario: {{$user->name}}</p>
                        <p>Correo electrónico: {{$user->email}}</p>

                        <a href="{{route('users.index')}}" class="btn btn-link">Volver al listado</a>
                </div>
        </div>
@endsection
<?php
       /* <p>Bio: {{$user->profile->bio}}</p>
        @if($user->profile->twitter != null)
           <p>Twitter: {{$user->profile->twitter}}</p>
        @else
        <p>No tienes una cuenta de Twitter asociada a tu perfil</p>
        @endif*/?>