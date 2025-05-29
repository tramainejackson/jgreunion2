<x-app-layout>

    <div class="container-fluid" id="">

        <div class="row">
            <div class="col-12 py-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="mt-5 pb-3">Jackson/Green Family
                    Reunion</h1>

                {{--                <h2 class="my-3">{{ ucwords($family_member->full_name()) }} Profile</h2>--}}
            </div>
        </div>

        @include('components.nav')

        <div class="row">

            <div class="col-12 d-flex flex-column flex-xl-row align-items-center">

                <div class="mt-3 my-xl-4">
                    <div class="">
                        <a href="{{ route('members.index') }}" class="btn btn-info btn-lg">All Members</a>
                    </div>
                </div>

            </div>

            <div class="col-12 col-md-10 mx-auto my-2 duplicatesCol animated">

                <div class="col-12 d-flex flex-column flex-xl-row align-items-center">
                    <h1 class="">Check For Duplicate Accounts</h1>
                </div>

                @if($duplicates_check !== null)

                    @foreach($duplicates_check as $duplicate)
                        @php
                            $firstAccount = App\Models\FamilyMember::getDuplicates($duplicate->firstname, $duplicate->city, $duplicate->state)->first();
                        @endphp

                            <!--Card-->
                        <div class="card my-3 border border-1 border-secondary">

                            <div class="d-flex flex-center justify-content-center my-3">

                                <!-- Member Profile Avatar -->
                                <div class="mx-4">
                                    <img
                                        src="{{ $duplicate->get_profile_image() }}"
                                        class="img-fluid" height="350px" width="250px"/>
                                </div>

                                <!-- Bio Info -->
                                <div class="mx-4">
                                    <h3 class="h3-responsive"><span
                                            class="font-weight-bold pr-3">Name:</span>&nbsp;<span
                                            class="text-muted">{{ $firstAccount->full_name() }}</span></h3>

                                    <h3 class="h3-responsive"><span
                                            class="font-weight-bold pr-3">Address:</span>&nbsp;<span
                                            class="text-muted">{{ $firstAccount->full_address() }}</span></h3>

                                    <h3 class="h3-responsive"><span
                                            class="font-weight-bold pr-3">Email:</span>&nbsp;<span
                                            class="text-muted">{{ $firstAccount->email != null ? $firstAccount->email : 'No Email Address' }}</span>
                                    </h3>

                                </div>

                            </div>

                            <!--Card content-->
                            <div class="card-body text-center">
                                @foreach(App\Models\FamilyMember::getDuplicates($duplicate->firstname, $duplicate->city, $duplicate->state)->get() as $dupe)

                                    @if($loop->iteration != 1)

                                        <div class="container-fluid">

                                            <div
                                                class="row flex-column flex-xl-row align-items-center justify-content-around">

                                                <p class="my-0 col-12 col-xl-2">{{ $dupe->full_name() }}</p>

                                                <p class="my-0 col-12 col-xl-4">{{ $dupe->full_address() }}</p>

                                                <div class="col-12 col-xl-4">
                                                    <button data-mdb-ripple-init
                                                            data-mdb-modal-init
                                                            data-mdb-target="#modalConfirmDelete"
                                                            class="btn btn-rounded btn-outline-danger deleteDupe"
                                                            onclick="document.getElementById('delete_duplicate_member').value = event.target.firstElementChild.value;"
                                                            type="button">Delete Duplicate
                                                        <input type="number" class="hidden"
                                                               value="{{ $firstAccount->id . '.' . $dupe->id }}"
                                                               hidden/>
                                                    </button>

                                                    <button data-mdb-ripple-init
                                                        data-mdb-modal-init
                                                        data-mdb-target="#modalConfirmKeep"
                                                        class="btn btn-rounded btn-outline-warning keepDupe"
                                                        onclick="document.getElementById('keep_duplicate_member').value = event.target.firstElementChild.value;"
                                                        type="button">Not A Dupe
                                                        <input type="number" class="hidden"
                                                               value="{{ $dupe->id }}"
                                                               hidden/>
                                                    </button>

                                                </div>
                                            </div>
                                        </div>

                                    @endif

                                    <hr class="" {{ $loop->last ? 'hidden' : '' }} />

                                @endforeach

                            </div>

                        </div>
                        <!--/.Card-->

                    @endforeach

                @else

                    <div class="text-center">
                        <h2 class="text-center">There are no duplicates currently found in the system</h2>
                    </div>

                @endif

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
                    <p class="heading">Are you sure you want to delete this account?</p>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <i class="fa fa-times fa-4x animated rotateIn"></i>
                </div>

                <!--Footer-->
                <div class="modal-footer flex-center">
                    <form action="{{ action([\App\Http\Controllers\FamilyMemberController::class, 'update_duplicate'],) }}" method="POST">
                        @csrf

                        <input type="text" name="delete_duplicate_member_form" id="" value="Y" hidden>
                        <input type="text" name="delete_duplicate_member" id="delete_duplicate_member" hidden>

                        <button type="submit" class="btn btn-outline-danger">Yes</button>

                        <button type="button" class="btn btn-danger waves-effect" data-mdb-ripple-init data-mdb-dismiss="modal">No</button>

                    </form>

                </div>

            </div>
            <!--/.Content-->

        </div>

    </div>
    <!--Modal: modalConfirmDelete-->

    <!--Modal: modalConfirmKeep-->
    <div class="modal fade" id="modalConfirmKeep" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">

        <div class="modal-dialog modal-sm modal-notify modal-warning" role="document">

            <!--Content-->
            <div class="modal-content text-center">

                <!--Header-->
                <div class="modal-header d-flex justify-content-center">
                    <p class="heading">Are you sure you want to keep this account?</p>
                </div>

                <!--Body-->
                <div class="modal-body">
                    <i class="fa fa-times fa-4x"></i>
                </div>

                <!--Footer-->
                <div class="modal-footer flex-center">
                    <form action="{{ action([\App\Http\Controllers\FamilyMemberController::class, 'update_duplicate'],) }}" method="POST">
                        @csrf

                        <input type="text" name="keep_duplicate_member_form" id="" value="Y" hidden>
                        <input type="number" name="keep_duplicate_member" id="keep_duplicate_member" hidden>

                        <button type="submit" class="btn btn-outline-danger">Yes</button>

                        <button type="button" class="btn btn-danger waves-effect" data-mdb-ripple-init data-mdb-dismiss="modal">No</button>

                    </form>

                </div>

            </div>
            <!--/.Content-->

        </div>

    </div>
    <!--Modal: modalConfirmKeep-->
</x-app-layout>
