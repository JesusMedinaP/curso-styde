@extends('layout')

@section('title', "Crear usuario");

@section('content')

<div class="card mt-5 m-lg-5">
                <h4 class="card-header">Crear nuevo Usuario</h4>
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
                <form method="post" action="{{url('usuarios')}}">
                        {{ csrf_field() }}
                        <div class="mb-3 m-lg-3">
                            <label for="name" class="form-label">Nombre:</label>
                            <input type="text" class="form-control" name="name" id="name" placeholder="Introduce tu nombre" value="{{old('name')}}">
                        </div>
                        <div class="mb-3 m-lg-3">
                            <label for="email" class="form-label">Email:</label>
                            <input type="email" class="form-control" name="email" id="email" placeholder="Introduce tu Email" value="{{old('email')}}">
                        </div>
                        <div class="mb-3 m-lg-3" class="form-label">
                            <label for="password">Contraseña:</label>
                            <input type="password" class="form-control" name="password" id="password" placeholder="Mayor a 6 caracteres">
                        </div>
                    <div class="mb-3 m-lg-3">
                        <label for="bio" class="form-label">Bio:</label>
                        <textarea type="text" class="form-control" name="bio" id="bio" placeholder="Escribe algo sobre ti">{{old('bio')}}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="profession_id">Profesión</label>
                        <select name="profession_id" id="profession_id" class="form-control">
                            <option value="">Selecciona una profesión</option>
                            @foreach($professions as $profession)
                            <option value="{{$profession->id}}"{{old('profession_id') == $profession->id ? ' selected' : ''}}>
                                {{$profession->title}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 m-lg-3">
                        <label for="twitter" class="form-label">Twitter:</label>
                        <input type="url" class="form-control" name="twitter" id="twitter" placeholder="https://twitter.com/" value="{{old('twitter')}}">
                    </div>

                    <h5>Habilidades</h5>
                    @foreach($skills as $skill)
                        <div class="form-check form-check-inline">
                            <input name="skills[{{$skill->id}}]"
                                   class="form-check-input"
                                   type="checkbox"
                                   id="skill_{{$skill->id}}"
                                   value="{{$skill->id}}"
                                    {{ old("skills.$skill->id") ? 'checked' : ''}}>
                            <label class="form-check-label" for="skill_{{$skill->id}}">{{$skill->name}}</label>
                        </div>
                    @endforeach

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary m-lg-3">Crear Usuario</button>
                        <a href="{{route('users.index')}}" class="btn btn-light m-lg-3">Volver al listado de usuarios</a>
                    </div>
                </form>
         </div>
</div>

@endsection
