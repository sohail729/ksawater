@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Dealers</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active">Dealers</li>
                                </ul>
                            </nav>
                        </div><!-- .nk-block-head-content -->

                    </div><!-- .nk-block-between -->
                </div>

                <!-- main alert @s -->
                @include('admin.partials.alerts')
                <!-- main alert @e -->

                <div class="components-preview  mx-auto">
                    <div class="nk-block nk-block-lg">
                        <div class="card card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Full Name</th>
                                            <th>Email</th>
                                            <th>City</th>
                                            <th>Status</th>
                                            <th>Is Verified?</th>
                                            <th>Is Featured?</th>
                                            <th>Registered On</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $dealers as $dealer )
                                        <tr id="rowID-{{ $dealer->id }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$dealer->fullname }}
                                                @if (!empty($dealer->block_reason))
                                                <p><span class="badge badge-xs bg-warning">Blocked - {{ $dealer->block_reason }}</span></p>
                                                @endif
                                            </td>
                                            <td>{{$dealer->email }}</td>
                                            <td>{{ ucfirst($dealer->detail->city)}}</td>
                                            <td>
                                                @unless ($dealer->status == 2)
                                                @if ($dealer->payment_status == 'paid')
                                                <span class="badge badge-sm bg-outline-success">Paid</span>
                                                @else
                                                <span class="badge badge-sm bg-outline-danger">Upaid</span>
                                                @endif
                                                @else
                                                <span class="badge badge-sm bg-warning">EXPIRED</span>
                                                @endunless

                                            <td>
                                                @if ($dealer->is_verified)
                                                <span class="badge badge-sm bg-outline-success">Yes</span>
                                                @else
                                                <span class="badge badge-sm bg-outline-warning">No</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($dealer->is_featured)
                                                <span class="badge badge-sm bg-outline-success">Yes</span>
                                                @else
                                                <span class="badge badge-sm bg-outline-warning">No</span>
                                                @endif
                                            </td>
                                            <td title="{{ \Carbon\Carbon::parse($dealer->created_at)->format('jS M, Y - H:i a') }}">{{ \Carbon\Carbon::parse($dealer->created_at)->format('jS M, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a title="View Dealer Details" class="btn btn-info btn-sm"
                                                    href='{{ route("admin.dealer.show", $dealer->id) }}'><em
                                                        class="icon ni ni-eye"></em></a>
                                                    <a title="View Subscription History" class="btn btn-warning btn-sm"
                                                    href='{{ route("admin.subscriptions.index", $dealer->id) }}'><em
                                                        class="icon ni ni-history"></em></a>
                                                </div>
                                            </td>

                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div> <!-- nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>

@endsection
