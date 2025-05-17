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

        @include('components.nav')

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
                               href="{{ route('member_registration', ['reunion' => $active_reunion->id, 'member' => $family_member->id]) }}"><i
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

    <!--Modal: modalConfirmDelete-->
    <div class="modal fade" id="modalConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">

        <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">

            <!--Content-->
            <div class="modal-content text-center">

                <!--Header-->
                <div class="modal-header d-flex justify-content-center">
                    <p class="heading">Are you sure you want to delete this family member?</p>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <i class="fa fa-times fa-4x animated rotateIn"></i>
                </div>

                <!--Footer-->
                <div class="modal-footer flex-center">
                    <form action="{{ route('members.destroy', $family_member->id) }}" method="POST">
                        @csrf
                        @method('DELETE')


                        <button type="submit" class="btn btn-outline-danger">Yes</button>

                        <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">No</button>

                    </form>

                </div>

            </div>
            <!--/.Content-->

        </div>

    </div>
    <!--Modal: modalConfirmDelete-->
</x-app-layout>
