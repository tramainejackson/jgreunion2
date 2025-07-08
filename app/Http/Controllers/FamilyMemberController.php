<?php

namespace App\Http\Controllers;

use App\Models\FamilyMember;
use App\Models\State;
use App\Models\Registration;
use App\Models\Reunion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManagerStatic as Image;

class FamilyMemberController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $search = false;
        $search_results = null;

        if (\request('search')) {
            $search_results = FamilyMember::getSearches(\request('search'));
            $search = true;
        }

        $family_member = Auth::check() ? Auth::user()->member : null;
        $distribution_list = $search == true ? $search_results : FamilyMember::orderby('lastname', 'asc')->orderby('address', 'asc')->get();
        $duplicates_check = FamilyMember::checkDuplicates();
        $duplicates = $duplicates_check->isNotEmpty() ? $duplicates_check : null;

        return response()->view('admin.members.index', compact('distribution_list', 'duplicates', 'family_member'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $states = State::all();

        return response()->view('admin.members.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|max:30',
            'lastname' => 'required|max:30',
            'phone' => 'nullable|numeric',
            'zip' => 'nullable|max:99999|min:0',
        ]);

        $member = new FamilyMember();
        $member->firstname = $request->firstname;
        $member->lastname = $request->lastname;
        $member->email = $request->email;
        $member->address = $request->address;
        $member->city = $request->city;
        $member->state = $request->state;
        $member->zip = $request->zip;
        $member->phone = $request->phone;
        $member->age_group = $request->age_group;
        $member->mail_preference = $request->mail_preference;

        if ($member->save()) {
            if (isset($request->reunion_id)) {
                $reunion = Reunion::find($request->reunion_id);

                $registration = new Registration();
                $totalPrice = $reunion->adult_price;
                $registration->family_member_id = $member->id;
                $registration->reunion_id = $reunion->id;
                $registration->registree_name = $member->firstname . ' ' . $member->lastname;
                $registration->total_amount_due = $registration->due_at_reg = $totalPrice;
                $registration->reg_date = Carbon::now();
                $registration->adult_names = $member->firstname;

                if ($registration->save()) {
                    return redirect()->action('RegistrationController@edit', $registration)->with('status', 'Member and Registration Created Successfully');
                }
            } else {
                return redirect()->action('FamilyMemberController@edit', $member)->with('status', 'Member Created Successfully');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param FamilyMember $member
     * @return Response
     */
    public function show(FamilyMember $member)
    {
        $reunions = Reunion::orderby('reunion_year', 'desc')->get();

        $newReunionCheck = Reunion::active();
        $newReunionCheck->count() > 0 ? $newReunionCheck = $newReunionCheck->first() : $newReunionCheck = null;
        $newReunionCheck = Reunion::where('reunion_complete', 'N')->get()->last();

        $family_member = $member;
        $father = $family_member->father != null ? FamilyMember::find($family_member->father) : null;
        $mother = $family_member->mother != null ? FamilyMember::find($family_member->mother) : null;
        $spouse = $family_member->spouse != null ? FamilyMember::find($family_member->spouse) : null;
        $siblings = $family_member->siblings != null ? $family_member->getSiblings(explode('; ', $family_member->siblings)) : null;
        $children = $family_member->children != null ? $family_member->getChildren(explode('; ', $family_member->children)) : null;
        $active_reunion = Reunion::active()->first();
        $registered_for_reunion = $active_reunion !== null ? Registration::memberRegistered($family_member->id, $active_reunion->id)->first() : null;

        return response()->view('admin.members.show', compact('spouse', 'family_member', 'active_reunion', 'father', 'mother', 'siblings', 'children', 'registered_for_reunion', 'reunions', 'newReunionCheck'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FamilyMember $member
     * @return mixed
     */
    public function edit(FamilyMember $member)
    {
        $reunions = Reunion::orderby('reunion_year', 'desc')->get();

        $newReunionCheck = Reunion::active();
        $newReunionCheck->count() > 0 ? $newReunionCheck = $newReunionCheck->first() : $newReunionCheck = null;
        $newReunionCheck = Reunion::where('reunion_complete', 'N')->get()->last();

        $user = Auth::user();
        $userPhone1 = substr($user["phone"], 0, 3);
        $userPhone2 = substr($user["phone"], 3, 3);
        $userPhone3 = substr($user["phone"], 6, 4);

        $family_member = $member;
        $states = State::all();
        $members = FamilyMember::orderby('firstname', 'asc')->get();
        $siblings = $family_member->siblings != null ? explode('; ', $family_member->siblings) : null;
        $children = $family_member->children != null ? explode('; ', $family_member->children) : null;
        $family_members = FamilyMember::household($family_member->family_id);
        $potential_family_members = FamilyMember::potentialHousehold($family_member);
        $active_reunion = Reunion::active()->first();
        $registered_for_reunion = $active_reunion !== null ? Registration::memberRegistered($family_member->id, $active_reunion->id)->first() : null;

        if (Auth::user()->is_admin() || Auth::user()->member->id == $member->id) {
            return response()->view('admin.members.edit', compact('user', 'userPhone1', 'userPhone2', 'userPhone3', 'states', 'family_members', 'family_member', 'active_reunion', 'potential_family_members', 'members', 'siblings', 'children', 'registered_for_reunion', 'reunions', 'newReunionCheck'));
        } else {
            return redirect()->action([FamilyMemberController::class, 'show'], $family_member);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, FamilyMember $member)
    {

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
        $member->date_of_birth = new Carbon($request->date_of_birth);
        $member->address = $request->address;
        $member->city = $request->city;
        $member->state = $request->state;
        $member->zip = $request->zip;
        $member->descent = $request->descent;
        $member->notes = $request->notes;
        $member->mother = $request->mother != 'blank' ? $request->mother : null;
        $member->father = $request->father != 'blank' ? $request->father : null;
        $member->spouse = $request->spouse != 'blank' ? $request->spouse : null;
        $new_siblings = isset($request->new_siblings) ? $request->new_siblings : array();
        $new_children = isset($request->new_children) ? $request->new_children : array();
        $current_siblings = isset($request->siblings) ? $request->siblings : array();
        $current_children = isset($request->children) ? $request->children : array();
        $all_siblings = array_merge($new_siblings, $current_siblings);
        $all_children = array_merge($new_children, $current_children);
        $member->siblings = implode('; ', $member->remove_blanks($all_siblings));
        $member->children = implode('; ', $member->remove_blanks($all_children));
//        $houseMembers = str_ireplace('; blank', '', implode('; ', $request->houseMember)) != 'blank' ? str_ireplace('; blank', '', implode('; ', $request->houseMember)) : null;
        $member->phone = $request->phone;
        $member->age_group = $request->age_group;
        $member->mail_preference = $request->mail_preference;
        $member->instagram = $request->instagram;
        $member->facebook = $request->facebook;
        $member->twitter = $request->twitter;
        $error = '';

        // If household members isn't empty then add a family ID
        // to all the parties
//		if($houseMembers != null) {
//			$maxFamilyID = FamilyMember::max('family_id');
//			$hhMembers = explode('; ', $houseMembers);
//
//			if($member->family_id == null) {
//				$newFamilyID = $maxFamilyID + 1;
//				$member->family_id = $newFamilyID;
//
//				foreach($hhMembers as $hhID) {
//					$hhMember = FamilyMember::find($hhID);
//					$hhMember->family_id = $newFamilyID;
//					$hhMember->save();
//				}
//			} else {
//				foreach($hhMembers as $hhID) {
//					$hhMember = FamilyMember::find($hhID);
//
//					if($hhMember->family_id != $member->family_id) {
//						$hhMember->family_id = $member->family_id;
//						$hhMember->save();
//					}
//				}
//			}
//		}

        if ($request->hasFile('profile_image')) {
            $newImage = $request->file('profile_image');
            $fileName = $newImage->getClientOriginalName();

            // Check to see if images is too large
            if ($newImage->getError() == 1) {
                $error .= "The file " . $fileName . " is too large and could not be uploaded";
            } elseif ($newImage->getError() == 0) {
                // Check to see if images is about 25MB
                // If it is then resize it
                if ($newImage->getSize() < 25000000) {
                    if ($newImage->guessExtension() == 'jpeg' || $newImage->guessExtension() == 'png' || $newImage->guessExtension() == 'gif' || $newImage->guessExtension() == 'webp' || $newImage->guessExtension() == 'jpg') {
                        $image = Image::make($newImage->getRealPath())->orientate();
                        $path = $newImage->store('public/images');

                        if ($image->save(storage_path('app/' . $path))) {
                            // prevent possible upsizing
                            // Create a larger version of the image
                            // and save to large image folder
                            $image->resize(1800, null, function ($constraint) {
                                $constraint->aspectRatio();
                                // $constraint->upsize();
                            });
                        }

                        $member->profile_image = str_ireplace('public/images/', '', $path);

                    } else {
                        $error .= "The file " . $fileName . " could not be added bcause it is the wrong image type.";
                    }
                } else {
                    // Resize the image before storing. Will need to hash the filename first
                    $path = $newImage->store('public/images');
                    $image = Image::make($newImage)->orientate()->resize(1500, null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $image->save(storage_path('app/' . $path));

                    $member->profile_image = str_ireplace('public/images/', '', $path);

                }
            } else {
                $error .= "The file " . $fileName . " may be corrupt and could not be uploaded.";
            }
        }

        if ($member->save()) {
            return redirect()->back()->with('status', 'Member Updated Successfully');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param FamilyMember $member
     * @return Response
     */
    public function destroy(FamilyMember $member)
    {
        if ($member->delete()) {

            return redirect()->action('FamilyMemberController@index')->with('status', 'Family member account deleted successfully');

        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function duplicates()
    {
        $duplicates_check = FamilyMember::checkDuplicates();
        $duplicates_check = $duplicates_check->isNotEmpty() ? $duplicates_check : null;

        if (Auth::user()->is_admin()) {
            return response()->view('admin.members.duplicates', compact('duplicates_check'));
        } else {
            return redirect()->action([FamilyMemberController::class, 'index']);
        }

    }

    /**
     * Keep the potential duplicate account.
     *
     * @return Response
     */
    public function update_duplicate(Request $request)
    {
        $keep = isset($request->keep_duplicate_member) && $request->keep_duplicate_member_form == 'Y';
        $delete = isset($request->delete_duplicate_member) && $request->delete_duplicate_member_form == 'Y';

        if($keep) {
            $member = FamilyMember::find($request->keep_duplicate_member);
            $member->duplicate = 0;

            if ($member->save()) {
                return redirect()->back();
            }
        } elseif ($delete) {
            $accounts = explode('.', $request->delete_duplicate_member);
            $parent_account = FamilyMember::find($accounts[0]);
            $delete_account = FamilyMember::find($accounts[1]);
            $delete_account_registrations = $delete_account->registrations;
            $delete_account_committees = $delete_account->committees;
dd($delete_account);
            //Update reunion committees with parent account
            if($delete_account_committees->isNotEmpty()) {
                foreach ($delete_account_committees as $updated_committee) {
                    $updated_committee->family_member_id = $parent_account->id;
                    $updated_committee->save();
                }
            }

            //Update reunion registrations with parent account
            if($delete_account_registrations->isNotEmpty()) {
                foreach ($delete_account_registrations as $update_registration) {
                    $update_registration->family_member_id = $parent_account->id;

                    if($update_registration->save()) {
                        $update_registration->delete();
                    }
                }
            }

            // Delete the member account
            if ($delete_account->delete()) {
                return redirect()->back();
            }
        } else {

        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function add_house_hold(Request $request)
    {
        $member = FamilyMember::find($request->FamilyMember);
        $addingMember = FamilyMember::find($request->houseMember);
        $maxFamilyID = FamilyMember::max('family_id');

        // If household members isn't empty then add a family ID
        // to all the parties
        if ($member->family_id == null) {
            $newFamilyID = $maxFamilyID + 1;
            $member->family_id = $newFamilyID;
            $addingMember->family_id = $newFamilyID;

            if ($addingMember->save()) {
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

        return response()->view('admin.members.edit', compact('states', 'FamilyMembers', 'member', 'active_reunion', 'potential_FamilyMembers', 'members', 'siblings', 'children', 'registered_for_reunion'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function remove_house_hold(Request $request)
    {
        $member = FamilyMember::find($request->FamilyMember);
        $removeHH = FamilyMember::find($request->remove_hh);
        $familyID = $member->family_id;
        $familyMembers = FamilyMember::where([
            ['family_id', $familyID],
            ['family_id', '<>', null]
        ])->get();

        // If household members is equal to 2 then remove
        // family ID from both users
        if ($familyMembers->count() <= 2) {
            $removeHH->family_id = $member->family_id = null;

            if ($removeHH->save()) {
                if ($member->save()) {
                }
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

        return response()->view('admin.members.edit', compact('states', 'FamilyMembers', 'member', 'active_reunion', 'potential_FamilyMembers', 'members', 'siblings', 'children', 'registered_for_reunion'));
    }
}
