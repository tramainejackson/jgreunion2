<x-guest-layout>

    <div class="container" id="loginPage">
        <div id="login_div_wrapper">
            <div id="login_div">
                <h2 id="reg_form_header">Forgot Password</h2>

                <div id="login_form_input">
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-outline my-2" data-mdb-input-init>
                            <input id="username"
                                   type="text"
                                   class="form-control"
                                   name="email"
                                   value="{{ old('username') }}"
                                   placeholder="Enter Email Address" required autofocus>

                            <label for="email" class="form-label">Email Address</label>

                            @if ($errors->has('username'))
                                <span class="help-block">
										<strong>{{ $errors->first('username') }}</strong>
									</span>
                            @endif
                        </div>

                        <div class="flex items-center justify-end mt-2">
                            <div class="">
                                <button type="submit" class="btn btn-info" id="">Email Password Reset Link</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
