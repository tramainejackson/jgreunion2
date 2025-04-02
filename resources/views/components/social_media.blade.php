<div id="social_information" class="my-5">
    <div class="row" id="">
        <div class="header_div">
            <h3 id="social_header">Social Information</h3>

            @if($user["show_social"] == "Y")
                <span class="visibleHeaderSpan">Visible to all</span>
                <input type="checkBox"
                       class="visibleCheckBox"
                       name="show_social" value="Y"
                       checked/>
            @else
                <span class="visibleHeaderSpan">Visible to all</span>
                <input type="checkBox"
                       class="visibleCheckBox"
                       name="show_social" value="Y"/>
            @endif
        </div>

        <div class="sectionDiv">
            <span class="inputTitle">Instagram</span>
            <input type="text" name="instagram" id="" class="profileInput"
                   value="{{ $user["instagram"] }}" placeholder="Add Instagram Name"/>
        </div>
        <div class="sectionDiv">
            <span class="inputTitle">Twitter</span>
            <input type="text" name="twitter" id="" class="profileInput"
                   value="{{ $user["twitter"] }}" placeholder="Add Twitter Name"/>
        </div>
        <div class="sectionDiv">
            <span class="inputTitle">Facebook</span>
            <input type="text" name="facebook" id="" class="profileInput"
                   value="{{ $user["facebook"] }}" placeholder="Add Facebook Name"/>
        </div>
    </div>
</div>
