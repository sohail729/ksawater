@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Products</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url("/") }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.product.index') }}">Products</a></li>
                                    <li class="breadcrumb-item active">Form</li>
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
                                @if (empty($product))
                                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                                @else
                                <form action="{{ route('admin.product.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @endif
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Title *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="title" id="title" class="form-control" placeholder="E.g Nova Water, 330mL Case, 40 Bottles" value="{{ $product->title ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Category *</label>
                                            <div class="form-control-wrap">
                                               <select class="select2" name="category_id" id="category_id" data-placeholder="-- Select Category --" required>
                                                <option value="" selected hidden></option>
                                                @foreach ($categories as $category)
                                                @if (empty($product))
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                                @else
                                                <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                                <textarea class="form-control" name="description" id="description"  rows="2" placeholder="Product Description">{{ $product->description ?? ''}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Price * <small>(In SAR)</small></label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="price" id="price" class="form-control" placeholder="E.g 15" value="{{ $product->price ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Volume *</label>
                                            <div class="form-control-wrap">
                                                <input type="number" name="volume" id="volume" class="form-control" placeholder="E.g 300" value="{{ $product->volume ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Unit *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="unit" id="unit" class="form-control" placeholder="E.g ml or L" value="{{ $product->unit ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Bundle Qty *</label>
                                            <div class="form-control-wrap">
                                                <input type="number" min="1" name="bundle" id="bundle" class="form-control" placeholder="E.g 12" value="{{ $product->bundle ?? ""}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <label class="form-label" for="fv-full-name">Image <small>(Optional)</small></label>
                                                <div class="form-file">
                                                    <input type="file" class="form-file-input" id="image" name="image" accept="image/*">
                                                    <label class="form-file-label" for="image">Choose Image</label>
                                                </div>
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
