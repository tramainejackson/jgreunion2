<div id="reunion_update_form" class="my-3 container-fluid">
    <div class="row">

        <div class="col">
            <h2 class="text-left">Edit {{ ucwords($reunion->reunion_city) }} Reunion</h2>
        </div>

        @if($reunion->reunion_complete == 'N')
            <div class="col-6">
                <div id="complete_reunion_btns" class="d-flex align-content-center justify-content-around">

                    <div class="">
                        <h5 class="text-center mb-0">Is Reunion Completed?</h5>
                    </div>

                    <div class="">
                        <button type="button"
                                class="mx-auto btn btn-sm btn-outline-success completeReunionBtn">
                            <input type="checkbox" name="reunion_complete" value="Y"
                                   hidden/>Yes
                        </button>

                        <button type="button"
                                class="mx-auto btn btn-sm btn-success completeReunionBtn active">
                            <input type="checkbox" name="reunion_complete" value="N"
                                   hidden checked/>No
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <div class="row">

        <div class="form-group mr-3 col-4">
            <label for="" class="d-block form-control-label">Paper Registration Form</label>

            <div class="file-upload-wrapper mb-2">
                <input type="file"
                       name="paper_reg_form"
                       class="form-control"
                       value=""/>
            </div>
        </div>

        @if($reunion->registration_form != null)
            <div class="align-items-center col d-flex">
                <label for="" class="d-block form-control-label"></label>

                <a href="{{ asset('storage/' . str_ireplace('public/', '', $reunion->registration_form)) }}"
                   class="btn btn-link btn-outline-info text-dark"
                   download="{{ $reunion->reunion_year }}_Registration_Form">View Registration Form</a>
            </div>
        @endif
    </div>

    <div class="form-outline mb-2" data-mdb-input-init>
        <input type="text"
               name="reunion_city"
               class="form-control"
               value="{{ old('reunion_city') ? old('reunion_city') : $reunion->reunion_city }}"/>

        <label class="form-label" for="reunion_city">City</label>
    </div>

    <div class="form-outline mb-2">
        <select class="form-control" name="reunion_state" data-mdb-select-init>
            @foreach($states as $state)
                <option
                    value="{{ $state->state_abb }}" {{ old('reunion_state') && old('reunion_state') == $state->state_abb ? 'selected' : ($reunion->reunion_state == $state->state_abb ? 'selected' : '') }}>{{ $state->state_name }}</option>
            @endforeach
        </select>

        <label class="form-label select-label" for="reunion_state">State</label>
    </div>

    <div class="form-outline mb-2">
        <select class="form-control" name="reunion_year" data-mdb-select-init>
            @for($i=0; $i <= 10; $i++)
                <option
                    value="{{ $carbonDate->addYear()->year }}" {{ old('reunion_year') && old('reunion_year') == $carbonDate->year ? 'selected' : ($reunion->reunion_year == $carbonDate->year ? 'selected' : '') }}>{{ $carbonDate->year }}</option>
            @endfor
        </select>

        <label class="form-label select-label" for="reunion_state">Year</label>
    </div>

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text" id="">$</span>

                <div class="form-outline mb-2" data-mdb-input-init>
                    <input type="number"
                           name="adult_price"
                           class="form-control"
                           value="{{ old('adult_price') ? old('adult_price') : $reunion->adult_price }}"
                           step="0.01"
                           placeholder="Price For Adult 18-Older"/>

                    <label class="form-label" for="adult_price">Adult Price</label>
                </div>

                <span class="input-group-text" id="basic-addon1">Per Adult</span>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text" id="basic-addon1">$</span>

                <div class="form-outline mb-2" data-mdb-input-init>
                    <input type="number"
                           name="youth_price"
                           class="form-control"
                           value="{{ old('youth_price') ? old('youth_price') : $reunion->youth_price }}"
                           step="0.01"
                           placeholder="Price For Youth 4-18"/>
                    <label class="form-label" for="youth_price">Youth Price</label>
                </div>

                <span class="input-group-text" id="basic-addon1">Per Youth</span>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="input-group">
                <span class="input-group-text" id="">$</span>

                <div class="form-outline mb-2" data-mdb-input-init>
                    <input type="number"
                           name="child_price"
                           class="form-control"
                           value="{{ old('child_price') ? old('child_price') : $reunion->child_price }}"
                           step="0.01"
                           placeholder="Price For Children 3-Under"/>
                    <label class="form-label" for="child_price">Child Price</label>
                </div>

                <span class="input-group-text" id="basic-addon1">Per Child</span>
            </div>
        </div>
    </div>

    <!-- Hotel Information Section -->
    <div class="form-block-header">
        <h3 class="">Hotel Information</h3>
    </div>

    <div class="form-outline mb-2" data-mdb-input-init>
        <input type="text"
               name="hotel_name"
               class="form-control"
               value="{{ $reunion->hotel ? $reunion->hotel->name : '' }}"
               placeholder="Enter Hotel Name"/>

        <label class="form-label" for="member_title">Hotel Name</label>
    </div>

    <div class="form-outline mb-2" data-mdb-input-init>
        <input type="text"
               name="hotel_address"
               class="form-control"
               value="{{ $reunion->hotel ? $reunion->hotel->location : '' }}"
               placeholder="Enter Hotel Address"/>

        <label class="form-label" for="hotel_address">Hotel Address</label>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <div class="form-outline mb-2" data-mdb-input-init>
                <input type="tel"
                       name="hotel_phone"
                       class="form-control"
                       value="{{ $reunion->hotel ? $reunion->hotel->phone : '' }}"
                       placeholder="Enter Hotel Phone Number"/>

                <label class="form-label" for="hotel_phone">Hotel Phone Number</label>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="input-group">
                <span class="input-group-text"><i class="fa fa-dollar" aria-hidden="true"></i></span>

                <div class="form-outline mb-2" data-mdb-input-init>
                    <input type="text"
                           name="hotel_cost"
                           class="form-control"
                           value="{{ $reunion->hotel ? $reunion->hotel->cost : '' }}"
                           placeholder="Enter Hotel Room Nightly Cost"/>

                    <label class="form-label" for="hotel_cost">Hotel Room Cost</label>
                </div>

                <span class="input-group-text" id="">Per Night</span>
            </div>
        </div>
    </div>

    <div class="input-group">
        <span class="input-group-text">https://</span>

        <div class="form-outline mb-2" data-mdb-input-init>
            <input type="text"
                   name="hotel_room_booking"
                   class="form-control"
                   value="{{ $reunion->hotel ? $reunion->hotel->book_room_link : '' }}"
                   placeholder="Enter Link To Book A Room"/>

            <label class="form-label" for="hotel_room_booking">Hotel Room Booking Link</label>
        </div>
    </div>
</div>
