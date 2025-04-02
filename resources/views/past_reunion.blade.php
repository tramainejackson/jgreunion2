<x-guest-layout>

    <div id="reunion_page" class="container-fluid">
        <div class="row">
            <div class="col-12 pt-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="pt-5 text-warning">Past Reunion</h1>
                <h2 class="">Jackson/Green Family Reunion {{ $reunion->reunion_year }}</h2>
                <h3 class="pb-5 text-decoration-underline">{{ $reunion->reunion_city }}
                    , {{ $reunion->reunion_state }}</h3>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-11 col-lg-9 mx-auto">
                <div class="pastReunionContent" id="hotel_information">
                    <h2 id="hotel_information_header" class="bg-primary border border-2 d-inline-block p-2 reunion_content_header rounded-4 text-white">Hotel Information</h2>

                    <div id="hotel_content" class="container-fluid white rounded">
                        <div class="row">
                            <div class="col-12 col-lg-9">

                                @if($reunion->hotel)
                                    <p class="hotelContentInfo my-1"><b
                                            class="font-weight-bold text-uppercase">Hotel:</b> {{ $reunion->hotel->name }}
                                    </p>

                                    <p class="hotelContentInfo my-1"><b
                                            class="font-weight-bold text-uppercase">Address:</b> {{ $reunion->hotel->location }}
                                    </p>

                                    <p class="hotelContentInfo my-1"><b
                                            class="font-weight-bold text-uppercase">Phone:</b> {{ $reunion->hotel->phone }}
                                    </p>

                                    <p class="hotelContentInfo my-1"><b
                                            class="font-weight-bold text-uppercase">Rooms:</b>
                                        ${{ $reunion->hotel->cost }}/per night plus taxes for a standard room</p>

                                @else

                                    <p class="hotelContentInfo my-1"><b class="font-weight-bold text-uppercase">No Hotel
                                            Information</b></p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>

                <div class="pastReunionContent" id="activities_information">
                    <h2 id="activities_information_header" class="bg-primary border border-2 d-inline-block p-2 reunion_content_header rounded-4 text-white">Activities</h2>

                    @if($events->count() < 1)

                        <div class="col-12 white rounded">
                            <p class="text-center mt-3 emptyInfo">No Activities Added</p>
                        </div>

                    @else

                        <div class="activities_content col-12 mx-auto my-2 py-2">

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

                <hr/>

                <div class="pastReunionContent" id="contact_information">
                    <h2 id="contact_information_header" class="bg-primary border border-2 d-inline-block p-2 reunion_content_header rounded-4 text-white">Committee Information</h2>

                    <div id="" class="white contactContent mx-0 px-0 px-md-2 table-responsive rounded">
                        <table id="contact_information_table" class="table text-center">
                            <thead>
                            <tr>
                                <th><u>Title</u></th>
                                <th><u>Name</u></th>
                                <th><u>Email Address</u></th>
                            </tr>

                            </thead>
                            <tbody>

                            @foreach($committee_members as $committee)

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

                <hr/>

                <div class="pastReunionContent" id="reunion_pictures">
                    <div class="">
                        <h2 id="contact_information_header" class="bg-primary border border-2 d-inline-block p-2 reunion_content_header rounded-4 text-white">Pictures</h2>
                    </div>

                    @if($reunion->images->isNotEmpty())

                        <div class="row">
                            <div id="mdb-lightbox-ui"></div>

                            <div class="mdb-lightbox">

                                @foreach($reunion->images as $image)
                                    <figure class="col-md-4">
                                        <!--Large image-->
                                        <a href="{{ asset(str_ireplace('images', 'images/lg', $image->path)) }}"
                                           data-size="1700x{{ $image->lg_height }}">
                                            <!-- Thumbnail-->
                                            <img src="{{ asset(str_ireplace('images', 'images/sm', $image->path)) }}"
                                                 class="img-fluid">
                                        </a>
                                    </figure>

                                @endforeach

                            </div>
                        </div>

                    @else

                        <div class="hidden">
                            <h2 class="">No reunion pictures</h2>
                        </div>

                    @endif
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
