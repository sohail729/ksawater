@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Brands</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active">Brands</li>
                                </ul>
                            </nav>
                        </div><!-- .nk-block-head-content -->

                    </div><!-- .nk-block-between -->
                </div>

                <!-- main alert @s -->
                @include('partials.alerts')
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
                                            <th>Is Top? <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" title="Visibilty on Popular Brands on Homepage"><em class="icon ni ni-info"></em></a> </th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $brands as $brand )
                                        <tr id="rowID-{{ $brand->id }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$brand->name }}</td>
                                            <td>
                                                @if ($brand->is_top)
                                                <span class="badge bg-success">Yes</span>
                                                @else
                                                <span class="badge bg-warning">No</span>
                                                @endif
                                            </td>
                                            <td><a title="{{$brand->description}}" href="{{!empty($brand->logo) ? $brand->logo : asset('images/car-placeholder-1.png')}}" target="_blank"><img loading="lazy"  style="width: 50px;height: auto;" class="img-thumbnail" src="{{!empty($brand->logo) ? $brand->logo : asset('images/car-placeholder-1.png')}}" alt=""></a></td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-info btn-sm"
                                                    href='{{ route("admin.brands.edit", $brand->id) }}'><em
                                                        class="icon ni ni-edit"></em></a>
                                                    <a class="btn btn-danger btn-sm delete" href='javascript:void(0)' data-id='{{ $brand->id }}' data-route='{{ route("admin.brands.destroy", $brand->id) }}'><em class="icon ni ni-trash"></em></a>
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
