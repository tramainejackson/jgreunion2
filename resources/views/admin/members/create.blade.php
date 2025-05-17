<x-app-layout>

    <div class="container-fluid" id="">

        <x-admin-jumbotron>Create Member</x-admin-jumbotron>

        @include('components.nav')

        <div class="row">

            <div class="col-11 col-lg-8 membersForm mx-auto mt-lg-1">

                <a href="{{ route('members.index') }}" class="btn btn-info btn-lg ms-4 ms-lg-3 mt-2 mt-lg-4 position-absolute start-0">All Members</a>

                <h1 class="mb-4 mt-3 pt-5 pt-lg-0">Create New Member</h1>

                <form action="{{ route('members.store') }}" method="POST">
                    @csrf

                    <div class="form-outline mb-2" data-mdb-input-init>
                        <input type="text"
                               name="firstname"
                               class="form-control"
                               value="{{ old('firstname') ? old('firstname') : '' }}"
                               placeholder="Enter First Name"/>

                        <label class="form-label" for="firstname">Firstname</label>

                        @if($errors->has('firstname'))
                            <span class="text-danger">First Name cannot be empty</span>
                        @endif
                    </div>

                    <div class="form-outline mb-2" data-mdb-input-init>
                        <input type="text"
                               name="lastname"
                               class="form-control"
                               value="{{ old('lastname') ? old('lastname') : '' }}"
                               placeholder="Enter Last Name"/>

                        <label class="form-label" for="lastname">Lastname</label>

                        @if($errors->has('lastname'))
                            <span class="text-danger">Last Name cannot be empty</span>
                        @endif
                    </div>

                    <div class="form-outline mb-2" data-mdb-input-init>
                        <input type="text"
                               name="email"
                               class="form-control"
                               value="{{ old('email') ? old('email') : '' }}"
                               placeholder="Enter Email Address"/>

                        <label class="form-label" for="email">Email Address</label>
                    </div>

                    <div class="form-outline mb-2" data-mdb-input-init>
                        <input type="text"
                               name="address"
                               class="form-control"
                               value="{{ old('address') ? old('address') : '' }}"
                               placeholder="Enter Address"/>
                        <label class="form-label" for="address">Address</label>
                    </div>

                    <div class="row">
                        <div class="mb-2 col-4">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="city"
                                       class="form-control"
                                       value="{{ old('city') ? old('city') : '' }}"
                                       placeholder="Enter City"/>

                                <label class="form-label" for="city">City</label>
                            </div>
                        </div>

                        <div class="mb-2 col-4">
                            <div class="form-outline">
                                <select class="form-control" name="state" data-mdb-select-init>
                                    @foreach($states as $state)
                                        <option
                                            value="{{ $state->state_abb }}" {{ old('reunion_state') && old('state') == $state->state_abb ? 'selected' : '' }}>{{ $state->state_name }}</option>
                                    @endforeach
                                </select>

                                <label class="form-label select-label" for="state">State</label>
                            </div>
                        </div>

                        <div class="mb-2 col-4">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="number"
                                       name="zip"
                                       class="form-control"
                                       max="99999"
                                       value="{{ old('zip') ? old('zip') : '' }}"
                                       placeholder="Enter Zip Code"/>

                                <label class="form-label" for="zip">Zip Code</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-outline mb-2" data-mdb-input-init>
                        <input type="tel"
                               name="phone"
                               class="form-control"
                               value="{{ old('phone') ? old('phone') : '' }}"
                               placeholder="Enter Phone Numer"/>

                        <label class="form-label" for="city">Phone</label>
                    </div>

                    <div class="form-outline mb-2">
                        <select class="form-control" name="age_group" data-mdb-select-init>
                            <option value="adult" {{ old('age_group') && old('age_group') == 'M' ? 'selected' : '' }}>
                                Adult
                            </option>
                            <option value="youth" {{ old('age_group') && old('age_group') == 'E' ? 'selected' : '' }}>
                                Youth
                            </option>
                            <option value="child" {{ old('age_group') && old('age_group') == 'E' ? 'selected' : '' }}>
                                Child
                            </option>
                        </select>

                        <label class="form-label select-label" for="age_group">Age Group</label>
                    </div>

                    <div class="form-outline mb-2">
                        <select class="form-control" name="mail_preference" data-mdb-select-init>
                            <option
                                value="M" {{ old('mail_preference') && old('mail_preference') == 'M' ? 'selected' : '' }}>
                                Mail
                            </option>
                            <option
                                value="E" {{ old('mail_preference') && old('mail_preference') == 'E' ? 'selected' : '' }}>
                                Email
                            </option>
                        </select>

                        <label class="form-label select-label" for="mail_preference">Mail Preference</label>
                    </div>

                    <div class="form-group mt-3">
                        <button class="btn btn-primary form-control" type="submit">Create New Member</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
