@extends('layouts.app')

@section('content')
	<div class="container-fluid" id="profilePage">
	
		@include('admin.nav')
		
		<div class="row white">
			<div class="col-2 my-2">
				<div class="">
					<a href="/reunions/{{ $registration->reunion->id }}/edit" class="btn btn-info btn-lg">All Registrations</a>
				</div>
			</div>
			<div class="col-8 membersForm">
			
				<h1 class="mt-2 mb-4">Edit
					@if($registration->family_member_id != null)
						<a href="/members/{{ $registration->family_member_id }}/edit" class="">{{ $registration->registree_name }}</a> ({{ $registration->reunion->reunion_city }} Reunion {{ $registration->reunion->reunion_year }})
					@else
						{{ $registration->registree_name }} ({{ $registration->reunion->reunion_city }} Reunion {{ $registration->reunion->reunion_year }})
					@endif
				</h1>
				
				<!-- Update Form -->
				{!! Form::open(['action' => ['RegistrationController@update', 'registration' => $registration->id], 'method' => 'PUT']) !!}
				
					@if($registration->family_id != null)
						<div class="form-group">
							<label class="form-label" for="registree">Registree</label>
							
							<select class="browser-default form-control" name="registree">
								
								@foreach($family as $family_member)
								
									<option value="{{ $family_member->id }}" {{ $family_member->id == $registration->reunion_dl->id ? 'selected' : '' }}>{{ $family_member->firstname . ' ' . $family_member->lastname }}</option>
									
								@endforeach
								
							</select>
						</div>
					@endif
					
					@if($registration->family_member_id != null)
						<div class="form-group">
							<label class="form-label" for="address">Address</label>
							<input type="text" name="address" class="form-control" value="{{ $registration->reunion_dl->address }}" placeholder="Enter Address" disabled />
						</div>
						<div class="form-row">
							<div class="form-group col-4">
								<label class="form-label" for="city">City</label>
								<input type="text" name="city" class="form-control" value="{{ $registration->reunion_dl->city }}" placeholder="Enter City" disabled />
							</div>
							<div class="form-group col-4">
								<label class="form-label" for="state">State</label>
								<select class="form-control browser-default" name="state" disabled>
									@foreach($states as $state)
										<option value="{{ $state->state_abb }}" {{ $registration->reunion_dl->state == $state->state_abb ? 'selected' : '' }}>{{ $state->state_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-4">
								<label class="form-label" for="zip">Zip</label>
								<input type="number" name="zip" class="form-control" value="{{ $registration->reunion_dl->zip }}" placeholder="Enter Zip Code" disabled />
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-6">
								<label class="form-label" for="email">Email</label>
								<input type="text" name="email" class="form-control" value="{{ $registration->reunion_dl->email }}" placeholder="Enter Email Address" disabled />
							</div>
							<div class="form-group col-6">
								<label class="form-label" for="phone">Phone</label>
								<input type="number" name="phone" class="form-control" value="{{ old('phone') ? old('phone') : $registration->reunion_dl->phone }}" placeholder="No Phone Number Entered" disabled />
							</div>
						</div>
						
					@else
						
						<div class="form-group">
							<label class="form-label" for="address">Address</label>
							<input type="text" name="address" class="form-control" value="{{ $registration->address }}" placeholder="Enter Address" disabled />
						</div>
						<div class="form-row">
							<div class="form-group col-4">
								<label class="form-label" for="city">City</label>
								<input type="text" name="city" class="form-control" value="{{ $registration->city }}" placeholder="Enter City" disabled />
							</div>
							<div class="form-group col-4">
								<label class="form-label" for="state">State</label>
								<select class="form-control browser-default" name="state" disabled>
									@foreach($states as $state)
										<option value="{{ $state->state_abb }}" {{ $registration->state == $state->state_abb ? 'selected' : '' }}>{{ $state->state_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group col-4">
								<label class="form-label" for="zip">Zip</label>
								<input type="number" name="zip" class="form-control" value="{{ $registration->zip }}" placeholder="Enter Zip Code" disabled />
							</div>
						</div>
						<div class="form-row">
							<div class="form-group col-6">
								<label class="form-label" for="email">Email</label>
								<input type="text" name="email" class="form-control" value="{{ $registration->email }}" placeholder="Enter Email Address" disabled />
							</div>
							<div class="form-row col-6">
								<label class="form-label col-12" for="phone">Phone</label>
								<div class="form-group col-3">
									<input type="number" name="phone" class="form-control" value="{{ old('phone') ? old('phone') : $registration->phone }}" placeholder="###" disabled />
								</div>
							</div>
						</div>
						
					@endif
					
					<div class="form-block-header">
						<h3 class="">Registration Information
							<button type="button" class="btn btn-outline-success mb-2" data-toggle="modal" data-target="#add_reg_members_form">Add Member To Registration</button>
						</h3>
					</div>
					<div class="shirtSizesDiv">
						<div class="form-row">
							<div class="form-group col-4">
								<label class="form-label text-danger" for="due_at_reg">Registration Amount</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">$</span>
									</div>
									<input type="number" name="due_at_reg" class="form-control" value="{{ $registration->due_at_reg > 0 ? $registration->due_at_reg : '' }}" placeholder="Enter Registration Cost" step="0.01" />
								</div>
							</div>
							<div class="form-group col-4">
								<label class="form-label text-danger" for="total_amount_due">Due Amount</label>
								<div class="input-group">
								
									<div class="input-group-prepend">
										<span class="input-group-text" id="">$</span>
									</div>
									
									<input type="number" name="total_amount_due" class="form-control" value="{{ $registration->total_amount_due > 0 ? $registration->total_amount_due : '' }}" placeholder="Enter Due Cost" step="0.01" />
									
								</div>
							</div>
							
							<div class="form-group col-4">
								<label class="form-label text-danger" for="total_amount_paid">Paid Amount</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text" id="basic-addon1">$</span>
									</div>
									<input type="number" name="total_amount_paid" class="form-control" value="{{ $registration->total_amount_paid > 0 ? $registration->total_amount_paid : '' }}" placeholder="Enter Amount Paid" step="0.01" />
								</div>
							</div>
						</div>
						<div class="form-group">
							<h3 class="">Shirt Sizes</h3>
						</div>
						<div class="form-row">
							<div class="form-group col-4 mb-0">
								<label for="" class="form-label">Adults</label>
							</div>
							<div class="form-group col-4 mb-0">
								<label for="" class="form-label">Youth</label>
							</div>
							<div class="form-group col-4 mb-0">
								<label for="" class="form-label">Children</label>
							</div>
						</div>
						<div class="form-row" id="shirt_sizes_div">
							<div class="form-group col-4">
								@if($adults != null)
								
									@foreach($adults as $family_reg)
									
										@if($family_reg != '' || $family_reg != null)
											<div class="my-1">
												<div class="input-group">
												
													<input type="text" name="" class="form-control" value="{{ $family_reg }}" disabled />

													<div class="input-group-append">
														<button class="btn btn-outline-danger removeRegIndividualBtn{{ $family_reg == $registration->reunion_dl->firstname ? ' disabled' : '' }}" type="button" onclick="remove_from_reg('{{ $registration->id }}', 'adult{{ $loop->iteration }}')" {{ $family_reg == $registration->reunion_dl->firstname ? ' disabled' : '' }}>Remove</button>
													</div>
												</div>

												<select class="browser-default form-control my-1" name="adult_sizes[]">
													<option value="S" {{ isset($adultSizes[$loop->iteration - 1]) ? $adultSizes[$loop->iteration - 1] == 'S' ? 'selected' : '' : ' '}}>Small</option>
													<option value="M" {{ isset($adultSizes[$loop->iteration - 1]) ? $adultSizes[$loop->iteration - 1] == 'M' ?  'selected' : ''  : '' }}>Medium</option>
													<option value="L" {{ isset($adultSizes[$loop->iteration - 1]) ? $adultSizes[$loop->iteration - 1] == 'L' ?  'selected' : '' : '' }}>Large</option>
													<option value="XL" {{ isset($adultSizes[$loop->iteration - 1]) ? $adultSizes[$loop->iteration - 1] == 'XL' ?  'selected' : '' : '' }}>Extra Large</option>
													<option value="XXL" {{ isset($adultSizes[$loop->iteration - 1]) ? $adultSizes[$loop->iteration - 1] == 'XXL' ?  'selected' : '' : '' }}>2XL</option>
													<option value="XXXL" {{ isset($adultSizes[$loop->iteration - 1]) ? $adultSizes[$loop->iteration - 1] == 'XXXL' ?  'selected' : '' : '' }}>3XL</option>
												</select>
											</div>
										@endif
									@endforeach
								@endif
							</div>
							
							<div class="form-group col-4">
								@if($youths != null)
									@foreach($youths as $family_reg)
										@if($family_reg != '' || $family_reg != null)
											<div class="my-1">
												<input type="text" name="remove_reg_individual" class="hidden" value="youth{{ $loop->iteration }}" hidden />
												
												<div class="input-group">
													<input type="text" name="" class="form-control" value="{{ $family_reg }}" disabled />
													<div class="input-group-append">
														<button class="btn btn-outline-danger removeRegIndividualBtn" type="button" onclick="remove_from_reg('{{ $registration->id }}', 'youth{{ $loop->iteration }}')">Remove</button>
													</div>
												</div>
													
												<select class="browser-default form-control my-1" name="youth_sizes[]">
													<option value="S" {{ isset($youthSizes[$loop->iteration - 1]) ? $youthSizes[$loop->iteration - 1] == 'S' ? 'selected' : '' : ' '}}>Youth XSmall</option>
													<option value="M" {{ isset($youthSizes[$loop->iteration - 1]) ? $youthSizes[$loop->iteration - 1] == 'M' ?  'selected' : ''  : '' }}>Youth Small</option>
													<option value="L" {{ isset($youthSizes[$loop->iteration - 1]) ? $youthSizes[$loop->iteration - 1] == 'L' ?  'selected' : '' : '' }}>Youth Medium</option>
													<option value="XL" {{ isset($youthSizes[$loop->iteration - 1]) ? $youthSizes[$loop->iteration - 1] == 'XL' ?  'selected' : '' : '' }}>Youth Large</option>
													<option value="XXL" {{ isset($youthSizes[$loop->iteration - 1]) ? $youthSizes[$loop->iteration - 1] == 'XXL' ?  'selected' : '' : '' }}>Adult Small</option>
													<option value="XXXL" {{ isset($youthSizes[$loop->iteration - 1]) ? $youthSizes[$loop->iteration - 1] == 'XXXL' ?  'selected' : '' : '' }}>Adult Large</option>
												</select>
											</div>
										@endif
									@endforeach
								@endif
							</div>
							
							<div class="form-group col-4">
								@if($childs != null)
									@foreach($childs as $family_reg)
										@if($family_reg != '' || $family_reg != null)
											<div class="my-1">
												<div class="input-group">
													<input type="text" name="remove_reg_individual" class="hidden" value="child{{ $loop->iteration }}" hidden />
													
													<input type="text" name="" class="form-control" value="{{ $family_reg }}" disabled />
													<div class="input-group-append">
														<button class="btn btn-outline-danger removeRegIndividualBtn" type="button" onclick="remove_from_reg('{{ $registration->id }}', 'child{{ $loop->iteration }}')">Remove</button>
													</div>
												</div>
												
												<select class="browser-default form-control my-1" name="children_sizes[]">
													<option value="S" {{ isset($childrenSizes[$loop->iteration - 1]) ? $childrenSizes[$loop->iteration - 1] == 'S' ? 'selected' : '' : ' '}}>12 Months</option>
													<option value="M" {{ isset($childrenSizes[$loop->iteration - 1]) ? $childrenSizes[$loop->iteration - 1] == 'M' ?  'selected' : ''  : '' }}>2T</option>
													<option value="L" {{ isset($childrenSizes[$loop->iteration - 1]) ? $childrenSizes[$loop->iteration - 1] == 'L' ?  'selected' : '' : '' }}>3T</option>
													<option value="XL" {{ isset($childrenSizes[$loop->iteration - 1]) ? $childrenSizes[$loop->iteration - 1] == 'XL' ?  'selected' : '' : '' }}>4T</option>
													<option value="XXL" {{ isset($childrenSizes[$loop->iteration - 1]) ? $childrenSizes[$loop->iteration - 1] == 'XXL' ?  'selected' : '' : '' }}>5T</option>
													<option value="XXXL" {{ isset($childrenSizes[$loop->iteration - 1]) ? $childrenSizes[$loop->iteration - 1] == 'XXXL' ?  'selected' : '' : '' }}>6</option>
												</select>
											</div>
										@endif
									@endforeach
								@endif
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="form-label" for="reg_notes">Registration Notes</label>
						<textarea class="form-control" name="reg_notes" placeholder="Enter registration notes for {{ $registration->registree_name }}">{{ $registration->reg_notes }}</textarea>
					</div>
					<div class="form-group">
					
						<button class="btn btn-primary form-control" type="submit">Update Registration</button>
						
					</div>
				{!! Form::close() !!}
				<!-- End update form -->
			</div>
			
			<!-- Add registration member modal-->
			<div class="modal fade addRegMembersForm" id="add_reg_members_form">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						{!! Form::open(['action' => ['RegistrationController@add_registration_member', 'registration' => $registration->id], 'method' => 'PUT']) !!}
							<div class="modal-header">
								<h2 class="">Add Member To Registration</h2>
							</div>
							<div class="modal-body">
								<div id="accordion">
									<div class="card">
										<div class="card-header" style="background: linear-gradient(to bottom right, aquamarine, black); color: whitesmoke;">
											<h3 class="col-12"  data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Select From Added Members</h3>
										</div>
										<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
											<div class="card-body">
												<div class="form-group">
												
													<label class="form-label" for="dl_id">Member</label>
													
													<select class="form-control browser-default" name="dl_id">
														@foreach($all_members as $thisMember)
															@php
																$thisReg = $thisMember->registrations()->where([
																	['reunion_id', '=', $registration->reunion_id],
																	['family_member_id', '=', $thisMember->id]
																])->first();
															@endphp
															
															<option value="{{ $thisMember->id }}" class="{{ $thisReg != null ? $thisReg->family_member_id == $thisMember->id ? 'text-danger' : '' : '' }}" {{ $thisReg != null ? $thisReg->family_member_id == $thisMember->id ? 'disabled' : '' : '' }}>{{ $thisMember->firstname . ' ' . $thisMember->lastname }}{{ $thisReg != null ? $thisReg->family_member_id == $thisMember->id ? ' - member already registered' : '' : '' }}</option>
														@endforeach
													</select>
												</div>
											</div>
										</div>
									</div>
									
									<div class="card">
										<div class="card-header" style="background: linear-gradient(to bottom right, #f7ff7f, black); color: whitesmoke;">
											<h3 class="col-12"  data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Add New Member</h3>
										</div>
										<div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
											<div class="card-body">
												<div class="form-row">
													<div class="form-group col-6">
														<label for="" class="form-label">Firstname</label>
														<input type="text" name="firstname" class="form-control" value="" placeholder="Enter Firstname" disabled />
													</div>
													<div class="form-group col-6">
														<label for="" class="form-label">Lastname</label>
														<input type="text" name="lastname" class="form-control" value="" placeholder="Enter Lastname"disabled />
													</div>
												</div>
												<div class="form-row">
													<div class="form-group col-6">
														<label for="" class="form-label">Age Group</label>
														<select class="form-control browser-default" name="age_group" disabled>
															<option value="adult">Adult</option>
															<option value="youth">Youth</option>
															<option value="child">Child</option>
														</select>
													</div>
													<div class="form-group col-6">
														<label for="" class="form-label">Shirt Size</label>
														<select name="shirt_size" class="shirt_size browser-default form-control" disabled>
															<option value="S" selected>Small</option>
															<option value="M">Medium</option>
															<option value="L" >Large</option>
															<option value="XL">XL</option>
															<option value="XXL">XXL</option>
															<option value="XXXL">3XL</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">
								<div class="form-group">
								
									<button class="btn btn-primary btn-lg form-control" type="submit">Add To Registration</button>

								</div>
							</div>
						{!! Form::close() !!}
					</div>
					
					<script>
						$('body').on('change', '#add_reg_members_form [name="age_group"]', function() {
							if($('[name="age_group"]').val() == 'adult') {
								$('#add_reg_members_form select[name="shirt_size"] option').eq(0).text('Small');
								$('#add_reg_members_form [name="shirt_size"] option').eq(1).text('Medium');
								$('#add_reg_members_form [name="shirt_size"] option').eq(2).text('Large');
								$('#add_reg_members_form [name="shirt_size"] option').eq(3).text('XL');
								$('#add_reg_members_form [name="shirt_size"] option').eq(4).text('XXL');
								$('#add_reg_members_form [name="shirt_size"] option').eq(5).text('3XL');
							} else if($('[name="age_group"]').val() == 'youth') {
								$('#add_reg_members_form select[name="shirt_size"] option').eq(0).text('Youth XSmall');
								$('#add_reg_members_form [name="shirt_size"] option').eq(1).text('Youth Small');
								$('#add_reg_members_form [name="shirt_size"] option').eq(2).text('Youth Medium');
								$('#add_reg_members_form [name="shirt_size"] option').eq(3).text('Youth Large');
								$('#add_reg_members_form [name="shirt_size"] option').eq(4).text('Adult Small');
								$('#add_reg_members_form [name="shirt_size"] option').eq(5).text('Adult Medium');
							} else if($('[name="age_group"]').val() == 'child') {
								$('#add_reg_members_form select[name="shirt_size"] option').eq(0).text('12 Months');
								$('#add_reg_members_form [name="shirt_size"] option').eq(1).text('2T');
								$('#add_reg_members_form [name="shirt_size"] option').eq(2).text('3T');
								$('#add_reg_members_form [name="shirt_size"] option').eq(3).text('4T');
								$('#add_reg_members_form [name="shirt_size"] option').eq(4).text('5T');
								$('#add_reg_members_form [name="shirt_size"] option').eq(5).text('6');
							}
						});
					</script>
				</div>
			</div>
		</div>	
	</div>
@endsection