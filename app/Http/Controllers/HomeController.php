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
        $this->middleware('auth')->except(['store', 'home']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$user = Auth::user();
		$userPhone1 = substr($user["phone"], 0, 3);
		$userPhone2 = substr($user["phone"], 3, 3);
		$userPhone3 = substr($user["phone"], 6, 4);
		$newReunionCheck = Reunion::where('reunion_complete', 'N')->get()->last();
		$states = State::all();

        return view('home', compact('user', 'userPhone1', 'userPhone1', 'userPhone2', 'userPhone3', 'newReunionCheck', 'states'));
    }

    /**
     * Show the application home page.
     *
     * @return \Illuminate\Http\Response
    */
    public function home()
    {
		$reunions = Reunion::orderby('reunion_year', 'desc')->get();
		$newReunionCheck = Reunion::active();

		$newReunionCheck->count() > 0 ? $newReunionCheck = $newReunionCheck->first() : $newReunionCheck = null;

		return view('welcome', compact('reunions', 'newReunionCheck'));
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

        return view('home', compact('user', 'userPhone1', 'rows', 'userPhone1', 'userPhone2', 'userPhone3', 'newReunionCheck', 'states'));
    }

	/**
     * Show the application settings for admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function settings()
    {
        return view('admin.settings.edit');
    }

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
     * Update the settings for the site.
     *
     * @return \Illuminate\Http\Response
    */
    public function update_settings(Request $request) {
		$counter = 0;

		if($request->hasFile('photo')) {
			foreach($request->file('photo') as $newImage) {
				// Count how many images already saved for every iteration
				$carouselCount = CarouselImage::all()->count();

				// Check to see if upload is an image
				if($newImage->guessExtension() == 'jpeg' || $newImage->guessExtension() == 'png' || $newImage->guessExtension() == 'gif' || $newImage->guessExtension() == 'webp' || $newImage->guessExtension() == 'jpg') {

					$addImage = new CarouselImage();
					// Check to see if images is too large
					if($newImage->getError() == 1) {
						$fileName = $request->file('photo')[0]->getClientOriginalName();
						$error .= "<li class='errorItem'>The file " . $fileName . " is too large and could not be uploaded</li>";
					} elseif($newImage->getError() == 0) {
						// Check to see if images is about 25MB
						// If it is then resize it
						if($newImage->getClientSize() < 25000000) {
							$image = Image::make($newImage->getRealPath())->orientate();
							$path = $newImage->store('public/images');

							// prevent possible upsizing
							// Create a larger version of the image
							// and save to large image folder
							$image->resize(1600, null, function ($constraint) {
								$constraint->aspectRatio();
								// $constraint->upsize();
							});

							if($image->save(storage_path('app/'. $path))) {}

							$addImage->path = str_ireplace('public', 'storage', $path);
							$addImage->height = $image->height();
							$addImage->width = $image->width();

							if($carouselCount < 10) {

								if($addImage->save()) {
									$counter++;
								}

							}

						} else {
							// Resize the image before storing. Will need to hash the filename first
							$path = $newImage->store('public/images');
							$image = Image::make($newImage)->orientate()->resize(1500, null, function ($constraint) {
								$constraint->aspectRatio();
								$constraint->upsize();
							});

							$image->save(storage_path('app/'. $path));
							$addImage->property_id = $showSeason->id;

							if($carouselCount < 10) {

								if($addImage->save()) {
									$counter++;
								}

							}
						}
					} else {
						$error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
					}
				} else {
					$error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
				}
			}

			return redirect()->back()->with('status', 'Images added successfully');
		}
	}

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
    */
    public function update(Request $request, FamilyMember $FamilyMember)
    {

    }

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_house_hold(Request $request) {
		$member = FamilyMember::find($request->FamilyMember);
		$addingMember = FamilyMember::find($request->houseMember);
		$maxFamilyID = FamilyMember::max('family_id');

		// If household members isn't empty then add a family ID
		// to all the parties
		if($member->family_id == null) {
			$newFamilyID = $maxFamilyID + 1;
			$member->family_id = $newFamilyID;
			$addingMember->family_id = $newFamilyID;

			if($addingMember->save()) {
				$member->save();
			}
		} else {
			$addingMember->family_id = $member->family_id;
			$addingMember->save();
		}

		$states = State::all();
		$members = FamilyMember::orderby('firstname', 'asc')->get();
		$siblings = explode('; ', $member->sibling);
		$children = explode('; ', $member->child);
		$active_reunion = Reunion::where('reunion_complete', 'N')->first();

		$FamilyMembers = FamilyMember::where([
			['family_id', $member->family_id],
			['family_id', '<>', 'null']
		])->get();

		$potential_FamilyMembers = FamilyMember::where([
			['address', $member->address],
			['city', $member->city],
			['state', $member->state]
		])->get();

		$registered_for_reunion = Registration::where([
			['family_id', $member->family_id],
			['family_id', '<>', 'null']
		])
		->orwhere('dl_id', $member->id)
		->get();

		return view('admin.members.edit', compact('states', 'FamilyMembers', 'member', 'active_reunion', 'potential_FamilyMembers', 'members', 'siblings', 'children', 'registered_for_reunion'));
	}

	/**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function remove_house_hold(Request $request) {
		$member = FamilyMember::find($request->FamilyMember);
		$removeHH = FamilyMember::find($request->remove_hh);
		$familyID = $member->family_id;
		$familyMembers = FamilyMember::where([
			['family_id', $familyID],
			['family_id', '<>', null]
		])->get();

		// If household members is equal to 2 then remove
		// family ID from both users
		if($familyMembers->count() <= 2) {
			$removeHH->family_id = $member->family_id = null;

			if($removeHH->save()) {
				if($member->save()) {}
			}
		} else {
			$removeHH->family_id = null;
			$removeHH->save();
		}

		$states = State::all();
		$members = FamilyMember::orderby('firstname', 'asc')->get();
		$siblings = explode('; ', $member->sibling);
		$children = explode('; ', $member->child);
		$FamilyMembers = FamilyMember::where([
			['family_id', $member->family_id],
			['family_id', '<>', 'null']
		])->get();
		$potential_FamilyMembers = FamilyMember::where([
			['address', $member->address],
			['city', $member->city],
			['state', $member->state]
		])->get();
		$active_reunion = Reunion::where('reunion_complete', 'N')->first();
		$registered_for_reunion = Registration::where([
				['family_id', $member->family_id],
				['family_id', '<>', 'null']
			])
			->orwhere('dl_id', $member->id)
			->get();

		return view('admin.members.edit', compact('states', 'FamilyMembers', 'member', 'active_reunion', 'potential_FamilyMembers', 'members', 'siblings', 'children', 'registered_for_reunion'));
	}
}
