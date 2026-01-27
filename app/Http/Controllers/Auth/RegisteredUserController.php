<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Registration_Admin;
use App\Mail\Registration_User;
use App\Mail\Registration_Account;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //Track registration attempts
        Log::info('There was an attempt to register a new user');

        $this->validate($request, [
            'firstname' => 'required|max:30',
            'lastname' => 'required|max:30',
            'email' => 'required|string|email|max:100|unique:family_members',
            'username' => 'required|string|max:100|unique:users',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user) {
            $user->member()->create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        //Track successful registration attempts
        Log::info('There was a successful registration by ' . $request->username);

        if (App::environment('local')) {
            //The environment is local
            Mail::to('jackson.tramaine3@gmail.com')->send(new Registration_Account($user));
        } else {
            Mail::to($user->email)->send(new Registration_Account($user));
            Mail::to('jacksongreenreunion@gmail.com')->send(new Registration_Account($user));
        }

        return redirect('members/' . $user->member->id);
    }
}
