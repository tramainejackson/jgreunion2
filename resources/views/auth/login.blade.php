<x-guest-layout>

    <div class="container" id="loginPage">
        <div id="login_div_wrapper">
            <div id="login_div">
                <h2 id="reg_form_header">Login</h2>

                <div id="login_form_input">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-outline mb-2" data-mdb-input-init>
                            <input id="username"
                                   type="text"
                                   class="form-control"
                                   name="username"
                                   value="{{ old('username') }}"
                                   placeholder="Enter Username" required autofocus>

                            <label for="username" class="form-label">Username</label>

                            @if ($errors->has('username'))
                                <span class="help-block">
										<strong>{{ $errors->first('username') }}</strong>
									</span>
                            @endif
                        </div>

                        <div class="form-outline mb-2" data-mdb-input-init>
                            <input id="password"
                                   type="password"
                                   class="form-control"
                                   name="password"
                                   placeholder="Enter Password" required>

                            <label for="password" class="form-label">Password</label>

                            @if ($errors->has('password'))
                                <span class="help-block">
										<strong>{{ $errors->first('password') }}</strong>
									</span>
                            @endif
                        </div>

                        <!-- Remember me not working -->
                        <!-- <div class="form-group">
							<div class="">
								<div class="checkbox">
									<label>
										<input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
									</label>
								</div>
							</div>
						</div> -->

                        <div class="d-flex flex-row py-3 justify-content-center">
                            <div class="px-3">
                                <a class="btn btn-info" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>

                            <div class="px-3">
                                <button type="submit" class="btn btn-info" id="">Login</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
