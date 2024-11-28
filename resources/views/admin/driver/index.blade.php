@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Drivers</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active"><a href="{{ route('admin.drivers.index') }}">Drivers</a></li>
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
                                            <th>Name</th>
                                            <th>Phone</th>
                                            <th>Availability</th>
                                            <th>Status</th>
                                            <th>National Id</th>
                                            <th>Vehicle Number</th>
                                            <th>License Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $drivers as $driver )
                                        <tr id="rowID-{{ $driver->id }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$driver->fullname }}</td>
                                            <td>{{$driver->phone }}</td>
                                            <td>{{$driver->availability }}</td>
                                            <td>
                                                @if ($driver->status == 1)
                                                <span class="badge bg-success">Active</span>
                                                @else
                                                <span class="badge bg-warning">Inactive</span>
                                                @endif
                                            </td>
                                            <td>{{$driver->national_id }}</td>
                                            <td>{{$driver->vehicle_number }}</td>
                                            <td>{{$driver->license_number }}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-info btn-sm"
                                                    href='{{ route("admin.drivers.edit", $driver->id) }}'><em
                                                        class="icon ni ni-edit"></em></a>
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
@push('scripts')
    <script>

    </script>
@endpush
