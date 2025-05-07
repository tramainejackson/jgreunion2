<x-app-layout>

    <div class="container-fluid" id="profilePage">

        <div class="row">
            <div class="col-12 py-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="mt-5 pb-3">Jackson/Green Family
                    Reunion</h1>

                <h2 class="my-3">Family Members Name List</h2>
            </div>
        </div>

        @include('components.nav')

        <div class="row py-4" id="distribution_list">

            @if($family_member->user->is_admin())
                <div class="align-items-center col-11 d-flex mx-auto pb-4 ps-4" id="">
                    <div class="pe-2">
                        <div class="">
                            <a href="{{ route('members.create') }}" class="btn btn-warning">Create New Member</a>
                        </div>
                    </div>

                    @if($duplicates !== null)
                        <div class="pe-2">
                            <a href="{{ route('duplicate_members') }}" class="btn btn-dark">Check
                                Duplicates</a>
                        </div>
                    @endif
                </div>
            @endif

            <div class="col-11 mx-auto">

                <form action="{{ route('members.index') }}" method="GET">

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-4" id="">
                                <input type="text" name="search" class="memberFilter form-control shadow-2-strong" value="{{ request('search') ? request('search') : '' }}"
                                       placeholder="Filter By Name"/>
                            </div>

                            <div class="col-4">
                                <button class="btn btn-secondary" type="submit">Start Filter</button>
                                <a class="btn btn-outline-secondary" href="{{ route('members.index') }}">Clear
                                    Search</a>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-11 mx-auto py-4">

            <div class="datatable" data-mdb-datatable-init>

                <table id="family_members_table" class="table table-striped table-hover" cellspacing="0"
                       width="100%">

                    <thead>
                    <tr>
                        @if($family_member->user->is_admin())
                            <th class="text-center">Edit</th>
                        @else
                            <th class="text-center">Edit</th>
                        @endif

                        <th class="text-center">First Name</th>
                        <th class="text-center">Last Name</th>

                        @if($family_member->user->is_admin())
                            <th class="text-center">Address</th>
                        @endif

                        <th class="text-center">City</th>
                        <th class="text-center">State</th>
                        <th class="text-center">Zip</th>

                        @if($family_member->user->is_admin())
                            <th class="text-center">Phone</th>
                        @endif

                        <th class="text-center">Email</th>

                        @if($family_member->user->is_admin())
                            <th class="text-center">Preference</th>
                            <th class="text-center">Notes</th>
                        @endif
                    </tr>
                    </thead>

                    <tbody>

                    @foreach($distribution_list as $member)
                        <tr>
                            @if($family_member->user->is_admin())
                                <td class="text-truncate"><a href="{{ route('members.edit', $member->id) }}"
                                                             class="btn btn-warning">Edit</a></td>
                            @else
                                <td class="text-truncate"><a href="{{ route('members.show', $member->id) }}"
                                                             class="btn btn-info">View</a></td>
                            @endif

                            <td class="text-truncate nameSearch">{{ $member->firstname }}</td>
                            <td class="text-truncate nameSearch">{{ $member->lastname }}</td>

                            @if($family_member->user->is_admin())
                                <td class="text-truncate">{{ $member->address }}</td>
                            @endif

                            <td class="text-truncate">{{ $member->city }}</td>
                            <td class="text-truncate">{{ $member->state }}</td>
                            <td class="text-truncate">{{ $member->zip }}</td>

                            @if($family_member->user->is_admin())
                                <td class="text-truncate">{{ $member->phone }}</td>
                            @endif

                            <td class="text-truncate">{{ $member->email }}</td>

                            @if($family_member->user->is_admin())
                                <td class="text-truncate text-center" data-toggle="tooltip" data-placement="left"
                                    title="{{ $member->mail_preference == 'M' ? 'Mail' : 'Email' }}">{{ $member->mail_preference }}</td>
                                <td class="text-truncate" data-toggle="tooltip" data-placement="left"
                                    title="{{ $member->notes }}">{{ $member->notes != null ? 'Y' : 'N' }}</td>
                            @endif
                        </tr>
                    @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>
    </div>
</x-app-layout>
