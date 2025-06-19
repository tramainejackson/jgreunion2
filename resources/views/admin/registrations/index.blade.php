<x-app-layout>
    <div class="container-fluid" id="">
        <div class="row">

            <div class="col-12 col-lg-2 my-2">
                <div class="">
                    <a href="{{ route('registrations.create') }}" class="btn btn-info btn-lg">Create New
                        Registration</a>
                </div>
            </div>

            <div class="col-12 col-md-10 col-lg-9 my-3 mx-auto">

                <div class="">
                    <h2 class="">Registrations for {{ $reunion->reunion_city . ' ' .  $reunion->reunion_year }}</h2>
                </div>

                <div id="registrations_list" class="row bg-light">
                    <div class="col-12">
                        <table class="table table-striped table-hover table-responsive">
                            <thead>
                            <tr>
                                <th>Registree</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Zip</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Due At Reg</th>
                                <th>Total Due</th>
                                <th>Total Paid</th>
                                <th>Reg Notes</th>
                                <th>Edit</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if($registrations->count() > 0)
                                @foreach($registrations as $registration)
                                    <tr>
                                        <td class="text-truncate">{{ $registration->registree_name }}</td>
                                        <td class="text-truncate">{{ $registration->address }}</td>
                                        <td class="text-truncate">{{ $registration->city }}</td>
                                        <td class="text-truncate">{{ $registration->state }}</td>
                                        <td class="text-truncate">{{ $registration->zip }}</td>
                                        <td class="text-truncate">{{ $registration->phone }}</td>
                                        <td class="text-truncate">{{ $registration->email }}</td>
                                        <td class="text-truncate">${{ $registration->due_at_reg }}</td>
                                        <td class="text-truncate">${{ $registration->total_amount_due }}</td>
                                        <td class="text-truncate">${{ $registration->total_amount_paid }}</td>
                                        <td class="text-truncate">{{ $registration->registration != null ? 'Y' : 'N' }}</td>
                                        <td class="text-truncate"><a href="#" class="btn btn-warning">Edit</a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12" class="text-center display-4">No registrations on file for this
                                        reunion
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
