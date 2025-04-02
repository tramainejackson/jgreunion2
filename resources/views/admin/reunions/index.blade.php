<x-app-layout>

    <div class="container-fluid" id="profilePage">

        @include('components.nav')

        <div class="row">

            <div class="col-12 col-lg-2 my-2">
                <div class="">
                    <a href="{{ route('reunions.create') }}" class="btn btn-info btn-lg">Create New Reunion</a>
                </div>
            </div>

            <div class="col-12 col-md-10 col-lg-9 my-3 mx-auto">

                <ul class="list-group accordion md-accordion" id="accordionEx" role="tablist"
                    aria-multiselectable="true">

                    <li class="list-group-item list-group-item-info">All Reunions</li>

                    @foreach($reunions as $reunion)

                        <li class="list-group-item list-group-item-action reunionItem{{ $reunion->reunion_complete == 'N' ? ' activeReunionItem' : '' }}">

                            <h2 class="" role="tab" id="headingOne{{$loop->iteration}}" data-toggle="collapse"
                                data-parent="#accordionEx" href="#reunionAccordion{{$loop->iteration}}"
                                aria-expanded="true"
                                aria-controls="reunionAccordion{{$loop->iteration}}">{{ $reunion->reunion_city . ' ' . $reunion->reunion_year }}</h2>

                            @if($reunion->has_site == 'Y')

                                <div class="container-fluid collapse{{ $loop->iteration == 1 ? ' show' : '' }}"
                                     role="tabpanel" id="reunionAccordion{{$loop->iteration}}"
                                     data-parent="#accordionEx">

                                    <div class="form-row my-3">
                                        <div class="form-group col-12 col-md-4">
                                            <label class="form-label" for="reunion_city">City</label>
                                            <input type="text" class="form-control" value="{{ $reunion->reunion_city }}"
                                                   disabled/>
                                        </div>
                                        <div class="form-group col-6 col-md-4">
                                            <label class="form-label" for="reunion_state">State</label>

                                            <select class="form-control browser-default" disabled>
                                                <option
                                                        value="{{ $reunion->reunion_state }}">{{ $reunion->reunion_state }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-6 col-md-4">
                                            <label class="form-label" for="reunion_state">Year</label>

                                            <select class="form-control browser-default" disabled>
                                                <option
                                                        value="{{ $reunion->reunion_year }}">{{ $reunion->reunion_year }}</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-row my-3">
                                        <div class="form-group col-12 col-md-4">

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

                                        <div class="form-group col-12 col-md-4">

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

                                        <div class="form-group col-12 col-md-4">

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

                                    @else

                                        <div class="form-group">
                                            <a href="{{ route('create_reunion_pictures', ['reunion' => $reunion->id]) }}"
                                               class="btn btn-lg btn-outline-light-green">Add Reunion Photos</a>
                                        </div>

                                    @endif

                                </div>

                            @else

                                <div class="container-fluid collapse" id="reunionAccordion{{$loop->iteration}}">
                                    <h3 class="text-center">No Additional Information For This Reunion</h3>
                                </div>

                            @endif

                        </li>

                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
