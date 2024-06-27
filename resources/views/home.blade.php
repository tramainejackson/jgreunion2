<x-app-layout>
    <div class="container-fluid" id="profilePage">
        <div id="overlay"></div>
        <div id="modal"></div>
        <div id="navi">
            <div class="page_header">
                <h1>Jackson &amp; Green Family Reunion</h1>
            </div>

            <div id="family_account">
                @if(!Auth::check())
                    <a href='/registration' class='profileLink'>Register</a>
                    <a href='/login' class='profileLink'>Login</a>
                @else
                    <a href='/profile' class='profileLink'>My Profile</a>
                    <a href='/logout' class='profileLink'>Logout</a>
                @endif
            </div>

            <div id="home_link">
                @if(!Auth::check())
                    <a href='/' class='homeLink'>Home</a>
                @else
                    <a href='/' class='homeLink profileLink'>Home</a>
                    <a href='/logout' class='profileLink'>Logout</a>
                @endif
            </div>

            @if($newReunionCheck != null)
                <div class="">
                    <a id="profile_registration" href="profile.php?register=false">Register For Upcoming Reunion</a>
                </div>
            @endif
        </div>

        <div id="profile_information" class="col-12">
            <form name="profile_form" id="" method="POST" action="profile.php" enctype="multipart/form-data">
                <div id="profile_photo">
                    @if($user["upload_photo"] == "")
                        <img id="profile_photo" src="images/img_placeholder.jpg"/>
                    @else
                        <img id="profile_photo" src="../uploads/{{ $user["upload_photo"] }}"/>
                    @endif
                    <input type="file" name="upload_photo" id="change_img_btn"/>
                </div>

                <div id="profile_header" class="profile_info_div">
                    <h2 id="profile_header_name">{{ $user["first_name"] . " " . $user["last_name"] }}</h2>
                    <span id="profile_header_username">{{ $user["username"] }}</span>
                </div>

                <div id="general_information" class="profile_info_div">
                    <div class="header_div">
                        <h3 id="general_header">Personal Information</h3>
                    </div>
                    <div class="sectionDiv">
                        <span class="inputTitle">Userame</span>
                        <input type="text" name="username" class="profileInput"
                               value="{{ $user["username"]  }}"/>

                        @if($user["password"])
                            <span class="inputTitle">Password</span>
                            <input type="password" name="password"
                                   class="profileInput" value="value"
                                   placeholder="Password"/>
                        @else
                            <span class="inputTitle">Password</span>
                            <input type="password" name="password"
                                   class="profileInput" value=""
                                   placeholder="Password"/>
                        @endif
                    </div>

                    <div class="sectionDiv">
                        <span class="inputTitle">Name</span>
                        <input type="text" name="first_name" class="profileInput profileNameInput1"
                               value="{{ $user["first_name"] }}" placeholder="Firstname"/>
                        <input type="text" name="last_name" id="" class="profileInput profileNameInput2"
                               value="{{ $user["last_name"] }}" placeholder="Lastname"/>
                    </div>
                    <div class="sectionDiv">
                        <span class="inputTitle">DOB</span>
                        <input type="date" name="date_of_birth" class="profileInput profileInputDOB"
                               value="{{ $user["date_of_birth"] }}"/>
                    </div>
                </div>
                <div id="family_information" class="profile_info_div">
                    <div class="header_div">
                        <h3 id="family_header">Family Tree</h3>
                    </div>
                    <span class="inputTitle descentTitle">Descent</span>
                    @if($user["descent"] == "Green")
                        <div class="descentDiv">
                            <input type="text" name="descent"
                                   class="profileInput profileInputGreenBtn descentInput descentSelected" value="Green"
                                   readonly/>
                            <input type="text" name="descent" class="profileInput profileInputJacksonBtn descentInput"
                                   value="Jackson" readonly/>
                            <input type="text" name="descent" class="profileInput profileInputSpouseBtn descentInput"
                                   value="Spouse" readonly/>
                            <input type="text" name="descent" class="profileInput profileInputFriendBtn descentInput"
                                   value="Friend" readonly/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Parents</span>
                            <input type="type" name="mother" class="profileInput profileInputMother"
                                   value="<?php echo $user["mother"]; ?>" placeholder="Mother"/>
                            <span class="separator">&amp;</span>
                            <input type="type" name="father" class="profileInput profileInputFather"
                                   value="<?php echo $user["father"]; ?>" placeholder="Father"/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Spouse</span>
                            <input type="input" name="spouse" class="profileInput profileInputSpouse"
                                   value="<?php echo $user["spouse"]; ?>" placeholder="Spouse Name"/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Siblings</span>
                            <input type="number" name="siblings" class="profileInput profileSiblingInput"
                                   value="<?php echo $user["siblings"]; ?>"/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Children</span>
                            <input type="number" name="children" class="profileInput profileInputChildren"
                                   value="<?php echo $user["children"]; ?>"/>
                        </div>
                    @elseif($user["descent"] == "Jackson")
                        <div class="descentDiv">
                            <input type="text" name="descent" class="profileInput profileInputGreenBtn descentInput"
                                   value="Green" readonly/>
                            <input type="text" name="descent"
                                   class="profileInput profileInputJacksonBtn descentInput descentSelected"
                                   value="Jackson"
                                   readonly/>
                            <input type="text" name="descent" class="profileInput profileInputSpouseBtn descentInput"
                                   value="Spouse" readonly/>
                            <input type="text" name="descent" class="profileInput profileInputFriendBtn descentInput"
                                   value="Friend" readonly/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Parents</span>
                            <input type="type" name="mother" class="profileInput profileInputMother"
                                   value="<?php echo $user["mother"]; ?>" placeholder="Mother"/>
                            <span class="separator">&amp;</span>
                            <input type="type" name="father" class="profileInput profileInputFather"
                                   value="<?php echo $user["father"]; ?>" placeholder="Father"/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Spouse</span>
                            <input type="input" name="spouse" class="profileInput profileInputSpouse"
                                   value="<?php echo $user["spouse"]; ?>" placeholder="Spouse Name"/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Siblings</span>
                            <input type="number" name="siblings" class="profileInput profileSiblingInput"
                                   value="<?php echo $user["siblings"]; ?>"/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Children</span>
                            <input type="number" name="children" class="profileInput profileInputChildren"
                                   value="<?php echo $user["children"]; ?>"/>
                        </div>
                    @elseif($user["descent"] == "Spouse")
                        <div class="descentDiv">
                            <input type="text" name="descent" class="profileInput profileInputGreenBtn descentInput"
                                   value="Green" readonly/>
                            <input type="text" name="descent" class="profileInput profileInputJacksonBtn descentInput"
                                   value="Jackson" readonly/>
                            <input type="text" name="descent"
                                   class="profileInput profileInputSpouseBtn descentInput descentSelected"
                                   value="Spouse"
                                   readonly/>
                            <input type="text" name="descent" class="profileInput profileInputFriendBtn descentInput"
                                   value="Friend" readonly/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Spouse</span>
                            <input type="input" name="spouse" class="profileInput profileInputSpouse"
                                   value="<?php echo $user["spouse"]; ?>" placeholder="Spouse Name"/>
                        </div>
                        <div class="sectionDiv">
                            <span class="inputTitle">Children</span>
                            <input type="number" name="children" class="profileInput profileInputChildren"
                                   value="<?php echo $user["children"]; ?>"/>
                        </div>
                    @else
                        <div class="descentDiv">
                            <input type="text" name="descent" class="profileInput profileInputGreenBtn descentInput"
                                   value="Green" readonly/>
                            <input type="text" name="descent" class="profileInput profileInputJacksonBtn descentInput"
                                   value="Jackson" readonly/>
                            <input type="text" name="descent" class="profileInput profileInputSpouseBtn descentInput"
                                   value="Spouse" readonly/>
                            <input type="text" name="descent"
                                   class="profileInput profileInputFriendBtn descentInput descentSelected"
                                   value="Friend"
                                   readonly/>
                        </div>
                    @endif
                </div>

                <div id="contact_information" class="profile_info_div">
                    <div class="header_div">
                        <h3 id="contact_header">Contact Information</h3>
                        @if($user["show_contact"] == "Y")
                            <span class="visibleHeaderSpan">Visible to all</span>
                            <input type="checkBox"
                                   class="visibleCheckBox"
                                   name="show_contact" value="Y"
                                   checked/>
                        @else
                            <span class="visibleHeaderSpan">Visible to all</span>
                            <input type="checkBox"
                                   class="visibleCheckBox"
                                   name="show_contact" value="Y"/>
                        @endif
                    </div>
                    <div class="sectionDiv">
                        <span class="inputTitle">Email</span>
                        <input type="text" name="email" class="profileInput" value="{{ $user["email"] }}"
                               placeholder="Email"/>
                    </div>
                    <div class="sectionDiv">
                        <span class="inputTitle">Phone</span>
                        <input type="text" name="phone[]" class="profileInput profileInputPhone1" placeholder="###"
                               value="{{ $userPhone1 }}" maxlength="3"/>
                        <span class="phone_par_span">-</span>
                        <input type="text" name="phone[]" class="profileInput profileInputPhone2" placeholder="###"
                               value="{{ $userPhone2 }}" maxlength="3"/>
                        <span class="phone_par_span">-</span>
                        <input type="text" name="phone[]" class="profileInput profileInputPhone3" placeholder="####"
                               value="{{ $userPhone3 }}" maxlength="4"/>
                    </div>

                    <div class="sectionDiv">
                        <span class="inputTitle">Address</span>
                        <input type="text" name="address" class="profileInput profileInputAddress" placeholder="Address"
                               value="<?php echo $user["address"]; ?>"/>
                        <input type="text" name="city" class="profileInput profileInputCity" placeholder="City"
                               value="<?php echo $user["city"]; ?>"/>
                        <select name="state" id="state_select" class="profileInput profileInputState"
                                placeholder="State">
                            @foreach($states as $state)
                                @if($state->state_abb == $user->state)
                                    <option value="<?php echo $state->state_abb; ?>"
                                            selected><?php echo $state->state_abb; ?></option>
                                @else
                                    <option
                                        value="<?php echo $state->state_abb; ?>"><?php echo $state->state_abb; ?></option>
                                @endif
                            @endforeach
                        </select>
                        <input type="text" name="zip" id="" class="profileInput profileInputZip" placeholder="Zip Code"
                               value="<?php echo $user["zip"]; ?>"/>
                    </div>
                </div>

                <div id="social_information" class="profile_info_div">
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
                <input type="submit" name="submit" id="submit_profile_update" value="Update"/>
            </form>
        </div>
    </div>
</x-app-layout>
