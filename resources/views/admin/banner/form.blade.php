@extends('admin.layouts.dashboard')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.min.css"/>
@endpush
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Advertisement Banner</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="">Advertisement Banner</a></li>
                                    <!-- <li class="breadcrumb-item active"></li> -->
                                </ul>
                            </nav>
                        </div>
                    </div><!-- .nk-block-head -->

                    <!-- main alert @s -->
                    @include('admin.partials.alerts')

                    {{-- {{ dd($banner->position) }} --}}

                    <!-- main alert @e -->

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                @if (empty($banner))
                                <form action="{{ route('admin.banner.store') }}" method="POST" enctype="multipart/form-data">
                                @else
                                <form action="{{ route('admin.banner.update', ['banner' => $banner->id]) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @endif
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Title <small>(Optional)</small></label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="title" id="title" class="form-control" placeholder="Banner Title" value="{{ $banner->title ?? ''}}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Status *</label>
                                            <select class="select2" name="status" id="status" data-placeholder="-- Select Status --" required>
                                                <option value="1" {{ !empty($banner) && $banner->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="2" {{ !empty($banner) && $banner->status == 2 ? 'selected' : '' }}>Inactive</option>
                                               </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <label class="form-label" for="fv-full-name">Image *</label>
                                                <div class="form-file">
                                                    <input type="file" class="form-file-input" id="image" name="image" accept="image/*">
                                                    <label class="form-file-label" for="image">Choose Image</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!empty($banner))
                                    <div class="col-md-12">
                                        <h5>Preview</h5>
                                        <div class="container">
                                            <img class="img-fluid" src="{{  $banner->image }}" alt="">
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value={{ !empty($banner) ? "Update" : "Save" }}>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript">

$(document).ready(function () {
        $('#end_date').datetimepicker();

        $('.select2').select2({
            placeholder: function () {
                $(this).data('placeholder');
            },
        });
    });

</script>
@endpush
