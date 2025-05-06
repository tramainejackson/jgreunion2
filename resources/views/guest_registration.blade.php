<x-guest-layout>

    <div id="" class="container-fluid">
        <div class="row">
            <div class="col-12 pt-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="pt-5">Jackson/Green Family
{{--                    Reunion {{ $reunion->reunion_year }}</h1>--}}
                <h3 class="pb-5 text-decoration-underline">{{ $reunion->reunion_city }}
                    , {{ $reunion->reunion_state }}</h3>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center pt-3">Registration Form</h2>

                <div id="registrationWarning1" class="text-center mb-1">
                    <button type="button" class="paymentsFinePrint btn btn-outline-warning"><i
                            class="fas fa-triangle-exclamation"></i>&nbsp;If you have already completed a registration,
                        please do not create another one. Please reach out to one of the committee members to make
                        changes to your registration&nbsp;<i class="fas fa-triangle-exclamation"></i></button>
                    <span class="fst-italic fw-light text-muted" onclick="document.getElementById('registrationWarning1').remove();" style="font-size: 0.7rem;">Dismiss</span>
                </div>

                <div id="registrationWarning2" class="text-center mb-4">
                    <button type="button" class="paymentsFinePrint btn btn-outline-warning"><i
                            class="fas fa-triangle-exclamation"></i>&nbsp;If you have created a profile, you can submit
                        a registration through your profile and view already completed registrations&nbsp;<i
                            class="fas fa-triangle-exclamation"></i></button>
                    <span class="fst-italic fw-light text-muted" onclick="document.getElementById('registrationWarning2').remove();" style="font-size: 0.7rem;">Dismiss</span>
                </div>
            </div>
        </div>
    </div>

    @include('components.forms.reunion_registration_form')

</x-guest-layout>
