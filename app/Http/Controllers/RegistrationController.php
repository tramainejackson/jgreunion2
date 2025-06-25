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
use Illuminate\Support\Arr;
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

        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'address' => 'required|max:100',
            'city' => 'required|max:100',
            'zip' => 'required|max:99999|min:0|numeric',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        if (!Auth::check()) {
            // Create New Member
            $member = new FamilyMember();
        } else {
            $member = FamilyMember::find($request->member);
        }

        // Create New Registration
        $registration = new Registration();
        $registration->reunion_id = Settings::first()->current_reunion;
        $registration->address = $member->address = $request->address;
        $registration->city = $member->city = $request->city;
        $registration->state = $member->state = $request->state;
        $registration->zip = $member->zip = $request->zip;
        $registration->email = $member->email = $request->email;
        $registration->phone = $member->phone = $request->phone != '' ? $request->phone : null;
        $registration->first_name = $request->first_name;
        $registration->last_name = $request->last_name;
        $registration->reg_date = Carbon::now();
        $registration->shirt_size = isset($request->adult_shirts) ? $request->adult_shirts[0] : null;
        $registration->adult_shirts = isset($request->adult_shirts) ? join('; ', $request->adult_shirts) : null;
        $registration->youth_shirts = isset($request->youth_shirts) ? join('; ', $request->youth_shirts) : null;
        $registration->children_shirts = isset($request->children_shirts) ? join('; ', $request->children_shirts) : null;

        $member->firstname = $request->first_name;
        $member->lastname = $request->last_name;
        $member->age_group = 'adult';

        // If the adult name isn't entered then use the
        // registree's first name
        if ($request->attending_adult <= 1) {
            $registration->adult_names = $request->first_name;
        } else {
            $registration->adult_names = '';

            foreach ($request->attending_adult_first_name as $index => $adultName) {
                if ($index == 0) {
                    $registration->adult_names .= $adultName;
                } else {
                    $registration->adult_names .= '; ' . $adultName;
                }
            }
        }

        $registration->youth_names = isset($request->attending_youth_first_name) ? join('; ', $request->attending_youth_first_name) : null;
        $registration->children_names = isset($request->attending_children_first_name) ? join('; ', $request->attending_children_first_name) : null;
        $registration->total_amount_due = $registration->due_at_reg = $request->total_amount_due;

        if ($member->save()) {
            $registration->family_member_id = $member->id;

            if ($registration->save()) {
                if (App::environment('local')) {
                    //The environment is local
                    Mail::to('jackson.tramaine3@gmail.com')->send(new Registration_Admin($registration, $registration->reunion));
                } else {
                    Mail::to($registration->email)->send(new Registration_Admin($registration, $registration->reunion));
                    Mail::to('jacksongreenreunion@gmail.com')->send(new Registration_User($registration, $registration->reunion));
                }

                if (count($request->attending_adult_first_name) > 1) {
                    foreach ($request->attending_adult_first_name as $key => $newAdult) {
                        // Skip the first adult name
                        if ($key > 0) {
                            // Create New Member
                            $adultMember = new FamilyMember();

                            // Create New Registration
                            $adultRegistration = new Registration();
                            $adultRegistration->reunion_id = $registration->reunion_id;
                            $adultRegistration->parent_registration_id = $registration->id;
                            $adultRegistration->shirt_size = $request->adult_shirts[$key];
                            $adultRegistration->address = $adultMember->address = $member->address;
                            $adultRegistration->city = $adultMember->city = $member->city;
                            $adultRegistration->state = $adultMember->state = $member->state;
                            $adultRegistration->zip = $adultMember->zip = $member->zip;
                            $adultRegistration->first_name = $request->attending_adult_first_name[$key];
                            $adultRegistration->last_name = $request->attending_adult_last_name[$key];
                            $adultMember->firstname = $request->attending_adult_first_name[$key];
                            $adultMember->lastname = $request->attending_adult_last_name[$key];
                            $adultMember->age_group = 'adult';

                            if ($adultMember->save()) {
                                $adultRegistration->family_member_id = $adultMember->id;
                                $adultRegistration->save();
                            }
                        }
                    }
                }

                // If the youths aren't equal to null then create a child registration
                // and a member account
                if (isset($request->attending_youth_first_name)) {
                    foreach ($request->attending_youth_first_name as $key => $newYouth) {
                        // Create New Member
                        $youthMember = new FamilyMember();

                        // Create New Registration
                        $youthRegistration = new Registration();
                        $youthRegistration->reunion_id = $registration->reunion_id;
                        $youthRegistration->parent_registration_id = $registration->id;
                        $youthRegistration->shirt_size = $request->youth_shirts[$key];
                        $youthRegistration->address = $youthMember->address = $member->address;
                        $youthRegistration->city = $youthMember->city = $member->city;
                        $youthRegistration->state = $youthMember->state = $member->state;
                        $youthRegistration->zip = $youthMember->zip = $member->zip;
                        $youthRegistration->first_name = $request->attending_youth_first_name[$key];
                        $youthRegistration->last_name = $request->attending_youth_last_name[$key];
                        $youthMember->firstname = $request->attending_youth_first_name[$key];
                        $youthMember->lastname = $request->attending_youth_last_name[$key];
                        $youthMember->age_group = 'youth';

                        if ($youthMember->save()) {
                            $youthRegistration->family_member_id = $youthMember->id;
                            $youthRegistration->save();
                        }
                    }
                }

                // If the children aren't equal to null then create a child registration
                // and a member account
                if (isset($request->attending_children_first_name)) {
                    foreach ($request->attending_children_first_name as $key => $newChild) {
                        // Create New Member
                        $childMember = new FamilyMember();

                        // Create New Registration
                        $childRegistration = new Registration();
                        $childRegistration->reunion_id = $registration->reunion_id;
                        $childRegistration->parent_registration_id = $registration->id;
                        $childRegistration->shirt_size = $request->children_shirts[$key];
                        $childRegistration->address = $childMember->address = $member->address;
                        $childRegistration->city = $childMember->city = $member->city;
                        $childRegistration->state = $childMember->state = $member->state;
                        $childRegistration->zip = $childMember->zip = $member->zip;
                        $childRegistration->first_name = $request->attending_children_first_name[$key];
                        $childRegistration->last_name = $request->attending_children_last_name[$key];
                        $childMember->firstname = $request->attending_children_first_name[$key];
                        $childMember->lastname = $request->attending_children_last_name[$key];
                        $childMember->age_group = 'child';

                        if ($childMember->save()) {
                            $childRegistration->family_member_id = $childMember->id;
                            $childRegistration->save();
                        }
                    }
                }

                if (!Auth::check()) {
                    return redirect()->action([HomeController::class, 'index'])->with('status', 'Registration Completed Successfully');
                } else {
                    return redirect()->action([FamilyMemberController:: class, 'edit'], $member)->with('status', 'Registration Added Successfully');
                }
            }
        } else {

            return redirect()->back()->with('bad_status', 'Registration Not Completed. Please Try To Register Again');
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

        // Get the children registrations of the parent registration
        $children_registrations = $registration->children_reg;

        // Get the count of each age group
        $adults = collect();
        $youths = collect();
        $children = collect();

        //Add children registrations
        foreach ($children_registrations as $child_registration) {
            if (strtolower($child_registration->family_member->age_group) == 'adult') {
                $adults->prepend($child_registration);
            } elseif (strtolower($child_registration->family_member->age_group) == 'youth') {
                $youths->prepend($child_registration);
            } elseif (strtolower($child_registration->family_member->age_group) == 'child') {
                $children->prepend($child_registration);
            }
        }

        //Add parent registration first
        $adults->prepend($registration);

        return view('admin.registrations.edit', compact('registration', 'member', 'reunion', 'states', 'adults', 'youths', 'children', 'all_members'));
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
        $this->validate($request, [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'address' => 'required|max:100',
            'city' => 'required|max:100',
            'zip' => 'required|max:99999|min:0|numeric',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        $registration->first_name = $request->first_name;
        $registration->last_name = $request->last_name;
        $registration->address = $request->address;
        $registration->city = $request->city;
        $registration->state = $request->state;
        $registration->zip = $request->zip;
        $registration->email = $request->email;
        $registration->phone = $request->phone != '' ? $request->phone : null;
        $registration->shirt_size = isset($request->adult_shirts) ? $request->adult_shirts[0] : null;
        $registration->adult_shirts = isset($request->adult_shirts) ? join('; ', $request->adult_shirts) : null;
        $registration->youth_shirts = isset($request->youth_shirts) ? join('; ', $request->youth_shirts) : null;
        $registration->children_shirts = isset($request->children_shirts) ? join('; ', $request->children_shirts) : null;
        $registration->registration_notes = $request->registration_notes;

        // If the adult name isn't entered then use the
        // registree's first name
        if ($request->attending_adult <= 1) {
            $registration->adult_names = $request->first_name;
        } else {
            $registration->adult_names = '';

            foreach ($request->attending_adult_first_name as $index => $adultName) {
                if ($index == 0) {
                    $registration->adult_names .= $adultName;
                } else {
                    $registration->adult_names .= '; ' . $adultName;
                }
            }
        }

        $registration->youth_names = isset($request->attending_youth_first_name) ? join('; ', $request->attending_youth_first_name) : null;
        $registration->children_names = isset($request->attending_children_first_name) ? join('; ', $request->attending_children_first_name) : null;

        //Update individual registration rows
        if (isset($request->adult_member_reg_id)) {
            foreach ($request->adult_member_reg_id as $index => $adult_member_id) {
                $adult_reg_account = Registration::findOrFail($adult_member_id);
                $adult_reg_account->first_name = $request->attending_adult_first_name[$index + 1];
                $adult_reg_account->last_name = $request->attending_adult_last_name[$index + 1];
                $adult_reg_account->shirt_size = $request->adult_shirts[$index + 1];

                $adult_reg_account->save();

                //Remove account if delete is selected
                if (isset($request->remove_adult_member)) {
                    if ($request->remove_adult_member[$index] == 'Y') {
                        Registration::destroy($adult_reg_account->id);
                    }
                }
            }
        } else {
            if (isset($request->attending_new_adult_first_name)) {
                foreach ($request->attending_new_adult_first_name as $index => $new_adult_member) {
                    $adult_member_account = new FamilyMember();
                    $adult_reg_account = new Registration();
                    $adult_reg_account->reunion_id = $registration->reunion_id;
                    $adult_reg_account->parent_registration_id = $registration->id;
                    $adult_reg_account->first_name = $adult_member_account->firstname = $request->attending_new_adult_first_name[$index];
                    $adult_reg_account->last_name = $adult_member_account->lastname = $request->attending_new_adult_last_name[$index];
                    $adult_reg_account->shirt_size = $request->new_adult_shirts[$index];
                    $adult_reg_account->age_group = $adult_member_account->age_group = 'adult';
                    $adult_member_account->address = $registration->address;

                    if ($adult_member_account->save()) {
                        $adult_reg_account->family_member_id = $adult_member_account->id;
                        $adult_reg_account->save();
                    }
                }
            }
        }

        if (isset($request->youth_member_reg_id)) {
            foreach ($request->youth_member_reg_id as $index => $youth_member_id) {
                $youth_reg_account = Registration::findOrFail($youth_member_id);
                $youth_reg_account->first_name = $request->attending_youth_first_name[$index];
                $youth_reg_account->last_name = $request->attending_youth_last_name[$index];
                $youth_reg_account->shirt_size = $request->youth_shirts[$index];

                $youth_reg_account->save();

                //Remove account if delete is selected
                if (isset($request->remove_youth_member)) {
                    if ($request->remove_youth_member[$index] == 'Y') {
                        Registration::destroy($youth_reg_account->id);
                    }
                }
            }
        } else {
            if (isset($request->attending_new_youth_first_name)) {
                foreach ($request->attending_new_youth_first_name as $index => $new_youth_member) {
                    $youth_member_account = new FamilyMember();
                    $youth_reg_account = new Registration();
                    $youth_reg_account->reunion_id = $registration->reunion_id;
                    $youth_reg_account->parent_registration_id = $registration->id;
                    $youth_reg_account->first_name = $youth_member_account->firstname = $request->attending_new_youth_first_name[$index];
                    $youth_reg_account->last_name = $youth_member_account->lastname = $request->attending_new_youth_last_name[$index];
                    $youth_reg_account->shirt_size = $request->new_youth_shirts[$index];
                    $youth_reg_account->age_group = $youth_member_account->age_group = 'child';
                    $youth_member_account->address = $registration->address;

                    if ($youth_member_account->save()) {
                        $youth_reg_account->family_member_id = $youth_member_account->id;
                        $youth_reg_account->save();
                    }
                }
            }
        }

        if (isset($request->child_member_reg_id)) {
            foreach ($request->child_member_reg_id as $index => $child_member_id) {
                $child_reg_account = Registration::findOrFail($child_member_id);
                $child_reg_account->first_name = $request->attending_children_first_name[$index];
                $child_reg_account->last_name = $request->attending_children_last_name[$index];
                $child_reg_account->shirt_size = $request->children_shirts[$index];

                $child_reg_account->save();

                //Remove account if delete is selected
                if (isset($request->remove_child_member)) {
                    if ($request->remove_child_member[$index] == 'Y') {
                        Registration::destroy($child_reg_account->id);
                    }
                }
            }
        } else {
            if (isset($request->attending_new_children_first_name)) {
                foreach ($request->attending_new_children_first_name as $index => $new_child_member) {
                    $child_member_account = new FamilyMember();
                    $child_reg_account = new Registration();
                    $child_reg_account->reunion_id = $registration->reunion_id;
                    $child_reg_account->parent_registration_id = $registration->id;
                    $child_reg_account->first_name = $child_member_account->firstname = $request->attending_new_children_first_name[$index];
                    $child_reg_account->last_name = $child_member_account->lastname = $request->attending_new_children_last_name[$index];
                    $child_reg_account->shirt_size = $request->new_children_shirts[$index];
                    $child_reg_account->age_group = $child_member_account->age_group = 'youth';
                    $child_member_account->address = $registration->address;

                    if ($child_member_account->save()) {
                        $child_reg_account->family_member_id = $child_member_account->id;
                        $child_reg_account->save();
                    }
                }
            }
        }

        //Calculate the amount still due to be paid
        $registration->total_amount_paid = $request->total_amount_paid;
        $registration->due_at_reg = $request->due_at_reg;
        $registration->total_amount_due = $request->due_at_reg - $request->total_amount_paid;

        if ($registration->save()) {
            return redirect()->back()->with('status', 'Registration Updated Successfully');
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
