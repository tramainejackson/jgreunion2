<div id="reunion_registration_completion_form">
    <div class="container-fluid" id="">
        <div class="row">

            @if(Auth::check())
                <div class="card border border-1 px-0">
                    <div class="card-header bg-success-subtle text-center">
                        <h1 class="" style="color: navy;">Completed Registration
                            for Upcoming Reunion in {{ $active_reunion->reunion_city . ', ' . $active_reunion->reunion_state . ' ' . $active_reunion->reunion_year  }} </h1>
                    </div>

                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-4">
                                    <h4 class="text-center">
                                        <span
                                            class="fw-bold text-decoration-underline">Amount Due at Registration:</span>
                                        <br/>
                                        <span class="">${{ $registered_for_reunion->due_at_reg }}</span>
                                    </h4>
                                </div>

                                <div class="col-4">
                                    <h4 class="text-center">
                                        <span class="fw-bold text-decoration-underline">Amount Paid:</span>
                                        <br/>
                                        <span>${{ $registered_for_reunion->total_amount_paid }}</span>
                                    </h4>
                                </div>

                                <div class="col-4">
                                    <h4 class="text-center">
                                        <span class="fw-bold text-decoration-underline">Amount Due:</span>
                                        <br/>
                                        <span>${{ $registered_for_reunion->total_amount_due }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div>

                            <table class="table align-middle mb-0 bg-white table-striped table-fixed text-center">
                                <thead class="bg-light">
                                <tr>
                                    <th class="text-decoration-underline fw-bold">Name</th>
                                    <th class="text-decoration-underline fw-bold">Cost</th>
                                    <th class="text-decoration-underline fw-bold">Shirt Size</th>
                                </tr>
                                </thead>

                                <tbody>

                                @for($i=0; $i < count(explode('; ', $registered_for_reunion->adult_names)); $i++)
                                    <tr>
                                        <td>{{ explode('; ', $registered_for_reunion->adult_names)[$i] }}</td>
                                        <td>${{ $active_reunion->adult_price }}</td>
                                        <td>{{ isset($registered_for_reunion->adult_shirts[$i]) ? explode(';', $registered_for_reunion->adult_shirts)[$i] : 'Missing Shirt Size' }}</td>
                                    </tr>
                                @endfor

                                @if($registered_for_reunion->youth_names != null)
                                    @php $youth_shirts_arr = explode('; ', $registered_for_reunion->youth_shirts); @endphp

                                    @for($i=0; $i < count(explode(';', $registered_for_reunion->youth_names)); $i++)
                                        <tr>
                                            <td>{{ explode('; ', $registered_for_reunion->youth_names)[$i] }}</td>
                                            <td>${{ $active_reunion->youth_price }}</td>
                                            <td>{{ isset($youth_shirts_arr[$i]) ? $registered_for_reunion->youth_shirts_formatted($youth_shirts_arr[$i]) : 'Missing Shirt Size' }}</td>
                                        </tr>
                                    @endfor
                                @endif

                                @if($registered_for_reunion->children_names != null)
                                    @php $children_shirts_arr = explode('; ', $registered_for_reunion->children_shirts); @endphp

                                    @for($i=0; $i < count(explode(';', $registered_for_reunion->children_names)); $i++)
                                        <tr>
                                            <td>{{ explode('; ', $registered_for_reunion->children_names)[$i] }}</td>
                                            <td>${{ $active_reunion->child_price }}</td>
                                            <td>{{ isset($children_shirts_arr[$i]) ? $registered_for_reunion->children_shirts_formatted($children_shirts_arr[$i]) : 'Missing Shirt Size' }}</td>
                                        </tr>
                                    @endfor
                                @endif

                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            @else
            @endif

        </div>
    </div>

</div>
