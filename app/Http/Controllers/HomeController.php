<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Models\Registration;
use App\Models\Reunion;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reunions = Reunion::orderby('reunion_year', 'desc')->get();
        $newReunionCheck = Reunion::active();

        $newReunionCheck->count() > 0 ? $newReunionCheck = $newReunionCheck->first() : $newReunionCheck = null;

        return response()->view('welcome', compact('reunions', 'newReunionCheck'));
    }

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function distribution_list()
    {
		$user = Auth::user();
		$userPost = $user->post;
		$rows = $user->post->count();
		$userPhone1 = substr($user["phone"], 0, 3);
		$userPhone2 = substr($user["phone"], 3, 3);
		$userPhone3 = substr($user["phone"], 6, 4);
		$newReunionCheck = Reunion::where('reunion_complete', 'N')->get();
		$states = State::all();

        return response()->view('home', compact('user', 'userPhone1', 'rows', 'userPhone1', 'userPhone2', 'userPhone3', 'newReunionCheck', 'states'));
    }

	/**
     * Show the application contacts page.
     *
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        return response()->view('contact');
    }

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(FamilyMember $FamilyMember)
    {

    }

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request, FamilyMember $FamilyMember)
    {

    }
}
