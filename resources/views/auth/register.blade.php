<x-guest-layout>

    <div class="container" id="registerPage">
        <div id="registration_div_wrapper">
            <div id="registration_div">

                <h2 id="reg_form_header">Register</h2>

                <div id="reg_form_input">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="row">

                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"
                                           class="form-control"
                                           name="firstname"
                                           value="{{ old('firstname') ? old('firstname') : '' }}"
                                           placeholder="Enter First Name" autofocus>

                                    <label class="form-label" for="firstname">First Name</label>
                                </div>

                                @if ($errors->has('firstname'))
                                    <span class="text-danger">{{ $errors->first('firstname') }}</span>
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"
                                           class="form-control"
                                           name="lastname"
                                           value="{{ old('lastname') ? old('lastname') : '' }}"
                                           placeholder="Enter Last Name">

                                    <label class="form-label"  for="lastname">Last Name</label>
                                </div>

                                @if ($errors->has('lastname'))
                                    <span class="text-danger">{{ $errors->first('lastname') }}</span>
                                @endif
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="email"
                                           class="form-control"
                                           name="email"
                                           value="{{ old('email') ? old('email') : '' }}"
                                           placeholder="Enter Email Address">

                                    <label class="form-label" for="email">Email Address</label>
                                </div>

                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="text"
                                           class="form-control"
                                           name="username"
                                           value="{{ old('username') ? old('username') : '' }}"
                                           placeholder="Enter Username">

                                    <label class="form-label" for="username">Username</label>
                                </div>

                                @if ($errors->has('username'))
                                    <span class="text-danger">{{ $errors->first('username') }}</span>
                                @endif
                            </div>

                            <div class="col-12 col-md-6 mb-2">
                                <div class="form-outline" data-mdb-input-init>
                                    <input type="password"
                                           name="password"
                                           class="form-control"
                                           placeholder="Enter Password"/>

                                    <label class="form-label" for="password">Password</label>
                                </div>

                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
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
