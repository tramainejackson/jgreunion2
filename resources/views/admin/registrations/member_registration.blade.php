<x-app-layout>

    <div id="" class="container-fluid">
        <div class="row">
            <div class="col-12 pt-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="pt-5">Jackson/Green Family
                    Reunion {{ $reunion->reunion_year }}</h1>
                <h3 class="pb-5 text-decoration-underline">{{ $reunion->reunion_city }}
                    , {{ $reunion->reunion_state }}</h3>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center pt-3 mb-3">{{ $member->full_name() }} Registration Form</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-md-11 col-lg-10 mx-auto">
                @include('components.forms.reunion_registration_form')
            </div>
        </div>
    </div>

    <div class="container-fluid">

        <div id="" class="d-flex flex-wrap align-items-stretch justify-content-center">

            <!-- Hotel information -->
            <div class="col-6 my-1" id="">

                <div class="card h-100">

                    @if($reunion->hotel)

                        <div class="">
                            <h2 id="" class="text-center text-light">Hotel Information</h2>
                        </div>

                        <div class="">
                            <img
                                src="{{ asset($reunion->hotel->picture != null ? $reunion->hotel->picture : '/images/hotel_default.jpg' ) }}"
                                class="mw-100"/>
                        </div>

                        <div class="">
                            <p class="my-1"><span
                                    class="hotelInfoLabel">Hotel:</span> {{ $reunion->hotel->name != null ? $reunion->hotel->name : 'Not Hotel Added Yet' }}
                            </p>

                            <p class="my-1"><span
                                    class="hotelInfoLabel">Location:</span> {{ $reunion->hotel->location != null ? $reunion->hotel->location : 'Not Hotel Location Added Yet' }}
                            </p>

                            <p class="my-1"><span
                                    class="hotelInfoLabel">Room:</span> {{ $reunion->hotel->cost != null ? '$' . $reunion->hotel->cost . '/per night (not including taxes and fees)' : 'Not Hotel Room Cost Added Yet' }}
                            </p>

                            <p class="my-1"><span
                                    class="hotelInfoLabel">Contact:</span> {{ $reunion->hotel->phone != null ? $reunion->hotel->phone : 'Not Hotel Contact Added Yet'  }}
                            </p>

                            @if($reunion->hotel->phone != null)
                                <p class="my-1"><span class="hotelInfoLabel">Additional Info:</span> Please call for any
                                    room upgrades.</p>
                            @endif

                            @if($reunion->hotel->book_room_link == null && $reunion->hotel->phone != null)
                                <p class="my-1">*** Please Call To Book Room ***</p>
                            @endif

                        </div>

{{--                        <div class="">--}}

{{--                            <div class="form-block-header mb-xl-3">--}}

{{--                                <h3 class="text-center">Hotel Amenities</h3>--}}
{{--                            </div>--}}

{{--                            <div class="">--}}

{{--                                <ul class="list-unstyled px-1">--}}

{{--                                    @if($reunion->hotel->features->isNotEmpty())--}}

{{--                                        @foreach($reunion->hotel->features as $hotel_feature)--}}

{{--                                            <li class="">{{ $hotel_feature->feature_desc }}</li>--}}

{{--                                        @endforeach--}}

{{--                                    @else--}}

{{--                                        <li class="text-center text-muted">We're still gathering information about the--}}
{{--                                            hotel and its amenities. Check back later for additional information--}}
{{--                                        </li>--}}

{{--                                    @endif--}}

{{--                                </ul>--}}

{{--                            </div>--}}
{{--                        </div>--}}

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
            </div>

            <!-- Activities information -->
            <div class="col-6 my-1" id="">

                <div class="card h-100">

                    <div class="">
                        <h2 id="" class="text-center text-light">Activities</h2>
                    </div>

                    @if($reunion->events->count() < 1)

                        <div class="">
                            <p class="text-center mt-3 emptyInfo">No Activities Added Yet</p>
                        </div>

                    @else

                        <div class="activities_content">

                            @foreach($reunion->events as $events)

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

            </div>

            <!-- Contact/Committee information -->
            <div class="col-6 my-1" id="">

                <div class="card h-100">

                    <div class="">
                        <h2 id="" class="text-center text-light">Committee Information</h2>
                    </div>

                    <div class="table-responsive">

                        <table id="" class="table table-hover">
                            <thead>
                            <tr>
                                <th><u>Title</u></th>
                                <th><u>Name</u></th>
                                <th><u>Email Address</u></th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($reunion->committee as $committee)
                                <tr>
                                    <td>{{ ucwords(str_ireplace('_', ' ', $committee->member_title)) }}</td>
                                    <td>{{ ucwords($committee->member_name) }}</td>
                                    <td><i>{{ $committee->member_email }}</i></td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>Web Designer</td>
                                <td>Tramaine Jackson</td>
                                <td><i>jackson.tramaine3@yahoo.com</i></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>

            <!-- Payment Information -->
            <div class="col-6 my-1" id="">

                <div class="card h-100">

                    <div class="">
                        <h2 class="text-center text-light">Payment Information</h2>
                    </div>

                    <div id="paper_payment_option" class="payment_option">

                        @php $committee_president = $reunion->committee()->president(); @endphp

                        @if($committee_president->count() > 0)
                            <h2>Paper Payment</h2>
                            <p>Please make all checks payable
                                to {{ $committee_president->firstname . ' ' . $committee_president->lastname }}. Checks
                                can be sent to:</p>
                        @endif

                        @if($committee_president->count() > 0)

                            @if(strlen($committee_president->family_member->full_address()) > 5)
                                <p id="checks_address">
                                    <span>Address:</span>
                                    <span>{{ $committee_president->family_member->full_address()}}</span></span>
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
                            <p class="text-danger" id="checks_address">Committee Members Not Completed Yet. Once Members
                                Addedd, An Address Will Be Available</p>
                        @endif
                    </div>

                    <div id="electronic_payment_option" class="payment_option">
                        <h2>Electronic Payment</h2>
                        <p>All electronic payments can be sent to administrator@jgreunion.com for anyone who already has
                            a paypal account.</p>
                        <p>Click <a href=" https://www.paypal.com/pools/c/85OCIIUoUB" target="_blank">here</a> to go to
                            paypal.</p>
                    </div>

                    @if(!Auth::check())

                        <div class="" id="registrationReminderMsg">
                            <p>Please do not send any payment without completing the registration form first. You can
                                click <span id="registrationLink" class="d-none d-sm-inline" data-toggle="modal"
                                            data-target="#registration_modal">here</span><a
                                    href="/upcoming_reunion/{{$reunion->id}}/registration_form" id="registrationLink"
                                    class="d-sm-none d-inline">here</a> to complete your registration for the upcoming
                                reunion.</p>
                        </div>

                    @else

                        <div class="" id="registrationReminderMsg">
                            <p class="text-center">You are currently logged in as an admin. Please select <a
                                    href="/registrations/create/{{$reunion->id}}" id="registrationLink"
                                    class="d-inline">here</a> to complete the registration for someone else.</p>
                        </div>

                    @endif

                </div>
            </div>

        </div>

    </div>

</x-app-layout>
