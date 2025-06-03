<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\Reunion;
use App\Models\ReunionCommittee;
use App\Models\FamilyMember;
use App\Models\State;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManagerStatic as Image;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $reunions = Reunion::orderby('reunion_year', 'desc')->get();

        $newReunionCheck = Reunion::active();
        $newReunionCheck->count() > 0 ? $newReunionCheck = $newReunionCheck->first() : $newReunionCheck = null;
        $newReunionCheck = Reunion::where('reunion_complete', 'N')->get()->last();

        $user = Auth::user();
        $userPhone1 = substr($user["phone"], 0, 3);
        $userPhone2 = substr($user["phone"], 3, 3);
        $userPhone3 = substr($user["phone"], 6, 4);

        $family_member = Auth::user()->member;
        $states = State::all();
        $members = FamilyMember::orderby('firstname', 'asc')->get();
        $siblings = $family_member->siblings != null ? explode('; ', $family_member->siblings) : null;
        $children = $family_member->children != null ? explode('; ', $family_member->children) : null;
        $family_members = FamilyMember::household($family_member->family_id);
        $potential_family_members = FamilyMember::potentialHousehold($family_member);
        $active_reunion = Reunion::active()->first();
        $registered_for_reunion = $active_reunion !== null ? Registration::memberRegistered($family_member->id, $active_reunion->id)->first() : null;

        return response()->view('admin.my_profile.edit', compact('user', 'userPhone1', 'userPhone2', 'userPhone3', 'states', 'family_members', 'family_member', 'active_reunion', 'potential_family_members', 'members', 'siblings', 'children', 'registered_for_reunion', 'reunions', 'newReunionCheck'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\profile_post $profile_post
     * @return Response
     */
    public function show(profile_post $profile_post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\profile_post $profile_post
     * @return Response
     */
    public function edit(profile_post $profile_post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\FamilyMember $member
     * @return Response
     */
    public function update(Request $request, FamilyMember $member)
    {
//         dd($request);
        $this->validate($request, [
            'firstname' => 'required|max:30',
            'lastname' => 'required|max:30',
            'email' => 'nullable',
            'address' => 'nullable',
            'city' => 'nullable',
            'phone' => 'nullable|numeric',
            'zip' => 'nullable|max:99999|min:0|numeric',
        ]);

        $member->firstname = $request->firstname;
        $member->lastname = $request->lastname;
        $member->email = $request->email;
        $member->address = $request->address;
        $member->city = $request->city;
        $member->state = $request->state;
        $member->zip = $request->zip;
        $member->descent = $request->descent;
        $member->notes = $request->notes;
        $member->mother = $request->mother != 'blank' ? $request->mother : null;
        $member->father = $request->father != 'blank' ? $request->father : null;
        $member->spouse = $request->spouse != 'blank' ? $request->spouse : null;
        $member->sibling = str_ireplace('; blank', '', implode('; ', $request->siblings)) != 'blank' ? str_ireplace('; blank', '', implode('; ', $request->siblings)) : null;
        $member->child = str_ireplace('; blank', '', implode('; ', $request->children)) != 'blank' ? str_ireplace('; blank', '', implode('; ', $request->children)) : null;
        $houseMembers = str_ireplace('; blank', '', implode('; ', $request->houseMember)) != 'blank' ? str_ireplace('; blank', '', implode('; ', $request->houseMember)) : null;
        $member->phone = $request->phone;
        $member->age_group = $request->age_group;
        $member->mail_preference = $request->mail_preference;

        // If household members isn't empty then add a family ID
        // to all the parties
        if($houseMembers != null) {
            $maxFamilyID = \App\Models\FamilyMember::max('family_id');
            $hhMembers = explode('; ', $houseMembers);

            if($member->family_id == null) {
                $newFamilyID = $maxFamilyID + 1;
                $member->family_id = $newFamilyID;

                foreach($hhMembers as $hhID) {
                    $hhMember = FamilyMember::find($hhID);
                    $hhMember->family_id = $newFamilyID;
                    $hhMember->save();
                }
            } else {
                foreach($hhMembers as $hhID) {
                    $hhMember = FamilyMember::find($hhID);

                    if($hhMember->family_id != $member->family_id) {
                        $hhMember->family_id = $member->family_id;
                        $hhMember->save();
                    }
                }
            }
        }

        if($member->save()) {
            return redirect()->back()->with('status', 'Member Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\profile_post $profile_post
     * @return Response
     */
    public function destroy(profile_post $profile_post)
    {
        //
    }

    /**
     * Post the specified resource from storage.
     *
     * @param Request $request
     * @param Reunion $reunion
     * @param FamilyMember $member
     * @return mixed
     */
    public function store_registration(Request $request, Reunion $reunion, FamilyMember $member)
    {

        $this->validate($request, [
            'registree' => 'required|max:100',
            'address' => 'required|max:100',
            'city' => 'required|max:100',
            'zip' => 'required|max:99999|min:0|numeric',
            'email' => 'required|email',
            'phone' => 'nullable|numeric',
        ]);

        // Create New Registration
        $registration = new Registration();
        $registration->reunion_id = $reunion->id;
        $registration->family_member_id = $member->id;
        $registration->adult_names = $member->firstname;
        $registration->address = $request->address;
        $registration->city = $request->city;
        $registration->state = $request->state;
        $registration->zip = $request->zip;
        $registration->email = $request->email;
        $registration->phone = $request->phone;
        $registration->registree_name = $request->registree;
        $registration->reg_date = Carbon::now();

        // Calculate registration cost
        $aCost = $request->numAdults * $reunion->adult_price;
        $yCost = $request->numYouth * $reunion->youth_price;
        $cCost = $request->numChildren * $reunion->child_price;

        $registration->due_at_reg = $registration->total_amount_due = ($aCost + $yCost + $cCost);

        if ($registration->save()) {
            return redirect()->back()->with('status', 'You have been registered for the upcoming reunion');
        }

    }
}
