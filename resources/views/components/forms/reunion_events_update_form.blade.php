<div id="reunion_events_form" class="my-5 container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Reunion Events Section -->
            <div class="form-block-header mb-2">
                <h3 class="">Events
                    <button type="button" id="addReunionEvent" class="btn btn-outline-success">Add New
                        Event
                    </button>
                </h3>
            </div>

            @if($reunion->events->isEmpty())
                <div class="form-row emptyEvents">
                    <h2 class="text-center">No Events Added Yet For This Reunion</h2>
                </div>
            @endif

            @foreach($reunion_events as $event)
                @php $eventDate = new Carbon\Carbon($event->event_date); @endphp

                <div class="row mb-2" id="">
                    <div class="col-3">
                        <div class="form-outline mb-2" data-mdb-datepicker-init data-mdb-input-init data-mdb-format="mm-dd-yyyy">
                            <input type="text"
                                   name="event_date[]"
                                   class="form-control myDatePicker"
                                   placeholder="Select a date"
                                   value="{{ $eventDate->format('m-d-Y') }}"/>

                            <label class="form-label" for="event_date">Event Date</label>
                        </div>
                        <input type="text" name="event_id[]" class="hidden" value="{{ $event->id }}" hidden/>
                    </div>

                    <div class="col-3">
                        <div class="form-outline mb-2" data-mdb-input-init>
                            <input type="text"
                                   name="event_location[]"
                                   class="form-control"
                                   placeholder="Enter The Event Location"
                                   value="{{ $event->event_location }}"/>

                            <label class="form-label" for="event_location">Event Location</label>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-outline mb-2" data-mdb-input-init>
                                <textarea class="form-control"
                                          name="event_description[]"
                                          rows="1">{{ $event->event_description }}
                                </textarea>

                            <label class="form-label" for="event_description">Description</label>
                        </div>
                    </div>

                    <div class="form-group col-2">
                        <button type="button" class="btn btn-outline-danger w-100 deleteCommitteeMemberBtn">Delete Event
                            <input type="text" name="remove_reunion_event[]" value="N" hidden>
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="row d-none mb-2" id="new_reunion_event_row_default">
                <div class="col-3">
                    <div class="form-outline mb-2" data-mdb-format="mm-dd-yyyy">
                        <input type="text"
                               name="event_date[]"
                               class="form-control myDatePicker"
                               placeholder="Select a date"
                               disabled/>

                        <label class="form-label" for="event_date">Event Date</label>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-outline mb-2">
                        <input type="text"
                               name="event_location[]"
                               class="form-control"
                               placeholder="Enter The Event Location" disabled/>

                        <label class="form-label" for="event_location">Event Location</label>
                    </div>
                </div>

                <div class="col-4">
                    <div class="form-outline mb-2">
                                <textarea class="form-control"
                                          name="event_description[]"
                                          rows="1"
                                          disabled>
                                </textarea>

                        <label class="form-label" for="event_description">Description</label>
                    </div>
                </div>

                <div class="form-group col-2">
                    <button type="button" class="btn btn-danger w-100 removeReunionEvent">Remove Event</button>
                </div>
            </div>
        </div>
    </div>
</div>
