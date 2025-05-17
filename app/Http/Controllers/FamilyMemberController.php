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
     * @return Response
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

        if (Auth::user()->is_admin()) {
            return response()->view('admin.members.edit', compact('user', 'userPhone1', 'userPhone2', 'userPhone3', 'states', 'family_members', 'family_member', 'active_reunion', 'potential_family_members', 'members', 'siblings', 'children', 'registered_for_reunion', 'reunions', 'newReunionCheck'));
        } else {
            return redirect()->action([FamilyMemberController::class, 'show'], $family_member);
//            return response()->view('admin.members.show', compact('user', 'userPhone1', 'userPhone2', 'userPhone3', 'states', 'family_members', 'family_member', 'active_reunion', 'potential_family_members', 'members', 'siblings', 'children', 'registered_for_reunion', 'reunions', 'newReunionCheck'));
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

        return response()->view('admin.members.duplicates', compact('duplicates_check'));

    }

    /**
     * Delete the duplicate account.
     *
     * @return Response
     */
    public function delete_duplicates(FamilyMember $member)
    {
        $duplicates = FamilyMember::getDuplicates($member->firstname, $member->lastname, $member->city, $member->state)->get();
        $userAccount = $duplicates->first();
        $committee_member = $member->committees;
        $reunion_registrations = $member->registrations;
        $profile_post = $member->posts;

        $returnData = [];

        if ($userAccount->user && $member->user) {
            if ($userAccount->user->email === null) {
                $userAccount->user->email = $member->user->email;
            }
        } elseif (!$userAccount->user && $member->user) {
            $userAccount->user_id = $member->user->id;
        }

        $userAccount->family_id = $userAccount->family_id !== null ? $userAccount->family_id : $member->family_id;
        $userAccount->email = $userAccount->email !== null ? $userAccount->email : $member->email;
        $userAccount->address = $userAccount->address !== null ? $userAccount->address : $member->address;
        $userAccount->city = $userAccount->city !== null ? $userAccount->city : $member->city;
        $userAccount->state = $userAccount->state !== null ? $userAccount->state : $member->state;
        $userAccount->zip = $userAccount->zip !== null ? $userAccount->zip : $member->zip;
        $userAccount->phone = $userAccount->phone !== null ? $userAccount->phone : $member->phone;
        $userAccount->descent = $userAccount->descent !== null ? $userAccount->descent : $member->descent;
        $userAccount->mother = $userAccount->mother !== null ? $userAccount->mother : $member->mother;
        $userAccount->father = $userAccount->father !== null ? $userAccount->father : $member->father;
        $userAccount->spouse = $userAccount->spouse !== null ? $userAccount->spouse : $member->spouse;
        $userAccount->sibling = $userAccount->sibling !== null ? $userAccount->sibling : $member->sibling;
        $userAccount->child = $userAccount->child !== null ? $userAccount->child : $member->child;
        $userAccount->notes = $userAccount->notes !== null ? $userAccount->notes : $member->notes;
        $userAccount->age_group = $userAccount->age_group !== null ? $userAccount->age_group : $member->age_group;
        $userAccount->mail_preference = $userAccount->mail_preference !== null ? $userAccount->mail_preference : $member->mail_preference;
        $userAccount->instagram = $userAccount->instagram !== null ? $userAccount->instagram : $member->instagram;
        $userAccount->facebook = $userAccount->facebook !== null ? $userAccount->facebook : $member->facebook;
        $userAccount->twitter = $userAccount->twitter !== null ? $userAccount->twitter : $member->twitter;
        $userAccount->show_contact = $userAccount->show_contact !== null ? $userAccount->show_contact : $member->show_contact;
        $userAccount->show_socail = $userAccount->show_socail !== null ? $userAccount->show_socail : $member->show_socail;

        if ($userAccount->save()) {
        }

        // If the account being deleted has a registration
        // Change the registration to the account with a profile
        if ($member->registrations->isNotEmpty()) {

            foreach ($member->registrations as $dupeReg) {
                // Check if there is a registration for
                // the parent account already
                if (Registration::memberRegistered($userAccount->id, $dupeReg->reunion_id)->get()->isNotEmpty()) {
                    $parentReg = Registration::memberRegistered($userAccount->id, $dupeReg->reunion_id)->first();

                    $parentReg->youth_names = $parentReg->youth_names !== null ? $parentReg->youth_names : $dupeReg->youth_names;
                    $parentReg->children_names = $parentReg->children_names !== null ? $parentReg->children_names : $dupeReg->children_names;
                    $parentReg->adult_shirts = $parentReg->adult_shirts !== null ? $parentReg->adult_shirts : $dupeReg->adult_shirts;
                    $parentReg->youth_shirts = $parentReg->youth_shirts !== null ? $parentReg->youth_shirts : $dupeReg->youth_shirts;
                    $parentReg->children_shirts = $parentReg->children_shirts !== null ? $parentReg->children_shirts : $dupeReg->children_shirts;
                    $parentReg->email = $parentReg->email !== null ? $parentReg->email : $dupeReg->email;
                    $parentReg->address = $parentReg->address !== null ? $parentReg->address : $dupeReg->address;
                    $parentReg->city = $parentReg->city !== null ? $parentReg->city : $dupeReg->city;
                    $parentReg->city = $parentReg->city !== null ? $parentReg->city : $dupeReg->city;
                    $parentReg->state = $parentReg->state !== null ? $parentReg->state : $dupeReg->state;
                    $parentReg->zip = $parentReg->zip !== null ? $parentReg->zip : $dupeReg->zip;
                    $parentReg->phone = $parentReg->phone !== null ? $parentReg->phone : $dupeReg->phone;
                    $parentReg->due_at_reg = $parentReg->due_at_reg !== null ? $parentReg->due_at_reg : $dupeReg->due_at_reg;
                    $parentReg->total_amount_due = $parentReg->total_amount_due !== null ? $parentReg->total_amount_due : $dupeReg->total_amount_due;
                    $parentReg->total_amount_paid = $parentReg->total_amount_paid !== null ? $parentReg->total_amount_paid : $dupeReg->total_amount_paid;
                    $parentReg->reg_notes = $parentReg->reg_notes !== null ? $parentReg->reg_notes : $dupeReg->reg_notes;
                    $parentReg->parent_reg = $parentReg->parent_reg !== null ? $parentReg->parent_reg : $dupeReg->parent_reg;
                    $parentReg->additional_tees = $parentReg->additional_tees !== null ? $parentReg->additional_tees : $dupeReg->additional_tees;
                    $parentReg->addt_sizes = $parentReg->addt_sizes !== null ? $parentReg->addt_sizes : $dupeReg->addt_sizes;

                    if ($parentReg->save()) {

                        if ($dupeReg->delete()) {
                        }
                    }

                } else {

                    $dupeReg->family_member_id = $userAccount->id;

                    if ($dupeReg->save()) {
                    }
                }

            }

        }

        // If the account being deleted is a part of
        // a reunion committee. Update with parent account id
        if ($member->committees->isNotEmpty()) {
            foreach ($member->committees as $committee_member) {

                $committee_member->family_member_id = $userAccount->id;

                if ($committee_member->save()) {
                }

            }
        }

        // Delete the member account
        if ($member->delete()) {

            $duplicates_check = FamilyMember::checkDuplicates();
            $duplicates_check = $duplicates_check->isNotEmpty() ? $duplicates_check : null;

            array_push($returnData, 'Removed Account', view('admin.members.duplicates', compact('duplicates_check'))->render());

            return $returnData;

        }

    }

    /**
     * Keep the potential duplicate account.
     *
     * @return Response
     */
    public function keep_duplicate(FamilyMember $member)
    {
        $duplicates = FamilyMember::getDuplicates($member->firstname, $member->lastname, $member->city, $member->state)->get();
        $returnData = [];

        $member->duplicate = 'N';

        if ($member->save()) {

            array_push($returnData, 'Account Saved', $duplicates->count() - 1 == 1 ? 'Remove Card' : null);
            return $returnData;

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
