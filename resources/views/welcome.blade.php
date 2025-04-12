<x-guest-layout>

    <div id="jgreunion_page" class="container-fluid mb-5">
        <div class="col-12">
            <h1 class="text-center">View our future reunions and take a look at where we've been in the past.</h1>
        </div>

        <div id="jgreunion_past_future" class="col-12 my-3">

            <div class="row justify-content-around align-content-center">
                <div class="col-11 col-md-5 mb-2">
                    @if($newReunionCheck != null)

                        <a href="{{ route('reunions.show', ['reunion' => $newReunionCheck->id]) }}"
                           id="upcoming_btn"
                           class="btn btn-lg btn-block">Upcoming Reunion
                            - {{ ucwords($newReunionCheck->reunion_city) . ' ' . $newReunionCheck->reunion_year }}</a>
                    @else

                        <a href="#" id="upcoming_btn" class="btn btn-lg btn-block noActive">No Reunion Set
                            Yet</a>

                    @endif
                </div>

                <div class="col-11 col-md-5 mb-2" id="">
                    <button data-mdb-ripple-init
                            data-mdb-modal-init
                            data-mdb-target="#pastReunionModal"
                            id="past_btn"
                            class="btn btn-lg btn-block"
                            type="button">Past Reunions
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="reunion_history" class="container-fluid">
        <div class="row">
            <div class="col-12 text-white">

                <img id="reunion_history_pic" src="{{ asset('images/founder.png') }}" class=""/>

                <p class="py-5">To The Jackson Green Family,<br/><br/>Earlean Jackson, Victoria Jackson Darby and Hattie
                    Mae
                    Jackson Green, started the Jackson-Green Family
                    Reunion in the winter of 1982.<br/><br/>In beginning of forming the family reunion tradition,
                    Earlean,
                    Hattie Mae and Victoria knew that they
                    wanted a family reunion but didn’t want to be partial to either side of the family. Therefore the
                    decision
                    was made to form the family reunion
                    with both the Jackson (father’s line of decent) and Green (mother’s line of decent) families.
                    <br/><br/>The
                    first family reunion was held on the
                    second weekend of August in 1982. The reunion started with a barbeque on Friday in the backyard of
                    Victoria's home in St. Matthew, South Carolina.
                    On the following day, a program and dinner was held at the local middle school. Closing the family
                    reunion
                    weekend, on Sunday, a church services was
                    held at Salem Baptist Church in Fort Motte, South Carolina with Brother Sandy Jackson presiding over
                    the
                    service.<br/><br/>Since the first reunion,
                    Earlean, Victoria and Hattie Mae’s original vision to celebrate family has produced 16 more
                    bi-annual
                    reunions thus far. Currently the average attendance
                    at the Jackson-Green Family Reunions ranges between 150-200 people. Every two years the Jackson-
                    Green
                    reunion continues the family tradition of uplifting,
                    celebrating, and honoring family. The family legacy continues in 2016 as the Jackson-Green families
                    come
                    together in Philadelphia, PA.</p>
            </div>
        </div>
    </div>

    <div id="reunion_descent" class="container-fluid pb-3">
        <div class="row">
            <div class="col-12">
                <h3 class="h3-responsive px-5">It was from the union of Sandy Jackson (Rev. Sandy and Venus
                    Jackson’s
                    son) and Clander Green (Peter and Laura Green’s daughter) that brought the Jackson-Green
                    families
                    together.</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-8 col-xl-7">
                <div id="jackson_descent" class="reunion_descent_info mt-3 mb-5 wow fadeInUp" data-wow-delay="0.6s">

                    <h2 class="descent_info cooltext2 display-4">Jackson Line of Descent</h2>

                    <p class="px-2">Rev. Sandy Jackson II and his wife Venus, had nine children and forty
                        grandchildren
                        come from their union.</p>

                    <ol>
                        <li>Louis Jackson&nbsp;(six&nbsp;children)</li>
                        <li>Darryl&nbsp;Jackson&nbsp;(two&nbsp;children)</li>
                        <li>Willie&nbsp;&quot;HIT&quot;&nbsp;Jackson&nbsp;(two&nbsp;children)</li>
                        <li>Chair&nbsp;Jackson&nbsp;(one&nbsp;child)</li>
                        <li>Mary&nbsp;Magdalene&nbsp;Jackson&nbsp;(three&nbsp;children)</li>
                        <li>Cyrus&nbsp;&quot;Blump&quot;&nbsp;Jackson&nbsp;(eight&nbsp;children)</li>
                        <li>Sally Jackson</li>
                        <li>Sandy&nbsp;Jackson&nbsp;(nine&nbsp;children)</li>
                        <li>Hattie&nbsp;Jackson&nbsp;(nine&nbsp;children)</li>
                    </ol>
                </div>

                <div id="green_descent" class="reunion_descent_info mt-3 mb-5 wow fadeInUp" data-wow-delay="0.6s">
                    <h2 class="descent_info cooltext2 display-4">Green Line of Descent</h2>

                    <p class="px-2">From the union of Peter and Laura Green, there were eight children and fifty-six
                        grandchildren.</p>

                    <ol>
                        <li>Davis&nbsp;Green</li>
                        <li>Richard&nbsp;Green&nbsp;(four&nbsp;children)</li>
                        <li>Louis&nbsp;Green&nbsp;(five&nbsp;children)</li>
                        <li>Senda&nbsp;Green</li>
                        <li>Nancy&nbsp;Green&nbsp;(six&nbsp;children)</li>
                        <li>Anna&nbsp;Green&nbsp;(eleven&nbsp;children)</li>
                        <li>Peggy&nbsp;Green&nbsp;(eleven&nbsp;children)</li>
                        <li>Victoria&nbsp;Angus&nbsp;Green&nbsp;(eleven&nbsp;children)</li>
                    </ol>

                </div>
            </div>

            <div class="col-sm-4 col-xl-5 align-self-center wow fadeInRight" data-wow-delay="0.6s">
                <img id="family_tree_pic" src="images/funkynewtree.jpg" class="img-fluid"/>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pastReunionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Past Reunions (Year & Location)</h5>
                    <button type="button" class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @foreach($reunions as $pastReunion)
                        @if($pastReunion->reunion_complete == "Y")
                            @if($pastReunion->has_site == "Y")
                                <li class="pastReunion">
                                    <a class="pastReunionSite"
                                       href="/past_reunion/{{ $pastReunion->id }}"
                                       style="color: aquamarine;"
                                       target="_blank">{{ $pastReunion->reunion_year }}
                                        - {{ $pastReunion->reunion_city }}
                                        , {{ $pastReunion->reunion_state }}</a>
                                </li>
                            @else
                                <li class="pastReunion">{{ $pastReunion->reunion_year }}
                                    - {{ $pastReunion->reunion_city }}
                                    , {{ $pastReunion->reunion_state }}</li>
                            @endif
                        @endif
                    @endforeach
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
