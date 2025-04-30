<x-app-layout>

    @section('add_scripts')
        <script type="module">
            import {addNewRowFromBtn} from '/js/myjs_functions.js';
            import {btnToggle} from '/js/myjs_functions.js';

            document.getElementById('addSiblingRow').addEventListener("click", (event) => addNewRowFromBtn('sibling'));
            document.getElementById('addChildrenRow').addEventListener("click", (event) => addNewRowFromBtn('child'));

            for (let i = 0; i < document.getElementsByClassName('descentInput').length; i++) {
                document.getElementsByClassName('descentInput')[i].addEventListener("click", (event) => btnToggle(event.target));
            }

        </script>
    @endsection

    <div class="container-fluid" id="">

        @include('components.nav')

        <div class="row">

            @if(Auth::user()->is_admin())

                <div class="col-12 col-xl-2 my-2">
                    <div class="">
                        <a href="{{ route('members.index') }}" class="btn btn-info btn-lg btn-block">All Members</a>

                        <a href="{{ route('members.create') }}" class="btn btn-info btn-lg my-2 btn-block">Add New
                            Member</a>

                        <a href="#" type="button" data-toggle="modal" data-target="#modalConfirmDelete"
                           class="btn btn-danger btn-lg mb-2 btn-block">Delete Member</a>

                        @if($active_reunion != null)

                            <a href="{{ route('registrations.index') }}"
                               class="btn btn-success btn-block btn-lg{{ $registered_for_reunion != null ? ' disabled' : '' }}"
                               style="white-space: initial;"
                               onclick="event.preventDefault(); document.getElementById('one_click_registration').submit();">{{ $registered_for_reunion != null ? 'Member Already Registered For ' . $active_reunion->reunion_city . ' Reunion'  : 'Add Member To ' . $active_reunion->reunion_city . ' Reunion' }}</a>

                            <form action="{{ route('registrations.store') }}" method="POST" class="d-none">
                                @csrf

                                <input type="text" name="reg_member" class="" value="{{ $family_member->id }}" hidden/>

                                <input type="text" name="reunion_id" class="" value="{{ $active_reunion->id }}" hidden/>
                            </form>
                        @endif
                    </div>
                </div>

            @else

                <div class="col-12 pt-5 text-center font7"
                     style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                    <h1 class="pt-5">Jackson/Green Family Reunion</h1>
                    <h3 class="pb-5 text-decoration-underline">My Profile</h3>
                </div>

            @endif

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

                        @include('components.completed_registration')

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
