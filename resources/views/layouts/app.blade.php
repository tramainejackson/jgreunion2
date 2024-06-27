{{--@php $agent = new Jenssegers\Agent\Agent(); @endphp--}}
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
    <link href="https://fonts.googleapis.com/css2?family=Abril+Fatface&family=Bakbak+One&family=Cinzel&family=Cinzel+Decorative:wght@100;200;300;400;500;600;700;800;900&family=Montserrat&family=Montserrat+Alternates:wght@100;200;300;400;500;600;700;800;900&family=Moon+Dance&family=Raleway&family=Roboto+Flex:opsz,wght@8..144,100;8..144,400;8..144,700&display=swap"
          rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/style.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom_styles.min.css') }}">

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/mdb.min.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('/css/jgreunion.css') }}" rel="stylesheet">

    @yield('add_styles')

    @if(substr_count(request()->server('HTTP_USER_AGENT'), 'rv:') > 0)
        <link href="{{ asset('/css/myIEcss.css') }}" rel="stylesheet">
    @endif

</head>

<body>
<div id="app" class="">

    @if(session('status'))
        <h2 class="flashMessage text-center">{{ session('status') }}</h2>
    @endif

    @if(session('error'))
        <h2 class="errorMessage text-center">{{ session('error') }}</h2>
    @endif

    <div class="modal fade loadingSpinner">
        <div class="loader"></div>

        <div class="">
            <p class="text-white d-table mx-auto display-3"></p>
        </div>
    </div>

    {{--MAIN CONTENT--}}
    {{ $slot }}
    {{--MAIN CONTENT--}}

    <!-- Progress Bar Modal -->
    <div class="modal fade" id="progress_modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header justify-content-around align-items-center">
                    <h2 class="">Uploading....</h2>
                </div>
                <div class="modal-body">
                    <div class="progress" style="height: 20px">
                        <div class="progress-bar" id="pro" role="progressbar" style="width: 0%; height: 20px" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(Auth::guest())
    <!-- Footer -->
    <footer class="page-footer font-small unique-color-dark pt-4">

        <!-- Footer Elements -->
        <div class="container">

            <!-- Call to action -->
            <ul class="list-unstyled list-inline text-center py-2">
                <li class="list-inline-item">
                    <h5 class="mb-1">Register an account</h5>
                </li>

                <li class="list-inline-item">
                    <a href="{{ route('register') }}" class="btn btn-outline-white btn-rounded">Sign up!</a>
                </li>
            </ul>
            <!-- Call to action -->

        </div>
        <!-- Footer Elements -->

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2018 Copyright:
            <a href="http://jgreunion.com/">JGRunion.com</a>
        </div>
        <!-- Copyright -->

    </footer>
    <!-- Footer -->
@endif

<!-- Scripts -->
<!-- Bootstrap core JS -->
<script type="text/javascript" src="{{ asset('/js/jquery-3.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/mdb.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/addons/datatables.min.js') }}"></script>

<!-- Custom JS -->
{{--<script src="{{ asset('/js/jgreunion.js') }}"></script>--}}

<!-- Bootstrap core -->
<script type="module" src="{{ asset('js/mdb.es.min.js') }}"></script>
<!-- Custom Scripts -->
<script type="module" src="{{ asset('js/myjs_modules.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/myjs_functions.js') }}"></script>

@if(session('status'))
    <script type="module">
        import { Alert } from "{{ asset('/js/mdb.es.min.js') }}";

        document.getElementsByClassName('alertBody')[0].innerHTML = '{{ session('status') }}';
        new Alert(document.getElementById('return-data-alert')).show();
    </script>
@elseif(session('bad_status'))
    <script type="module">
        import { Alert } from "{{ asset('/js/mdb.es.min.js') }}";

        document.getElementsByClassName('alertBody')[1].innerHTML = '{{ session('bad_status') }}';
        new Alert(document.getElementById('return-data-alert-bad')).show();
    </script>
@endif

@yield('additional_scripts')

</body>
</html>
