<script type="module">
    import { addNewRowNumber, updateAdultName} from '/js/myjs_functions.js';

    document.getElementById('attending_adult').addEventListener("change", (event) => addNewRowNumber('adult'));
    document.getElementById('attending_youth').addEventListener("change", (event) => addNewRowNumber('youth'));
    document.getElementById('attending_children').addEventListener("change", (event) => addNewRowNumber('children'));
    document.getElementById('firstname').addEventListener("change", (event) => updateAdultName());
</script>

<div id="reunion_registration_form">

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <h2 class="text-center py-3">Registration Form</h2>
            </div>
        </div>
    </div>

    <form action="{{ route('registrations.store') }}" method="POST" name="registration_form">
        @csrf

        <div class="container-fluid" id="">
            <div class="row">
                <div class="col-12 col-sm-6 my-2">
                    <div class="form-outline" id="" data-mdb-input-init>
                        <input type="text"
                               name="firstname"
                               id="firstname"
                               class="form-control"
                               value="{{ old('firstname') ? old('firstname') : '' }}"
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
                               value="{{ old('lastname') ? old('lastname') : '' }}"
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
                               value="{{ old('address') ? old('address') : '' }}"
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
                               value="{{ old('city') ? old('city') : '' }}"
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
                                    value="{{ $state->state_abb }}" {{ old('reunion_state') && old('state') == $state->state_abb ? 'selected' : '' }}>{{ $state->state_name }}</option>
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
                               value="{{ old('zip') ? old('zip') : '' }}"
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
                               value="{{ old('phone') ? old('phone') : '' }}"/>

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
                               value="{{ old('email') ? old('email') : '' }}"/>

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
                                   min="1"
                                   value="1"/>
                        </td>

                        <td>
                            <div class="input-group">
                                <span class="input-group-text" id="basic-addon">$</span>

                                <input type="number"
                                       name="total_adult"
                                       id="total_adult"
                                       class="form-control"
                                       value=""
                                       disabled/>
                            </div>
                        </td>
                    </tr>

                    <tr id="attending_adult_row_duplicate" class="">
                        <td></td>
                        <td colspan="2">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="text"
                                       name="attending_adult_name[]"
                                       class="form-control"
                                       value=""
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
                                   min="0"
                                   value="0"/>
                        </td>
                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">$</span>
                                </div>
                                <input type="number" name="total_youth" id="total_youth" class="form-control" disabled/>
                            </div>
                        </td>
                    </tr>

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
                                <option value="S">Youth XSmall</option>
                                <option value="M">Youth Small</option>
                                <option value="L">Youth Medium</option>
                                <option value="XL">Youth Large</option>
                                <option value="XXL">Adult Small</option>
                                <option value="XXXL">Adult Medium</option>
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
                                   min="0"
                                   value="0"/>
                        </td>

                        <td>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon">$</span>
                                </div>

                                <input type="number" name="total_children" id="total_children" class="form-control"
                                       disabled/>
                            </div>
                        </td>
                    </tr>

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
                                <option value="XXXL">6</option>
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
                                       value=""
                                       disabled/>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="mx-auto col-11 col-sm-6 col-lg-4">
                    <button type="submit" class="btn btn-primary form-control"
                            onclick="event.preventDefault(); document.getElementById('total_amount_due').removeAttribute('disabled'); this.closest('form').submit();">
                        Submit Registration
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
