<x-app-layout>

    @section('add_scripts')
        <script type="text/javascript">
            document.getElementsByTagName('footer')[0].classList.add('fixed-bottom');
        </script>
    @endsection

    <div id="" class="container-fluid">
        <div class="row">
            <div class="col-12 py-5 text-center font7"
                 style="background: radial-gradient(darkgreen, green, #303a30); color: whitesmoke;">
                <h1 class="my-3">Jackson/Green Family
                    Reunion</h1>
            </div>

            <div class="col-8 col-md-8 mx-auto mt-4 text-center">
                <div class="mb-4">
                    <h1 class="mb-3">Almost There!</h1>

                    <p class="">Thanks for registering an account! Before getting started, we need verify your account.
                        We are working to get every account verified as soon as possible. If more than a week has passed
                        and you still don't have access, please reach to one of the contacts. You can view all the
                        contacts by clicking the link below.</p>

                    <a href="{{ route('contact') }}" class="btn btn-lg btn-primary">Contacts</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
