<x-app-layout>

    <div class="container-fluid" id="">

        <x-admin-jumbotron>Reunions</x-admin-jumbotron>

        @include('components.nav')

        <div class="row">

            <div class="col-12 col-lg-2 my-2">
                <div class="">
                    <a href="{{ route('reunions.create') }}" class="btn btn-info btn-lg">Create New Reunion</a>
                </div>
            </div>

            <div class="col-12 col-md-10 col-lg-9 my-3 mx-auto">

                <ul class="list-group accordion md-accordion" id="accordionEx" aria-multiselectable="true">

                    <li class="list-group-item list-group-item-info">All Reunions</li>

                    @foreach($reunions as $reunion)

                        <li class="list-group-item list-group-item-action accordion-item reunionItem{{ $reunion->reunion_complete == 'N' ? ' activeReunionItem' : '' }}">

                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed"
                                        type="button"
                                        data-mdb-collapse-init
                                        id="headingOne{{$loop->iteration}}"
                                        data-mdb-target="#reunionAccordion{{$loop->iteration}}"
                                        aria-expanded="true"
                                        aria-controls="reunionAccordion{{$loop->iteration}}">{{ $reunion->reunion_city . ' ' . $reunion->reunion_year }}</button>
                            </h2>

                            @if($reunion->has_site == 'Y')

                                <div class="container-fluid accordion-collapse collapse"
                                     aria-labelledby="flush-headingOne"
                                     id="reunionAccordion{{$loop->iteration}}"
                                     data-mdb-parent="#accordionEx">

                                    <div class="row ps-2 my-3">
                                        <div class="col-4">
                                            <p class="m-0">{{ $reunion->reunion_city . ', ' . $reunion->reunion_state . ' ' . $reunion->reunion_year}}</p>
                                        </div>
                                    </div>

                                    <div class="row ps-2 my-3">
                                        <div class="col-4">

                                            <label class="form-label" for="adult_price">Adult Price</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">$</span>
                                                </div>
                                                <input type="number" class="form-control"
                                                       value="{{ $reunion->adult_price }}" disabled/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon1">Per Adult</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-4">

                                            <label class="form-label" for="youth_price">Youth Price</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">$</span>
                                                </div>
                                                <input type="number" class="form-control"
                                                       value="{{ $reunion->youth_price }}" disabled/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon1">Per Youth</span>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-4">

                                            <label class="form-label" for="child_price">Child Price</label>

                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="basic-addon1">$</span>
                                                </div>

                                                <input type="number" class="form-control"
                                                       value="{{ $reunion->child_price }}" aria-label="Username"
                                                       disabled/>
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon1">Per Child</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-row justify-content-around mb-3">
                                        <button type="button" class="btn btn-primary col-12 col-md-4">Registrations
                                            <span
                                                class="badge badge-light">{{ $reunion->registrations->count() }}</span>
                                            <span class="sr-only">total registrations</span>
                                        </button>

                                        <button type="button" class="btn btn-primary col-12 col-md-4">Committee Members
                                            <span class="badge badge-light">{{ $reunion->committee->count() }}</span>
                                            <span class="sr-only">total committee members</span>
                                        </button>
                                    </div>

                                    @if($reunion->reunion_complete == 'N')

                                        <div class="form-group">
                                            <a href="/reunions/{{ $reunion->id }}/edit" class="btn btn-warning btn-lg">Edit
                                                Reunion</a>
                                        </div>

                                    @endif

                                </div>

                            @else

                                <div class="container-fluid collapse" id="reunionAccordion{{$loop->iteration}}">
                                    <h3 class="text-center my-3">No Additional Information For This Reunion</h3>
                                </div>

                            @endif

                        </li>

                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
