@extends('admin.layouts.dashboard')
@push('styles')
    <style>
        .nk-sale-data{
            display: flex;
            flex-shrink: 0;
            gap: 30px;
        }
    </style>
@endpush
@section('content')
  <!-- content @s -->
  <div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Overview</h3>
                            {{-- <div class="nk-block-des text-soft">
                                <p>Welcome to DashLite Dashboard Template.</p>
                            </div> --}}
                        </div><!-- .nk-block-head-content -->
                        <div class="nk-block-head-content">
                            {{-- <div class="toggle-wrap nk-block-tools-toggle">
                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                <div class="toggle-expand-content" data-content="pageMenu">
                                    <ul class="nk-block-tools g-3">
                                        <li>
                                            <div class="dropdown">
                                                <a href="#" class="dropdown-toggle btn btn-white btn-dim btn-outline-light" data-bs-toggle="dropdown"><em class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span class="d-none d-md-inline">Last</span> 30 Days</span><em class="dd-indc icon ni ni-chevron-right"></em></a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <ul class="link-list-opt no-bdr">
                                                        <li><a href="#"><span>Last 30 Days</span></a></li>
                                                        <li><a href="#"><span>Last 6 Months</span></a></li>
                                                        <li><a href="#"><span>Last 1 Years</span></a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                    </ul>
                                </div>
                            </div> --}}
                        </div><!-- .nk-block-head-content -->
                    </div><!-- .nk-block-between -->
                </div><!-- .nk-block-head -->
                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-xxl-6">
                            <div class="row g-gs">
                                {{-- <div class="col-lg-6 col-xxl-12">
                                    <div class="card card-bordered">
                                        <div class="card-inner">
                                            <div class="card-title-group align-start mb-2">
                                                <div class="card-title">
                                                    <h6 class="title">Sales Revenue</h6>
                                                    <p>In last 30 days revenue from subscription.</p>
                                                </div>
                                                <div class="card-tools">
                                                    <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Revenue from subscription"></em>
                                                </div>
                                            </div>
                                            <div class="align-end gy-3 gx-5 flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                                <div class="nk-sale-data-group flex-md-nowrap g-4">
                                                    <div class="nk-sale-data">
                                                        <span class="amount">14,299.59 <span class="change down text-danger"><em class="icon ni ni-arrow-long-down"></em>16.93%</span></span>
                                                        <span class="sub-title">This Month</span>
                                                    </div>
                                                    <div class="nk-sale-data">
                                                        <span class="amount">7,299.59 <span class="change up text-success"><em class="icon ni ni-arrow-long-up"></em>4.26%</span></span>
                                                        <span class="sub-title">This Week</span>
                                                    </div>
                                                </div>
                                                <div class="nk-sales-ck sales-revenue">
                                                    <canvas class="sales-bar-chart" id="salesRevenue"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- .col --> --}}
                                {{-- <div class="col-lg-3 col-xxl-12">
                                    <div class="row g-gs">
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">Total Earnings</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Total earnings from subscriptions to date."></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount">&euro; {{ $totalEarnings }}</span>
                                                            <span class="sub-title">
                                                        </div>
                                                        <div class="nk-sales-ck">
                                                            <canvas class="sales-bar-chart" id="activeSubscription"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .col --> --}}
                                {{-- <div class="col-lg-3 col-xxl-12">
                                    <div class="row g-gs">
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">Subscriptions</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Total active/expired subscriptions"></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount">{{ $activeSubs }}  <span class="sub-title">Active</span></span>
                                                            <span class="amount">{{ $expiredSubs }} <span class="sub-title">Expired</span></span>
                                                        </div>
                                                        <div class="nk-sales-ck">
                                                            <canvas class="sales-bar-chart" id="activeSubscription"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .col --> --}}
                                {{-- <div class="col-lg-3 col-xxl-12">
                                    <div class="row g-gs">
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">Cars Listed</h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Total cars listed to date"></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount">{{ $listedCars }} <span class="sub-title">Total</span> / {{ $onRentCars }} <span class="sub-title">On Rent</span> </span>
                                                            <span class="sub-title">
                                                        </div>
                                                        <div class="nk-sales-ck">
                                                            <canvas class="sales-bar-chart" id="activeSubscription"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .col --> --}}
                                {{-- <div class="col-lg-3 col-xxl-12">
                                    <div class="row g-gs">
                                        <div class="col-sm-6 col-lg-12 col-xxl-6">
                                            <div class="card card-bordered">
                                                <div class="card-inner">
                                                    <div class="card-title-group align-start mb-2">
                                                        <div class="card-title">
                                                            <h6 class="title">Visits <small>(Total/Unique)</small></h6>
                                                        </div>
                                                        <div class="card-tools">
                                                            <em class="card-hint icon ni ni-help-fill" data-bs-toggle="tooltip" data-bs-placement="left" title="Number of website visits"></em>
                                                        </div>
                                                    </div>
                                                    <div class="align-end flex-sm-wrap g-4 flex-md-nowrap">
                                                        <div class="nk-sale-data">
                                                            <span class="amount">{{ $total_vists }} / {{ $unique_visits }}</span>
                                                            <span class="sub-title">
                                                        </div>
                                                        <div class="nk-sales-ck">
                                                            <canvas class="sales-bar-chart" id="activeSubscription"></canvas>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div><!-- .card -->
                                        </div><!-- .col -->
                                    </div><!-- .row -->
                                </div><!-- .col --> --}}
                            </div><!-- .row -->
                        </div><!-- .col -->
                        {{-- <div class="col-xxl-8">
                            <div class="card card-bordered card-full">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title"><span class="me-2">Transaction</span> <a href="#" class="link d-none d-sm-inline">See History</a></h6>
                                        </div>
                                        <div class="card-tools">
                                            <ul class="card-tools-nav">
                                                <li><a href="#"><span>Paid</span></a></li>
                                                <li><a href="#"><span>Pending</span></a></li>
                                                <li class="active"><a href="#"><span>All</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-inner p-0 border-top">
                                    <div class="nk-tb-list nk-tb-orders">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span>Order No.</span></div>
                                            <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                                            <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                                            <div class="nk-tb-col tb-col-lg"><span>Ref</span></div>
                                            <div class="nk-tb-col"><span>Amount</span></div>
                                            <div class="nk-tb-col"><span class="d-none d-sm-inline">Status</span></div>
                                            <div class="nk-tb-col"><span>&nbsp;</span></div>
                                        </div>
                                        <div class="nk-tb-item">
                                            <div class="nk-tb-col">
                                                <span class="tb-lead"><a href="#">#95954</a></span>
                                            </div>
                                            <div class="nk-tb-col tb-col-sm">
                                                <div class="user-card">
                                                    <div class="user-avatar user-avatar-sm bg-purple">
                                                        <span>AB</span>
                                                    </div>
                                                    <div class="user-name">
                                                        <span class="tb-lead">Abu Bin Ishtiyak</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">02/11/2020</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-lg">
                                                <span class="tb-sub text-primary">SUB-2309232</span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="tb-sub tb-amount">4,596.75 <span>USD</span></span>
                                            </div>
                                            <div class="nk-tb-col">
                                                <span class="badge badge-dot badge-dot-xs bg-success">Paid</span>
                                            </div>
                                            <div class="nk-tb-col nk-tb-col-action">
                                                <div class="dropdown">
                                                    <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                        <ul class="link-list-plain">
                                                            <li><a href="#">View</a></li>
                                                            <li><a href="#">Invoice</a></li>
                                                            <li><a href="#">Print</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-inner-sm border-top text-center d-sm-none">
                                    <a href="#" class="btn btn-link btn-block">See History</a>
                                </div>
                            </div><!-- .card -->
                        </div><!-- .col --> --}}
                        {{-- <div class="col-lg-6 col-xxl-4">
                            <div class="card card-bordered h-100">
                                <div class="card-inner border-bottom">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Support Messages</h6>
                                        </div>
                                        <div class="card-tools">
                                            <a href="{{ route('admin.contact.showQueriesList') }}" class="link">View All</a>
                                        </div>
                                    </div>
                                </div>
                                <ul class="nk-support">
                                    @foreach ($supportMsgs as $message )
                                    <li class="nk-support-item">
                                        <div class="user-avatar">
                                            <img loading="lazy" src="./images/avatar/a-sm.jpg" alt="">
                                        </div>
                                        <div class="nk-support-content">
                                            <div class="title">
                                                <span>{{ $message->name }}</span>
                                            </div>
                                            <p><strong>Subject:</strong> {{ $message->subject }}</p>
                                            <span class="time">{{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</span>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div><!-- .card -->
                        </div><!-- .col --> --}}

                    </div><!-- .row -->
                </div><!-- .nk-block -->
            </div>
        </div>
    </div>
</div>
<!-- content @e -->
@endsection
