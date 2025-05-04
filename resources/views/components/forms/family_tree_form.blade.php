<div id="family_tree" class="my-5">
    <div class="row familyTreeGroup">
        <div class="form-group col-12">
            <h1 class="text-center">Family Tree</h1>
        </div>

        <div class="form-group col-12">
            <h3 class="text-center">Descent</h3>
        </div>

        <div class="form-group text-center col-12">
            <button type="button"
                    class="w-25 mx-auto btn my-2 descentInput{{ $family_member->descent == 'jackson' ? ' btn-success active' : ' btn-outline-success' }}">
                <input type="checkbox" name="descent" value="jackson"
                       hidden {{ $family_member->descent == 'jackson' ? 'checked' : '' }} />Jackson
            </button>

            <button type="button"
                    class="w-25 mx-auto btn my-2 descentInput{{ $family_member->descent == 'green' ? ' btn-success active' : ' btn-outline-success' }}">
                <input type="checkbox" name="descent" value="green"
                       hidden {{ $family_member->descent == 'green' ? 'checked' : '' }} />Green
            </button>
        </div>
    </div>

    <div class="row" id="">
        <div class="form-group col-12">
            <h3 class="text-center">Parents</h3>
        </div>

        <div class="col-6">
            <select class="mx-auto" name="mother" data-mdb-select-init>
                <option value="0">Not Listed</option>

                @foreach($members as $option)
                    <option
                        value="{{ $option->id }}" {{ $option->id == $family_member->mother ? 'selected' : '' }}>{{ $option->full_name() }}</option>
                @endforeach
            </select>

            <label for="mother" class="form-label select-label">Mother</label>
        </div>

        <div class="col-6">
            <select class="mx-auto" name="father" data-mdb-select-init>
                <option value="0">Not Listed</option>

                @foreach($members as $option)
                    <option
                        value="{{ $option->id }}" {{ $option->id == $family_member->father ? 'selected' : '' }}>{{ $option->full_name() }}</option>
                @endforeach
            </select>

            <label for="father" class="form-label select-label">Father</label>
        </div>
    </div>

    <div class="row" id="">
        <div class="col-12 mt-3">
            <h3 class="text-center">Spouse</h3>
        </div>

        <div class="text-center col-12">
            <select class="mx-auto" name="spouse" data-mdb-select-init>
                <option value="0">None</option>

                @foreach($members as $option)
                    <option
                        value="{{ $option->id }}" {{ $option->id == $family_member->spouse ? 'selected' : '' }}>{{ $option->full_name() }}</option>
                @endforeach
            </select>

            <label for="spouse" class="form-label select-label">Spouse</label>
        </div>
    </div>

    <div class="row" id="">
        <div class="col-12 mt-3">
            <h3 class="text-center">Siblings</h3>

            <div class="text-center col-12">
                <button type="button" class="w-50 mx-auto btn btn-outline-success" id="addSiblingRow">Add A Sibling
                </button>
            </div>
        </div>

        @if($siblings != null)
            @foreach($siblings as $sibling)
                @php $sibling = \App\Models\FamilyMember::find($sibling) @endphp

                <div class="col-12 mt-2">
                    <select class="mx-auto" name="siblings[]" data-mdb-select-init>
                        <option value="blank">--- Select/Remove A Sibling ---</option>

                        @foreach($members as $option)
                            <option
                                value="{{ $option->id }}" {{ $option->id == $sibling->id ? 'selected' : '' }}>{{ $option->full_name() }}</option>
                        @endforeach
                    </select>

                    <label for="siblings" class="form-label select-label">Siblings</label>
                </div>
            @endforeach
        @endif

        <!-- Blank row for adding a sibling member -->
        <div class="siblingRow mt-1 mb-2 d-none" id="new_sibling_row_default">
            <select class="mx-auto" name="new_siblings[]">

                <option value="blank">--- Select A Sibling ---</option>

                @foreach($members as $option)
                    <option value="{{ $option->id }}">{{ $option->full_name() }}</option>
                @endforeach
            </select>

            <label for="new_siblings" class="form-label select-label">Siblings</label>
        </div>
    </div>

    <div class="row" id="">
        <div class="col-12 mt-3">
            <h3 class="text-center">Children</h3>

            <div class="text-center col-12">
                <button type="button" class="w-50 mx-auto btn btn-outline-success" id="addChildrenRow">
                    Add A Child
                </button>
            </div>
        </div>

        @if($children != null)
            @foreach($children as $child)
                @php $child = \App\Models\FamilyMember::find($child) @endphp

                <div class="col-12 mt-2">
                    <select class="mx-auto" name="children[]" data-mdb-select-init>

                        <option value="blank">--- Select/Remove A Child ---</option>

                        @foreach($members as $option)
                            <option
                                value="{{ $option->id }}" {{ $option->id == $child->id ? 'selected' : '' }}>{{ $option->full_name() }}</option>
                        @endforeach
                    </select>

                    <label for="children" class="form-label select-label">Child</label>
                </div>
            @endforeach
        @endif

        <!-- Blank row for adding a child member -->
        <div class="form-group text-center col-12 childrenRow my-1 d-none" id="new_child_row_default">
            <select class="mx-auto" name="new_children[]">

                <option value="blank">--- Select A Child ---</option>

                @foreach($members as $option)
                    <option value="{{ $option->id }}">{{ $option->full_name() }}</option>
                @endforeach

                <label for="new_children" class="form-label select-label">Child</label>
            </select>
        </div>
    </div>
</div>
