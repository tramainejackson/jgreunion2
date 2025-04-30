<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="mt-3 mb-2">My Profile</h1>

            <form action="{{ route('members.update', $family_member->id) }}" method="POST">
                @csrf
                @method('PUT')

                @include('components.forms.user_profile_form')

                <hr class="hr hr-blurry">

                @include('components.forms.family_tree_form')

                <hr class="hr hr-blurry">

                @include('components.forms.social_media_form')

                <div class="houseHoldBlock">
                    <div class="form-block-header">
                        <h3 class="">Household Members
                            <button type="button" class="btn btn-outline-success mb-2 addHHMember">Add Household
                                Member
                            </button>
                        </h3>
                    </div>

                    @if($family_members->count() > 1)

                        @foreach($family_members as $family_member)

                            <div class="form-row">
                                <div class="form-group col-8">
                                    <input class="form-control"
                                           value="{{ $family_member->firstname . ' ' . $family_member->lastname }}"
                                           disabled/>
                                    <input value="{{ $family_member->id }}" hidden/>
                                </div>
                                <div class="">
                                    <div class="form-group col-2">
                                        <a href="#"
                                           class="btn btn-danger{{ $family_member->id == $family_member->id ? ' disabled' : '' }}"
                                           onclick="event.preventDefault(); removeFromHouseHold({{ $family_member->id . ',' .$family_member->id }});">Remove
                                            Household Member</a>
                                    </div>
                                </div>
                            </div>

                        @endforeach

                    @endif

                    <!-- Blank row for adding a household member -->
                    <div class="form-row hhMemberRow hidden">
                        <div class="form-group col-7">
                            <select class="form-control" name="houseMember[]">
                                <option value="blank">--- Select A Household Member ---</option>
                                @foreach($family_members as $option)
                                    <option
                                        value="{{ $option->id }}">{{ $option->firstname . ' ' . $option->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-2">
                            <button type="button" class="btn btn-danger d-block removeHHMember">Remove</button>
                        </div>
                    </div>

                    @if($potential_family_members->count() > $family_members->count())

                        <div class="form-block-header">
                            <h3 class="">Potential Household Members</h3>
                        </div>

                        @foreach($potential_family_members as $potential_family_member)
                            @if($potential_family_member->id != $family_member->id)
                                <div class="form-row">
                                    <div class="form-group col-8">
                                        <input class="form-control"
                                               value="{{ $potential_family_member->firstname . ' ' . $potential_family_member->lastname }}"
                                               disabled/>
                                        <input value="{{ $potential_family_member->id }}" hidden/>
                                    </div>
                                    <div class="">
                                        <div class="form-group col-2">
                                            <a href="#" class="btn btn-warning"
                                               onclick="event.preventDefault(); addToHouseHold({{$family_member->id . ',' . $potential_family_member->id}});">Add
                                                To Household</a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>

                <div class="form-group">
                    <button class="btn btn-primary form-control" type="submit">Update Member</button>
                </div>

            </form>
        </div>
    </div>
</div>
