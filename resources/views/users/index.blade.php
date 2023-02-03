@extends('layout')

@section('title', 'Usuarios')

@section('content')
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h1 class="pb-1">
            {{trans("users.titles.{$view}")}}
        </h1>
        <div class="form-check form-check-inline align-items-end">
            @if($view == 'index')
                <a href="{{ route('users.create') }}" class="btn btn-dark">Nuevo usuario</a>
                &nbsp;
                <a href="{{route('users.trashed')}}" class="btn btn-dark">Papelera</a>
            @else
                <a href="{{route('users.index')}}" class="btn btn-dark">Volver al listado</a>
            @endif
        </div>
    </div>

    @includeWhen($view == 'index','users._filters')

    @if ($users->isNotEmpty())

        <div class="table-responsive-lg">
            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>

                    <th scope="col">#</th>
                    <th scope="col"><a href=" {{$sortable->url('first_name')}} " class=" {{$sortable->classes('first_name')}} ">Nombre <i class="bi bi-sort-up"></i></a></th>
                    <th scope="col"><a href=" {{$sortable->url('email')}} " class="{{$sortable->classes('email')}}">Correo <i class="icon-sort"></i></a></th>
                    <th scope="col"><a href=" {{$sortable->url('created_at')}} " class="{{$sortable->classes('created_at')}}">Fechas <i class="icon-sort"></i></a></th>
                    <th scope="col" class="text-right th-actions">Acciones</th>


{{--                    <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>--}}
{{--                    <th scope="col" class="sort-desc">Nombre <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>--}}
{{--                    <th scope="col">Empresa<span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>--}}
{{--                    <th scope="col">Correo<span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>--}}
{{--                    <th scope="col">Rol<span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>--}}
{{--                    <th scope="col">Fechas<span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>--}}
{{--                    <th scope="col" class="text-right th-actions">Acciones</th>--}}
                </tr>
                </thead>
                <tbody>
                @each('users._row', $users, 'user')
                </tbody>
            </table>

            {{ $users->appends(request(['search']))->render() }}
        </div>
    @else
        <p>No hay usuarios registrados.</p>
    @endif
@endsection

@section('sidebar')
    @parent
@endsection