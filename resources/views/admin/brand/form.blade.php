@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Brands</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url("/") }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="">Brands</a></li>
                                    <!-- <li class="breadcrumb-item active"></li> -->
                                </ul>
                            </nav>
                        </div>
                    </div><!-- .nk-block-head -->

                    <!-- main alert @s -->
                    @include('admin.partials.alerts')

                    <!-- main alert @e -->

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                @if (empty($brand))
                                <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
                                @else
                                <form action="{{ route('admin.brands.update', ['brand' => $brand->id]) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @endif
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Name *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Brand Name" value="{{ $brand->name ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Is Top? <a href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-placement="top" title="Show on Popular Brands"><em class="icon ni ni-info"></em></a></label>
                                                <div class="preview-block">
                                                    <div class="custom-control  custom-control-lg custom-switch">
                                                        <input type="checkbox" name="is_top" class="custom-control-input" id="is_top" value="1" {{!empty($brand->is_top) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_top"></label>
                                                    </div>
                                                </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <label class="form-label" for="fv-full-name">Image</label>
                                                <div class="form-file">
                                                    <input type="file" class="form-file-input" id="logo" name="logo" accept="image/*">
                                                    <label class="form-file-label" for="logo">Choose Image</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Description <small>(Optional)</small></label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="description" id="description" class="form-control" placeholder="Brand Description" value="{{ $brand->description ?? ''}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value="Save">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        </div>
                    </div><!-- .nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
     $(document).ready(function () {
        $('.select2').select2({
            placeholder: function () {
                $(this).data('placeholder');
            },
        });
    });
</script>
@endpush