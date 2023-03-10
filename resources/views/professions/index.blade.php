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
            <th scope="col">Perfiles</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
        @foreach($professions as $profession)
            <tr>
                <th scope="row">{{$profession->id}}</th>
                <td>{{$profession->title}}</td>
                <td>{{$profession->profiles_count}}</td>
                <td>
                    @if($profession->profiles_count == 0)
                    <form action="{{ url("profesiones/{$profession->id}") }}" method="post">
                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                         <button type="submit" class="btn btn-link"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
