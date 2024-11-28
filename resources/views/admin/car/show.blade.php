@extends('admin.layouts.dashboard')


@section('content')
<div class="nk-content ">
    <div class="container">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Car Detail</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{
                                            env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.cars.index') }}">Cars</a></li>
                                    <li class="breadcrumb-item active">View Detail</li>
                                </ul>
                            </nav>
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            <a href="javascript:void(0)" onclick="goBack()"
                                class="btn btn-icon btn-outline-light bg-white d-inline-flex"><em
                                    class="icon ni ni-arrow-left"></em></a>
                        </div>
                    </div><!-- .nk-block-between -->
                </div>

                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-inner">
                            <div class="row pb-5">
                                <div class="col-lg-6">
                                    <div class="product-gallery me-xl-1 me-xxl-5">
                                        <div class="slider-init" id="sliderFor"
                                            data-slick='{"arrows": false, "fade": true, "asNavFor":"#sliderNav", "slidesToShow": 1, "slidesToScroll": 1}'>
                                            @foreach ($car->images as $image)
                                            <div class="slider-item rounded">
                                                <img loading="lazy" src="{{ $image->full }}" class="w-100" alt="">
                                            </div>
                                            @endforeach
                                        </div><!-- .slider-init -->
                                        <div class="slider-init slider-nav" id="sliderNav" data-slick='{"arrows": false, "slidesToShow": 5, "slidesToScroll": 1, "asNavFor":"#sliderFor", "centerMode":true, "focusOnSelect": true,
                "responsive":[ {"breakpoint": 1539,"settings":{"slidesToShow": 4}}, {"breakpoint": 768,"settings":{"slidesToShow": 3}}, {"breakpoint": 420,"settings":{"slidesToShow": 2}} ]
            }'>
                                            @foreach ($car->images as $image)
                                            <div class="slider-item">
                                                <div class="thumb">
                                                    <img loading="lazy" src="{{ $image->thumbnail }}" alt="">
                                                </div>
                                            </div>
                                            @endforeach

                                        </div><!-- .slider-nav -->
                                    </div><!-- .product-gallery -->
                                </div><!-- .col -->
                                <div class="col-lg-6">
                                    <div class="product-info">
                                        <div class="d-flex justify-content-between">
                                            <h2 class="product-title">{{ $car->brand->name }} <small>{{ $car->model->name ?? "" }} {{ $car->year }}</small></h2>
                                            @if (!empty($car->block_reason))
                                            <a title="Unblock Ad" href="javascript:;" class="unblock" data-href="{{ route('admin.cars.unblock', $car->id) }}" ><span class="badge badge-sm bg-success"><em class="icon ni ni-na"></em>&nbsp;Unblock Ad</span></a>
                                            @else
                                            <a title="Block Ad" href="javascript:;" class="block" data-href="{{ route('admin.cars.block', $car->id) }}" ><span class="badge badge-sm bg-danger"><em class="icon ni ni-na"></em>&nbsp;Block Ad</span></a>
                                            @endif
                                        </div>
                                        @if (!empty($car->block_reason))
                                        <p><strong class="text-danger">Reason: </strong>{!! $car->block_reason !!}</p>
                                        @endif
                                        <div class="product-meta">
                                            <ul class="d-flex flex-wrap justify-content-between g-4 gx-5">
                                                <li>
                                                    <div class="fs-14px text-muted">Cost Per Day <small>(&euro;)</small></div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->cost_per_day }}</div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Deposit Amount <small>(&euro;)</small></div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->deposit }}</div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Seats</div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ num2word($car->seats) }}</div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Type</div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->type }}</div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Fuel Type</div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->fuel_type }}</div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Mileage</div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->mileage }} km</div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Transmission</div>
                                                    <div class="fs-16px fw-bold text-secondary">
                                                        @if ($car->transmission)
                                                        <strong>Manual</strong>
                                                        @else
                                                        <strong>Automatic</strong>
                                                        @endif

                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Engine Capacity</div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->power_size }} HP</div>
                                                </li>

                                                <li>
                                                    <div class="fs-14px text-muted">Pickup Location</div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->pickup_location }}</div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Delivery Possible</div>
                                                    <div class="fs-16px fw-bold text-secondary">
                                                        @if ($car->delivery_possible)
                                                        <strong>Yes</strong>
                                                        @else
                                                        <strong>No</strong>
                                                        @endif
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Insurance Included</div>
                                                    <div class="fs-16px fw-bold text-secondary">
                                                        @if ($car->insurance_included)
                                                        <strong>Yes</strong>
                                                        @else
                                                        <strong>No</strong>
                                                        @endif
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Status</div>
                                                    <div class="fs-16px fw-bold text-secondary">
                                                        @if ($car->status == '1')
                                                        <span class="badge badge-lg bg-outline-success">Available</span>
                                                        @elseif ($car->status == '2')
                                                        <span class="badge badge-lg bg-outline-info">On Rent</span>
                                                        @else
                                                        <span class="badge badge-lg bg-outline-danger">Not Available</span>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Is Active?</div>
                                                    <div class="fs-16px fw-bold text-secondary">
                                                        @if ($car->is_active)
                                                        <span class="badge badge-lg bg-outline-success">Active</span>
                                                        @else
                                                        <span class="badge badge-lg bg-outline-warning">Not Active</span>
                                                        @endif
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="fs-14px text-muted">Rented By</div>
                                                    <div class="fs-16px fw-bold text-secondary">{{ $car->user->username }}</div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div><!-- .product-info -->
                                </div><!-- .col -->
                            </div><!-- .row -->

                        </div>
                    </div>
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="blockAdModal" tabindex="-1" role="dialog" aria-labelledby="blockAdModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Block Ad</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="giveawayForm">
            <div class="modal-body">
                    <div class="row g-gs">
                        <div class="col-md-12">
                            <div class="login-input">
                                <label for="">Reason *</label>
                               <textarea name="block_reason" id="block_reason" class="form-control" cols="30" rows="10" required placeholder="Write a brief reason for blocking"></textarea>
                            </div>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:;" class="btn btn-success btn-submit">Submit</a>
            </div>
            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
       $(document).on('click', '.block', function(e){
            e.preventDefault();
            $url = $(this).data('href');
            Swal.fire({
                icon: 'warning',
                input: 'textarea',
                title: 'Do you really want to block this ad?',
                text: "Blocking Reason?",
                showCancelButton: true,
                confirmButtonColor: '#e85347',
                cancelButtonColor: '#1ee0ac',
                confirmButtonText: `Block`,
                preConfirm: (value) => {
                    if (!value) {
                        Swal.showValidationMessage('Please enter blocking reason.');
                    }
                    return value;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: $url,
                        data: {reason: result.value},
                        success: function(data) {
                            if(data.status == 200){
                                Swal.fire({
                                title: data.message,
                                icon: 'success',
                                confirmButtonText: 'Okay!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload()
                                    }
                                });
                            }
                        },
                        error: function(error) {
                            alert('Something went wrong!')
                        }
                    });
                }
            });
        });

        $(document).on('click', '.unblock', function(e){
            e.preventDefault();
            $url = $(this).data('href');
            Swal.fire({
                icon: 'warning',
                title: 'Do you really want to unblock this ad?',
                showCancelButton: true,
                confirmButtonColor: '#1ee0ac',
                cancelButtonColor: '#e85347',
                confirmButtonText: `Unblock`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    url: $url,
                    success: function(data) {
                        if(data.status == 200){
                                Swal.fire({
                                title: data.message,
                                icon: 'success',
                                confirmButtonText: 'Okay!',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload()
                                    }
                                });
                            }
                    },
                    error: function(error) {
                        alert('Something went wrong!')
                    }
                });
                }
            });
        });
</script>
@endpush
