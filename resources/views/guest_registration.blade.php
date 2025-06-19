<x-guest-layout>

    <x-admin-jumbotron></x-admin-jumbotron>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-11 col-lg-10 mx-auto">
                <h2 class="text-center pt-3">Registration Form</h2>

                <div id="registrationWarning1" class="text-center mb-1">
                    <button type="button" class="paymentsFinePrint btn btn-outline-warning"><i
                            class="fas fa-triangle-exclamation"></i>&nbsp;If you have already completed a registration,
                        please do not create another one. Please reach out to one of the committee members to make
                        changes to your registration&nbsp;<i class="fas fa-triangle-exclamation"></i></button>
                    <span class="fst-italic fw-light text-muted"
                          onclick="document.getElementById('registrationWarning1').remove();"
                          style="font-size: 0.7rem;">Dismiss</span>
                </div>

                <div id="registrationWarning2" class="text-center mb-4">
                    <button type="button" class="paymentsFinePrint btn btn-outline-warning"><i
                            class="fas fa-triangle-exclamation"></i>&nbsp;If you have created a profile, you can submit
                        a registration through your profile and view already completed registrations&nbsp;<i
                            class="fas fa-triangle-exclamation"></i></button>
                    <span class="fst-italic fw-light text-muted"
                          onclick="document.getElementById('registrationWarning2').remove();"
                          style="font-size: 0.7rem;">Dismiss</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-11 col-lg-10 mx-auto">
                @include('components.forms.reunion_registration_form')
            </div>
        </div>
    </div>

</x-guest-layout>
