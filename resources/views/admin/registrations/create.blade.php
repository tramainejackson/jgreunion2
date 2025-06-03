<x-app-layout>

    @section('add_scripts')
        <script type="module">
            import {createNewRegistration} from '/js/myjs_functions.js';

            document.getElementById('create_reg_select').addEventListener("change", (event) => createNewRegistration(event.target));
        </script>
    @endsection

    <div class="container-fluid" id="">

        <x-admin-jumbotron>Create New Registration</x-admin-jumbotron>

        @include('components.nav')

        <div class="row">

            <div class="col-2 my-2">
                <a href="{{ route('registrations.index') }}" class="btn btn-info">All
                    Registrations</a>
            </div>

            <div class="col-10">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-12 pt-2">
                            <h1 class="text-center">{{ $reunion->reunion_city }} Registration Form</h1>
                        </div>

                        <div class="col-12 pt-2">
                            <div class="form-block-header mt-3">
                                <h3 class="mt-2">Add A Family Member From List</h3>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-5">

                        <div class="col-9">

                            <!-- Select User Already In Distro List -->
                            <select class="form-control createRegSelect" id="create_reg_select" data-mdb-select-init>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}"
                                            class="" {{ $member->registrations->isNotEmpty() ? $member->registrations->first()->reunion->id == $active_reunion->id ? 'disabled' : '' : '' }}>{{ $member->full_name() }}{{ $member->registrations->isNotEmpty() ? $member->registrations->first()->reunion->id == $active_reunion->id ? ' - member already registered' : '' : '' }}</option>
                                @endforeach
                            </select>

                            <label class="form-label select-label">Select A Family Member</label>
                        </div>

                        <div class="col-3">
                            <a href="#" target="_blank" type="button" class="d-none" id="member_registration_link" hidden></a>
                            <button type="button" class="btn btn-info createRegSelectLink" id="create_reg_select_link" onclick="document.getElementById('member_registration_link').click();">Go</button>
                        </div>
                    </div>

                    <hr class="hr hr-blurry">

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
