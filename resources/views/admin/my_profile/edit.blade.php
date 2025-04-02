<x-app-layout>

    @section('add_scripts')
        <script type="module">
            import { addNewRowFromBtn } from '/js/myjs_functions.js';
            import { btnToggle } from '/js/myjs_functions.js';

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

                <div class="col-12 pt-5 text-center font7" style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                    <h1 class="pt-5">Jackson/Green Family Reunion</h1>
                    <h3 class="pb-5 text-decoration-underline">My Profile</h3>
                </div>

            @endif

            <div class="col-11 col-xl-8 mt-2 membersForm mx-auto">

                @if($active_reunion != null)

                    @if($registered_for_reunion === null)

                        <h3 class="h3-responsive red-text text-center"><i class="fa fa-exclamation-circle"
                                                                          aria-hidden="true"></i>There is an upcoming
                            reunion to {{ $active_reunion->reunion_city . ', ' . $active_reunion->reunion_state }}.
                            Click here for more information and to register for the reunion <i
                                class="fa fa-exclamation-circle" aria-hidden="true"></i></h3>

                    @else

                        <h3 class="h3-responsive cyan-text text-center">You're all set for the next reunion. Click here
                            to see if there has been any updates</h3>

                    @endif

                    <div class="text-center">

                        <a href="{{ route('member_registration', ['reunion' => $active_reunion->id, 'member' => $family_member->id]) }}"
                           class="btn peach-gradient">New Reunion Information</a>

                    </div>

                @endif

                <h1 class="mt-2 mb-4">My Profile</h1>

                <form action="{{ route('members.update', $family_member->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @include('components.user_profile')

                    <hr class="hr hr-blurry">

                    @include('components.family_tree')

                    <hr class="hr hr-blurry">

                    @include('components.social_media')

                    <div class="houseHoldBlock">
                        <div class="form-block-header">
                            <h3 class="">Household Members
                                <button type="button" class="btn btn-outline-success mb-2 addHHMember">Add Household
                                    Member
                                </button>
                            </h3>
                        </div>

                        @if($family_members->count() > 1)

                            @foreach($family_members as $family_member)

                                <div class="form-row">
                                    <div class="form-group col-8">
                                        <input class="form-control"
                                               value="{{ $family_member->firstname . ' ' . $family_member->lastname }}"
                                               disabled/>
                                        <input value="{{ $family_member->id }}" hidden/>
                                    </div>
                                    <div class="">
                                        <div class="form-group col-2">
                                            <a href="#"
                                               class="btn btn-danger{{ $family_member->id == $family_member->id ? ' disabled' : '' }}"
                                               onclick="event.preventDefault(); removeFromHouseHold({{ $family_member->id . ',' .$family_member->id }});">Remove
                                                Household Member</a>
                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        @endif

                        <!-- Blank row for adding a household member -->
                        <div class="form-row hhMemberRow hidden">
                            <div class="form-group col-7">
                                <select class="form-control" name="houseMember[]">
                                    <option value="blank">--- Select A Household Member ---</option>
                                    @foreach($family_members as $option)
                                        <option
                                            value="{{ $option->id }}">{{ $option->firstname . ' ' . $option->lastname }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-2">
                                <button type="button" class="btn btn-danger d-block removeHHMember">Remove</button>
                            </div>
                        </div>

                        @if($potential_family_members->count() > $family_members->count())

                            <div class="form-block-header">
                                <h3 class="">Potential Household Members</h3>
                            </div>

                            @foreach($potential_family_members as $potential_family_member)
                                @if($potential_family_member->id != $family_member->id)
                                    <div class="form-row">
                                        <div class="form-group col-8">
                                            <input class="form-control"
                                                   value="{{ $potential_family_member->firstname . ' ' . $potential_family_member->lastname }}"
                                                   disabled/>
                                            <input value="{{ $potential_family_member->id }}" hidden/>
                                        </div>
                                        <div class="">
                                            <div class="form-group col-2">
                                                <a href="#" class="btn btn-warning"
                                                   onclick="event.preventDefault(); addToHouseHold({{$family_member->id . ',' . $potential_family_member->id}});">Add
                                                    To Household</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary form-control" type="submit">Update Member</button>
                    </div>

                </form>
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
