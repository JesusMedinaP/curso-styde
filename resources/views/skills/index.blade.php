@extends('layout')

@section('title')
    Profesiones
@endsection

@section('content')

    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">Listado de profesiones</h1>
    </div>

    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Título</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($skills as $skill)
            <tr>
                <th scope="row">{{$skill->id}}</th>
                <td>{{$skill->name}}</td>
                <td>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection