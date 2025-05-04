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

            </form>
        </div>
    </div>
</div>
