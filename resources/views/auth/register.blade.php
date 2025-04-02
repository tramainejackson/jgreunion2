<x-guest-layout>

    <div class="container" id="registerPage">
        <div id="registration_div_wrapper">
            <div id="registration_div">

                <h2 id="reg_form_header">Register</h2>

                <div id="reg_form_input">
                    <form action="{{ route('register_user') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"
                                           class="form-control"
                                           name="first_name"
                                           value="{{ old('first_name') ? old('first_name') : '' }}"
                                           placeholder="Enter Firstname" autofocus>

                                    <label class="form-label" for="first_name">First Name</label>

                                    @if ($errors->has('first_name'))
                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"
                                           class="form-control"
                                           name="last_name"
                                           value="{{ old('last_name') ? old('last_name') : '' }}"
                                           placeholder="Enter Firstname">

                                    <label class="form-label"  for="last_name">Last Name</label>

                                    @if ($errors->has('last_name'))
                                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="email"
                                           class="form-control"
                                           name="email"
                                           value="{{ old('email') ? old('email') : '' }}"
                                           placeholder="Enter Email Address">

                                    <label class="form-label" for="email">Email Address</label>

                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"
                                           class="form-control"
                                           name="username"
                                           value="{{ old('username') ? old('username') : '' }}"
                                           placeholder="Enter Username">

                                    <label class="form-label" for="last_name">Username</label>

                                    @if ($errors->has('username'))
                                        <span class="text-danger">{{ $errors->first('username') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="Enter Password"/>

                                    <label class="form-label" for="password">Password</label>

                                    @if ($errors->has('password'))
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="password"
                                           name="password_confirmation"
                                           class="form-control"
                                           placeholder="Confirm Password"/>

                                    <label class="form-label" for="password_confirmation">Confirm Password</label>

                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" id="" class="btn btn-info">Register</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
