<x-app-layout>

    <div class="container-fluid" id="">

        <div class="row">
            <div class="col-12 py-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="mt-5 pb-3">Jackson/Green Family
                    Reunion</h1>

                <h2 class="my-3">{{ ucwords($family_member->full_name()) }} Profile</h2>
            </div>
        </div>

        <div class="row">

            <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4 col-xxl-3 membersForm mx-auto mt-2">
                <div class="card">
                    <img
                        src="{{ $family_member->get_profile_image() }}"
                        class="card-img-top" alt="Family Member Image"/>
                    <div class="card-body">
                        <h1 class="card-title text-center font1 pb-3 display-4">{{ $family_member->full_name() }}</h1>

                        @if($family_member->descent != null)
                            <div class="text-center mb-4">
                                <button class="btn btn-lg btn-secondary"><span
                                        class="text-decoration-underline font2">Origin of Descent</span><br/><span
                                        class="fs-4 font1">{{ ucfirst($family_member->descent) }}</span>
                                </button>
                            </div>
                        @endif

                        @if($father != null)
                            <div class="container-fluid" id="show_parents">
                                <div class="row" id="">
                                    <div class="col-6 text-center" id="">
                                        <img src="{{ asset('/images/img_placeholder.jpg') }}"
                                             class="img-fluid rounded-circle img-thumbnail border-primary"
                                             alt="Father Image">

                                        <div class="m-n4">
                                            <span
                                                class="mw-100 border border-2 p-1 rounded rounded-5 bg-white border-primary"><a
                                                    href="{{ route('members.show', $father->id) }}"
                                                    class="text-primary">{{ $father->full_name() }}</a></span>
                                        </div>


                                        <span class="d-block mt-4">Father</span>
                                    </div>

                                    <div class="col-6 text-center" id="">
                                        <img src="{{ asset('/images/img_placeholder.jpg') }}"
                                             class="img-fluid rounded-circle img-thumbnail border-primary"
                                             alt="Mother Image">

                                        <div class="m-n4">
                                            <span
                                                class="mw-100 border border-2 p-1 rounded rounded-5 bg-white border-primary"><a
                                                    href="{{ route('members.show', $mother->id) }}"
                                                    class="text-primary">{{ $mother->full_name() }}</a></span>
                                        </div>


                                        <span class="d-block mt-4">Mother</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($spouse != null)
                            <div class="container-fluid" id="show_spouse">
                                <div class="row" id="">
                                    <div class="col-6 mx-auto text-center" id="">
                                        <img src="{{ asset('/images/img_placeholder.jpg') }}"
                                             class="img-fluid rounded-circle img-thumbnail border-secondary"
                                             alt="Spouse Image">

                                        <div class="m-n4">
                                            <span
                                                class="mw-100 border border-2 p-1 rounded rounded-5 bg-white border-secondary hover-shadow"><a
                                                    href="{{ route('members.show', $spouse->id) }}"
                                                    class="text-secondary">{{ $spouse->full_name() }}</a></span>
                                        </div>

                                        <span class="d-block mt-4">Spouse</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($siblings != null)
                            <div class="container-fluid" id="show_siblings">
                                <div class="row" id="">

                                    @foreach($siblings as $sibling)

                                        <div class="col-6 mx-auto text-center" id="">
                                            <img src="{{ asset('/images/img_placeholder.jpg') }}"
                                                 class="img-fluid rounded-circle img-thumbnail border-warning"
                                                 alt="Sibling Image">

                                            <div class="m-n4">
                                                <span
                                                    class="mw-100 border border-2 p-1 rounded rounded-5 bg-white border-warning"><a
                                                        href="{{ route('members.show', $sibling->id) }}"
                                                        class="text-warning">{{ $sibling->full_name() }}</a></span>
                                            </div>

                                            <span class="d-block mt-4">Sibling</span>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endif

                        @if($children != null)
                            <div class="container-fluid" id="show_children">
                                <div class="row" id="">

                                    @foreach($children as $child)

                                        <div class="col-6 mx-auto text-center" id="">
                                            <img src="{{ asset('/images/img_placeholder.jpg') }}"
                                                 class="img-fluid rounded-circle img-thumbnail border-success"
                                                 alt="Sibling Image">

                                            <div class="m-n4">
                                                <span
                                                    class="mw-100 border border-2 p-1 rounded rounded-5 bg-white border-success"><a
                                                        href="{{ route('members.show', $child->id) }}"
                                                        class="text-success">{{ $child->full_name() }}</a></span>
                                            </div>

                                            <span class="d-block mt-4">Child</span>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        @endif

                        <div class="mt-2">
                            <p class="mb-1"><span
                                    class="fw-bold">Location</span>:&nbsp;{{ $family_member->city . ', ' . $family_member->state }}
                            </p>

                            @if($family_member->descent != null)
                                <p class="mb-1"><span
                                        class="fw-bold">DOB</span>:&nbsp;{{ $family_member->date_of_birth }}</p>
                            @endif

                            @if($family_member->instagram != null)
                                <p class="mb-1"><i class="fab fa-instagram"></i>&nbsp;{{ $family_member->instagram }}
                                    &nbsp;<i class="fab fa-instagram"></i></p>
                            @endif

                            @if($family_member->facebook != null)
                                <p class="mb-1"><i
                                        class="fab fa-facebook-square"></i>&nbsp;{{ $family_member->facebook }}&nbsp;<i
                                        class="fab fa-facebook-square"></i></p>
                            @endif

                            @if($family_member->twitter != null)
                                <p class="mb-1"><i class="fab fa-twitter-square"></i>&nbsp;{{ $family_member->twitter }}
                                    &nbsp;<i class="fab fa-twitter-square"></i></p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
