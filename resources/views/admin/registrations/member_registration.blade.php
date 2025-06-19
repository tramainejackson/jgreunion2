<x-app-layout>

    <x-admin-jumbotron>Create New Registration For Upcoming Reunion</x-admin-jumbotron>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center pt-3 mb-3">{{ $member->full_name() }} Registration Form</h2>
            </div>
        </div>
    </div>

    <div class="container-fluid pb-5">
        <div class="row">
            <div class="col-12 col-md-11 col-lg-10 mx-auto">
                @include('components.forms.reunion_registration_form')
            </div>
        </div>
    </div>

    <div class="container-fluid mt-5">

        <!-- Hotel information -->
        <div class="row reunion_content my-5" id="hotel_information">

            @if($reunion->hotel)
                <div class="col-10 mx-auto">
                    <div class="card pb-3">

                        <img
                            src="{{ asset($reunion->hotel->picture != null ? $reunion->hotel->picture : '/images/hotel_default.jpg' ) }}"
                            class="card-img"/>

                        <div class="card-title">
                            <h1 class="text-center display-5 fw-bold my-3">Hotel Information</h1>
                        </div>

                        <div class="row pb-3">
                            <p class="col-12 ps-5 mb-1">
                                <span class="text-decoration-underline">Hotel:</span>
                                <span>{{ $reunion->hotel->name != null ? $reunion->hotel->name : 'No Hotel Added Yet' }}</span>
                            </p>

                            <p class="col-12 ps-5 mb-1">
                                <span class="text-decoration-underline">Location:</span>
                                <span>{{ $reunion->hotel->location != null ? $reunion->hotel->location : 'No Hotel Location Added Yet' }}</span>
                            </p>

                            <p class="col-12 ps-5 mb-1">
                                <span class="text-decoration-underline">Room:</span>
                                <span
                                    class="">{{ $reunion->hotel->cost != null ? '$' . $reunion->hotel->cost . '/per night (not including taxes and fees)' : 'No Hotel Room Cost Added Yet' }}</span>
                            </p>

                            <p class="col-12 ps-5 mb-1">
                                <span class="text-decoration-underline">Contact:</span>
                                <span
                                    class="">{{ $reunion->hotel->phone != null ? $reunion->hotel->phone : 'No Hotel Contact Added Yet'  }}</span>
                            </p>

                            @if($reunion->hotel->phone != null)
                                <p class="col-12 ps-5 mb-1">
                                    <span class="text-decoration-underline">Additional Info:</span> Please call for any
                                    room upgrades.
                                </p>
                            @endif

                            @if($reunion->hotel->book_room_link == null && $reunion->hotel->phone != null)
                                <p class="my-1">*** Please Call To Book Room ***</p>
                            @endif

                        </div>

                        @if($reunion->hotel->book_room_link !== null)

                            <div class="col-12 reunionInformationHeader py-1">
                                <h1 class="text-center display-5 fw-bold">Hotel Booking Link</h1>
                            </div>

                            <div class="col-12 text-center">
                                <a href="https://{{ $reunion->hotel->book_room_link }}" class="btn btn-warning btn-lg"
                                   target="_blank">Book Hotel Room</a>
                            </div>

                        @endif

                    </div>
                </div>

            @else

                <div class="col-12">
                    <h2 class="text-center">No Hotel Added Yet For This Reunion</h2>
                </div>

            @endif
        </div>

        <hr class="hr hr-blurry"/>

        <!-- Activities information -->
        <div class="row reunion_content my-5" id="activities_information" style="position: relative;">
            <div class="col-12 reunionInformationHeader py-1">
                <h1 class="text-center display-5 fw-bold">Reunion Itinerary</h1>
            </div>

            @if($events->count() < 1)

                <div class="col-12">
                    <p class="text-center mt-3 emptyInfo">No Activities Added Yet</p>
                </div>

            @else

                <div class="activities_content col-11 col-md-10 mx-auto my-2 py-2">
                    <div class="container-fluid">
                        <div class="row row-cols-md-3 row-cols-xl-4 justify-content-around align-content-center">

                            @foreach($events as $event)

                                <div class="col activitiesEvent">

                                    @php $eventDate = new Carbon\Carbon($event->event_date); @endphp

                                    <div class="">
                                        <h2 class="activitiesEventLocation">{{ $eventDate->format('m/d/Y') }}</h2>
                                    </div>

                                    <div class="">
                                        <ul class="activitiesDescription col-12">
                                            <li class="">
                                                <b><em>Location:&nbsp;</em></b>{{ $event->event_location }}
                                            </li>
                                            <li class=""><b><em>Event
                                                        Description:&nbsp;</em></b>{{ $event->event_description }}
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                            @endforeach

                        </div>
                    </div>
                </div>

            @endif

        </div>

        <hr class="hr hr-blurry"/>

        <!-- Payment Information -->
        <div class="row reunion_content my-5" id="payment_information">
            <div class="col-12 py-1 mb-3">
                <h1 class="text-center display-5 fw-bold">Payment Information</h1>
            </div>

            <div id="paper_payment_option" class="payment_option col-12 col-xl-6 mx-auto my-2">
                <div class="card border border-2 border-light-subtle h-100">

                    <div class="card-header">
                        <h2 class="text-center">Pay By Check</h2>
                    </div>

                    <div class="card-body">
                        @if($committee_president->count() > 0)
                            <p class="">Please make all checks payable to {{ $committee_president->member_name }}.</p>

                            @if(strlen($committee_president->family_member->full_address()) > 5)
                                <p id="checks_address">
                                    <span class="">Checks can be mailed to:</span>
                                    <span class="mb-0">{{ $committee_president->family_member->full_address()}}</span>
                                </p>
                            @endif

                            {{--                            @if($reunion->registration_form != null)--}}
                            {{--                                <p>Click--}}
                            {{--                                    <a href="{{ asset('storage/' . str_ireplace('public/', '', $reunion->registration_form)) }}"--}}
                            {{--                                       download="{{ $reunion->reunion_year }}_Registration_Form">here</a> to download--}}
                            {{--                                    the registration form.--}}
                            {{--                                </p>--}}
                            {{--                            @else--}}
                            {{--                                <p class="">Paper Registration Form Has Not Been Uploaded Yet</p>--}}
                            {{--                            @endif--}}
                        @else
                            <p class="text-danger" id="checks_address">Committee Members Not Completed Yet. Once
                                Committee is Finalized, The Information To Fill Out A Check Will Be Added For You</p>
                        @endif
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-around py-2">
                            <button type="button" class="paymentsFinePrint btn btn-outline-success"><i
                                    class="fas fa-square-check"></i>&nbsp;Partial payments accepted&nbsp;<i
                                    class="fas fa-square-check"></i></button>
                            <button type="button" class="paymentsFinePrint btn btn-outline-warning"><i
                                    class="fas fa-triangle-exclamation"></i>&nbsp;Any return checks will incur a $30
                                penalty fee&nbsp;<i class="fas fa-triangle-exclamation"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="electronic_payment_option" class="payment_option col-12 col-xl-6 mx-auto my-2">
                <div class="card border border-2 border-light-subtle h-100">

                    <div class="card-header">
                        <h2 class="text-center">Electronic Payment</h2>
                    </div>

                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="row row-cols-2 row-cols-md-4">

                                <div class="electronic_payment_div" id="paypal_div">
                                    <img src="{{ asset('images/paypal_icon.png') }}"
                                         class="img-fluid rounded rounded-circle px-5 mb-2" alt="">

                                    <p class="fs-4 fw-bold text-center text-decoration-underline display-6">Paypal</p>

                                    <p class="text-center"><a href="https://paypal.me/LoJackreunion" target="_blank">paypal.me/LoJackreunion</a>
                                    </p>
                                </div>

                                <div class="electronic_payment_div" id="cashapp_div">
                                    <img src="{{ asset('images/cashapp_icon.png') }}"
                                         class="img-fluid rounded rounded-circle px-5 mb-2" alt="">

                                    <p class="fs-4 fw-bold text-center text-decoration-underline display-6">Cash App</p>

                                    <p class="text-center"><a href="https://cash.app/$lojackreunion" target="_blank">$lojackreunion</a>
                                    </p>
                                </div>

                                <div class="electronic_payment_div" id="venmo_div">
                                    <img src="{{ asset('images/venmo_icon.png') }}"
                                         class="img-fluid rounded rounded-circle px-5 mb-2" alt="">

                                    <p class="fs-4 fw-bold text-center text-decoration-underline display-6">Venmo</p>

                                    {{--                                <p class="text-center">paypal.me/LoJackreunion</p>--}}
                                </div>

                                <div class="electronic_payment_div" id="zelle_div">
                                    <img src="{{ asset('images/zelle_icon.png') }}"
                                         class="img-fluid rounded rounded-circle px-5 mb-2" alt="">

                                    <p class="fs-4 fw-bold text-center text-decoration-underline display-6">Zelle</p>

                                    <p class="text-center">267-252-9481</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="hr hr-blurry"/>

        <!-- Contact/Committee information -->
        <div class="row reunion_content my-5" id="">
            <div class="col-12 col-md-10 reunionInformationHeader py-1 mx-auto">
                <h1 class="text-center display-5 fw-bold">Committee Information</h1>
            </div>

            <div class="col-12 col-md-10 mx-auto">
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
    </div>

</x-app-layout>
