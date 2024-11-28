@extends('admin.layouts.dashboard')


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
                                            <div class="nk-block-head">
                                                <h5 class="title">Personal Information</h5>
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
                                        <div class="nk-block">
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
                                                            {{  str_replace("upto", "upto ", $package[1]) }}
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
                                                            \Carbon\Carbon::parse($dealer->payment_date)->format('h:i a -
                                                            jS M, Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="profile-ud-item">
                                                    <div class="profile-ud wider">
                                                        <span class="profile-ud-label">Subscription End Date</span>
                                                        <span class="profile-ud-value">{{
                                                            \Carbon\Carbon::parse($dealer->subscription_end)->format('h:i a -
                                                            jS M, Y') }}</span>
                                                    </div>
                                                </div>

                                            </div><!-- .card-inner -->
                                        </div>
                                    </div><!-- .card-content -->
                                </div>
                            </div>
                        </div>
                    </div><!-- .card -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
@endsection
