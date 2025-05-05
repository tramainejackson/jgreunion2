<div id="carouselExampleSlidesOnly" class="carousel slide mb-5" data-mdb-ride="carousel" data-mdb-carousel-init>
    <div class="carousel-inner">
        @php $images = \App\Models\Images::all(); @endphp

        @foreach($images as $image)
            @if($image->add_to_carousel == 'Y')
                <div class="carousel-item{{ $loop->first ? ' active' : '' }}">
                    <img src="{{ asset('images/' . $image->image_name . '.png') }}" class="d-block w-100"
                         alt="{{ asset($image->image_desc) }}"/>

                    <div class="mask text-white" style="background-color: rgba(0, 0, 0, 0.5)">
                        <div class="container d-flex align-items-start justify-content-center h-100">
                            <div class="">
                                <h1 class="pt-5 mt-5">Jackson & Green Family Reunion</h1>
                                <h3 class="text-center">{{ $image->image_desc }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>

<script type="text/javascript">
    let images = document.getElementsByClassName('carousel-item');
    let createHeight = screen.availHeight * 0.33;

    if (images.length >= 1) {
        for (let i = 0; i < images.length; i++) {
            let thisImage = images[i].firstElementChild;

            thisImage.style.minHeight = createHeight + 'px';
        }
    }
</script>
