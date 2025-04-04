<x-guest-layout>

    <div id="reunion_page" class="container-fluid">
        <div class="row">
            <div class="col-12 pt-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="pt-5">Jackson/Green Family
                    Reunion {{ $reunion->reunion_year }}</h1>
                <h3 class="pb-5 text-decoration-underline">{{ $reunion->reunion_city }}
                    , {{ $reunion->reunion_state }}</h3>
            </div>
        </div>

        <!-- Registration Form -->
        <div class="row reunion_content my-4" id="registration_information">
            <div class="col">
                <h2 id="" class="text-center">Registration Forms</h2>

                <div class="col-12" id="registrationReminderMsg">
                    <p class="text-center">Please do not send any payment without completing the registration form first. You can click
{{--                        <span id="registrationLink"--}}
{{--                                    class="d-none d-sm-inline"--}}
{{--                                    data-toggle="modal"--}}
{{--                                    data-target="#registration_modal">here</span>--}}
                        <a href="/reunion/{{$reunion->id}}/guest_registration_form" target="_blank" id="registrationLink"
                           class="">here</a> to complete your registration for the upcoming reunion.
                    </p>
                </div>

{{--                @if(Auth::check())--}}

{{--                    <div class="col-12" id="registrationReminderMsg">--}}
{{--                        <p class="text-center">You are currently logged in as an admin. Please select <a--}}
{{--                                href="/registrations/create/{{$reunion->id}}" id="registrationLink"--}}
{{--                                class="d-inline">here</a> to complete the registration for someone else.</p>--}}
{{--                    </div>--}}

{{--                @endif--}}

{{--                @if(!Auth::check())--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-12">--}}
{{--                            <button data-mdb-modal-init--}}
{{--                                    data-mdb-ripple-init--}}
{{--                                    type="button"--}}
{{--                                    class="btn btn-info btn-lg d-block"--}}
{{--                                    id="registrationFormBtn"--}}
{{--                                    data-mdb-target="#registration_modal">Registration Form--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}
            </div>
        </div>
        <!-- Registration Form -->

        <hr class="hr hr-blurry"/>

        <!-- Hotel information -->
        <div class="row reunion_content my-4" id="hotel_information">

            @if($reunion->hotel)

                <div class="col-12 reunionInformationHeader py-1">
                    <h2 id="" class="text-center">Hotel Information</h2>
                </div>

                <div class="col-12 col-xl-4 my-1">
                    <img
                        src="{{ asset($reunion->hotel->picture != null ? $reunion->hotel->picture : '/images/hotel_default.jpg' ) }}"
                        class="mw-100"/>
                </div>

                <div class="col-12 col-xl-8">
                    <p class="my-1">
                        <span class="hotelInfoLabel">Hotel:</span>
                        <span>{{ $reunion->hotel->name != null ? $reunion->hotel->name : 'No Hotel Added Yet' }}</span>
                    </p>

                    <p class="my-1">
                        <span class="hotelInfoLabel">Location:</span>
                        <span>{{ $reunion->hotel->location != null ? $reunion->hotel->location : 'No Hotel Location Added Yet' }}</span>
                    </p>

                    <p class="my-1">
                        <span class="hotelInfoLabel">Room:</span>
                        <span
                            class="">{{ $reunion->hotel->cost != null ? '$' . $reunion->hotel->cost . '/per night (not including taxes and fees)' : 'No Hotel Room Cost Added Yet' }}</span>
                    </p>

                    <p class="my-1">
                        <span class="hotelInfoLabel">Contact:</span>
                        <span
                            class="">{{ $reunion->hotel->phone != null ? $reunion->hotel->phone : 'No Hotel Contact Added Yet'  }}</span>
                    </p>

                    @if($reunion->hotel->phone != null)
                        <p class="my-1">
                            <span class="hotelInfoLabel">Additional Info:</span> Please call for any
                            room upgrades.
                        </p>
                    @endif

                    @if($reunion->hotel->book_room_link == null && $reunion->hotel->phone != null)
                        <p class="my-1">*** Please Call To Book Room ***</p>
                    @endif

                </div>

{{--                <div class="col-12">--}}

{{--                    <div class="form-block-header mb-xl-3">--}}
{{--                        <h2 class="text-center">Hotel Amenities</h2>--}}
{{--                    </div>--}}

{{--                    <div class="">--}}

{{--                        <ul class="list-unstyled px-1">--}}

{{--                            @if($reunion->hotel->features->isNotEmpty())--}}

{{--                                @foreach($reunion->hotel->features as $hotel_feature)--}}
{{--                                    <li class="">{{ $hotel_feature->feature_desc }}</li>--}}
{{--                                @endforeach--}}

{{--                            @else--}}

{{--                                <li class="text-center text-muted">We're still gathering information about the--}}
{{--                                    hotel and its amenities. Check back later for additional information--}}
{{--                                </li>--}}

{{--                            @endif--}}

{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </div>--}}

                @if($reunion->hotel->book_room_link !== null)

                    <div class="col-12 text-center">
                        <a href="{{ $reunion->hotel->book_room_link }}" class="btn btn-warning btn-lg"
                           target="_blank">Book Hotel Room</a>
                    </div>

                @endif

            @else

                <div class="col-12">
                    <h2 class="text-center">No Hotel Added Yet For This Reunion</h2>
                </div>

            @endif
        </div>

        <hr class="hr hr-blurry"/>

        <!-- Activities information -->
        <div class="row reunion_content my-4" id="activities_information" style="position: relative;">
            <div class="col-12 reunionInformationHeader py-1">
                <h2 id="" class="text-center">Reunion Itinerary</h2>
            </div>

            @if($events->count() < 1)

                <div class="col-12">
                    <p class="text-center mt-3 emptyInfo">No Activities Added Yet</p>
                </div>

            @else

                <div class="activities_content col-11 col-md-10 mx-auto my-2 py-2">
                    @foreach($events as $events)
                        <div class="activitiesEvent container-fluid">
                            <div class="row">
                                @foreach($events as $event)
                                    @php
                                        $eventDate = new Carbon\Carbon($event->event_date);
                                    @endphp
                                    @if($loop->first)
                                        <div class="col-12 my-3">
                                            <h2 class="activitiesEventLocation d-inline">{{ $eventDate->format('m/d/Y') }}</h2>
                                        </div>
                                    @endif

                                    @if($loop->first)
                                        <div class="col-12">
                                            <ul class="activitiesDescription col-12">
                                                @endif
                                                <li class="">
                                                    <b><em>Location:&nbsp;</em></b>{{ $event->event_location }}
                                                </li>
                                                <li class=""><b><em>Event
                                                            Description:&nbsp;</em></b>{{ $event->event_description }}
                                                </li>
                                                @if(!$loop->last)
                                                    <li class="spacer-sm"></li>
                                                @endif
                                                @if($loop->last)
                                            </ul>
                                        </div>
                                    @endif

                                @endforeach

                            </div>

                        </div>

                    @endforeach

                </div>

            @endif

        </div>

        <hr class="hr hr-blurry"/>

        <!-- Payment Information -->
        <div class="row reunion_content my-4" id="payment_information">
            <div class="col-12 reunionInformationHeader py-1">
                <h2 class="text-center">Payment Information</h2>
            </div>

            <div id="paper_payment_option" class="payment_option col-11 col-xl-5 my-3 mx-auto">
                <h2 class="">Pay By Check</h2>

                @if($committee_president->count() > 0)
                    <p class="">Please make all checks payable
                        to {{ $committee_president->firstname . ' ' . $committee_president->lastname }}. Checks can be
                        sent to:</p>

                    @if(strlen($committee_president->family_member->full_address()) > 5)
                        <p id="checks_address">
                            <span class="">Address:</span>
                            <span class="">{{ $committee_president->family_member->full_address()}}</span>
                        </p>
                        <p class="paymentsFinePrint">*Partial payments accepted</p>
                        <p class="paymentsFinePrint">*Any return checks will incur a $30 penalty fee</p>
                    @endif

                    @if($reunion->registration_form != null)
                        <p>Click
                            <a href="{{ asset('storage/' . str_ireplace('public/', '', $reunion->registration_form)) }}"
                               download="{{ $reunion->reunion_year }}_Registration_Form">here</a> to download
                            the registration form.
                        </p>
                    @else
                        <p class="">Paper Registration Form Has Not Been Uploaded Yet</p>
                    @endif
                @else
                    <p class="text-danger" id="checks_address">Committee Members Not Completed Yet. Once Committee is
                        Finalized, The Information To Fill Out A Check Will Be Added For You</p>
                @endif
            </div>

            <div id="electronic_payment_option" class="payment_option my-3 col-11 col-xl-5 mx-auto">
                <h2 class="">Electronic Payment</h2>
                <p class="">All electronic payments can be sent to administrator@jgreunion.com for anyone who already
                    has
                    a paypal account.</p>
                <p class="">Click <a href=" https://www.paypal.com/pools/c/85OCIIUoUB" target="_blank">here</a> to go to
                    paypal.</p>
            </div>
        </div>

        <hr class="hr hr-blurry"/>

        <!-- Contact/Committee information -->
        <div class="row reunion_content my-4" id="">
            <div class="col-12 reunionInformationHeader py-1">
                <h2 id="" class="text-center">Committee Information</h2>
            </div>

            <div class="col-12">
                <table id="" class="table table-responsive">
                    <thead>
                    <tr>
                        <th scope="">Title</th>
                        <th scope="">Name</th>
                        <th scope="">Email Address</th>
                    </tr>
                    </thead>

                    <tbody>
                    @if($committee_members->isNotEmpty())
                        @foreach($committee_members as $committee)
                            <tr>
                                <td>{{ ucwords(str_ireplace('_', ' ', $committee->member_title)) }}</td>
                                <td>{{ ucwords($committee->member_name) }}</td>
                                <td><i>{{ $committee->member_email }}</i></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center">No Committee Members Added Yet For This Reunion</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Registered Members For This Reunion -->
        <div class="row reunion_content my-4" id="registered_members_information">
            <div class="col-12 reunionInformationHeader py-1">
                <h2 class="text-center">Registered Family Members</h2>
            </div>

            @if($registrations->count() < 1)
                <div class="col-12">
                    <p class="text-center emptyInfo mt-3">No Family Member Have Registered Yet</p>
                </div>
            @else
                @php
                    function ucname($string) {
                        $string =ucwords(strtolower($string));

                        foreach (array('-', '\'') as $delimiter) {
                          if (strpos($string, $delimiter)!==false) {
                            $string =implode($delimiter, array_map('ucfirst', explode($delimiter, $string)));
                          }
                        }
                        return $string;
                    }
                @endphp

                <div class="row row-cols-1 row-cols-md-3 g-4">

                    @foreach($registrations->chunk(10) as $chunck)

                        <div class="col">

                            @foreach($chunck as $registration)

                                <div class="card h-100">
                                    <div class="card-header text-center border-success">
                                        <h2 class=""><span
                                                class="">{{ $loop->iteration }}.</span>{{ ucname($registration->registree_name) }}
                                        </h2>
                                        <h4 class="">{{ $registration->city }},</span>{{ $registration->state }}</h4>
                                    </div>

                                    <div class="card-body">
                                        @if($registration->adult_names != null)
                                            <div class="">
                                                <h4 class="text-decoration-underline fw-bold mb-1">Adults</h4>

                                                @foreach(explode(';', $registration->adult_names) as $adult)
                                                    <p class="mb-0">{{ $adult }}</p>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($registration->youth_names != null)
                                            <div class="mt-2">
                                                <h4 class="text-decoration-underline fw-bold mb-1">Youth</h4>

                                                @foreach(explode(';', $registration->youth_names) as $youth)
                                                    <p class="mb-0">{{ $youth }}</p>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($registration->children_names != null)
                                            <div class="mt-2">
                                                <h4 class="text-decoration-underline fw-bold mb-1">Children</h4>

                                                @foreach(explode(';', $registration->children_names) as $child)
                                                    <p class="mb-0">{{ $child }}</p>
                                                @endforeach
                                            </div>
                                        @endif


                                        {{--                                        @if($registration->children_reg)--}}

                                        {{--                                            @foreach($registration->children_reg as $reg_member)--}}
                                        {{--                                                @php $firstname = explode(" ", $reg_member->registree_name); @endphp--}}

                                        {{--                                                <h3 class="h3-responsive pl-5 my-1">{{ ucwords(strtolower($firstname[0])) }}</h3>--}}

                                        {{--                                            @endforeach--}}
                                        {{--                                        @endif--}}

                                    </div>

                                    <div class="card-footer text-center border-success">Registration
                                        Date: {{ $registration->reg_date }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif
        </div>


        <div id="registration_modal" class="modal fade">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header align-content-center justify-content-center bg-primary font1">
                        <h1 class="text-white">{{ $reunion->reunion_year . ' ' . $reunion->reunion_city }} Registration
                            Form
                        </h1>
                        <button type="button" class="btn-close" data-mdb-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @include('components.forms.reunion_registration_form')
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
