<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Reunion;
use App\Models\FamilyMember;
use App\Models\State;
use App\Mail\Registration_Admin;
use App\Mail\Registration_User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @return Response
     */
    public function create(Reunion $reunion)
    {
        $members = FamilyMember::orderby('firstname', 'asc')->get();
        $states = State::all();

        return response()->view('admin.registrations.create', compact('reunion', 'members', 'states'));
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
            $registration->reunion_id = $request->reunion_id;
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

//             dd($registration->children_names);
            if ($member->save()) {
                $registration->family_members_id = $member->id;

                if ($registration->save()) {
//					\Mail::to($registration->email)->send(new Registration_Admin($registration, $registration->reunion));
//
//					\Mail::to('desmund94@gmail.com')->send(new Registration_User($registration, $registration->reunion));

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

            $registration = new Registration();
            $reunion = Reunion::find($request->reunion_id);
            $member = FamilyMember::find($request->reg_member);
            $totalPrice = $reunion->adult_price;
            $adults = $member->firstname;
            $youth = '';
            $children = '';

            $registration->family_members_id = $member->id;
            $registration->reunion_id = $reunion->id;
            $registration->registree_name = $member->firstname . ' ' . $member->lastname;
            $registration->total_amount_due = $registration->due_at_reg = $totalPrice;
            $registration->reg_date = Carbon::now();
            $registration->adult_names = $adults;
            $registration->youth_names = $youth == '' ? null : $youth;
            $registration->children_names = $children == '' ? null : $children;

            if ($registration->save()) {
                return redirect()->action([RegistrationController:: class, 'edit'], $registration)->with('status', 'Registration Added Successfully');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Registration $registration
     * @return Response
     */
    public function edit(Registration $registration)
    {
        $all_members = FamilyMember::orderby('firstname', 'asc')->get();
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

        return view('admin.registrations.edit', compact('registration', 'states', 'family', 'adultSizes', 'youthSizes', 'childrenSizes', 'adults', 'youths', 'childs', 'all_members'));
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
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Registration $registration
     * @return Response
     */
    public function update(Request $request, Registration $registration)
    {

        $registration->total_amount_due = $request->total_amount_due;
        $registration->total_amount_paid = $request->total_amount_paid;
        $registration->due_at_reg = $request->due_at_reg;
        $registration->adult_shirts = implode('; ', $request->adult_sizes);
        $registration->youth_shirts = implode('; ', isset($request->youth_sizes) ? $request->youth_sizes : array());
        $registration->children_shirts = implode('; ', isset($request->children_sizes) ? $request->children_sizes : array());
        $registration->reg_notes = $request->reg_notes;

        if ($registration->family_members_id != null) {

            $registration->registree_name = $registration->reunion_dl->firstname . ' ' . $registration->reunion_dl->lastname;
            $registration->family_members_id = $registration->reunion_dl->id;

        }

        if ($registration->save()) {

            return redirect()->action('RegistrationController@edit', $registration)->with('status', 'Registration Updated Successfully');

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Registration $registration
     * @return Response
     */
    public function destroy(Registration $registration)
    {
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
        $states = State::all();
        return view('guest_registration', compact('reunion', 'states'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Registration $registration
     * @return Response
     */
    public function add_registration_member(Request $request, Registration $registration)
    {
        if (isset($request->family_member_id)) {
            $member = FamilyMember::find($request->family_member_id);

            // Create a new registration
            $newRegistration = new Registration();
            $newRegistration->reunion_id = $registration->reunion_id;
            $newRegistration->family_members_id = $member->id;
            $newRegistration->reg_date = $registration->reg_date;
            $newRegistration->registree_name = $member->firstname . ' ' . $member->lastname;
            $newRegistration->parent_registration_id = $registration->id;
            $newRegistration->address = $member->address;
            $newRegistration->city = $member->city;
            $newRegistration->state = $member->state;
            $newRegistration->zip = $member->zip;
            $newRegistration->email = $member->email != null ? $member->email : null;
            $newRegistration->phone = $member->phone != null ? $member->phone : null;

            // Get all the shirt sizes
            $shirtSizes = explode('; ', $registration->shirt_sizes);

            // Get the count of the adults and youths age group
            // And get the sizes of the shirts in reference to
            // the amount of each age group
            $adults = $registration->adult_names != null || $registration->adult_names != '' ? $registration->adult_names : null;
            $adultSizes = array_slice($shirtSizes, 0, count(explode('; ', $adults)));
            $youths = $registration->youth_names != null || $registration->youth_names != '' ? $registration->youth_names : null;
            $youthSizes = array_slice($shirtSizes, count(explode('; ', $adults)), count(explode('; ', $youths)));
            $childs = $registration->children_names != null || $registration->children_names != '' ? $registration->children_names : null;
            $childrenSizes = array_slice($shirtSizes, (count(explode('; ', $adults)) + count(explode('; ', $youths))));

            // Add user to appropriate age group and their shirt size
            if ($member->age_group == 'adult') {
                if (count(explode('; ', $registration->adult_names)) > 1) {
                    $registration->adult_names .= '; ' . $member->firstname;
                    array_push($adultSizes, 'S');
                } else {
                    if ($adults != null) {
                        $registration->adult_names .= '; ' . $member->firstname;
                        array_push($adultSizes, 'S');
                    } else {
                        $registration->adult_names = $member->firstname;
                        array_unshift($adultSizes, 'S');
                    }
                }

                $registration->shirt_sizes = implode('; ', array_merge($adultSizes, $youthSizes, $childrenSizes));

            } elseif ($member->age_group == 'youth') {

                if (count(explode('; ', $registration->youth_names)) > 1) {
                    $registration->youth_names .= '; ' . $member->firstname;
                    array_push($youthSizes, 'S');
                } else {
                    if ($registration->youth_names != null || $registration->youth_names != '') {
                        $registration->youth_names .= '; ' . $member->firstname;
                        array_push($youthSizes, 'S');
                    } else {
                        $registration->youth_names = $member->firstname;
                        array_unshift($youthSizes, 'S');
                    }
                }

                $registration->shirt_sizes = implode('; ', array_merge($adultSizes, $youthSizes, $childrenSizes));

            } elseif ($member->age_group == 'child') {

                $registration->shirt_sizes .= '; ' . 'S';

                if (count(explode('; ', $registration->children_names)) > 1) {
                    $registration->children_names .= '; ' . $member->firstname;
                } else {
                    if ($registration->children_names != null || $registration->children_names != '') {
                        $registration->children_names .= '; ' . $member->firstname;
                    } else {
                        $registration->children_names = $member->firstname;
                    }
                }
            }

            // Adjust registration price to reflect the amount of
            // people in the registration
            $adultCost = $registration->adult_names != null ? $registration->reunion->adult_price * count(explode('; ', $registration->adult_names)) : 0;
            $youthCost = $registration->youth_names != null ? $registration->reunion->youth_price * count(explode('; ', $registration->youth_names)) : 0;
            $childrenCost = $registration->children_names != null ? $registration->reunion->child_price * count(explode('; ', $registration->children_names)) : 0;
            $registration->due_at_reg = $adultCost + $youthCost + $childrenCost;
            $registration->total_amount_due = $registration->due_at_reg - $registration->total_amount_paid;

            if ($newRegistration->save()) {
                if ($registration->save()) {
                    return redirect()->back()->with('status', 'New Member Added to Registration Successfully');
                }
            }

        } else {

            $this->validate($request, [
                'firstname' => 'required|max:50',
                'lastname' => 'required|max:50',
            ]);
            // Create New Member
            $member = new FamilyMember();
            $member->firstname = $request->firstname;
            $member->lastname = $request->lastname;
            $member->age_group = $request->age_group;
            $maxFamilyID = FamilyMember::max('family_id');

            // Create a new registration
            $newRegistration = new Registration();
            $newRegistration->reunion_id = $registration->reunion_id;
            $newRegistration->reg_date = $registration->reg_date;
            $newRegistration->registree_name = $request->firstname . ' ' . $request->lastname;
            $newRegistration->parent_registration_id = $registration->id;

            // If household members isn't empty then add a family ID
            // to all the parties
            if ($registration->FamilyMember->family_id == null) {
                $newFamilyID = $maxFamilyID + 1;
                $member->family_id = $newFamilyID;
                $registration->reunion_dl->family_id = $newFamilyID;

                if ($registration->reunion_dl->save()) {
                }
            } else {
                $member->family_id = $registration->reunion_dl->family_id;
            }

            $newRegistration->address = $member->address = $registration->reunion_dl->address;
            $newRegistration->city = $member->city = $registration->reunion_dl->city;
            $newRegistration->state = $member->state = $registration->reunion_dl->state;
            $newRegistration->zip = $member->zip = $registration->reunion_dl->zip;
            $newRegistration->phone = $member->phone = $registration->reunion_dl->phone != null ? $registration->reunion_dl->phone : null;
            $newRegistration->email = $registration->reunion_dl->email != null ? $registration->reunion_dl->email : null;

            // Get all the shirt sizes
            $shirtSizes = explode('; ', $registration->shirt_sizes);

            // Get the count of the adults and youths age group
            // And get the sizes of the shirts in reference to
            // the amount of each age group
            $adults = $registration->adult_names != null || $registration->adult_names != '' ? explode('; ', $registration->adult_names) : null;
            $adultSizes = array_slice($shirtSizes, 0, count($adults));
            $youths = $registration->youth_names != null || $registration->youth_names != '' ? explode('; ', $registration->youth_names) : null;
            $youthSizes = array_slice($shirtSizes, count($adults), count($youths));
            $childs = $registration->children_names != null || $registration->children_names != '' ? explode('; ', $registration->children_names) : null;
            $childrenSizes = array_slice($shirtSizes, (count($adults) + count($youths)));

            // Add user to appropriate age group and their shirt size
            if ($request->age_group == 'adult') {
                if (count(explode('; ', $registration->adult_names)) > 1) {
                    $registration->adult_names .= '; ' . $request->firstname;
                    array_push($adultSizes, $request->shirt_size);
                } else {
                    if ($adults != null) {
                        $registration->adult_names .= '; ' . $request->firstname;
                        array_push($adultSizes, $request->shirt_size);
                    } else {
                        $registration->adult_names = $request->firstname;
                        array_unshift($adultSizes, $request->shirt_size);
                    }
                }

                $registration->shirt_sizes = implode('; ', array_merge($adultSizes, $youthSizes, $childrenSizes));
            } elseif ($request->age_group == 'youth') {
                if (count(explode('; ', $registration->youth_names)) > 1) {
                    $registration->youth_names .= '; ' . $request->firstname;
                    array_push($youthSizes, $request->shirt_size);
                } else {
                    if ($registration->youth_names != null || $registration->youth_names != '') {
                        $registration->youth_names .= '; ' . $request->firstname;
                        array_push($youthSizes, $request->shirt_size);
                    } else {
                        $registration->youth_names = $request->firstname;
                        array_unshift($youthSizes, $request->shirt_size);
                    }
                }

                $registration->shirt_sizes = implode('; ', array_merge($adultSizes, $youthSizes, $childrenSizes));
            } elseif ($request->age_group == 'child') {
                $registration->shirt_sizes .= '; ' . $request->shirt_size;

                if (count(explode('; ', $registration->children_names)) > 1) {
                    $registration->children_names .= '; ' . $request->firstname;
                } else {
                    if ($registration->children_names != null || $registration->children_names != '') {
                        $registration->children_names .= '; ' . $request->firstname;
                    } else {
                        $registration->children_names = $request->firstname;
                    }
                }
            }

            // Adjust registration price to reflect the amount of
            // people in the registration
            $adultCost = $registration->adult_names != null ? $registration->reunion->adult_price * count(explode('; ', $registration->adult_names)) : 0;
            $youthCost = $registration->youth_names != null ? $registration->reunion->youth_price * count(explode('; ', $registration->youth_names)) : 0;
            $childrenCost = $registration->children_names != null ? $registration->reunion->child_price * count(explode('; ', $registration->children_names)) : 0;
            $registration->due_at_reg = $adultCost + $youthCost + $childrenCost;
            $registration->total_amount_due = $registration->due_at_reg - $registration->total_amount_paid;

            if ($member->save()) {
                $newRegistration->family_members_id = $member->id;

                if ($newRegistration->save()) {
                    if ($registration->save()) {
                        return redirect()->back()->with('status', 'New Member Added to Registration Successfully');
                    }
                }
            }
        }
        // dd($registration->adult_names);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Registration $registration
     * @return Response
     */
    public function remove_ind_member($registration, $remove_ind_member)
    {
        $registration = Registration::find($registration);
        $adults = explode('; ', $registration->adult_names);
        $youths = explode('; ', $registration->youth_names);
        $childs = explode('; ', $registration->children_names);
        $shirtSizes = explode('; ', $registration->shirt_sizes);
        $adultSizes = array_slice($shirtSizes, 0, count($adults));
        $youthSizes = array_slice($shirtSizes, count($adults), count($youths));
        $childrenSizes = array_slice($shirtSizes, (count($adults) + count($youths)));
        $removeIndex = 0;
        $removedName = "";
        $all_members = FamilyMember::orderby('firstname', 'asc')->get();
        $states = State::all();
        $family = FamilyMember::where([
            ['family_id', $registration->family_id],
            ['family_id', '<>', null]
        ])->get();

        if (substr_count($remove_ind_member, 'adult') > 0) {
            $removeIndex = (int)(str_ireplace('adult', '', $remove_ind_member) - 1);
            $removedName = $adults[$removeIndex];

            unset($adults[$removeIndex]);
            unset($adultSizes[$removeIndex]);

            $registration->adult_names = implode('; ', $adults);
        } elseif (substr_count($remove_ind_member, 'youth') > 0) {
            $removeIndex = (int)(str_ireplace('youth', '', $remove_ind_member) - 1);
            $removedName = $youths[$removeIndex];

            unset($youths[$removeIndex]);
            unset($youthSizes[$removeIndex]);

            $registration->youth_names = implode('; ', $youths);
        } elseif (substr_count($remove_ind_member, 'child') > 0) {
            $removeIndex = (int)(str_ireplace('child', '', $remove_ind_member) - 1);
            $removedName = $childs[$removeIndex];

            unset($childs[$removeIndex]);
            unset($childrenSizes[$removeIndex]);

            $registration->children_names = implode('; ', $childs);
        }

        // Adjust registration price to reflect the amount of
        // people in the registration
        $adultCost = $registration->adult_names != null ? $registration->reunion->adult_price * count(explode('; ', $registration->adult_names)) : 0;
        $youthCost = $registration->youth_names != null ? $registration->reunion->youth_price * count(explode('; ', $registration->youth_names)) : 0;
        $childrenCost = $registration->children_names != null ? $registration->reunion->child_price * count(explode('; ', $registration->children_names)) : 0;
        $registration->due_at_reg = $adultCost + $youthCost + $childrenCost;
        $registration->total_amount_due = $registration->due_at_reg - $registration->total_amount_paid;
        $registration->shirt_sizes = implode('; ', array_merge($adultSizes, $youthSizes, $childrenSizes));

        // Check and see if this a parent registration and
        // remove the user from the registration
        $associatedRegistrations = Registration::where([
            ['parent_registration_id	', $registration->id],
            ['registree_name', 'like', $removedName . '%']
        ])->get();

        if ($associatedRegistrations->count() > 0) {
            $associatedRegistrations[0]->parent_registration_id = null;
            $associatedRegistrations[0]->reg_notes = 'Removed from parent registration';

            if ($associatedRegistrations[0]->save()) {
                if ($associatedRegistrations[0]->delete()) {
                }
            }
        }

        if ($registration->save()) {
            return view('admin.registrations.edit', compact('registration', 'states', 'family', 'adultSizes', 'youthSizes', 'childrenSizes', 'adults', 'youths', 'childs', 'all_members'))->with('status', 'Member removed from registration successful');
        }
    }
}
