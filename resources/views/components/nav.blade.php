<script type="module">
    const myCollapsible = document.getElementById('navbarExample01');
    myCollapsible.addEventListener('shown.mdb.collapse', () => {
        if( document.getElementsByClassName('navbar')[0].classList.contains('navbar-scrolled') == false) {
            document.getElementsByClassName('navbar')[0].classList.add('navbar-scrolled');
        }
    })
</script>

<!-- Animated navbar-->
<nav class="navbar navbar-expand-lg fixed-top navbar-scroll" data-mdb-navbar-init>
    <div class="container-fluid">
        <button data-mdb-collapse-init
                id="jgreunion_nav_bar_btn"
                class="navbar-toggler ps-0"
                type="button"
                data-mdb-toggle="collapse"
                data-mdb-target="#navbarExample01"
                aria-controls="navbarExample01"
                aria-expanded="false"
                aria-label="Toggle navigation">

            <span
                class="d-flex justify-content-start align-items-center">
              <i class="fas fa-bars"></i>
            </span>
        </button>

        <div class="collapse navbar-collapse" id="navbarExample01">
            <ul class="navbar-nav me-auto">
                <li class="nav-item active">
                    <a class="nav-link" aria-current="page" href="{{ route('guest_home') }}">Home</a>
                </li>

                <li class="nav-item active">
                    <a class="nav-link" aria-current="page" href="{{ route('contact') }}">Contact</a>
                </li>

                @if($setting->current_reunion != null)
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('reunions.show', $setting->current_reunion) }}">Upcoming
                            Reunion</a>
                    </li>
                @endif

                @if(Auth::check())
                    @if(Auth::user()->is_admin())
                        <li class="nav-item">
                            <a href="{{ route('my_profile.edit', ['my_profile' => Auth::user()->member->id]) }}"
                               class="nav-link{{ Str::contains(url()->current(), ['index']) ? ' active' : '' }}">All
                                Family
                                Members</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('members.index') }}"
                               class="nav-link{{ Str::contains(url()->current(), ['index']) ? ' active' : '' }}">All
                                Family
                                Members</a>
                        </li>
                    @endif
                @endif
            </ul>

            <ul class="navbar-nav flex-row">
                @if(Auth::check())
                    <li class="nav-item">
                        <a href="{{ route('members.edit', Auth::user()->member->id) }}" class='nav-link pe-2'>My Profile</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('logout') }}" class="profileLink nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log
                            Out</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class='nav-link pe-2'>Register</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link pe-2" href="{{ route('login') }}">Login</a>
                    </li>
                @endif

                <li class="nav-item">
                    <a data-mdb-ripple-init
                       class="nav-link px-2"
                       href="https://www.facebook.com/groups/129978977047141/"
                       rel="nofollow"
                       target="_blank">
                        <i class="fab fa-facebook-square fb"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Animated navbar -->
