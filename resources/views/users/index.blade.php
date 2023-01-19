@extends('layout')

@section('content')
    <br>
    <div class="container-fluid">
    <div class="d-flex justify-content-between align-items-end">
        <h1 class="pb-3">{{ $title }}</h1>
        <p>
            <a href="{{route('users.create')}}" class="btn btn-primary">Nuevo Usuario</a>
        </p>
    </div>

    @if($users->isNotEmpty())
    <table class="table m-auto">
        <thead class="table-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Email</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>

        <tbody>

        @foreach($users as $user)
        <tr>
            <th scope="row">{{$user->id}}</th>
            <td>{{$user->name}}</td>
            <td>{{$user->email}}</td>
            <td>
                <form action="{{ route('users.destroy', $user) }}" method="post">
                    {{csrf_field()}}
                    {{method_field('DELETE')}}
                    <a href="{{ route('users.show', ['id' => $user->id]) }}" class="btn btn-link"><i class="bi bi-eye-fill"></i></a>
                    <a href="{{ route('users.edit', ['id' => $user->id] )}}" class="btn btn-link"><i class="bi bi-pencil-fill"></i></a>
                    <button type="submit" class="btn btn-link"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @endforeach

        </tbody>
    </table>
    @else
        <p>No hay usuarios registrados</p>
    @endif
    </div>
@endsection

