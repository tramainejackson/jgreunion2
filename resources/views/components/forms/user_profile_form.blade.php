<div id="user_profile" class="mt-2 mb-5">
    <div id="profile_photo_div">

        <img id="profile_photo" src="{{ $family_member->get_profile_image() }}" class="img-fluid img-thumbnail"/>

        <input type="file" name="profile_image" id="change_img_btn"/>
    </div>

    <div id="general_information" class="profile_info_div mt-3">
        <div class="header_div">
            <h3 id="general_header">Personal Information</h3>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="form-outline mb-2" data-mdb-input-init>
                    <input type="text"
                           name="username"
                           class="form-control"
                           value="{{ $family_member->user ? $family_member->user->username : 'No User Account' }}"
                           disabled/>

                    <label class="form-label" for="username">Username</label>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="form-outline mb-2" data-mdb-input-init>
                    <input type="password"
                           name="password"
                           class="form-control"
                           value="value"
                           placeholder="Password"
                           disabled/>

                    <label class="form-label" for="password">Password</label>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-12 col-md-4">
            <div class="form-outline mb-2" data-mdb-input-init>
                <input type="text"
                       name="firstname"
                       class="form-control"
                       value="{{ old('firstname') ? old('firstname') : $family_member->firstname }}"
                       placeholder="Enter First Name"/>

                <label class="form-label" for="firstname">Firstname</label>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-outline mb-2" data-mdb-input-init>
                <input type="text"
                       name="lastname"
                       class="form-control"
                       value="{{ old('lastname') ? old('lastname') : $family_member->lastname }}"
                       placeholder="Enter Last Name"/>

                <label class="form-label" for="lastname">Lastname</label>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-outline" data-mdb-datepicker-init data-mdb-input-init data-mdb-disable-future="true" data-mdb-format="yyyy-mm-dd">
                <input type="text"
                       name="date_of_birth"
                       id="datetimepicker-dateOptions"
                       class="form-control"
                       value="{{ old('date_of_birth') ? old('date_of_birth') : $family_member->date_of_birth }}"/>

                <label class="form-label" for="date_of_birth">DOB</label>
            </div>
        </div>
    </div>

    <div class="form-outline mb-2" data-mdb-input-init>
        <input type="text"
               name="address"
               class="form-control"
               value="{{ old('address') ? old('address') : $family_member->address }}"
               placeholder="Enter Address"/>

        <label class="form-label" for="address">Address</label>
    </div>

    <div class="row mb-2">
        <div class="col-12 col-md-4">
            <div class="form-outline" data-mdb-input-init>
                <input type="text"
                       name="city"
                       class="form-control"
                       value="{{ old('city') ? old('city') : $family_member->city }}"
                       placeholder="Enter City"/>

                <label class="form-label" for="city">City</label>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-outline">
                <select class="form-control" name="state" data-mdb-select-init>
                    @foreach($states as $state)
                        <option
                            value="{{ $state->state_abb }}" {{ $state->state_abb == $family_member->state ? 'selected' : '' }}>{{ $state->state_name }}</option>
                    @endforeach
                </select>

                <label class="form-label select-label" for="state">State</label>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="form-outline" data-mdb-input-init>
                <input type="number"
                       name="zip"
                       class="form-control"
                       max="99999"
                       value="{{ old('zip') ? old('zip') : $family_member->zip }}"
                       placeholder="Enter Zip Code"/>

                <label class="form-label" for="zip">Zip Code</label>
            </div>
        </div>
    </div>

    <div class="form-outline mb-2" data-mdb-input-init>
        <input type="email"
               name="email"
               class="form-control"
               value="{{ old('email') ? old('email') : $family_member->email }}"
               placeholder="Enter Email Address"/>

        <label class="form-label" for="email">Email</label>
    </div>

    <div class="form-outline mb-2" data-mdb-input-init>
        <input type="tel"
               name="phone"
               class="form-control"
               value="{{ old('phone') ? old('phone') : $family_member->phone }}"
               placeholder="Enter Phone Number"/>

        <label class="form-label" for="phone">Phone</label>
    </div>

    {{--                    <div class="form-group">--}}
    {{--                        @if($user["show_contact"] == "Y")--}}
    {{--                            <span class="visibleHeaderSpan">Visible to all</span>--}}
    {{--                            <input type="checkBox"--}}
    {{--                                   class="visibleCheckBox"--}}
    {{--                                   name="show_contact" value="Y"--}}
    {{--                                   checked/>--}}
    {{--                        @else--}}
    {{--                            <span class="visibleHeaderSpan">Visible to all</span>--}}
    {{--                            <input type="checkBox"--}}
    {{--                                   class="visibleCheckBox"--}}
    {{--                                   name="show_contact" value="Y"/>--}}
    {{--                        @endif--}}
    {{--                    </div>--}}

    <div class="form-outline mb-2">
        <select class="form-control" name="age_group" data-mdb-select-init>
            <option value="adult" {{ $family_member->age_group == 'adult' ? 'selected' : '' }}>Adult
            </option>
            <option value="youth" {{ $family_member->age_group == 'youth' ? 'selected' : '' }}>Youth
            </option>
            <option value="child" {{ $family_member->age_group == 'child' ? 'selected' : '' }}>Child
            </option>
        </select>

        <label class="form-label select-label" for="age_group">Age Group</label>
    </div>

    <div class="form-outline mb-2">
        <select class="form-control" name="mail_preference" data-mdb-select-init>
            <option value="M" {{ $family_member->mail_preference == 'M' ? 'selected' : '' }}>Mail
            </option>
            <option value="E" {{ $family_member->mail_preference == 'E' ? 'selected' : '' }}>Email
            </option>
        </select>

        <label class="form-label select-label" for="mail_preference">Mail Preference</label>
    </div>

    <div class="form-outline mb-2" data-mdb-input-init>
                        <textarea class="form-control"
                                  name="notes"
                                  rows="3">{{ $family_member->notes }}
                        </textarea>

        <label class="form-label" for="notes">Additional Notes</label>
    </div>
</div>
