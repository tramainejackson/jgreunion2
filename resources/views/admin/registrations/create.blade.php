<x-app-layout>
    <div class="container-fluid" id="">

        @include('components.nav')

        <div class="row">

            <div class="col-2 my-2">
                <div class="">
                    <a href="{{ route('registrations.index') }}" class="btn btn-info btn-lg">All
                        Registrations</a>
                </div>
            </div>

            <div class="col-8">
                <div class="container-fluid">
                    <div class="row mb-5">

                        <div class="col-12 pt-2 pb-4">
                            <h1 class="text-center">{{ $reunion->reunion_city }} Registration Form</h1>
                        </div>

                        <div class="col-12 pt-2 pb-4">
                            <div class="form-block-header mt-3">
                                <h3 class="mt-2 mb-4">Add A Family Member From List</h3>
                            </div>
                        </div>

                        <div class="col-9 align-items-center justify-content-center d-flex">

                            <!-- Select User Already In Distro List -->
                            <select class="form-control createRegSelect" data-mdb-select-init>
                                @foreach($members as $member)
                                    @php
                                        $thisReg = $member->registrations()->where([
                                            ['reunion_id', '=', $reunion->id],
                                            ['family_members_id', '=', $member->id]
                                        ])->first();
                                    @endphp

                                    <option value="{{ $member->id }}"
                                            class="{{ $thisReg != null ? $thisReg->dl_id == $member->id ? 'text-danger' : '' : '' }}" {{ $thisReg != null ? $thisReg->dl_id == $member->id ? 'disabled' : '' : '' }}>{{ $member->firstname . ' ' . $member->lastname }}{{ $thisReg != null ? $thisReg->dl_id == $member->id ? ' - member already registered' : '' : '' }}</option>
                                @endforeach
                            </select>

                            <label class="form-label select-label">Select A Family Member</label>
                        </div>

                        <div class="">
                            <a href="#" class="btn btn-info createRegSelectLink">Go</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-block-header mt-5">
                                <h3 class="mb-4">If the family member isn't available to select, please create a new
                                    family member profile by following the button below.</h3>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-block-header">
                                <a href="{{ route('members.create') }}" class="btn btn-primary">Create A New Family
                                    Member</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
