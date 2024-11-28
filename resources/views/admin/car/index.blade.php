@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Cars</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active">Cars</li>
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
                                            <th>Dealer</th>
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
                                        @foreach( $cars as $car )
                                        <tr id="rowID-{{ $car->id }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>
                                                @if (!empty($car->user))
                                                {{$car->user->username }} <a href="{{ route('admin.dealer.show', $car->user->id) }}" target="_blank" title="Visit Profile"><em class="icon ni ni-external"></em></a>
                                                @else
                                                -
                                                @endif
                                            </td>
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

