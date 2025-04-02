<x-guest-layout>
    <div id="" class="container-fluid">
        <div class="row">
            <div class="col-12 pt-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="pt-5">Jackson/Green Family
                    Reunion {{ $reunion->reunion_year }}</h1>
                <h3 class="pb-5 text-decoration-underline">{{ $reunion->reunion_city }}
                    , {{ $reunion->reunion_state }}</h3>
            </div>
        </div>
    </div>

    @include('components.forms.reunion_registration_form')

</x-guest-layout>
