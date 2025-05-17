<x-app-layout>

    @section('add_scripts')
        <script type="module">
            import {addNewRowFromBtn} from '/js/myjs_functions.js';

            document.getElementById('addCommitteeMember').addEventListener("click", (event) => addNewRowFromBtn('committee'));
            document.getElementById('addReunionEvent').addEventListener("click", (event) => addNewRowFromBtn('reunion_event'));
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

                <form action="{{ route('reunions.update', $reunion->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="">
                        <h2 class="text-left">Edit {{ ucwords($reunion->reunion_city) }} Reunion</h2>
                    </div>

                    @include('components.forms.reunion_update_form')

                    <hr class="hr hr-blurry">

                    @include('components.forms.committee_members_update_form')

                    <hr class="hr hr-blurry">

                    @include('components.forms.reunion_events_upodate_form')

                    @if($reunion->reunion_complete == 'N')
                        <button class="btn btn-warning btn-lg btn-block my-2"
                                type="button"
                                data-mdb-ripple-init
                                data-mdb-modal-init
                                data-mdb-target="#completeReunionModal">Complete Reunion
                        </button>
                    @endif

                    <div class="form-group mb-5">
                        <button class="btn btn-primary form-control" type="submit">Update Reunion</button>
                    </div>

                </form>

                <hr class="hr hr-blurry">

            </div>
        </div>

        @include('components.forms.reunion_registrations_completed')

        <!--Modal: modalConfirmDelete-->
        <div class="modal fade"
             id="completeReunionModal"
             tabindex="-1"
             role="dialog"
             aria-labelledby="completeReunionModal"
             aria-hidden="true">

            <div class="modal-dialog modal-sm modal-notify modal-warning" role="document">

                <form action="{{ route('reunions.update', $reunion->id) }}">
                    @csrf
                    @method('PUT')

                    <!--Content-->
                    <div class="modal-content text-center">
                        <!--Header-->
                        <div class="modal-header align-content-center justify-content-center bg-danger-subtle">
                            <p class="m-0 display-6 fw-bolder text-primary-emphasis">Are you sure?</p>
                        </div>

                        <!--Body-->
                        <div class="modal-body">
                            <h2 class="">Completing this reunion will make it inactive and disable any update to it.
                                Continue?</h2>

                            <input type="text" name="completed_reunion" class="hidden" value="Y" hidden/>
                        </div>

                        <!--Footer-->
                        <div class="modal-footer flex-center">
                            <button type="submit" class="btn btn-outline-warning">Yes</button>

                            <button type="button" class="btn btn-warning waves-effect" data-mdb-dismiss="modal">No</button>
                        </div>
                    </div>
                    <!--/.Content-->
                </form>
            </div>
        </div>
        <!--Modal: modalConfirmDelete-->
    </div>
</x-app-layout>
