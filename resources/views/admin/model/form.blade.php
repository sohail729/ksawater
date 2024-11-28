@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Model</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url("/") }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="">Model</a></li>
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
                                @if (empty($model))
                                <form action="{{ route('admin.models.store') }}" method="POST" enctype="multipart/form-data">
                                @else
                                <form action="{{ route('admin.models.update', ['model' => $model->id]) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @endif
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Name *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Model Name" value="{{ $model->name ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Brand *</label>
                                            <div class="form-control-wrap">
                                               <select class="select2" name="brand_id" id="brand_id" data-placeholder="-- Select Brand --" required>
                                                <option value="" selected hidden></option>
                                                @foreach ($brands as $brand)
                                                @if (empty($model))
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                                @else
                                                <option value="{{ $brand->id }}" {{ $brand->id == $model->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                                @endif
                                                @endforeach
                                               </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Description <small>(Optional)</small></label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="description" id="description" class="form-control" placeholder="Model Description" value="{{ $model->description ?? ''}}">
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
