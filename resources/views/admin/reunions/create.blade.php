<x-app-layout>

    @section('add_scripts')
        <script type="module">
            import {addNewRowFromBtn} from '/js/myjs_functions.js';

            document.getElementById('addCommitteeMember').addEventListener("click", (event) => addNewRowFromBtn('committee'));
        </script>
    @endsection

    <div class="container-fluid" id="">

        <x-admin-jumbotron>Create Reunion</x-admin-jumbotron>

        @include('components.nav')

        <div class="row">

            {{--            <div class="col-12 col-md-2 my-2">--}}
            {{--                <div class="">--}}
            {{--                    <a href="{{ route('reunions.index') }}" class="btn btn-info btn-lg">All Reunions</a>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="col-10 col-md-11 col-lg-9 reunionForm mx-auto">

                <h1 class="mt-2 mb-0">Create New Reunion</h1>
                <h4 class="nt-0 mb-4 text-danger">**Creating A New Reunion Will Make All Current Reunions
                    Complete**</h4>

                <form action="{{ route('reunions.store') }}" method="POST">
                    @csrf

                    <div class="container-fluid mb-3" id="">
                        <div class="row" id="">
                            <div class="col-12" id="">
                                <div class="form-block-header">
                                    <h3 class="">Location</h3>
                                </div>
                            </div>

                            <div class="col-4" id="">
                                <div class="form-outline mb-2" data-mdb-input-init>
                                    <input type="text"
                                           name="reunion_city"
                                           class="form-control"
                                           value="{{  old('reunion_city') }}"
                                           placeholder="Enter City For Next Reunion"/>

                                    <label class="form-label" for="reunion_city">City</label>
                                </div>
                            </div>

                            <div class="col-4" id="">
                                <div class="form-outline mb-2">
                                    <select class="form-control" name="reunion_state" data-mdb-select-init>
                                        @foreach($states as $state)
                                            <option
                                                value="{{ $state->state_abb }}" {{ old('reunion_state') && old('reunion_state') == $state->state_abb ? 'selected' : '' }}>{{ $state->state_name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label class="form-label select-label" for="reunion_state">State</label>
                                </div>
                            </div>

                            <div class="col-4" id="">
                                <div class="form-outline mb-2">
                                    <select class="form-control" name="reunion_year" data-mdb-select-init>
                                        @for($i=0; $i <= 10; $i++)
                                            <option
                                                value="{{ $carbonDate->addYear()->year }}" {{ old('reunion_year') && old('reunion_year') == $year->year_num ? 'selected' : '' }}>{{ $carbonDate->year }}
                                            </option>
                                        @endfor
                                    </select>

                                    <label class="form-label select-label" for="reunion_state">Year</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mb-3" id="">
                        <div class="row" id="">
                            <div class="col-12" id="">
                                <div class="form-block-header">
                                    <h3 class="">Prices</h3>
                                </div>
                            </div>

                            <div class="col-4" id="">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="">$</span>

                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="number"
                                               name="adult_price"
                                               class="form-control"
                                               value="{{ old('adult_price') }}"
                                               step="0.01"
                                               placeholder="Price For Adult 18-Older"/>

                                        <label class="form-label" for="adult_price">Adult Price</label>
                                    </div>

                                    <span class="input-group-text" id="">Per Adult</span>
                                </div>
                            </div>

                            <div class="col-4" id="">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="">$</span>

                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="number"
                                               name="youth_price"
                                               class="form-control"
                                               value="{{ old('youth_price') }}"
                                               step="0.01"
                                               placeholder="Price For Youth 4-18"/>

                                        <label class="form-label" for="youth_price">Youth Price</label>
                                    </div>

                                    <span class="input-group-text" id="">Per Youth</span>
                                </div>
                            </div>

                            <div class="col-4" id="">
                                <div class="input-group mb-2">
                                    <span class="input-group-text" id="">$</span>

                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="number"
                                               name="child_price"
                                               class="form-control"
                                               value="{{ old('child_price') }}"
                                               step="0.01"
                                               placeholder="Price For Children 3-Under"/>

                                        <label class="form-label" for="child_price">Child Price</label>
                                    </div>

                                    <span class="input-group-text" id="">Per Child</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-fluid mb-3" id="">
                        <div class="row" id="">
                            <div class="col-12" id="">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h3 class="">Committee</h3>

                                    <button type="button" id="addCommitteeMember" class="btn btn-outline-success mb-2">Add Committee
                                        Member
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-6 col-md-4">
                                <div class="form-outline">
                                    <select class="form-control" name="member_title[]" data-mdb-select-init>
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

                            <div class="col-6 col-md-8">
                                <div class="form-outline">
                                    <select class="form-control" name="dl_id[]" data-mdb-select-init>
                                        @foreach($members as $member)
                                            <option
                                                value="{{ $member->id }}" {{ old('dl_id') && old('dl_id') == $member->id ? 'selected' : '' }}>{{ $member->firstname . ' ' . $member->lastname }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label select-label" for="dl_id">Member</label>
                                </div>
                            </div>
                        </div>

                        <div class="row d-none mb-2" id="new_committee_row_default">
                            <div class="col-6 col-md-4">
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

                            <div class="col-6 col-md-8">
                                <div class="form-outline">
                                    <select class="form-control" name="dl_id[]" disabled>
                                        @foreach($members as $member)
                                            <option
                                                value="{{ $member->id }}" {{ old('dl_id') && old('dl_id') == $member->id ? 'selected' : '' }}>{{ $member->firstname . ' ' . $member->lastname }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label select-label" for="dl_id">Member</label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="form-group">
                        <button class="btn btn-primary form-control" type="submit">Create New Reunion</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
