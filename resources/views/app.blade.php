<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" value="{{ csrf_token() }}"/>
    <title>{{env('MIX_APP_NAME')}}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"> -->
    <link href="{{ mix('css/app.css') }}" type="text/css" rel="stylesheet"/>
</head>
<body>
<!-- <h1>{{  Auth::user() }}</h1> -->

@if (Auth::check())
    <script>
        window.Laravel = {!!json_encode([
            'isLoggedin' => true,
            'user' => Auth::user()
        ])!!}
    </script>
@else
    <script>
        window.Laravel = {!!json_encode([
            'isLoggedin' => false
        ])!!}
    </script>
@endif

<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->

<div id="app" class="app-main">
</div>
<footer class="page-footer font-small bg-footer pt-4">
    <div class="container">

    <!-- <ul class="list-unstyled list-inline text-center">
        <li class="list-inline-item">
        <h5 class="mb-1">{{config('app.name')}}</h5>
        </li>
        <li class="list-inline-item">
        <button type="button" class="btn btn-item-default">Sign up!</button>
        </li>
    </ul> -->

    </div>
    <div class="footer-copyright text-center py-3 bg-sub">© 2022 Copyright:
        <a href="#">{{config('app.name')}}</a>
    </div>
</footer>
<script src="{{ mix('js/app.js') }}" type="text/javascript"></script>
</body>
</html>