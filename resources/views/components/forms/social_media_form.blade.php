<div id="social_information" class="my-5">
    <div class="row" id="">
        <div class="header_div">
            <h1 id="social_header" class="text-center">Social Information</h1>

{{--            <span class="visibleHeaderSpan">Visible to all</span>--}}
{{--            <input type="checkBox" c--}}
{{--                   class="visibleCheckBox"--}}
{{--                   name="show_social"--}}
{{--                   value="Y"--}}
{{--                {{ $user->show_social == "Y" ? 'checked': '' }} />--}}
        </div>

        <div class="sectionDiv">
            <div class="form-outline mb-2" data-mdb-input-init>

                <input type="text"
                       name="instagram"
                       class="form-control"
                       value="{{ $family_member->instagram != null ? $family_member->instagram : '' }}"
                       placeholder="Add Instagram Name"/>

                <label class="form-label" for="instagram">Instagram Username</label>
            </div>
        </div>

        <div class="sectionDiv">
            <div class="form-outline mb-2" data-mdb-input-init>
                <input type="text"
                       name="twitter"
                       class="form-control"
                       value="{{ $family_member->twitter != null ? $family_member->twitter : '' }}"
                       placeholder="Add X (Twitter) Name"/>

                <label class="form-label" for="twitter">X Username</label>
            </div>
        </div>

        <div class="sectionDiv">
            <div class="form-outline mb-2" data-mdb-input-init>

                <input type="text"
                       name="facebook"
                       class="form-control"
                       value="{{ $family_member->facebook != null ? $family_member->facebook : '' }}"
                       placeholder="Add Facebook Name"/>

                <label class="form-label" for="facebook">Facebook Username</label>
            </div>
        </div>
    </div>
</div>
