<x-app-layout>

    @section('add_scripts')
        <script type="module">
            import {addNewRowFromBtn} from '/js/myjs_functions.js';
            import {btnToggle} from '/js/myjs_functions.js';
            import {filePreview} from '/js/myjs_functions.js';

            document.getElementById('addSiblingRow').addEventListener("click", (event) => addNewRowFromBtn('sibling'));
            document.getElementById('addChildrenRow').addEventListener("click", (event) => addNewRowFromBtn('child'));
            document.getElementById('change_img_btn').addEventListener("change", (event) => filePreview(event.target));

            for (let i = 0; i < document.getElementsByClassName('descentInput').length; i++) {
                document.getElementsByClassName('descentInput')[i].addEventListener("click", (event) => btnToggle(event.target));
            }

        </script>
    @endsection

    <div class="container-fluid" id="">

        <div class="row">

            <div class="col-12 pt-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="pt-5">Jackson/Green Family Reunion</h1>
                <h3 class="pb-5 text-decoration-underline">My Profile</h3>
            </div>

            <div class="col-11 col-xl-8 mt-2 membersForm mx-auto">

                @if($active_reunion != null)

                    @if($registered_for_reunion === null)

                        <div class=" mt-4 mb-5">
                            <a class="btn btn-outline-danger fs-5"
                               href="{{ route('registrations.create', ['member' => $family_member->id]) }}"><i
                                    class="fa fa-exclamation-circle" aria-hidden="true"></i>&nbsp;There is an upcoming
                                reunion
                                to {{ $active_reunion->reunion_city . ', ' . $active_reunion->reunion_state .' for ' . $active_reunion->reunion_year }}
                                . Click here
                                for more information and to register for the reunion&nbsp;<i
                                    class="fa fa-exclamation-circle"
                                    aria-hidden="true"></i></a>
                        </div>

                    @else

                        @include('components.member_completed_registration')

                    @endif

                @endif

                @include('components.forms.members_update_form')

            </div>
        </div>
    </div>
</x-app-layout>
