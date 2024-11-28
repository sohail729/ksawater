@extends('admin.layouts.dashboard')
@push('styles')
    <style>
        .nav-tabs .nav-item:nth-child(4) {
            position: absolute;
            right: 15px;
        }
    </style>
@endpush
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Dealer Profile</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{
                                            env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dealer.index') }}">Dealers</a>
                                    </li>
                                    <li class="breadcrumb-item active">Profile</li>
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

                {{--
                <a title="View Subscription History" target="_blank" class="btn btn-warning btn-sm"
                    href='{{ route("admin.subscriptions.index", $dealer->id) }}'><em
                        class="icon ni ni-history"></em></a> --}}


                <div class="nk-block">
                    <div class="card card-bordered">
                        <div class="card-content">
                            <ul class="nav nav-tabs nav-tabs-mb-icon nav-tabs-card">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab1"><em
                                            class="icon ni ni-user-circle"></em><span>Personal</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab2"><em
                                            class="icon ni ni-sign-euro-alt"></em><span>Subscription</span></a>
                                </li>
                                <li class="nav-item">
                                    @if (in_array($dealer->status, [0, 2]))
                                    <a title="Giveaway Subscription!" href="javascript:;" class="nav-link text-success" data-bs-toggle="modal" data-bs-target="#giveawayModal"><em class="icon ni ni-gift"></em>&nbsp;Giveaway Subscription</a>
                                    @endif
                                </li>
                                @if ($dealer->status == 2)
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:;" ><span class="badge badge-sm bg-warning">Subscription Expired</span></a>
                                </li>
                                @endif

                                {{-- <li class="nav-item align-self-center">
                                    <a class="btn btn-warning btn-sm" title="View Subscription History" target="_blank"
                                        href='{{ route("admin.subscriptions.index", $dealer->id) }}'><em
                                            class="icon ni ni-history"></em></a>
                                </li> --}}
                                {{-- <li class="nav-item">
                                    <a class="nav-link" href="#"><em
                                            class="icon ni ni-repeat"></em><span>Transactions</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><em
                                            class="icon ni ni-file-text"></em><span>Documents</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><em
                                            class="icon ni ni-bell"></em><span>Notifications</span></a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#"><em
                                            class="icon ni ni-activity"></em><span>Activities</span></a>
                                </li>
                                <li class="nav-item nav-item-trigger d-xxl-none">
                                    <a href="#" class="toggle btn btn-icon btn-trigger" data-target="userAside"><em
                                            class="icon ni ni-user-list-fill"></em></a>
                                </li> --}}
                            </ul><!-- .nav-tabs -->
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab1">
                                    <div class="card-inner">
                                        <div class="nk-block">
                                            <div class="nk-block-head d-flex justify-content-between">
                                                <h5 class="title">Personal Information</h5>
                                                @if (!empty($dealer->block_reason))
                                                <a title="Unblock Dealer" href="javascript:;" class="unblock" data-href="{{ route('admin.dealer.unblock', $dealer->id) }}" ><span class="badge badge-sm bg-success"><em class="icon ni ni-na"></em>&nbsp;Unblock</span></a>
                                                 @else
                                                 <a title="Block Dealer" href="javascript:;" class="block" data-href="{{ route('admin.dealer.block', $dealer->id) }}" ><span class="badge badge-sm bg-danger"><em class="icon ni ni-na"></em>&nbsp;Block</span></a>
                                                @endif
                                            </div><!-- .nk-block-head -->
                                            <div class="profile-ud-list">
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Username</span>
                                                        <span class="profile-ud-value">{{ $dealer->username }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Full Name</span>
                                                        <span class="profile-ud-value">{{ $dealer->fullname ?? ""
                                                            }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Phone Number</span>
                                                        <span class="profile-ud-value">{{ $dealer->detail->phone ?? ""
                                                            }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Email Address</span>
                                                        <span class="profile-ud-value">{{ $dealer->email ?? "" }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Showroom/Garage Address</span>
                                                        <span class="profile-ud-value">{{ $dealer->detail->address
                                                            }}</span>
                                                    </div>
                                                </div>
                                            </div><!-- .profile-ud-list -->
                                        </div><!-- .nk-block -->
                                        <div class="nk-block">
                                            <div class="nk-block-head nk-block-head-line">
                                                <h6 class="title overline-title text-base">Additional Information</h6>
                                            </div><!-- .nk-block-head -->
                                            <div class="profile-ud-list">
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Joining Date</span>
                                                        <span class="profile-ud-value">{{
                                                            \Carbon\Carbon::parse($dealer->created_at)->format('h:i a -
                                                            jS M, Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">City</span>
                                                        <span class="profile-ud-value">{{ ucfirst($dealer->detail->city)
                                                            }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Verified?</span>
                                                        <span class="profile-ud-value">
                                                            @if ($dealer->is_verified)
                                                            <span class="badge badge-lg bg-outline-success">Yes</span>
                                                            @else
                                                            <span class="badge badge-lg bg-outline-warning">No</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                @if ($dealer->is_verified)
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Verified On</span>
                                                        <span class="profile-ud-value">
                                                            {{ \Carbon\Carbon::parse($dealer->verified_on)->format('h:i
                                                            a - jS M, Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @if (!empty($dealer->last_verified))
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Last Verified On</span>
                                                        <span class="profile-ud-value">
                                                            {{
                                                            \Carbon\Carbon::parse($dealer->last_verified)->format('h:i a
                                                            - jS M, Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @endif
                                                @endif
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Featured?</span>
                                                        <span class="profile-ud-value">
                                                            @if ($dealer->is_featured)
                                                            <span class="badge badge-lg bg-outline-success">Yes</span>
                                                            @else
                                                            <span class="badge badge-lg bg-outline-warning">No</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                @if ($dealer->is_featured)
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Featured Cars Limit</span>
                                                        <span class="profile-ud-value">
                                                            {{ $dealer->featured_limit }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Current Listed Featured
                                                            Cars</span>
                                                        <span class="profile-ud-value">
                                                            {{ $dealer->featuredCars()->count() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Featured Start Time</span>
                                                        <span class="profile-ud-value">
                                                            {{
                                                            \Carbon\Carbon::parse($dealer->featured_start)->format('h:i
                                                            a - jS M, Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Featured End Time</span>
                                                        <span class="profile-ud-value">
                                                            {{ \Carbon\Carbon::parse($dealer->featured_end)->format('h:i
                                                            a - jS M, Y') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @endif
                                            </div><!-- .profile-ud-list -->
                                        </div><!-- .nk-block -->
                                        {{-- <div class="nk-divider divider md"></div>
                                        <div class="nk-block">
                                            <div class="nk-block-head nk-block-head-sm nk-block-between">
                                                <h5 class="title">Admin Note</h5>
                                                <a href="#" class="link link-sm">+ Add Note</a>
                                            </div><!-- .nk-block-head -->
                                            <div class="bq-note">
                                                <div class="bq-note-item">
                                                    <div class="bq-note-text">
                                                        <p>Aproin at metus et dolor tincidunt feugiat eu id quam.
                                                            Pellentesque habitant morbi tristique senectus et netus et
                                                            malesuada fames ac turpis egestas. Aenean sollicitudin non
                                                            nunc vel pharetra. </p>
                                                    </div>
                                                    <div class="bq-note-meta">
                                                        <span class="bq-note-added">Added on <span class="date">November
                                                                18, 2019</span> at <span class="time">5:34
                                                                PM</span></span>
                                                        <span class="bq-note-sep sep">|</span>
                                                        <span class="bq-note-by">By <span>Softnio</span></span>
                                                        <a href="#" class="link link-sm link-danger">Delete Note</a>
                                                    </div>
                                                </div><!-- .bq-note-item -->
                                                <div class="bq-note-item">
                                                    <div class="bq-note-text">
                                                        <p>Aproin at metus et dolor tincidunt feugiat eu id quam.
                                                            Pellentesque habitant morbi tristique senectus et netus et
                                                            malesuada fames ac turpis egestas. Aenean sollicitudin non
                                                            nunc vel pharetra. </p>
                                                    </div>
                                                    <div class="bq-note-meta">
                                                        <span class="bq-note-added">Added on <span class="date">November
                                                                18, 2019</span> at <span class="time">5:34
                                                                PM</span></span>
                                                        <span class="bq-note-sep sep">|</span>
                                                        <span class="bq-note-by">By <span>Softnio</span></span>
                                                        <a href="#" class="link link-sm link-danger">Delete Note</a>
                                                    </div>
                                                </div><!-- .bq-note-item -->
                                            </div><!-- .bq-note -->
                                        </div><!-- .nk-block --> --}}
                                    </div><!-- .card-inner -->
                                </div>
                                <div class="tab-pane" id="tab2">
                                    <div class="card-inner">
                                        @if (!empty($dealer->package))
                                        <div class="btn-group float-end" role="group" aria-label="Basic example">
                                            <a class="btn btn-outline-warning " title="View Subscription History"
                                                target="_blank"
                                                href='{{ route("admin.subscriptions.index", $dealer->id) }}'><em
                                                    class="icon ni ni-history"></em></a>
                                        </div>
                                        <div class="nk-block float-start">
                                            <div class="profile-ud-list">
                                                @php
                                                $package = explode('/', $dealer->package);
                                                @endphp
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Package Duration</span>
                                                        <span class="profile-ud-value">
                                                            {{ $package[0] }}
                                                            @if ($package[0] == '1')
                                                            Month
                                                            @else
                                                            Months
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Package Upload Limit</span>
                                                        <span class="profile-ud-value">
                                                            {{ str_replace("upto", "upto ", $package[1]) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Payment Status</span>
                                                        <span class="profile-ud-value">
                                                            @if ($dealer->payment_status == 'paid')
                                                            <span class="badge badge-lg bg-outline-success">Paid</span>
                                                            @else
                                                            <span class="badge badge-lg bg-outline-danger">Unpaid</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Stripe Payment Intent</span>
                                                        <span class="profile-ud-value">
                                                            {{ $dealer->payment_intent }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Payment Date</span>
                                                        <span class="profile-ud-value">{{
                                                            \Carbon\Carbon::parse($dealer->payment_date)->format('h:i a
                                                            -
                                                            jS M, Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Subscription End Date</span>
                                                        <span class="profile-ud-value">{{
                                                            \Carbon\Carbon::parse($dealer->subscription_end)->format('h:i
                                                            a -
                                                            jS M, Y') }}</span>
                                                    </div>
                                                </div>

                                            </div><!-- .card-inner -->
                                        </div>
                                        @else
                                        <p >This dealer has not purchased any package yet. <a href="javascript:;" class="text-success" data-bs-toggle="modal" data-bs-target="#giveawayModal">Giveaway Subscription! </a></p>
                                        @endif
                                    </div><!-- .card-content -->
                                </div>

                            </div>
                        </div>
                    </div><!-- .card -->
                </div><!-- .nk-block -->

                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title fw-normal">Dealer Listing</h3>
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div>

                {{-- <div class="components-preview  mx-auto"> --}}
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Brand</th>
                                            <th>Model</th>
                                            <th>Type</th>
                                            <th>Seats</th>
                                            <th>Cost/Day <small>(&euro;)</small></th>
                                            <th>Status</th>
                                            <th>Is Active?</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $dealer->cars as $car )
                                        <tr id="rowID-{{ $car->id }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$car->brand->name }}</td>
                                            <td>{{$car->model->name ?? "" }}</td>
                                            <td>{{$car->type }}</td>
                                            <td>{{num2word($car->seats) }}</td>
                                            <td>{{$car->cost_per_day }}</td>
                                            <td>
                                                @if ($car->status == '1')
                                                <span class="badge bg-outline-success">Available</span>
                                                @elseif ($car->status == '2')
                                                <span class="badge bg-outline-info">On Rent</span>
                                                @else
                                                <span class="badge bg-outline-danger">Not Available</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($car->is_active)
                                                <span class="badge bg-outline-success">Active</span>
                                                @else
                                                <span class="badge bg-outline-warning">Not Active</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-info btn-sm"
                                                    href='{{ route("admin.cars.show", $car->id) }}'><em
                                                        class="icon ni ni-eye"></em></a>
                                                    <a class="btn btn-danger btn-sm remove" data-id="{{ $car->id }}" data-href="{{ route("admin.cars.destroy", $car->id) }}"><em class="icon ni ni-trash"></em></a>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div> <!-- nk-block -->
                {{-- </div><!-- .components-preview --> --}}

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="giveawayModal" tabindex="-1" role="dialog" aria-labelledby="giveawayModalLabel"
    aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Package Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="giveawayForm">
            <div class="modal-body">
                    <div class="row g-gs">
                        <div class="col-lg-6">
                            <div class="login-input">
                                <label for="">Duration</label>
                                <select  name="package[duration]" id="package-duration" class="form-select" required>
                                    <option value="" selected hidden>Select Duration *</option>
                                    @foreach ($packages as $key => $package)
                                    <option value="{{ $key }}">{{ $key }} {{ $key == 1 ? 'Month' : 'Months' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="login-input">
                                <label for="">Select Package</label>
                                <select name="package[range]" id="package-range"  class="form-select" required disabled>
                                    <option value="" selected hidden>Select Package *</option>
                                </select>
                            </div>
                        </div>
                    </div>
                      <div class="col-lg-12">
                        <div class="login-input pt-2">
                                <div id="package-total">
                                    <label for="">Amount:</label>
                                    <span>&euro;</span><span id="amount">0.00</span> <small id="vat-text">(21% VAT Inclusive)</small>
                                    <input type="hidden" name="amount">
                                </div>
                            </div>
                            </div>
                    <input type="hidden" name="dealer_id" value="{{ $dealer->id }}">
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
    let packages = @json($packages);
         $('#package-duration').on('change', function() {
            let duration = $(this).val();
            var ranges = packages[duration];
            var options = [];

            Object.keys(ranges).forEach(function(range) {
                var option = {
                    value: range,
                    text: range.replace(/(^[a-z])/, (match) => match.toUpperCase()).replace(/(\d)/, ' $1')
                };
                options.push(option);
            });
            $('#package-range').html('')
            options.forEach(function(option) {
                $('#package-range').append($('<option>', option));
            });

            let range = $('#package-range').val();
            if (range == '') {
                range = $('#package-range option:eq(1)').val();
                // range = 'upto5';
                $('#package-range').val(range);
            }
            $('#package-range').prop('disabled', false);
            amount = parseFloat(packages[duration][range]);
            amount = parseFloat(amount + (amount * 0.21)).toFixed(2)
            $('[name="amount"]').val(amount)
            $('#package-total #amount').text(amount)
        })

        $('#package-range').on('change', function() {
            let range = $(this).val();
            let duration = $('#package-duration').val();
            if (duration == '') {
                duration = $('#package-duration option:eq(1)').val();
                // duration = '1';
                $('#package-range').val(duration);
            }
            amount = parseFloat(packages[duration][range]);
            amount = parseFloat(amount + (amount * 0.21)).toFixed(2)
            $('[name="amount"]').val(amount)
            $('#package-total #amount').text(amount)
        })

        $(document).on('click', '.btn-submit', function(e){
            e.preventDefault();
            form = $('#giveawayForm');
            duration = form.find('[name="package[duration]').val()
            range = form.find('[name="package[range]').val()
            amount = form.find('[name="amount"]').val()
            dealer_id = form.find('[name="dealer_id"]').val()
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                showCancelButton: true,
                confirmButtonText: `Yes`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    url: "{{ route('admin.dealer.giveaway-subscription') }}",
                    data: {duration, range, amount, dealer_id},
                    success: function(data) {
                        if(data.status == 200){
                            location.reload()
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
                title: 'Do you really want to unblock this dealer?',
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
                            location.reload()
                        }
                    },
                    error: function(error) {
                        alert('Something went wrong!')
                    }
                });
                }
            });
        });
        $(document).on('click', '.block', function(e){
            e.preventDefault();
            $url = $(this).data('href');
            Swal.fire({
                icon: 'warning',
                input: "text",
                title: 'Do you really want to block this dealer? This will also hide all the listed cars for the dealer.',
                text: "Blocking Reason? (Optional)",
                showCancelButton: true,
                confirmButtonColor: '#e85347',
                cancelButtonColor: '#1ee0ac',
                confirmButtonText: `Block`,
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

        $(document).on('click', '.remove', function(e){
            e.preventDefault();
            $url = $(this).data('href');
            Swal.fire({
                icon: 'warning',
                title: 'Are you sure?',
                showCancelButton: true,
                confirmButtonColor: '#e85347',
                cancelButtonColor: '#1ee0ac',
                confirmButtonText: `Remove`,
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                    type: "DELETE",
                    url: $url,
                    data: {
                        _token: '{{csrf_token()}}',
                    },
                    success: function(data) {
                        NioApp.DataTable.init();
                        // if(data.status == 200){
                        //     Swal.fire("", data.message,"success");
                        // }
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
