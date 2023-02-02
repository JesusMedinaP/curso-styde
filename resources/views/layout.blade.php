<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>@yield('title') Curso Laravel</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.css" integrity="sha256-CNwnGWPO03a1kOlAsGaH5g8P3dFaqFqqGFV/1nkX5OU=" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/css/gijgo.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->


</head>

<body>

<header>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-logo" href="/">
            <img src="" alt="Styde" width="100" height="30">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/usuarios') }}">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/profesiones') }}">Profesiones</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/habilidades') }}">Habilidades</a>
                </li>
            </ul>
        </div>
    </nav>
</header>

<!-- Begin page content -->
<main role="main" class="container navbar-nav-scroll">
    <div class="row mt-3">
        <div class="col-12">
            @yield('content')
        </div>
    </div>
</main>

<footer class="footer">
    <div class="container">
        <span class="text-muted">Esto es un texto hasta abajo</span>
    </div>
</footer>


<style>



    body {
        padding-top: 60px;
    }

    .navbar-logo {
        margin-right: 24px;
    }

    .table {
    thead {
    .th-actions {
        min-width: 120px;
    }

    th {
    .oi {
        font-size: 8px;
        display: none;
        color: $brand-info;
        margin: 0 0 0 12px;
        transform: translateY(-2px);
    }

    &.sort-asc {
    .oi-caret-top {
        display: inline-block;
    }
    }

    &.sort-desc {
    .oi-caret-bottom {
        display: inline-block;
    }
    }
    }
    }

    tbody {
    th {
        vertical-align: middle;
    }

    td {
        color: lighten($text-color, 20%);
        vertical-align: middle;
    }

    .skills {
    th,
    td {
        border: 0;
    }
    }
    }
    }

    .note {
        display: block;
        font-size: 0.75em;
        line-height: 1.25;
        font-weight: 200;
        color: mix(#fff, $text-color, 20%);
    }

    .row-filters {
        padding-bottom: 6px;
    }

    .form-dates {
        justify-content: flex-end;

    .input-group {
        margin-left: 6px;
        flex-basis: 140px;
    }
    }

    .drop-skills {
        position: relative;
        overflow: visible;

    .btn {
        position: relative;
        z-index: 0;
    }
    }

    .skills-list {
        position: absolute;
        z-index: 1;
        top: 99%;
        left: 0;
        padding: 0;
        border: 1px solid transparent;
        border-radius: 0.25rem;
        background-color: #fff;

    .form-check {
        padding: 0 24px 0 32px;
        height: 0;
        white-space: nowrap;
        overflow: hidden;
        justify-content: flex-start;
        transition: all 0.15s ease-out;
        position: relative;

    &:hover {
         background-color: #f2f2f2;
     }

    input[type="checkbox"] {
        position: absolute;
        left: 12px;
        top: 14px;
    }
    }
    }

    .drop-skills:hover {
    .skills-list {
        transform: translateY(0);
        pointer-event: all;
        opacity: 1;
        border-color: rgba(0, 0, 0, 0.125)
    }

    .form-check {
        padding: 8px 24px 8px 32px;
        height: 40px;
    }
    }

    .status {
        display: inline-block;
        margin: 0 0 0 6px;
        width: 8px;
        height: 8px;
        border-radius: 5px;

    &.st-active {
         background-color: green;
     }

    .st-inactive {
         background-color: red;
     }
    }

    .gj-datepicker-bootstrap [role="right-icon"] button {
        background-color: #5a6268;

    &:hover {
         background-color: mix(#000, #5a6268, 20%);
     }

    .material-icons,
    .gj-icon {
        font-size: 18px;
        top: 6px;
        color: #fff;
    }
    }

</style>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/gijgo@1.9.10/js/gijgo.min.js"></script>
<script>
    $('#from').datepicker({
        uiLibrary: 'bootstrap4',
        format : 'dd/mm/yyyy'
    });
    $('#to').datepicker({
        uiLibrary: 'bootstrap4',
        format : 'dd/mm/yyyy'
    });
</script>
</body>
</html>