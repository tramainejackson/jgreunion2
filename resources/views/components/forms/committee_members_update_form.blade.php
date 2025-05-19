<div id="committee_members_form" class="my-5 container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Committee Members Section -->
            <div class="form-block-header">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="">Committee</h3>

                    <button type="button" id="addCommitteeMember" class="btn btn-outline-success mb-2">Add
                        Committee Member
                    </button>

                    <button type="button" class="btn btn-primary mb-2">Committee Members <span
                            class="badge badge-light">{{ $reunion->committee->count() }}</span>
                        <span class="sr-only">total committee members</span>
                    </button>
                </div>
            </div>

            @foreach($reunion->committee as $committee_member)
                <div class="row">
                    <div class="col-6 col-md-4 col-lg-4 mb-0 mb-md-2">
                        <select class="form-control" name="member_title[]" data-mdb-select-init>
                            <option
                                value="president" {{ $committee_member->member_title != null && $committee_member->member_title == 'president' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'president')) }}</option>
                            <option
                                value="vice_president" {{$committee_member->member_title != null && $committee_member->member_title == 'vice_president' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'vice_president')) }}</option>
                            <option
                                value="treasurer" {{ $committee_member->member_title != null && $committee_member->member_title == 'treasurer' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'treasurer')) }}</option>
                            <option
                                value="secretary" {{ $committee_member->member_title != null && $committee_member->member_title == 'secretary' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'secretary')) }}</option>
                            <option
                                value="chairman" {{ $committee_member->member_title != null && $committee_member->member_title == 'chairman' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'chairman')) }}</option>
                        </select>

                        <label class="form-label select-label" for="member_title">Committee Title</label>

                        <input type="text" name="committee_member_id[]" class="hidden"
                               value="{{ $committee_member->id }}" hidden/>

                    </div>

                    <div class="col-6 col-md-4 col-lg-4 mb-0 mb-md-2">

                        <select class="form-control browser-default" name="family_member_id[]" data-mdb-select-init>

                            @foreach($members as $member)
                                <option
                                    value="{{ $member->id }}" {{ old('family_member_id') && old('family_member_id') == $member->id ? 'selected' : ($committee_member->family_member_id == $member->id ? 'selected' : '') }}>{{ $member->firstname . ' ' . $member->lastname }}</option>
                            @endforeach

                        </select>

                        <label class="form-label select-label" for="dl_id">Member</label>
                    </div>

                    <div class="col-12 col-md-4 col-lg-4 mb-5 mb-md-2">
                        <button type="button" class="btn btn-outline-danger w-100 m-0 deleteCommitteeMemberBtn">Delete Member
                            <input type="text" name="remove_committee_member[]" value="N" hidden>
                        </button>
                    </div>
                </div>
            @endforeach

            @if($reunion->committee->isEmpty())
                <div class="row emptyCommittee">
                    <div class="col">
                        <h2 class="text-center">No Committee Members Added Yet</h2>
                    </div>
                </div>
            @endif

            <div class="row d-none mb-2" id="new_committee_row_default">
                <div class="col-5 col-md-4">
                    <div class="form-outline">
                        <select class="form-control" name="member_title[]" disabled>
                            <option
                                value="president" {{ old('member_title') && old('member_title') == 'president' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'president')) }}</option>
                            <option
                                value="vice_president" {{ old('member_title') && old('member_title') == 'vice_president' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'vice_president')) }}</option>
                            <option
                                value="treasurer" {{ old('member_title') && old('member_title') == 'treasurer' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'treasurer')) }}</option>
                            <option
                                value="secretary" {{ old('member_title') && old('member_title') == 'secretary' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'secretary')) }}</option>
                            <option
                                value="chairman" {{ old('member_title') && old('member_title') == 'chairman' ? 'selected' : '' }}>{{ ucwords(str_ireplace('_', ' ', 'chairman')) }}</option>
                        </select>

                        <label class="form-label select-label" for="member_title">Committee Title</label>
                    </div>
                </div>

                <div class="col-5 col-md-6">
                    <div class="form-outline">
                        <select class="form-control" name="family_member_id[]" disabled>
                            @foreach($members as $member)
                                <option
                                    value="{{ $member->id }}" {{ old('family_member_id') && old('family_member_id') == $member->id ? 'selected' : '' }}>{{ $member->firstname . ' ' . $member->lastname }}</option>
                            @endforeach
                        </select>

                        <label class="form-label select-label" for="dl_id">Member</label>
                    </div>
                </div>

                <div class="col-2 col-md-2 mb-5 mb-md-2">
                    <button type="button" class="btn btn-danger w-100 removeCommitteeMember m-0">Remove</button>
                </div>
            </div>
        </div>
    </div>
</div>
