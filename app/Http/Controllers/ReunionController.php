<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Reunion;
use App\Models\ReunionCommittee;
use App\Models\ReunionEvent;
use App\Models\FamilyMember;
use App\Models\State;
use App\Models\ReunionHotel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class ReunionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'show_past_reunion']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $reunions = Reunion::orderby('reunion_year', 'desc')->get();

        return response()->view('admin.reunions.index', compact('reunions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $states = State::all();
        $members = FamilyMember::orderby('firstname', 'asc')->get();
        $carbonDate = Carbon::now()->subYear();

        return response()->view('admin.reunions.create', compact('states', 'carbonDate', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // Check for any active reunions and make completed_reunion
        if (Reunion::active()->count() > 0) {
            $completeReunion = Reunion::active()->first();

            $completeReunion->reunion_complete = 'Y';

            if ($completeReunion->save()) {
            }
        }

        $reunion = new Reunion();
        $reunion->reunion_city = $request->reunion_city;
        $reunion->reunion_state = $request->reunion_state;
        $reunion->reunion_year = $request->reunion_year;
        $reunion->adult_price = $request->adult_price;
        $reunion->youth_price = $request->youth_price;
        $reunion->child_price = $request->child_price;

        if ($reunion->save()) {
            for ($x = 0; $x < count($request->member_title); $x++) {
                // Create New Reunion Object
                $committee_member = new ReunionCommittee();

                // Get member from distro list
                $member = FamilyMember::find($request->dl_id[$x]);

                $committee_member->family_member_id = $member->id;
                $committee_member->reunion_id = $reunion->id;
                $committee_member->member_name = $member->firstname . ' ' . $member->lastname;
                $committee_member->member_title = $request->member_title[$x];
                $committee_member->member_email = $member->email;
                $committee_member->member_phone = $member->phone;

                if ($committee_member->save()) {

                }
            }

            return redirect()->action('ReunionController@edit', $reunion)->with('status', 'Reunion Created Succssfully');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Reunion $reunion
     * @return Response
     */
    public function show(Reunion $reunion)
    {
        $registrations = $reunion->registrations()->parents()->get();
        $committee_president = $reunion->committee()->president();
        $committee_members = $reunion->committee;
        $states = State::all();
        $events = $reunion->events->groupBy('event_date');
        $registered_user = false;

        if (Auth::check()) {
            if (Auth::user()->member != null) {
                if (Auth::user()->member->registrations != null) {
                    $registrations = Auth::user()->member->registrations;

                    foreach ($registrations as $registration) {
                        if ($registration->reunion->id == $reunion->id) {
                            $registered_user = true;
                            break;
                        } else {
                            $registered_user = false;
                        }
                    }
                } else {
                    $registered_user = false;
                }
            } else {
                $registered_user = false;
            }
        }

        return response()->view('upcoming_reunion', compact('registered_user', 'registrations', 'committee_members', 'events', 'committee_president', 'reunion', 'states'));
    }

    /**
     * Display the past reunion.
     *
     * @param Reunion $reunion
     * @return mixed
     */
    public function show_past_reunion(Reunion $reunion)
    {
        $registrations = Registration::where('reunion_id', $reunion->id);
        $committee_members = $reunion->committee;
        $events = $reunion->events->groupBy('event_date');

        return view('past_reunion', compact('registrations', 'reunion', 'committee_members', 'events'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Reunion $reunion
     * @return mixed
     */
    public function edit(Reunion $reunion)
    {
        $states = State::all();
        $carbonDate = Carbon::now()->subYear();
        $members = FamilyMember::orderby('firstname', 'asc')->get();
        $titles = CommitteeTitle::all();
        $reunion_events = $reunion->events()->orderBy('event_date')->get();
        $totalRegistrations = $reunion->registrations()->where('parent_reg', null)->count();
        $adults = $reunion->registrations()->where('parent_reg', null)->pluck('adult_names');
        $youths = $reunion->registrations()->where('parent_reg', null)->pluck('youth_names');
        $children = $reunion->registrations()->where('parent_reg', null)->pluck('children_names');
        $adultShirts = $reunion->registrations()->where('parent_reg', null)->pluck('adult_shirts');
        $youthShirts = $reunion->registrations()->where('parent_reg', null)->pluck('youth_shirts');
        $childrenShirts = $reunion->registrations()->where('parent_reg', null)->pluck('children_shirts');

        $totalAdults = count(array_filter(explode(';', $adults->implode(';'))));
        $totalYouths = count(array_filter(explode(';', $youths->implode(';'))));
        $totalChildren = count(array_filter(explode(';', $children->implode(';'))));

        $totalShirts = count(array_filter(explode(';', $adultShirts->implode(';')))) + count(array_filter(explode(';', $youthShirts->implode(';')))) + count(array_filter(explode(';', $childrenShirts->implode(';'))));

        $totalFees = is_numeric($reunion->registrations()->totalRegFees()) ? $reunion->registrations()->totalRegFees() : 0;
        $totalRegFeesPaid = is_numeric($reunion->registrations()->totalRegFeesPaid()) ? $reunion->registrations()->totalRegFeesPaid() : 0;
        $totalRegFeesDue = is_numeric($reunion->registrations()->totalRegFeesDue()) ? $reunion->registrations()->totalRegFeesDue() : 0;

        // Shirts Sizes Totals
        // Adults
        $aSm = $aMd = $aLg = $aXl = $aXXl = $aXXXl = 0;
        foreach (array_filter(explode(';', $adultShirts->implode(';'))) as $shirtSize) {
            if (trim($shirtSize) == 'S') {
                $aSm++;
            } elseif (trim($shirtSize) == 'M') {
                $aMd++;
            } elseif (trim($shirtSize) == 'L') {
                $aLg++;
            } elseif (trim($shirtSize) == 'XL') {
                $aXl++;
            } elseif (trim($shirtSize) == 'XXL') {
                $aXXl++;
            } elseif (trim($shirtSize) == 'XXXL') {
                $aXXXl++;
            }
        }

        // Youths
        $yXSm = $ySm = $yMd = $yLg = 0;
        foreach (array_filter(explode(';', $youthShirts->implode(';'))) as $shirtSize) {
            if (trim($shirtSize) == 'S') {
                $yXSm++;
            } elseif (trim($shirtSize) == 'M') {
                $ySm++;
            } elseif (trim($shirtSize) == 'L') {
                $yMd++;
            } elseif (trim($shirtSize) == 'XL') {
                $yLg++;
            } elseif (trim($shirtSize) == 'XXL') {
                $aSm++;
            } elseif (trim($shirtSize) == 'XXXL') {
                $aLg++;
            }
        }

        // Children
        $c6 = $c5T = $c4T = $c3T = $c2T = $c12M = 0;
        foreach (array_filter(explode(';', $childrenShirts->implode(';'))) as $shirtSize) {
            if (trim($shirtSize) == 'S') {
                $c12M++;
            } elseif (trim($shirtSize) == 'M') {
                $c2T++;
            } elseif (trim($shirtSize) == 'L') {
                $c3T++;
            } elseif (trim($shirtSize) == 'XL') {
                $c4T++;
            } elseif (trim($shirtSize) == 'XXL') {
                $c5T++;
            } elseif (trim($shirtSize) == 'XXXL') {
                $c6++;
            }
        }

        return view('admin.reunions.edit', compact('reunion', 'reunion_events', 'states', 'carbonDate', 'members', 'titles', 'totalRegistrations', 'totalAdults', 'totalYouths', 'totalChildren', 'totalFees', 'totalRegFeesPaid', 'totalRegFeesDue', 'totalShirts', 'aSm', 'aMd', 'aLg', 'aXl', 'aXXl', 'aXXXl', 'yXSm', 'ySm', 'yMd', 'yLg', 'c5T', 'c4T', 'c3T', 'c2T', 'c12M', 'c6'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reunion $reunion)
    {
        if (isset($request->completed_reunion)) {
            $reunion->reunion_complete = $request->completed_reunion;

            if ($reunion->save()) {
                return redirect()->action('ReunionController@index')->with('status', 'Reunion Completed');
            }
        } else {
            if ($reunion->hotel) {
                $hotel = $reunion->hotel;
            } else {
                $hotel = new ReunionHotel();
                $hotel->reunion_id = $reunion->id;
            }

            $reunion->reunion_city = $request->reunion_city;
            $reunion->reunion_state = $request->reunion_state;
            $reunion->reunion_year = $request->reunion_year;
            $reunion->adult_price = $request->adult_price;
            $reunion->youth_price = $request->youth_price;
            $reunion->child_price = $request->child_price;
            $hotel->name = $request->hotel_name;
            $hotel->location = $request->hotel_address;
            $hotel->phone = $request->hotel_phone;
            $hotel->cost = $request->hotel_cost;
            $hotel->book_room_link = $request->hotel_room_booking;

            if ($request->hasFile('paper_reg_form')) {
                $path = $request->file('paper_reg_form')->store('public/reg_forms');
                $reunion->registration_form = $path;
            }

            if ($hotel->save()) {

                if ($reunion->save()) {

                    if (isset($request->event_id)) {

                        if (count($request->event_id) < count($request->event_date)) {

                            foreach ($reunion->events as $key => $event) {
                                $eventDate = new Carbon($request->event_date[$key]);
                                $event->event_location = $request->event_location[$key];
                                $event->event_description = $request->event_description[$key];
                                $event->event_date = $eventDate;

                                $event->save();
                            }

                            for ($x = count($request->event_id); $x < count($request->event_date); $x++) {
                                // Create New Reunion Event Object
                                $event = new Reunion_event();

                                $eventDate = new Carbon($request->event_date[$x]);
                                $event->reunion_id = $reunion->id;
                                $event->event_location = $request->event_location[$x];
                                $event->event_description = $request->event_description[$x];
                                $event->event_date = $eventDate;

                                if ($event->save()) {

                                }
                            }

                        } else {

                            foreach ($reunion->events as $key => $event) {
                                $eventDate = new Carbon($request->event_date[$key]);
                                $event->event_location = $request->event_location[$key];
                                $event->event_description = $request->event_description[$key];
                                $event->event_date = $eventDate;

                                $event->save();
                            }
                        }

                    } elseif (!isset($request->event_id) && isset($request->event_date)) {

                        for ($x = 0; $x < count($request->event_date); $x++) {
                            // Create New Reunion Event Object
                            $event = new Reunion_event();

                            $eventDate = new Carbon($request->event_date[$x]);
                            $event->reunion_id = $reunion->id;
                            $event->event_location = $request->event_location[$x];
                            $event->event_description = $request->event_description[$x];
                            $event->event_date = $eventDate->toDateString();

                            if ($event->save()) {
                            }
                        }
                    }

                    if (isset($request->committee_member_id)) {

                        if (count($request->committee_member_id) < count($request->member_title)) {

                            foreach ($reunion->committee as $key => $committee_member) {

                                $member_dl = FamilyMember::find($request->dl_id[$key]);
                                $committee_member->family_member_id = $request->dl_id[$key];
                                $committee_member->member_title = $request->member_title[$key];
                                $committee_member->member_name = $member_dl->firstname . ' ' . $member_dl->lastname;
                                $committee_member->member_email = $member_dl->email;
                                $committee_member->member_phone = $member_dl->phone;
                                $committee_member->save();
                            }

                            for ($x = count($request->committee_member_id); $x < count($request->member_title); $x++) {
                                // Create New Reunion Object
                                $committee_member = new ReunionCommittee();

                                // Get member from distro list
                                $member = FamilyMember::find($request->dl_id[$x]);

                                $committee_member->family_member_id = $member->id;
                                $committee_member->reunion_id = $reunion->id;
                                $committee_member->member_name = $member->firstname . ' ' . $member->lastname;
                                $committee_member->member_title = $request->member_title[$x];
                                $committee_member->member_email = $member->email;
                                $committee_member->member_phone = $member->phone;

                                if ($committee_member->save()) {

                                }
                            }
                        } else {
                            foreach ($reunion->committee as $key => $committee_member) {
                                $member_dl = FamilyMember::find($request->dl_id[$key]);
                                $committee_member->family_member_id = $request->dl_id[$key];
                                $committee_member->member_title = $request->member_title[$key];
                                $committee_member->member_name = $member_dl->firstname . ' ' . $member_dl->lastname;
                                $committee_member->member_email = $member_dl->email;
                                $committee_member->member_phone = $member_dl->phone;
                                $committee_member->save();
                            }
                        }

                    } elseif (!isset($request->committee_member_id) && isset($request->member_title)) {

                        for ($x = 0; $x < count($request->member_title); $x++) {
                            // Create New Reunion Object
                            $committee_member = new ReunionCommittee();

                            // Get member from distro list
                            $member = FamilyMember::find($request->dl_id[$x]);

                            $committee_member->family_member_id = $member->id;
                            $committee_member->reunion_id = $reunion->id;
                            $committee_member->member_name = $member->firstname . ' ' . $member->lastname;
                            $committee_member->member_title = $request->member_title[$x];
                            $committee_member->member_email = $member->email;
                            $committee_member->member_phone = $member->phone;

                            if ($committee_member->save()) {

                            }
                        }
                    }

                    return redirect()->action('ReunionController@edit', $reunion)->with('status', 'Reunion Updated Succssfully');
                }
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Show the form for creating new pictures for specific reunion.
     *
     * @return \Illuminate\Http\Response
     */
    public function create_reunion_pictures(Reunion $reunion)
    {
        $images = CarouselImage::all();

        return view('admin.reunions.pictures.create', compact('images', 'reunion'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_reunion_image(Request $request, Reunion $reunion)
    {
        if ($request->hasFile('photo')) {
            $newImage = $request->file('photo');

            // Check to see if upload is an image
            if ($newImage->guessExtension() == 'jpeg' || $newImage->guessExtension() == 'png' || $newImage->guessExtension() == 'gif' || $newImage->guessExtension() == 'webp' || $newImage->guessExtension() == 'jpg') {

                // Check to see if images is too large
                if ($newImage->getError() == 1) {

                    $fileName = $request->file('photo')[0]->getClientOriginalName();
                    $error .= "<li class='errorItem'>The file " . $fileName . " is too large and could not be uploaded</li>";

                } elseif ($newImage->getError() == 0) {

                    // Check to see if images is about 25MB
                    // If it is then resize it
                    if ($newImage->getClientSize() < 25000000) {
                        $image = Image::make($newImage->getRealPath())->orientate();
                        $path = $newImage->store('public/reunion_background');

                        if ($image->save(storage_path('app/' . $path))) {
                            // Prevent possible upsizing
                            // Create a larger version of the image
                            // and save to large image folder
                            $image->resize(1700, null, function ($constraint) {
                                $constraint->aspectRatio();
                                // $constraint->upsize();
                            });

                            if ($image->save(storage_path('app/' . $path))) {

                                $reunion->picture = str_ireplace('public', 'storage', $path);

                                if ($reunion->save()) {
                                }

                            }
                        }

                    } else {
                        // Resize the image before storing. Will need to hash the filename first
                        $path = $newImage->store('public/reunion_background');
                        $image = Image::make($newImage)->orientate()->resize(1500, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        if ($image->save(storage_path('app/' . $path))) {

                            $reunion->picture = str_ireplace('public', 'storage', $path);

                            if ($reunion->save()) {
                            }

                        }

                    }
                } else {
                    $error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
                }
            } else {
                $error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
            }

            return 'Image added';

        } else {


        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_hotel_image(Request $request, Reunion $reunion)
    {
        $hotel;

        if ($reunion->hotel) {
            $hotel = $reunion->hotel;
        } else {
            $hotel = new ReunionHotel();
            $hotel->reunion_id = $reunion->id;
        }

        if ($request->hasFile('photo')) {
            $newImage = $request->file('photo');

            // Check to see if upload is an image
            if ($newImage->guessExtension() == 'jpeg' || $newImage->guessExtension() == 'png' || $newImage->guessExtension() == 'gif' || $newImage->guessExtension() == 'webp' || $newImage->guessExtension() == 'jpg') {

                // Check to see if images is too large
                if ($newImage->getError() == 1) {

                    $fileName = $request->file('photo')[0]->getClientOriginalName();
                    $error .= "<li class='errorItem'>The file " . $fileName . " is too large and could not be uploaded</li>";

                } elseif ($newImage->getError() == 0) {

                    // Check to see if images is about 25MB
                    // If it is then resize it
                    if ($newImage->getClientSize() < 25000000) {
                        $image = Image::make($newImage->getRealPath())->orientate();
                        $path = $newImage->store('public/images');

                        if ($image->save(storage_path('app/' . $path))) {
                            // Prevent possible upsizing
                            // Create a larger version of the image
                            // and save to large image folder
                            $image->resize(1700, null, function ($constraint) {
                                $constraint->aspectRatio();
                                // $constraint->upsize();
                            });

                            if ($image->save(storage_path('app/' . $path))) {

                                $hotel->picture = str_ireplace('public', 'storage', $path);

                                if ($hotel->save()) {
                                }

                            }
                        }

                    } else {
                        // Resize the image before storing. Will need to hash the filename first
                        $path = $newImage->store('public/images');
                        $image = Image::make($newImage)->orientate()->resize(1500, null, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        });

                        if ($image->save(storage_path('app/' . $path))) {

                            $hotel->picture = str_ireplace('public', 'storage', $path);

                            if ($hotel->save()) {
                            }

                        }

                    }
                } else {
                    $error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
                }
            } else {
                $error .= "<li class='errorItem'>The file " . $fileName . " may be corrupt and could not be uploaded</li>";
            }

            return 'Image added';

        } else {


        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_reunion_pictures(Request $request, Reunion $reunion)
    {
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $newImage) {

                // Check to see if upload is an image
                if ($newImage->guessExtension() == 'jpeg' || $newImage->guessExtension() == 'png' || $newImage->guessExtension() == 'gif' || $newImage->guessExtension() == 'webp' || $newImage->guessExtension() == 'jpg') {

                    $addImage = new ReunionImage();

                    // Check to see if images is too large
                    if ($newImage->getError() == 1) {
                        $fileName = $request->file('photo')[0]->getClientOriginalName();
                        $error .= "<li class='errorItem'>The file " . $fileName . " is too large and could not be uploaded</li>";
                    } elseif ($newImage->getError() == 0) {
                        // Check to see if images is about 25MB
                        // If it is then resize it
                        if ($newImage->getClientSize() < 25000000) {
                            $image = Image::make($newImage->getRealPath())->orientate();
                            $path = $newImage->store('public/images');

                            if ($image->save(storage_path('app/' . $path))) {
                                // prevent possible upsizing
                                // Create a larger version of the image
                                // and save to large image folder
                                $image->resize(1700, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                    // $constraint->upsize();
                                });


                                if ($image->save(storage_path('app/' . str_ireplace('images', 'images/lg', $path)))) {
                                    // Get the height of the current large image
                                    $addImage->lg_height = $image->height();

                                    // Create a smaller version of the image
                                    // and save to large image folder
                                    $image->resize(544, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });

                                    if ($image->save(storage_path('app/' . str_ireplace('images', 'images/sm', $path)))) {
                                        // Get the height of the current small image
                                        $addImage->sm_height = $image->height();
                                    }
                                }
                            }

                            $addImage->path = str_ireplace('public', 'storage', $path);
                            $addImage->reunion_id = $reunion->id;

                            if ($addImage->save()) {
                            }

                        } else {
                            // Resize the image before storing. Will need to hash the filename first
                            $path = $newImage->store('public/images');
                            $image = Image::make($newImage)->orientate()->resize(1500, null, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });

                            $image->save(storage_path('app/' . $path));
                            $addImage->property_id = $showSeason->id;

                            if ($carouselCount < 10) {

                                if ($addImage->save()) {
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

        } else {

            return redirect()->back()->with('status', 'No Images Added');

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function remove_event(ReunionEvent $reunion_event)
    {
        if ($reunion_event->delete()) {
            return $reunion_event;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function remove_committee_member(ReunionCommittee $ReunionCommittee)
    {
        if ($ReunionCommittee->delete()) {
            return $ReunionCommittee;
        }
    }
}
