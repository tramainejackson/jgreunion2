<!-- Footer -->
<footer class="page-footer bg-secondary-subtle pt-4 mt-5">

    <!-- Footer Elements -->
    <div class="container">

        @if(Auth::check())
            <!-- Call to action -->
            <ul class="list-unstyled list-inline text-center mb-0">
                <li class="list-inline-item">
                    <a href="{{ route('logout') }}" class="btn btn-outline-primary btn-rounded"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log
                        Out</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
            <!-- Call to action -->
        @else
            <!-- Call to action -->
            <ul class="list-unstyled list-inline text-center mb-0">
                <li class="list-inline-item">
                    <h5 class="mb-1">Register an account</h5>
                </li>

                <li class="list-inline-item">
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-rounded">Sign up!</a>
                </li>
            </ul>
            <!-- Call to action -->
        @endif

    </div>
    <!-- Footer Elements -->

    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2018 Copyright:
        <a href="https://www.tramainejackson.com/">Tramaine Jackson Tech LLC</a>
    </div>
    <!-- Copyright -->

</footer>
<!-- Footer -->
