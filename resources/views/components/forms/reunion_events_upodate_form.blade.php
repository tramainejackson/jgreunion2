<div id="reunion_events_form" class="my-5 container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Reunion Events Section -->
            <div class="form-block-header">
                <h3 class="">Events
                    <button type="button" id="addReunionEvent" class="btn btn-outline-success mb-2">Add New
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

                <div class="form-row">
                    <div class="form-group col-3">
                        <label class="form-label" for="member_title">Event Date</label>

                        <input type="text" name="event_date[]" class="form-control datetimepicker"
                               value="{{ $eventDate->format('m/d/Y') }}" placeholder="Select A Date"/>
                        <input type="text" name="event_id[]" class="hidden" value="{{ $event->id }}" hidden/>
                    </div>
                    <div class="form-group col-3">
                        <label class="form-label" for="member_title">Event Location</label>
                        <input type="text" name="event_location[]" class="form-control"
                               placeholder="Enter The Event Location" value="{{ $event->event_location }}"/>
                    </div>
                    <div class="form-group col-4">
                        <label class="form-label" for="dl_id">Description</label>
                        <textarea class="form-control" name="event_description[]"
                                  placeholder="Enter A Description of The Event"
                                  rows="1">{{ $event->event_description }}</textarea>
                    </div>
                    <div class="form-group col-2">
                        <label class="form-label m-0" for="">&nbsp;</label>

                        <button type="button" class="btn btn-danger w-100 m-0"
                                onclick="event.preventDefault(); removeReunionEvent({{ $event->id }});">Delete
                            Event
                        </button>
                    </div>
                </div>
            @endforeach

            <div class="row d-none mb-2" id="new_reunion_event_row_default">
                <div class="col-3">
                    <div class="form-outline mb-2">
                        <input type="text"
                               name="event_date[]"
                               class="form-control myDatePicker"
                               placeholder="Select a date"
                               disabled/>

                        <label class="form-label" for="event_date">Event Date</label>
                    </div>
                </div>

                <div class="col-3">
                    <div class="form-outline mb-2" data-mdb-input-init>
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
                    <button type="button" class="btn btn-danger w-100 removeReunionEventRow">Remove</button>
                </div>
            </div>
        </div>
    </div>
</div>
