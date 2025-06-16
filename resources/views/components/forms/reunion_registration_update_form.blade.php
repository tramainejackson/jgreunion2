@section('add_scripts')
    <script type="module">
        import {addNewRowNumber, updateAdultName} from '/js/myjs_functions.js';

        document.getElementById('attending_adult').addEventListener("change", (event) => addNewRowNumber('adult'));
        document.getElementById('attending_youth').addEventListener("change", (event) => addNewRowNumber('youth'));
        document.getElementById('attending_children').addEventListener("change", (event) => addNewRowNumber('children'));
        document.getElementById('firstname').addEventListener("change", (event) => updateAdultName());
    </script>
@endsection

<div id="reunion_registration_form">

    <form action="{{ route('registrations.update', $registration) }}" method="POST" name="registration_form">
        @csrf
        @method('PUT')

        <div class="container-fluid" id="">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="firstname"
                               id="firstname"
                               class="form-control"
                               value="{{ $registration->registree_name != '' ? $registration->registree_name : '' }}"
                               placeholder="Enter Firstname"
                               required/>

                        <label for="firstname" class="form-label">Enter First Name</label>
                    </div>

                    @if($errors->has('firstname'))
                        <span class="text-danger">{{ $errors->first('firstname') }}</span>
                    @endif
                </div>

                <div class="col-12 col-sm-6 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="lastname"
                               id="lastname"
                               class="form-control"
                               placeholder="Enter Lastname"
                               value="{{ $registration->registree_name != '' ? $registration->registree_name : '' }}"
                               required/>

                        <label for="lastname" class="form-label">Enter Last Name</label>
                    </div>

                    @if($errors->has('lastname'))
                        <span class="text-danger">{{ $errors->first('lastname') }}</span>
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
                        <td>
                            <button class="btn btn-danger">Delete</button>
                        </td>
                        <td colspan="2">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_adult_name[]"
                                       class="form-control"
                                       value="{{ Auth::check() ? $member->firstname : '' }}"
                                       required
                                />

                                <label for="attending_adult_name" class="form-label">First Name</label>
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
                        @for($x=0; $x < count($adults); $x++)
                            @if($x != 0)
                                <tr id="" class="attending_adult_row">
                                    <td>
                                        <button class="btn btn-danger">Delete</button>
                                    </td>
                                    <td colspan="2">
                                        <div class="form-outline" data-mdb-input-init>
                                            <input type="text"
                                                   name="attending_adult_name[]"
                                                   class="form-control"
                                                   value="{{ $adults[$x] }}"
                                                   required
                                            />

                                            <label for="attending_adult_name" class="form-label">First Name</label>
                                        </div>
                                    </td>

                                    <td>
                                        <select name="adult_shirts[]" class="shirt_size" data-mdb-select-init>
                                            <option value="S"{{ $adultSizes[$x] == 'S' ? ' selected' : '' }}>Small</option>
                                            <option value="M"{{ $adultSizes[$x] == 'M' ? ' selected' : '' }}>Medium</option>
                                            <option value="L"{{ $adultSizes[$x] == 'L' ? ' selected' : '' }}>Large</option>
                                            <option value="XL"{{ $adultSizes[$x] == 'XL' ? ' selected' : '' }}>XL</option>
                                            <option value="XXL"{{ $adultSizes[$x] == 'XXL' ? ' selected' : '' }}>XXL</option>
                                            <option value="XXXL"{{ $adultSizes[$x] == 'XXXL' ? ' selected' : '' }}>3XL</option>
                                        </select>

                                        <label for="adult_shirts" class="form-label select-label">Shirt Size</label>
                                    </td>
                                </tr>
                            @endif
                        @endfor
                    @endif

                    <tr id="attending_adult_row_default" class="attending_adult_row d-none">
                        <td></td>
                        <td colspan="2">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_adult_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_adult_name" class="form-label">First Name</label>
                            </div>
                        </td>

                        <td>
                            <select name="adult_shirts[]" class="shirt_size" disabled>
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
                        @for($x=0; $x < count($youths); $x++)
                            <tr id="" class="attending_youth_row">
                                <td>
                                    <button class="btn btn-danger">Delete</button>
                                </td>
                                <td colspan="2">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text"
                                               name="attending_youth_name[]"
                                               class="form-control"
                                               value="{{ $youths[$x] }}"
                                               required
                                               />

                                        <label for="attending_youth_name" class="form-label">First Name</label>
                                    </div>
                                </td>

                                <td>
                                    <select name="youth_shirts[]" class="shirt_size" data-mdb-select-init>
                                        <option value="XS"{{ $youthSizes[$x] == 'XS' ? ' selected' : '' }}>Youth XSmall</option>
                                        <option value="S"{{ $youthSizes[$x] == 'S' ? ' selected' : '' }}>Youth Small</option>
                                        <option value="M"{{ $youthSizes[$x] == 'M' ? ' selected' : '' }}>Youth Medium</option>
                                        <option value="L"{{ $youthSizes[$x] == 'L' ? ' selected' : '' }}>Youth Large</option>
                                        <option value="XL"{{ $youthSizes[$x] == 'XL' ? ' selected' : '' }}>Adult Small</option>
                                        <option value="XXL"{{ $youthSizes[$x] == 'XXXL' ? ' selected' : '' }}>Adult Medium</option>
                                    </select>

                                    <label for="youth_shirts" class="form-label select-label">Shirt Size</label>
                                </td>
                            </tr>
                        @endfor
                    @endif

                    <tr id="attending_youth_row_default" class="attending_youth_row d-none">
                        <td></td>
                        <td colspan="2">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_youth_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_youth_name" class="form-label">First Name</label>
                            </div>
                        </td>

                        <td>
                            <select name="youth_shirts[]" class="shirt_size" disabled>
                                <option value="XS">Youth XSmall</option>
                                <option value="S">Youth Small</option>
                                <option value="M">Youth Medium</option>
                                <option value="L">Youth Large</option>
                                <option value="XL">Adult Small</option>
                                <option value="XXL">Adult Medium</option>
                            </select>

                            <label for="youth_shirts" class="form-label select-label">Shirt Size</label>
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
                                   min="{{ $childs != null ? (count($childs)) : 1 }}"
                                   value="{{ $childs != null ? (count($childs)) : 1 }}"/>
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
                                       value="{{ $childs != null ? (count($childs) * $reunion->child_price) : $reunion->child_price }}"
                                       disabled/>
                            </div>
                        </td>
                    </tr>

                    @if($childs != null && (count($childs) >= 1))
                        @for($x=0; $x < count($childs); $x++)
                            <tr id="" class="attending_children_row">
                                <td>
                                    <button class="btn btn-danger">Delete</button>
                                </td>
                                <td colspan="2">
                                    <div class="form-outline" data-mdb-input-init>
                                        <input type="text"
                                               name="attending_children_name[]"
                                               class="form-control"
                                               value="{{ $childs[$x] }}"
                                               required/>

                                        <label for="attending_children_name" class="form-label">First Name</label>
                                    </div>
                                </td>

                                <td>
                                    <select name="children_shirts[]" class="shirt_size" data-mdb-select-init>
                                        <option value="S"{{ $childrenSizes[$x] == 'S' ? ' selected' : '' }}>12 Months</option>
                                        <option value="M"{{ $childrenSizes[$x] == 'M' ? ' selected' : '' }}>2T</option>
                                        <option value="L"{{ $childrenSizes[$x] == 'L' ? ' selected' : '' }}>3T</option>
                                        <option value="XL"{{ $childrenSizes[$x] == 'XL' ? ' selected' : '' }}>4T</option>
                                        <option value="XXL"{{ $childrenSizes[$x] == 'XXL' ? ' selected' : '' }}>5T</option>
                                        <option value="XXXL"{{ $childrenSizes[$x] == 'XXXL' ? ' selected' : '' }}>6T</option>
                                    </select>

                                    <label for="children_shirts" class="form-label select-label">Shirt Size</label>
                                </td>
                            </tr>
                        @endfor
                    @endif

                    <tr id="attending_children_row_default" class="attending_children_row d-none">
                        <td></td>
                        <td colspan="2">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_children_name[]"
                                       class="form-control"
                                       required
                                       disabled/>

                                <label for="attending_children_name" class="form-label">First Name</label>
                            </div>
                        </td>

                        <td>
                            <select name="children_shirts[]" class="shirt_size" disabled>
                                <option value="S">12 Months</option>
                                <option value="M">2T</option>
                                <option value="L">3T</option>
                                <option value="XL">4T</option>
                                <option value="XXL">5T</option>
                                <option value="XXXL">6T</option>
                            </select>

                            <label for="children_shirts" class="form-label select-label">Shirt Size</label>
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
                                       name="total_amount_due"
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
                                   name="due_at_reg"
                                   class="form-control"
                                   value="{{ $registration->due_at_reg > 0 ? $registration->due_at_reg : '' }}"
                                   placeholder="Enter Registration Cost"
                                   step="0.01"
                                   aria-describedby="basic-addon1"
                                {{ !Auth::user()->is_admin() ? 'disabled' : '' }}/>
                        </div>
                    </div>

                    <div class="col px-md-2 pb-3">
                        <label class="form-label text-danger mb-0" for="total_amount_due">Amount Due</label>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon2">$</span>

                            <input type="number"
                                   name="total_amount_due"
                                   class="form-control"
                                   value="{{ $registration->total_amount_due > 0 ? $registration->total_amount_due : '' }}"
                                   placeholder="Enter Due Cost"
                                   step="0.01"
                                   aria-describedby="basic-addon2"
                                {{ !Auth::user()->is_admin() ? 'disabled' : '' }}/>
                        </div>
                    </div>

                    <div class="col pb-3">
                        <label class="form-label text-danger mb-0" for="total_amount_paid">Amount Paid</label>

                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon3">$</span>

                            <input type="number"
                                   name="total_amount_paid"
                                   class="form-control"
                                   value="{{ $registration->total_amount_paid > 0 ? $registration->total_amount_paid : '' }}"
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
                          placeholder="Enter registration notes for {{ $registration->registree_name }}"
                            {{ !Auth::user()->is_admin() ? 'disabled' : '' }}>{{ $registration->reg_notes }}</textarea>
                <label class="form-label" for="registration_notes">Registration Notes</label>
            </div>

            <div class="row mt-4">
                <div class="mx-auto col-11 col-sm-6 col-lg-4">
                    <button type="submit" class="btn btn-primary btn-lg form-control"
                            onclick="event.preventDefault(); document.getElementById('total_amount_due').removeAttribute('disabled'); this.closest('form').submit();">
                        Update Registration
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
