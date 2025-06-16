<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Models\Registration;
use App\Models\Reunion;
use App\Models\FamilyMember;
use App\Models\State;
use App\Mail\Registration_Admin;
use App\Mail\Registration_User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['store', 'guest_registration']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index()
    {
        $reunion = Reunion::where('reunion_complete', 'N')->first();
        $registrations = $reunion->registrations;

        return response()->view('admin.registrations.index', compact('reunion', 'registrations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return mixed
     */
    public function create(Reunion $reunion)
    {
        $members = FamilyMember::orderby('firstname', 'asc')->get();
        $states = State::all();
        $active_reunion = Reunion::active()->isNotEmpty() ? Reunion::active()->first() : null;
        $reunion = Reunion::active()->isNotEmpty() ? Reunion::active()->first() : null;
        $events = $reunion->events;
        $committee_members = $reunion->committee;
        $committee_president = $reunion->committee()->president();

        if (request()->query('member')) {
            if (is_numeric(request()->query('member'))) {
                $member = FamilyMember::find(request()->query('member'));

                if ($member != null) {
                    $registered_for_reunion = Registration::memberRegistered($member->id, $reunion->id)->first();

                    if (Auth::user()->is_admin() != null) {
                        return response()->view('admin.registrations.member_registration', compact('reunion', 'member', 'states', 'events', 'committee_members', 'committee_president', 'registered_for_reunion'));
                    } else {
                        $member = Auth::user()->member;

                        if ($registered_for_reunion) {
                            return redirect()->action([FamilyMemberController::class, 'edit'], $member);
                        } else {
                            return response()->view('admin.registrations.member_registration', compact('reunion', 'member', 'states', 'events', 'committee_members', 'committee_president', 'registered_for_reunion'));
                        }
                    }
                }
            }
        } else {
            return response()->view('admin.registrations.create', compact('reunion', 'members', 'active_reunion', 'events', 'committee_members', 'committee_president', 'states'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {

            $this->validate($request, [
                'firstname' => 'required|max:50',
                'lastname' => 'required|max:50',
                'address' => 'required|max:100',
                'city' => 'required|max:100',
                'zip' => 'required|max:99999|min:0|numeric',
                'email' => 'nullable|email',
                'phone' => 'nullable',
            ]);

            // Create New Member
            $member = new FamilyMember();

            // Create New Registration
            $registration = new Registration();
            $registration->reunion_id = Settings::first()->current_reunion;
            $registration->address = $member->address = $request->address;
            $registration->city = $member->city = $request->city;
            $registration->state = $member->state = $request->state;
            $registration->zip = $member->zip = $request->zip;
            $registration->email = $member->email = $request->email;
            $registration->phone = $member->phone = $request->phone != '' ? $request->phone : null;
            $registration->registree_name = $request->firstname . ' ' . $request->lastname;
            $registration->reg_date = Carbon::now();
            $registration->adult_shirts = isset($request->adult_shirts) ? join('; ', $request->adult_shirts) : null;
            $registration->youth_shirts = isset($request->youth_shirts) ? join('; ', $request->youth_shirts) : null;
            $registration->children_shirts = isset($request->children_shirts) ? join('; ', $request->children_shirts) : null;

            $member->firstname = $request->firstname;
            $member->lastname = $request->lastname;
            $member->age_group = 'adult';

            // If the adult name isn't entered then use the
            // registree's first name
            if ($request->attending_adult <= 1) {
                $registration->adult_names = $request->firstname;
            } else {
                $registration->adult_names = '';

                foreach ($request->attending_adult_name as $index => $adultName) {
                    if ($index == 0) {
                        $registration->adult_names .= $adultName;
                    } else {
                        $registration->adult_names .= '; ' . $adultName;
                    }
                }
            }

            $registration->youth_names = isset($request->attending_youth_name) ? join('; ', $request->attending_youth_name) : null;
            $registration->children_names = isset($request->attending_children_name) ? join('; ', $request->attending_children_name) : null;
            $registration->total_amount_due = $registration->due_at_reg = $request->total_amount_due;

            if ($member->save()) {
                $registration->family_member_id = $member->id;

                if ($registration->save()) {
                    if (App::environment('local')) {
                        //The environment is local
                        Mail::to('jackson.tramaine3@gmail.com')->send(new Registration_Admin($registration, $registration->reunion));
                    } else {
                        Mail::to($registration->email)->send(new Registration_Admin($registration, $registration->reunion));
                        Mail::to('jacksond1961@yahoo.com')->send(new Registration_User($registration, $registration->reunion));
                    }

                    $newAdults = explode('; ', $registration->adult_names);
                    if (count($newAdults) > 1) {
                        foreach ($newAdults as $key => $newAdult) {
                            // Skip the first adult name
                            if ($key > 0) {
                                // Create New Member
                                $adultMember = new FamilyMember();

                                // Create New Registration
                                $adultRegistration = new Registration();
                                $adultRegistration->reunion_id = $request->reunion_id;
                                $adultRegistration->address = $adultMember->address = $member->address;
                                $adultRegistration->city = $adultMember->city = $member->city;
                                $adultRegistration->state = $adultMember->state = $member->state;
                                $adultRegistration->zip = $adultMember->zip = $member->zip;
                                $adultRegistration->email = $adultMember->email = $member->email;
                                $adultRegistration->phone = $adultMember->phone = $member->phone != '' ? $member->phone : null;
                                $adultRegistration->registree_name = $newAdult . ' ' . $member->lastname;
                                $adultRegistration->reg_date = Carbon::now();
                                $adultRegistration->parent_registration_id = $registration->id;
                                $adultMember->firstname = $newAdult;
                                $adultMember->lastname = $member->lastname;
                                $adultMember->age_group = 'youth';

                                if ($adultMember->save()) {
                                    $adultRegistration->save();
                                }
                            }
                        }
                    }

                    // If the youths aren't equal to null then create a child registration
                    // and a member account
                    if ($registration->youth_names != null) {
                        $newYouths = explode('; ', $registration->youth_names);

                        foreach ($newYouths as $newYouth) {
                            // Create New Member
                            $youthMember = new FamilyMember();

                            // Create New Registration
                            $youthRegistration = new Registration();
                            $youthRegistration->reunion_id = $request->reunion_id;
                            $youthRegistration->address = $youthMember->address = $member->address;
                            $youthRegistration->city = $youthMember->city = $member->city;
                            $youthRegistration->state = $youthMember->state = $member->state;
                            $youthRegistration->zip = $youthMember->zip = $member->zip;
                            $youthRegistration->email = $youthMember->email = $member->email;
                            $youthRegistration->phone = $youthMember->phone = $member->phone != '' ? $member->phone : null;
                            $youthRegistration->registree_name = $newYouth . ' ' . $member->lastname;
                            $youthRegistration->reg_date = Carbon::now();
                            $youthRegistration->parent_registration_id = $registration->id;
                            $youthMember->firstname = $newYouth;
                            $youthMember->lastname = $member->lastname;
                            $youthMember->age_group = 'youth';

                            if ($youthMember->save()) {
                                $youthRegistration->save();
                            }
                        }
                    }

                    // If the children aren't equal to null then create a child registration
                    // and a member account
                    if ($registration->children_names != null) {
                        $newChildren = explode('; ', $registration->children_names);

                        foreach ($newChildren as $newChild) {
                            // Create New Member
                            $childMember = new FamilyMember();

                            // Create New Registration
                            $childRegistration = new Registration();
                            $childRegistration->reunion_id = $request->reunion_id;
                            $childRegistration->address = $childMember->address = $member->address;
                            $childRegistration->city = $childMember->city = $member->city;
                            $childRegistration->state = $childMember->state = $member->state;
                            $childRegistration->zip = $childMember->zip = $member->zip;
                            $childRegistration->email = $childMember->email = $member->email;
                            $childRegistration->phone = $member->phone;
                            $childRegistration->reg_date = Carbon::now();
                            $childRegistration->phone = $childMember->phone = $member->phone != '' ? $member->phone : null;
                            $childRegistration->registree_name = $newChild . ' ' . $member->lastname;
                            $childRegistration->parent_registration_id = $registration->id;
                            $childMember->firstname = $newChild;
                            $childMember->lastname = $member->lastname;
                            $childMember->age_group = 'child';

                            if ($childMember->save()) {
                                $childRegistration->save();
                            }
                        }
                    }

                    return redirect()->action([HomeController::class, 'index'])->with('status', 'Registration Completed Successfully');
                }
            } else {

                return redirect()->back()->with('bad_status', 'Registration Not Completed. Please Try To Register Again');
            }

        } else {

            $this->validate($request, [
                'firstname' => 'required|max:50',
                'lastname' => 'required|max:50',
                'address' => 'required|max:100',
                'city' => 'required|max:100',
                'zip' => 'required|max:99999|min:0|numeric',
                'email' => 'nullable|email',
                'phone' => 'nullable',
            ]);

            $registration = new Registration();
            $reunion = Reunion::find(Settings::first()->current_reunion);
            $member = FamilyMember::find($request->member);
            $registration->reunion_id = $reunion->id;
            $registration->family_member_id = $member->id;
            $registration->address = $request->address;
            $member->address = $member->address == null ? $request->address : $member->address;
            $registration->city = $request->city;
            $member->city = $member->city == null ? $request->city : $member->city;
            $registration->zip = $request->zip;
            $member->zip = $member->zip == null ? $request->zip : $member->zip;
            $registration->state = $request->state;
            $member->state = $member->state == null ? $request->state : $member->state;
            $registration->email = $request->email;
            $member->email = $member->email == null ? $request->email : $member->email;
            $registration->phone = $request->phone != '' ? $request->phone : null;
            $member->phone = $member->phone == null ? $request->phone : $member->phone;
            $registration->registree_name = $request->firstname . ' ' . $request->lastname;
            $registration->reg_date = Carbon::now();
            $registration->adult_shirts = isset($request->adult_shirts) ? join('; ', $request->adult_shirts) : null;
            $registration->youth_shirts = isset($request->youth_shirts) ? join('; ', $request->youth_shirts) : null;
            $registration->children_shirts = isset($request->children_shirts) ? join('; ', $request->children_shirts) : null;

            //Save the new member information
            $member->save();

            // If the adult name isn't entered then use the
            // registree's first name
            if ($request->attending_adult <= 1) {
                $registration->adult_names = $request->firstname;
            } else {
                $registration->adult_names = '';

                foreach ($request->attending_adult_name as $index => $adultName) {
                    if ($index == 0) {
                        $registration->adult_names .= $adultName;
                    } else {
                        $registration->adult_names .= '; ' . $adultName;
                    }
                }
            }

            $registration->youth_names = isset($request->attending_youth_name) ? join('; ', $request->attending_youth_name) : null;
            $registration->children_names = isset($request->attending_children_name) ? join('; ', $request->attending_children_name) : null;
            $registration->total_amount_due = $registration->due_at_reg = $request->total_amount_due;

            if ($registration->save()) {
                if (Auth::user()->is_admin() == null || Auth::user()->is_admin() == 'N') {
                    if (App::environment('local')) {
                        //The environment is local
                        Mail::to('jackson.tramaine3@gmail.com')->send(new Registration_Admin($registration, $registration->reunion));
                    } else {
                        Mail::to($registration->email)->send(new Registration_Admin($registration, $registration->reunion));
                        Mail::to('jacksond1961@yahoo.com')->send(new Registration_User($registration, $registration->reunion));
                    }
                }

                $newAdults = explode('; ', $registration->adult_names);
                if (count($newAdults) > 1) {
                    foreach ($newAdults as $key => $newAdult) {
                        // Skip the first adult name
                        if ($key > 0) {
                            // Create New Member
                            $adultMember = new FamilyMember();

                            // Create New Registration
                            $adultRegistration = new Registration();
                            $adultRegistration->reunion_id = $registration->reunion_id;
                            $adultRegistration->address = $adultMember->address = $member->address;
                            $adultRegistration->city = $adultMember->city = $member->city;
                            $adultRegistration->state = $adultMember->state = $member->state;
                            $adultRegistration->zip = $adultMember->zip = $member->zip;
                            $adultRegistration->email = $adultMember->email = $member->email;
                            $adultRegistration->phone = $adultMember->phone = $member->phone != '' ? $member->phone : null;
                            $adultRegistration->registree_name = $newAdult . ' ' . $member->lastname;
                            $adultRegistration->reg_date = Carbon::now();
                            $adultRegistration->parent_registration_id = $registration->id;
                            $adultMember->firstname = $newAdult;
                            $adultMember->lastname = $member->lastname;
                            $adultMember->age_group = 'youth';

                            if ($adultMember->save()) {
                                $adultRegistration->save();
                            }
                        }
                    }
                }

                // If the youths aren't equal to null then create a child registration
                // and a member account
                if ($registration->youth_names != null) {
                    $newYouths = explode('; ', $registration->youth_names);

                    foreach ($newYouths as $newYouth) {
                        // Create New Member
                        $youthMember = new FamilyMember();

                        // Create New Registration
                        $youthRegistration = new Registration();
                        $youthRegistration->reunion_id = $registration->reunion_id;
                        $youthRegistration->address = $youthMember->address = $member->address;
                        $youthRegistration->city = $youthMember->city = $member->city;
                        $youthRegistration->state = $youthMember->state = $member->state;
                        $youthRegistration->zip = $youthMember->zip = $member->zip;
                        $youthRegistration->email = $youthMember->email = $member->email;
                        $youthRegistration->phone = $youthMember->phone = $member->phone != '' ? $member->phone : null;
                        $youthRegistration->registree_name = $newYouth . ' ' . $member->lastname;
                        $youthRegistration->reg_date = Carbon::now();
                        $youthRegistration->parent_registration_id = $registration->id;
                        $youthMember->firstname = $newYouth;
                        $youthMember->lastname = $member->lastname;
                        $youthMember->age_group = 'youth';

                        if ($youthMember->save()) {
                            $youthRegistration->save();
                        }
                    }
                }

                // If the children aren't equal to null then create a child registration
                // and a member account
                if ($registration->children_names != null) {
                    $newChildren = explode('; ', $registration->children_names);

                    foreach ($newChildren as $newChild) {
                        // Create New Member
                        $childMember = new FamilyMember();

                        // Create New Registration
                        $childRegistration = new Registration();
                        $childRegistration->reunion_id = $registration->reunion_id;
                        $childRegistration->address = $childMember->address = $member->address;
                        $childRegistration->city = $childMember->city = $member->city;
                        $childRegistration->state = $childMember->state = $member->state;
                        $childRegistration->zip = $childMember->zip = $member->zip;
                        $childRegistration->email = $childMember->email = $member->email;
                        $childRegistration->phone = $member->phone;
                        $childRegistration->reg_date = Carbon::now();
                        $childRegistration->phone = $childMember->phone = $member->phone != '' ? $member->phone : null;
                        $childRegistration->registree_name = $newChild . ' ' . $member->lastname;
                        $childRegistration->parent_registration_id = $registration->id;
                        $childMember->firstname = $newChild;
                        $childMember->lastname = $member->lastname;
                        $childMember->age_group = 'child';

                        if ($childMember->save()) {
                            $childRegistration->save();
                        }
                    }
                }

                return redirect()->action([FamilyMemberController:: class, 'edit'], $member)->with('status', 'Registration Added Successfully');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Registration $registration
     * @return mixed
     */
    public function edit(Registration $registration)
    {
        $all_members = FamilyMember::orderby('firstname', 'asc')->get();
        $member = $registration->family_member;
        $reunion = $registration->reunion;
        $states = State::all();
        $family = FamilyMember::where([
            ['family_id', $registration->family_id],
            ['family_id', '<>', null]
        ])->get();

        // Get all the shirt sizes
        $shirtSizes = explode('; ', $registration->shirt_sizes);

        // Get the count of each age group
        $adults = $registration->adult_names != null || $registration->adult_names != '' ? explode('; ', $registration->adult_names) : null;
        $youths = $registration->youth_names != null || $registration->youth_names != '' ? explode('; ', $registration->youth_names) : null;
        $childs = $registration->children_names != null || $registration->children_names != '' ? explode('; ', $registration->children_names) : null;

        // Get the sizes of the shirts in reference to the amount
        // of each age group
        $adultSizes = explode('; ', $registration->adult_shirts);
        $youthSizes = explode('; ', $registration->youth_shirts);
        $childrenSizes = explode('; ', $registration->children_shirts);

        return view('admin.registrations.edit', compact('registration', 'member', 'reunion', 'states', 'family', 'adultSizes', 'youthSizes', 'childrenSizes', 'adults', 'youths', 'childs', 'all_members'));
    }

    /**
     * Display the specified resource.
     *
     * @param Registration $registration
     * @return mixed
     */
    public function show($registration)
    {
        $reunion = $registration->reunion;

        return view('admin.registrations.show', compact('reunion', 'registration'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Registration $registration
     * @return mixed
     */
    public function update(Request $request, Registration $registration)
    {
        dd($registration);
        $registration->total_amount_due = $request->total_amount_due;
        $registration->total_amount_paid = $request->total_amount_paid;
        $registration->due_at_reg = $request->due_at_reg;
        $registration->adult_shirts = implode('; ', $request->adult_sizes);
        $registration->youth_shirts = implode('; ', isset($request->youth_sizes) ? $request->youth_sizes : array());
        $registration->children_shirts = implode('; ', isset($request->children_sizes) ? $request->children_sizes : array());
        $registration->reg_notes = $request->reg_notes;

        if ($registration->family_member_id != null) {

            $registration->registree_name = $registration->reunion_dl->firstname . ' ' . $registration->reunion_dl->lastname;
            $registration->family_member_id = $registration->reunion_dl->id;

        }

        if ($registration->save()) {
            return redirect()->action('RegistrationController@edit', $registration)->with('status', 'Registration Updated Successfully');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Registration $registration
     * @return mixed
     */
    public function destroy(Registration $registration)
    {
        if ($registration->children_reg->isNotEmpty()) {
            foreach ($registration->children_reg as $childReg) {
                if ($childReg->delete()) {
                }
            }
        }

        if ($registration->delete()) {
            return redirect()->back()->with('status', 'Registration Deleted Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Reunion $reunion
     * @return mixed
     */
    public function guest_registration(Reunion $reunion)
    {
        if (Auth::guest()) {
            $member = new FamilyMember();
        } else {
            $member = Auth::user()->member;
        }

        $states = State::all();

        return view('guest_registration', compact('reunion', 'states', 'member'));
    }
}
