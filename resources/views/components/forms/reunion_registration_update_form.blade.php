@section('add_scripts')
    <script type="module">
        import {addNewRowNumber, updateAdultName, deleteCommitteeMemberBtn, checkErrors} from '/js/myjs_functions.js';

        document.getElementById('attending_adult').addEventListener("change", (event) => addNewRowNumber('adult'));
        document.getElementById('attending_youth').addEventListener("change", (event) => addNewRowNumber('youth'));
        document.getElementById('attending_children').addEventListener("change", (event) => addNewRowNumber('children'));
        document.getElementById('first_name').addEventListener("change", (event) => updateAdultName());
        document.getElementById('last_name').addEventListener("change", (event) => updateAdultName());
        document.getElementById('submit_reg_form').addEventListener("click", (event) => checkErrors(document.getElementById('registration_update_form')));

        const deleteCommitteeMembersBtns = document.getElementById('registration_update_form').querySelectorAll('button.deleteCommitteeMemberBtn');

        deleteCommitteeMembersBtns.forEach(function (deleteBtn) {
            deleteBtn.addEventListener("click", (event) => deleteCommitteeMemberBtn(event.target));
        });
    </script>
@endsection

<div id="reunion_registration_form">

    <form action="{{ route('registrations.update', $registration) }}" method="POST" name="registration_update_form"
          id="registration_update_form">
        @csrf
        @method('PUT')

        <div class="container-fluid" id="">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="first_name"
                               id="first_name"
                               class="form-control"
                               value="{{ $registration->first_name != '' ? $registration->first_name : '' }}"
                               placeholder="Enter First Name"
                               required/>

                        <label for="first_name" class="form-label">Enter First Name</label>
                    </div>

                    @if($errors->has('first_name'))
                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                    @endif
                </div>

                <div class="col-12 col-sm-6 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="last_name"
                               id="last_name"
                               class="form-control"
                               placeholder="Enter Last Name"
                               value="{{ $registration->last_name != '' ? $registration->last_name : '' }}"
                               required/>

                        <label for="lastname" class="form-label">Enter Last Name</label>
                    </div>

                    @if($errors->has('last_name'))
                        <span class="text-danger">{{ $errors->first('last_name') }}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="address"
                               id="address"
                               class="form-control"
                               placeholder="Enter Home Address"
                               value="{{ $registration->address != '' ? $registration->address : '' }}"
                               required/>

                        <label for="address" class="form-label">Enter Address</label>
                    </div>

                    @if($errors->has('address'))
                        <span class="text-danger">{{ $errors->first('address') }}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-4 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="city"
                               id="city"
                               class="form-control"
                               placeholder="Enter City"
                               value="{{ $registration->city != '' ? $registration->city : '' }}"
                               required/>

                        <label for="city" class="form-label">Enter City</label>
                    </div>

                    @if($errors->has('city'))
                        <span class="text-danger">{{ $errors->first('city') }}</span>
                    @endif
                </div>

                <div class="col-6 col-sm-4 my-2">
                    <div class="form-outline" id="">
                        <select class="" name="state" data-mdb-select-init required>
                            @foreach($states as $state)
                                <option
                                    value="{{ $state->state_abb }}" {{ $registration->state == $state->state_abb ? 'selected' : '' }}>{{ $state->state_name }}</option>
                            @endforeach
                        </select>

                        <label for="state" class="form-label select-label">Select State</label>
                    </div>
                </div>

                <div class="col-6 col-sm-4 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="number"
                               name="zip"
                               id="zip"
                               class="form-control"
                               placeholder="Enter Zip Code"
                               value="{{ $registration->zip != '' ? $registration->zip : '' }}"
                               required/>

                        <label for="zip" class="form-label">Enter Zip Code</label>
                    </div>

                    @if($errors->has('zip'))
                        <span class="text-danger">{{ $errors->first('zip') }}</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="phone"
                               id="phone"
                               class="form-control"
                               placeholder="Enter Phone Number"
                               value="{{ $registration->phone != '' ? $registration->phone : '' }}"/>

                        <label for="phone" class="form-label">Enter Phone Number</label>

                        @if($errors->has('phone'))
                            <span
                                class="text-danger">{{ $errors->first('phone') }}. No special characters required</span>
                        @endif
                    </div>
                </div>

                <div class="col-12 col-md-6 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="email"
                               name="email"
                               id="email"
                               class="form-control"
                               placeholder="Email Address"
                               value="{{ $registration->email != '' ? $registration->email : '' }}"/>

                        <label for="email" class="form-label">Enter Email Address</label>

                        @if($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table" id="registration_form_table">
                    <thead>
                    <tr>
                        <th scope="col" class="" style="min-width: 165px;"></th>
                        <th scope="col" class="" abbr="Cost PP" style="min-width: 165px;">Cost Per Person</th>
                        <th scope="col" class="" style="min-width: 165px;">Number Attending</th>
                        <th scope="col" class="" style="min-width: 165px;">Total Cost</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr id="adult_row" scope="row">
                        <td class="fixed-cell">Adults (Ages 16+)</td>
                        <td class="costPP">
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon1">$</span>

                                <input type="number"
                                       id="cost_per_adult"
                                       class="form-control"
                                       value="{{ $reunion->adult_price }}"
                                       aria-describedby="basic-addon1"
                                       disabled/>
                            </div>
                        </td>

                        <td>
                            <input type="number"
                                   name="attending_adult"
                                   id="attending_adult"
                                   class="form-control"
                                   min="{{ $adults != null ? (count($adults)) : 1 }}"
                                   value="{{ $adults != null ? (count($adults)) : 1 }}"/>
                        </td>

                        <td>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon">$</span>

                                <input type="number"
                                       name="total_adult"
                                       id="total_adult"
                                       class="form-control"
                                       value="{{ $adults != null ? (count($adults) * $reunion->adult_price) : $reunion->adult_price }}"
                                       disabled/>
                            </div>
                        </td>
                    </tr>

                    <tr id="attending_adult_row_duplicate" class="">
                        <td></td>
                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_adult_first_name[]"
                                       class="form-control"
                                       value="{{ Auth::check() ? $member->firstname : '' }}"
                                       required
                                />

                                <label for="attending_adult_first_name" class="form-label">First Name</label>
                            </div>
                        </td>

                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_adult_last_name[]"
                                       class="form-control"
                                       value="{{ Auth::check() ? $member->lastname : '' }}"
                                       required
                                />

                                <label for="attending_adult_last_name" class="form-label">Last Name</label>
                            </div>
                        </td>

                        <td>
                            <select name="adult_shirts[]" class="shirt_size" data-mdb-select-init>
                                <option value="S">Small</option>
                                <option value="M">Medium</option>
                                <option value="L">Large</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="XXXL">3XL</option>
                            </select>

                            <label for="adult_shirts" class="form-label select-label">Shirt Size</label>
                        </td>
                    </tr>

                    @if($adults != null && (count($adults) >= 2))
                        @foreach($adults as $index => $adult)
                            @if($index != 0)

                                <tr id="" class="attending_adult_row">
                                    <td>
                                        <button type="button"
                                                class="btn btn-outline-danger w-100 m-0 deleteCommitteeMemberBtn">Delete
                                            <input type="text" name="remove_adult_member[]" value="N" hidden>
                                        </button>
                                    </td>

                                    <td>
                                        <div class="form-outline" data-mdb-input-init>
                                            <input type="text"
                                                   name="attending_adult_first_name[]"
                                                   class="form-control"
                                                   value="{{ $adult->first_name }}"
                                                   required
                                            />

                                            <label for="attending_adult_first_name" class="form-label">First
                                                Name</label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="form-outline" data-mdb-input-init>
                                            <input type="text"
                                                   name="attending_adult_last_name[]"
                                                   class="form-control"
                                                   value="{{ $adult->last_name }}"
                                                   required
                                            />

                                            <label for="attending_adult_last_name" class="form-label">Last Name</label>
                                        </div>
                                    </td>

                                    <td>
                                        <select name="adult_shirts[]" class="shirt_size" data-mdb-select-init>
                                            <option value="S"{{ $adult->shirt_size == 'S' ? ' selected' : '' }}>Small
                                            </option>
                                            <option value="M"{{ $adult->shirt_size == 'M' ? ' selected' : '' }}>Medium
                                            </option>
                                            <option value="L"{{ $adult->shirt_size == 'L' ? ' selected' : '' }}>Large
                                            </option>
                                            <option value="XL"{{ $adult->shirt_size == 'XL' ? ' selected' : '' }}>XL
                                            </option>
                                            <option value="XXL"{{ $adult->shirt_size == 'XXL' ? ' selected' : '' }}>XXL
                                            </option>
                                            <option value="XXXL"{{ $adult->shirt_size == 'XXXL' ? ' selected' : '' }}>
                                                3XL
                                            </option>
                                        </select>

                                        <label for="adult_shirts" class="form-label select-label">Shirt Size</label>
                                    </td>

                                    <td class="d-none"><input type="number" name="adult_member_reg_id[]"
                                                              value="{{ $adult->id }}" hidden></td>
                                </tr>
                            @endif
                        @endforeach
                    @endif

                    <tr id="attending_adult_row_default" class="attending_adult_row d-none">
                        <td></td>

                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_new_adult_first_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_new_adult_first_name" class="form-label">First Name</label>
                            </div>
                        </td>

                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_new_adult_last_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_new_adult_last_name" class="form-label">Last Name</label>
                            </div>
                        </td>

                        <td>
                            <select name="new_adult_shirts[]" class="shirt_size" disabled>
                                <option value="S">Small</option>
                                <option value="M">Medium</option>
                                <option value="L">Large</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                                <option value="XXXL">3XL</option>
                            </select>

                            <label for="new_adult_shirts" class="form-label select-label">Shirt Size</label>
                        </td>
                    </tr>

                    <tr id="youth_row">
                        <td>Youth (Ages 7-15)</td>
                        <td class="costPP">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">$</span>
                                </div>

                                <input type="number"
                                       id="cost_per_youth"
                                       class="form-control"
                                       value="{{ $reunion->youth_price }}"
                                       step="0.01"
                                       disabled/>
                            </div>
                        </td>
                        <td>
                            <input type="number"
                                   name="attending_youth"
                                   id="attending_youth"
                                   class="form-control"
                                   min="{{ $youths != null ? (count($youths)) : 1 }}"
                                   value="{{ $youths != null ? (count($youths)) : 1 }}"/>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">$</span>
                                </div>
                                <input type="number"
                                       name="total_youth"
                                       id="total_youth"
                                       class="form-control"
                                       value="{{ $youths != null ? (count($youths) * $reunion->youth_price) : $reunion->youth_price }}"
                                       disabled/>
                            </div>
                        </td>
                    </tr>

                    @if($youths != null && (count($youths) >= 1))
                        @foreach($youths as $index => $youth)
                            <tr id="" class="attending_youth_row">
                                <td>
                                    <button type="button"
                                            class="btn btn-outline-danger w-100 m-0 deleteCommitteeMemberBtn">Delete
                                        <input type="text" name="remove_youth_member[]" value="N" hidden>
                                    </button>
                                </td>

                                <td>
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text"
                                               name="attending_youth_first_name[]"
                                               class="form-control"
                                               value="{{ $youth->first_name }}"
                                               required
                                        />

                                        <label for="attending_youth_first_name" class="form-label">First Name</label>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text"
                                               name="attending_youth_last_name[]"
                                               class="form-control"
                                               value="{{ $youth->last_name }}"
                                               required
                                        />

                                        <label for="attending_youth_last_name" class="form-label">Last Name</label>
                                    </div>
                                </td>

                                <td>
                                    <select name="youth_shirts[]" class="shirt_size" data-mdb-select-init>
                                        <option value="XS"{{ $youth->shirt_size == 'XS' ? ' selected' : '' }}>Youth
                                            XSmall
                                        </option>
                                        <option value="S"{{ $youth->shirt_size == 'S' ? ' selected' : '' }}>Youth Small
                                        </option>
                                        <option value="M"{{ $youth->shirt_size == 'M' ? ' selected' : '' }}>Youth Medium
                                        </option>
                                        <option value="L"{{ $youth->shirt_size == 'L' ? ' selected' : '' }}>Youth Large
                                        </option>
                                        <option value="XL"{{ $youth->shirt_size == 'XL' ? ' selected' : '' }}>Adult
                                            Small
                                        </option>
                                        <option value="XXL"{{ $youth->shirt_size == 'XXXL' ? ' selected' : '' }}>Adult
                                            Medium
                                        </option>
                                    </select>

                                    <label for="youth_shirts" class="form-label select-label">Shirt Size</label>
                                </td>

                                <td class="d-none"><input type="number" name="youth_member_reg_id[]"
                                                          value="{{ $youth->id }}" hidden></td>
                            </tr>
                        @endforeach
                    @endif

                    <tr id="attending_youth_row_default" class="attending_youth_row d-none">
                        <td></td>

                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_new_youth_first_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_new_youth_first_name" class="form-label">First Name</label>
                            </div>
                        </td>

                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_new_youth_last_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_new_youth_last_name" class="form-label">Last Name</label>
                            </div>
                        </td>

                        <td>
                            <select name="new_youth_shirts[]" class="shirt_size" disabled>
                                <option value="XS">Youth XSmall</option>
                                <option value="S">Youth Small</option>
                                <option value="M">Youth Medium</option>
                                <option value="L">Youth Large</option>
                                <option value="XL">Adult Small</option>
                                <option value="XXL">Adult Medium</option>
                            </select>

                            <label for="new_youth_shirts" class="form-label select-label">Shirt Size</label>
                        </td>
                    </tr>

                    <tr id="children_row">
                        <td>Children (Ages 1-6)</td>
                        <td class="costPP">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">$</span>
                                </div>

                                <input type="number"
                                       id="cost_per_children"
                                       class="costPC form-control"
                                       value="{{ $reunion->child_price }}"
                                       step="0.01"
                                       disabled/>
                            </div>
                        </td>
                        <td>
                            <input type="number"
                                   name="attending_children"
                                   id="attending_children"
                                   class="form-control"
                                   min="{{ $children != null ? (count($children)) : 1 }}"
                                   value="{{ $children != null ? (count($children)) : 1 }}"/>
                        </td>

                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">$</span>
                                </div>

                                <input type="number"
                                       name="total_children"
                                       id="total_children"
                                       class="form-control"
                                       value="{{ $children != null ? (count($children) * $reunion->child_price) : $reunion->child_price }}"
                                       disabled/>
                            </div>
                        </td>
                    </tr>

                    @if($children != null && (count($children) >= 1))
                        @foreach($children as $index => $child)
                            <tr id="" class="attending_children_row">
                                <td>
                                    <button type="button"
                                            class="btn btn-outline-danger w-100 m-0 deleteCommitteeMemberBtn">Delete
                                        <input type="text" name="remove_child_member[]" value="N" hidden>
                                    </button>
                                </td>

                                <td>
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text"
                                               name="attending_children_first_name[]"
                                               class="form-control"
                                               value="{{ $child->first_name }}"
                                               required/>

                                        <label for="attending_children_first_name" class="form-label">First Name</label>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text"
                                               name="attending_children_last_name[]"
                                               class="form-control"
                                               value="{{ $child->last_name }}"
                                               required/>

                                        <label for="attending_children_last_name" class="form-label">Last Name</label>
                                    </div>
                                </td>

                                <td>
                                    <select name="children_shirts[]" class="shirt_size" data-mdb-select-init>
                                        <option value="S"{{ $child->shirt_size == 'S' ? ' selected' : '' }}>12 Months
                                        </option>
                                        <option value="M"{{ $child->shirt_size == 'M' ? ' selected' : '' }}>2T</option>
                                        <option value="L"{{ $child->shirt_size == 'L' ? ' selected' : '' }}>3T</option>
                                        <option value="XL"{{ $child->shirt_size == 'XL' ? ' selected' : '' }}>4T
                                        </option>
                                        <option value="XXL"{{ $child->shirt_size == 'XXL' ? ' selected' : '' }}>5T
                                        </option>
                                        <option value="XXXL"{{ $child->shirt_size == 'XXXL' ? ' selected' : '' }}>6T
                                        </option>
                                    </select>

                                    <label for="children_shirts" class="form-label select-label">Shirt Size</label>
                                </td>

                                <td class="d-none"><input type="number" name="child_member_reg_id[]"
                                                          value="{{ $child->id }}" hidden></td>
                            </tr>
                        @endforeach
                    @endif

                    <tr id="attending_children_row_default" class="attending_children_row d-none">
                        <td></td>

                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_new_children_first_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_new_children_first_name" class="form-label">First Name</label>
                            </div>
                        </td>

                        <td>
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_new_children_last_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_new_children_last_name" class="form-label">Last Name</label>
                            </div>
                        </td>

                        <td>
                            <select name="new_children_shirts[]" class="shirt_size" disabled>
                                <option value="S">12 Months</option>
                                <option value="M">2T</option>
                                <option value="L">3T</option>
                                <option value="XL">4T</option>
                                <option value="XXL">5T</option>
                                <option value="XXXL">6T</option>
                            </select>

                            <label for="new_children_shirts" class="form-label select-label">Shirt Size</label>
                        </td>
                    </tr>

                    <tr id="totalDue_row">
                        <td class="total_due">Total Due:</td>
                        <td class="total_due"></td>
                        <td class="total_due"></td>
                        <td class="total_due">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">$</span>
                                </div>

                                <input type="number"
                                       name="due_at_reg"
                                       id="total_amount_due"
                                       class="form-control"
                                       value="{{ $registration->due_at_reg }}"
                                       disabled/>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="form-block-header mt-4">
                <h3 class="">Registration Cost Information</h3>
            </div>

            <div class="registrationCost" id="registration_cost">
                <div class="d-flex flex-column flex-md-row">
                    <div class="col pb-3">

                        <label class="form-label text-danger mb-0" for="due_at_reg">Registration Amount</label>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">$</span>

                            <input type="number"
                                   class="form-control"
                                   value="{{ $registration->due_at_reg }}"
                                   placeholder="Enter Registration Cost"
                                   step="0.01"
                                   aria-describedby="basic-addon1"
                                   disabled/>
                        </div>
                    </div>

                    <div class="col px-md-2 pb-3">
                        <label class="form-label text-danger mb-0" for="total_amount_due">Amount Due</label>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon2">$</span>

                            <input type="number"
                                   name="total_amount_due"
                                   class="form-control"
                                   value="{{ $registration->total_amount_due }}"
                                   placeholder="Enter Cost Due"
                                   step="0.01"
                                   aria-describedby="basic-addon2"
                                   disabled/>
                        </div>
                    </div>

                    <div class="col pb-3">
                        <label class="form-label text-danger mb-0" for="total_amount_paid">Amount Paid</label>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon3">$</span>

                            <input type="number"
                                   name="total_amount_paid"
                                   class="form-control"
                                   value="{{ $registration->total_amount_paid }}"
                                   placeholder="Enter Amount Paid"
                                   step="0.01"
                                   aria-describedby="basic-addon3"
                                {{ !Auth::user()->is_admin() ? 'disabled' : '' }}/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-outline" data-mdb-input-init>
                <textarea class="form-control"
                          name="registration_notes"
                          placeholder="Enter registration notes for {{ $registration->full_name() }}"
                            {{ !Auth::user()->is_admin() ? 'disabled' : '' }}>{{ $registration->reg_notes }}</textarea>
                <label class="form-label" for="registration_notes">Registration Notes</label>
            </div>

            <div class="row mt-4">
                <div class="mx-auto col-11 col-sm-6 col-lg-4">
                    <button type="submit" class="btn btn-primary btn-lg form-control" id="submit_reg_form">Update
                        Registration
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
