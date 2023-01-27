{{csrf_field()}}
<form method="get" action="{{url('usuarios')}}">
                <div class="row row-filters">
                    <div class="col-12">
                        @foreach(['' => 'Todos', 'with_team' => 'Con equipo', 'without_team' => 'Sin equipo'] as $value => $text)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="team_" id="team_{{$value ?: 'all'}}"
                                   value="{{$value}}"
                                    {{$value == request('team') ? 'checked' : ''}}>
                            <label class="form-check-label" for="team_{{$value ?: 'all'}}">{{$text}}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
    <div class="row row-filters">
        <div class="col-md-6">
            <div class="form-inline form-search">
                <div class="input-group">
                    <input type="search" name="search" class="form-control form-control-sm" placeholder="Buscar...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary btn-sm"><span class="oi oi-magnifying-glass"></span></button>
                    </div>
                </div>
                &nbsp;
                {{--                        <div class="btn-group">--}}
                {{--                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                {{--                                Profesión--}}
                {{--                            </button>--}}
                {{--                            <div class="dropdown-menu">--}}
                {{--                                <a class="dropdown-item" href="#">Todos</a>--}}
                {{--                                <a class="dropdown-item" href="#">Desarrollador back-end</a>--}}
                {{--                                <a class="dropdown-item" href="#">Desarrollador front-end</a>--}}
                {{--                                <a class="dropdown-item" href="#">Diseñador web</a>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                &nbsp;
                {{--                        <div class="btn-group">--}}
                {{--                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">--}}
                {{--                                Habilidades--}}
                {{--                            </button>--}}
                {{--                            <div class="drop-menu skills-list">--}}
                {{--                                <div class="form-group form-check">--}}
                {{--                                    <input type="checkbox" class="form-check-input" id="skill1">--}}
                {{--                                    <label class="form-check-label" for="skill1">CSS</label>--}}
                {{--                                </div>--}}
                {{--                                <div class="form-group form-check">--}}
                {{--                                    <input type="checkbox" class="form-check-input" id="skill2">--}}
                {{--                                    <label class="form-check-label" for="skill2">Laravel</label>--}}
                {{--                                </div>--}}
                {{--                                <div class="form-group form-check">--}}
                {{--                                    <input type="checkbox" class="form-check-input" id="skill3">--}}
                {{--                                    <label class="form-check-label" for="skill3">Front-End</label>--}}
                {{--                                </div>--}}
                {{--                                <div class="form-group form-check">--}}
                {{--                                    <input type="checkbox" class="form-check-input" id="skill4">--}}
                {{--                                    <label class="form-check-label" for="skill4">Bases de Datos</label>--}}
                {{--                                </div>--}}
                {{--                                <div class="form-group form-check">--}}
                {{--                                    <input type="checkbox" class="form-check-input" id="skill5">--}}
                {{--                                    <label class="form-check-label" for="skill5">Javascript</label>--}}
                {{--                                </div>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                {{--                </div>--}}

                {{--                <div class="col-md-6 text-right">--}}
                {{--                    <div class="form-inline form-dates">--}}
                {{--                        <label for="date_start" class="form-label-sm">Fecha</label>&nbsp;--}}
                {{--                        <div class="input-group">--}}
                {{--                            <input type="text" class="form-control form-control-sm" name="date_start" id="date_start" placeholder="Desde">--}}
                {{--                        </div>--}}
                {{--                        <div class="input-group">--}}
                {{--                            <input type="text" class="form-control form-control-sm" name="date_end" id="date_end" placeholder="Hasta">--}}
                {{--                        </div>--}}
                {{--                        &nbsp;--}}
                {{--                        <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>--}}
                {{--                    </div>--}}
                {{--                </div>--}}
            </div>
        </div>
    </div>
</form>