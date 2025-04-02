<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Jackson and Green Family Reunion"/>
    <meta name="author" content="Tramaine Jackson"/>

    <title>@yield('Title', 'Jackson/Green Reunion')</title>

    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link
        href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Bakbak+One&family=Cinzel&family=Cinzel+Decorative:wght@100;200;300;400;500;600;700;800;900&family=Montserrat&family=Montserrat+Alternates:wght@100;200;300;400;500;600;700;800;900&family=Moon+Dance&family=Raleway&family=Roboto+Flex:opsz,wght@8..144,100;8..144,400;8..144,700&display=swap"
        rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom_styles.min.css') }}">

    @yield('add_styles')
</head>

<body>

<div id="app" class="">

    @include('components.nav')

    @if(request()->routeIs('guest_home') || request()->routeIs('login') || request()->routeIs('register'))
        @include('components.carousel')
    @endif

    {{--MAIN CONTENT--}}
    {{ $slot }}

    <div
        class="alert fade"
        id="return-data-alert"
        role="alert"
        data-mdb-color="success"
        data-mdb-position="top-right"
        data-mdb-stacking="true"
        data-mdb-width="300px"
        data-mdb-append-to-body="true"
        data-mdb-hidden="true"
        data-mdb-autohide="true"
        data-mdb-delay="4000">
        <p class="alertBody m-0 p-0 text-center"></p>
    </div>

    <div
        class="alert fade"
        id="return-data-alert-bad"
        role="alert"
        data-mdb-color="danger"
        data-mdb-position="top-right"
        data-mdb-stacking="true"
        data-mdb-width="300px"
        data-mdb-append-to-body="true"
        data-mdb-hidden="true"
        data-mdb-autohide="true"
        data-mdb-delay="4000">
        <p class="alertBody m-0 p-0 text-center"></p>
    </div>
    {{--MAIN CONTENT--}}
</div>

@include('components.footer')

<!-- Scripts -->
<!-- Core -->
<script type="module" src="{{ asset('js/mdb.es.min.js') }}"></script>
<!-- Custom Scripts -->
<script type="module" src="{{ asset('js/myjs_modules.js') }}"></script>
<script type="module" src="{{ asset('js/myjs_functions.js') }}"></script>

@if(session('status'))
    <script type="module">
        import {Alert} from "{{ asset('/js/mdb.es.min.js') }}";

        document.getElementsByClassName('alertBody')[0].innerHTML = '{{ session('status') }}';
        new Alert(document.getElementById('return-data-alert')).show();
    </script>
@elseif(session('bad_status'))
    <script type="module">
        import {Alert} from "{{ asset('/js/mdb.es.min.js') }}";

        document.getElementsByClassName('alertBody')[1].innerHTML = '{{ session('bad_status') }}';
        new Alert(document.getElementById('return-data-alert-bad')).show();
    </script>
@endif

@yield('add_scripts')

</body>
</html>
