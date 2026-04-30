<div class="container-fluid">
    <div class="row">
        <div class="col">
            <h1 class="mt-3 mb-2">My Profile</h1>

            <form action="{{ route('members.update', $family_member->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                @include('components.forms.user_profile_form')

                <hr class="hr hr-blurry">

                @include('components.forms.family_tree_form')

                <hr class="hr hr-blurry">

                @include('components.forms.social_media_form')

{{--                <hr class="hr hr-blurry">--}}

{{--                @include('components.forms.household_form')--}}

                <div class="form-group">
                    <button class="btn btn-primary btn-lg form-control" type="submit">Update Member Profile</button>
                </div>

                <div class="form-group">
                    <button class="btn btn-outline-danger btn-lg form-control mt-2" type="button" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#modalConfirmDelete">Delete Member</button>
                </div>

            </form>
        </div>
    </div>
</div>

<!--Modal: modalConfirmDelete-->
<div class="modal fade" id="modalConfirmDelete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-sm modal-notify modal-danger" role="document">

        <!--Content-->
        <div class="modal-content text-center">

            <!--Header-->
            <div class="modal-header d-flex justify-content-center">
                <p class="heading">Are you sure you want to delete this family member?</p>
            </div>

            <!--Body-->
            <div class="modal-body">
                <p><span class="fw-bold">Name: </span><span>{{ $family_member->full_name() }}</span></p>

                @if($family_member->registrations->count() > 0)
                    <p class="" style="line-height: normal;">This family member has been registered for {{ $family_member->registrations->count() }} family reunion events.</p>
                @else
                    {{ 'No' }}
                @endif

                @if($family_member->user)
                    <p class="" style="line-height: normal;">This family member has a user account</p>
                @endif
            </div>

            <!--Footer-->
            <div class="modal-footer flex-center">
                <form action="{{ route('members.destroy', $family_member->id) }}" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-outline-danger">Yes</button>

                    <button type="button" class="btn btn-danger" data-mdb-ripple-init data-mdb-dismiss="modal">No</button>

                </form>

            </div>

        </div>
        <!--/.Content-->

    </div>

</div>
<!--Modal: modalConfirmDelete-->
