<x-guest-layout>

    <div class="container" id="loginPage">
        <div id="login_div_wrapper">
            <div id="login_div">
                <h2 id="reg_form_header">Create New Password</h2>

                <div id="login_form_input">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-outline my-2" data-mdb-input-init>
                            <input id="username"
                                   type="text"
                                   class="form-control"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="Enter Email Address" required autofocus>

                            <label for="email" class="form-label">Email Address</label>

                            @if ($errors->has('email'))
                                <span class="help-block">
										<strong>{{ $errors->first('email') }}</strong>
									</span>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="form-outline my-2" data-mdb-input-init>
                            <input id="password"
                                   type="text"
                                   class="form-control"
                                   name="password"
                                   value="{{ old('password') }}"
                                   placeholder="Enter New Password" required autofocus>

                            <label for="password" class="form-label">New Password</label>

                            @if ($errors->has('password'))
                                <span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
                            @endif
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-outline my-2" data-mdb-input-init>
                            <input id="password_confirmation"
                                   type="text"
                                   class="form-control"
                                   name="password_confirmation"
                                   value="{{ old('password_confirmation') }}"
                                   placeholder="Enter Confirmation Password" required autofocus>

                            <label for="password_confirmation" class="form-label">Confirm Password</label>

                            @if ($errors->has('password_confirmation'))
                                <span class="help-block">
										<strong>{{ $errors->first('password_confirmation') }}</strong>
									</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-2">
                            <div class="">
                                <button type="submit" class="btn btn-info" id="">Reset Password</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
