<x-app-layout>

    <x-admin-jumbotron>Edit Current Registration</x-admin-jumbotron>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center pt-3 m-3">{{ $member->full_name() }} Registration Form</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <div class="row">
            <div class="col-12 col-md-11 col-lg-10 mx-auto">
                @include('components.forms.reunion_registration_update_form')
            </div>
        </div>
    </div>

</x-app-layout>
