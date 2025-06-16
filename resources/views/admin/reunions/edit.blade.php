<x-app-layout>

    @section('add_scripts')
        <script type="module">
            import {addNewRowFromBtn} from '/js/myjs_functions.js';
            import {updateModalToRemoveModel} from '/js/myjs_functions.js';
            import {deleteCommitteeMemberBtn} from '/js/myjs_functions.js';
            import {btnToggle} from '/js/myjs_functions.js';

            document.getElementById('addCommitteeMember').addEventListener("click", (event) => addNewRowFromBtn('committee'));
            document.getElementById('addReunionEvent').addEventListener("click", (event) => addNewRowFromBtn('reunion_event'));

            const deleteRegistrationBtns = document.getElementById('reunion_registration_completed').querySelectorAll('button.deleteRegistration');
            const deleteCommitteeMembersBtns = document.getElementById('committee_members_form').querySelectorAll('button.deleteCommitteeMemberBtn');
            const deleteReunionEventsBtns = document.getElementById('reunion_events_form').querySelectorAll('button.deleteCommitteeMemberBtn');
            const completeReunionBtns = document.getElementById('complete_reunion_btns').querySelectorAll('button.completeReunionBtn');

            deleteRegistrationBtns.forEach(function (deleteBtn) {
                deleteBtn.addEventListener("click", (event) => updateModalToRemoveModel(event.target));
            });

            deleteCommitteeMembersBtns.forEach(function (deleteBtn) {
                deleteBtn.addEventListener("click", (event) => deleteCommitteeMemberBtn(event.target));
            });

            deleteReunionEventsBtns.forEach(function (deleteBtn) {
                deleteBtn.addEventListener("click", (event) => deleteCommitteeMemberBtn(event.target));
            });

            completeReunionBtns.forEach(function (completeBtn) {
                completeBtn.addEventListener("click", (event) => btnToggle(event.target));
            });

        </script>
    @endsection

    <div class="container-fluid" id="">

        <x-admin-jumbotron>Edit Reunion</x-admin-jumbotron>

        @include('components.nav')

        <div class="row">
            {{--            <div class="col-12 col-xl-2 my-2">--}}

            {{--                <div class="">--}}
            {{--                                        <a href="{{ route('create_reunion_pictures', ['reunion' => $reunion->id]) }}"--}}
            {{--                                           class="btn btn-lg btn-block btn-outline-light-green">Add Reunion Photos</a>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="col-12 col-md-10 col-xl-8 my-2 mx-auto">

                <form action="{{ route('reunions.update', $reunion->id) }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    @include('components.forms.reunion_update_form')

                    <hr class="hr hr-blurry">

                    @include('components.forms.committee_members_update_form')

                    <hr class="hr hr-blurry">

                    @include('components.forms.reunion_events_update_form')

                    <div class="form-group mb-5">
                        <button class="btn btn-primary form-control" type="submit">Update Reunion</button>
                    </div>

                </form>

                <hr class="hr hr-blurry">

            </div>
        </div>

        @include('components.forms.reunion_registrations_completed')

    </div>
</x-app-layout>
