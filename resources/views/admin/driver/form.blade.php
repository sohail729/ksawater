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
                            <h2 class="nk-block-title fw-normal">Drivers</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.drivers.index') }}">Drivers</a></li>
                                    <li class="breadcrumb-item active">Form</li>
                                    <!-- <li class="breadcrumb-item active"></li> -->
                                </ul>
                            </nav>
                        </div>
                    </div><!-- .nk-block-head -->

                    <!-- main alert @s -->
                    @include('admin.partials.alerts')

                    {{-- {{ dd($driver->position) }} --}}

                    <!-- main alert @e -->

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                @if (empty($driver))
                                <form action="{{ route('admin.drivers.store') }}" method="POST" enctype="multipart/form-data">
                                @else
                                <form action="{{ route('admin.drivers.update', ['driver' => $driver->id]) }}" method="post" enctype="multipart/form-data">
                                @method('put')
                                @endif
                                @csrf
                                <div class="row g-gs">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Fullname *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="fullname" id="fullname" class="form-control" placeholder="Fullname" value="{{ $driver->fullname ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Phone *</label>
                                            <div class="form-control-wrap">
                                                <input type="tel" name="phone" id="phone" class="form-control" placeholder="Phone" value="{{ $driver->phone ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Email *</label>
                                            <div class="form-control-wrap">
                                                <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $driver->email ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Password *</label>
                                            <div class="btn-group float-end" role="group">
                                                <a href="javascript:void(0)" class="btn btn-xs btn-info copy-pass" title="Copy Password to Clipboard">
                                                    <em class="icon ni ni-copy-fill"></em>
                                                </a>
                                                <a href="javascript:void(0)" class="btn btn-xs btn-success" id="generatePassword" title="Generate Random Password">
                                                    <em class="icon ni ni-repeat-v"></em>
                                                </a>
                                            </div>
                                            <div class="form-control-wrap">
                                                <input type="text" name="password" id="password" class="form-control" placeholder="Password" value="{{ $driver->password ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">National ID *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="national_id" id="national_id" class="form-control" placeholder="National ID" value="{{ $driver->national_id ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Vehicle Number *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="vehicle_number" id="vehicle_number" class="form-control" placeholder="Vehicle Number" value="{{ $driver->vehicle_number ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">License Number *</label>
                                            <div class="form-control-wrap">
                                                <input type="text" name="license_number" id="license_number" class="form-control" placeholder="License Number" value="{{ $driver->license_number ?? ''}}" required>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!empty($driver))
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="form-label" for="fv-full-name">Status *</label>
                                            <select class="form-select" name="status" id="status" data-placeholder="-- Select Status --" required>
                                                <option value="1" {{ !empty($driver) && $driver->status == 1 ? 'selected' : '' }}>Active</option>
                                                <option value="2" {{ !empty($driver) && $driver->status == 2 ? 'selected' : '' }}>Inactive</option>
                                               </select>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-success" value={{ !empty($driver) ? "Update" : "Save" }}>
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
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js"></script> --}}
<script type="text/javascript">

$(document).ready(function () {
    $('#generatePassword').on('click', function () {
        const password = generatePassword(8);
        $('[name="password"]').val(password);
    });

    function generatePassword(length) {
        const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let password = '';
        for (let i = 0; i < length; i++) {
            const randomIndex = Math.floor(Math.random() * characters.length);
            password += characters[randomIndex];
        }
        return password;
    }

    $('.copy-pass').on('click', function () {
        var titleValue = $(this).siblings('span').text();
        console.log(titleValue)
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(titleValue);
        tempInput.select();
        document.execCommand('copy');
        tempInput.remove();
        alert("Password Copied!");
    });

//         $('#end_date').datetimepicker();
//         $('.select2').select2({
//             placeholder: function () {
//                 $(this).data('placeholder');
//             },
//         });
    });

</script>
@endpush
