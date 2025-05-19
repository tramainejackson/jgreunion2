<div id="reunion_registration_completed" class="my-5 container-fluid">
    <!-- Registered Members Section -->
    <div class="form-block-header">
        <h3 class="text-left">Registered Members
            <a href="{{ route('registrations.create') }}"
               class="btn btn-outline-success mb-2" target="_blank">Add New Registration</a>

            <button type="button" class="btn btn-primary mb-2">Registrations <span
                    class="badge badge-light">{{ $totalRegistrations }}</span>
                <span class="sr-only">total registrations</span>
            </button>

            <button type="button"
                    class="btn btn-outline-primary mb-2"
                    data-mdb-target="#viewRgistrationsModal"
                    data-mdb-ripple-init
                    data-mdb-modal-init>View Registrations Totals
            </button>
        </h3>
    </div>

    <div class="row row-cols-3 my-2">

        @foreach($reunion->registrations()->parents() as $registration)

            @php
                $adults = $registration->adult_names != null || $registration->adult_names != '' ? explode('; ', $registration->adult_names) : array();
                $youths = $registration->youth_names != null || $registration->youth_names != '' ? explode('; ', $registration->youth_names) : array();
                $childs = $registration->children_names != null || $registration->children_names != '' ? explode('; ', $registration->children_names) : array();
            @endphp

            <div class="col">
                <div class="card h-100">
                    <img
                        src="{{ $registration->family_member->get_profile_image() }}"
                        class="card-img-top align-self-center" alt="Family Member Image"/>

                    <div class="card-body">
                        <h1 class="card-title text-center font1 pb-3">{{ $registration->family_member->full_name() }}</h1>

                        <div class="col-12 col-md mb-1 justify-content-center d-flex align-items-center">
                            <button type="button" class="btn btn-primary btn-lg btn-block mb-1 mb-md-2">Registration
                                Total
                                <span
                                    class="badge badge-light">{{ (count($adults) + count($youths) + count($childs)) }}</span>
                                <span class="sr-only">total household members</span>
                            </button>
                        </div>

                        <div class="col-12 col-md-auto justify-content-center d-flex align-items-center mb-1">
                            <a href="{{ route('registrations.edit', $registration->id) }}"
                               class="btn btn-warning btn-block">Edit</a>
                        </div>

                        <div
                            class="col-12 col-md mb-4 mb-md-1 justify-content-center d-flex align-items-center">
                            <button type="button"
                                    class="btn btn-danger btn-block text-truncate deleteRegistration"
                                    data-mdb-modal-init
                                    data-mdb-ripple-init
                                    data-mdb-target="#delete_registration">Delete Registration
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Order of classes is being used by updateModalToRemoveModel() --}}
                <div class="container-fluid d-none forModalInfo{{ $registration->id }}" id="">
                    <div class="row" id="">
                        <div class="col" id="">
                            <p class="mb-1"><span class="fw-bold">Registree Name</span>:&nbsp;{{ $registration->registree_name }}</p>
                            <p class="mb-1"><span class="fw-bold">Address</span>:&nbsp;{{ $registration->city . ', ' . $registration->state }}</p>
                            <p class="mb-1"><span class="fw-bold">Total People on Registration</span>:&nbsp;{{ (count($adults) + count($youths) + count($childs))}}</p>
                            <p class="mb-1"><span class="fw-bold">Total Amount Due</span>:&nbsp;${{ $registration->total_amount_due }}</p>
                            <p class="mb-1"><span class="fw-bold">Total Amount Paid</span>:&nbsp;${{ $registration->total_amount_paid }}</p>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach
    </div>

    @if($reunion->registrations()->isEmpty())

        <div class="form-row emptyRegistrations">
            <h2 class="text-left col-10">No Members Registered Yet</h2>
        </div>

    @endif
</div>

<!--Modal: modalConfirmDelete-->
<div class="modal fade"
     id="viewRgistrationsModal"
     tabindex="-1" role="dialog"
     aria-labelledby="viewRgistrationsModal"
     aria-hidden="true">

    <div class="modal-dialog modal-lg" role="document">

        <!--Content-->
        <div class="modal-content text-center">
            <!--Header-->
            <div class="modal-header justify-content-center align-content-center bg-success-subtle">
                <p class="m-0 display-6 fw-bolder text-primary-emphasis">View Registration Totals</p>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover">

                        <thead>
                        <tr>
                            <th>Total Registrations</th>
                            <th># Adults</th>
                            <th># Youth</th>
                            <th># Children</th>
                            <th>$ Total Reg Fees</th>
                            <th>$ Total Reg Paid</th>
                            <th>$ Total Reg Due</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>{{ $totalRegistrations }}</td>
                            <td>{{ $totalAdults }}</td>
                            <td>{{ $totalYouths }}</td>
                            <td>{{ $totalChildren }}</td>
                            <td>${{ $totalFees }}</td>
                            <td>${{ $totalRegFeesPaid }}</td>
                            <td>${{ $totalRegFeesDue }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">

                        <thead>
                        <tr>
                            <th>Total Shirts</th>
                            <th>3XL</th>
                            <th>2XL</th>
                            <th>XL</th>
                            <th>Large</th>
                            <th>Medium</th>
                            <th>Small</th>
                            <th>Youth Large</th>
                            <th>Youth Medium</th>
                            <th>Youth Small</th>
                            <th>Youth XS</th>
                            <th>6</th>
                            <th>5T</th>
                            <th>4T</th>
                            <th>3T</th>
                            <th>2T</th>
                            <th>12 Month</th>
                        </tr>
                        </thead>

                        <tbody>
                        <tr>
                            <td>{{ $totalShirts }}</td>
                            <td>{{ $aXXXl }}</td>
                            <td>{{ $aXXl }}</td>
                            <td>{{ $aXl }}</td>
                            <td>{{ $aLg }}</td>
                            <td>{{ $aMd }}</td>
                            <td>{{ $aSm }}</td>
                            <td>{{ $yLg }}</td>
                            <td>{{ $yMd }}</td>
                            <td>{{ $ySm }}</td>
                            <td>{{ $yXSm }}</td>
                            <td>{{ $c6 }}</td>
                            <td>{{ $c5T }}</td>
                            <td>{{ $c4T }}</td>
                            <td>{{ $c3T }}</td>
                            <td>{{ $c2T }}</td>
                            <td>{{ $c12M }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>

            <!--Footer-->
            <div class="modal-footer flex-center">
                <button type="button" class="btn btn-warning waves-effect" data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
<!--Modal: modalConfirmDelete-->

<div class="modal fade" id="delete_registration" tabindex="-1" role="dialog" aria-labelledby="deleteRegistrationModal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('registrations.destroy', ['registration' => 0]) }}" method="POST">

            @csrf
            @method('DELETE')

            <div class="modal-content">
                <div class="modal-header align-content-center justify-content-center bg-danger text-bg-danger">
                    <h2 class="text-center">Delete Registration</h2>
                </div>
                <div class="modal-body">
                </div>

                <!--Footer-->
                <div class="modal-footer align-content-center justify-content-around">
                    <button type="submit" class="btn btn-danger waves-effect" data-mdb-dismiss="modal">Delete
                        Registration
                    </button>

                    <button type="button" class="btn btn-warning waves-effect" data-mdb-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>
