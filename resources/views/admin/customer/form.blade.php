@extends('admin.layouts.dashboard')

@push('styles')
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.min.css" />
@endpush
@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="components-preview mx-auto">
                    <div class="nk-block-head nk-block-head-lg wide-sm">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Customers</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{
                                            env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('admin.customers.index') }}">Customers</a></li>
                                    <li class="breadcrumb-item active">Form</li>
                                    <!-- <li class="breadcrumb-item active"></li> -->
                                </ul>
                            </nav>
                        </div>
                    </div><!-- .nk-block-head -->

                    <!-- main alert @s -->
                    @include('admin.partials.alerts')

                    {{-- {{ dd($user->position) }} --}}

                    <!-- main alert @e -->

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                @if (empty($user))
                                <form action="{{ route('admin.customers.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @else
                                    <form action="{{ route('admin.customers.update', ['user' => $user->id]) }}"
                                        method="post" enctype="multipart/form-data">
                                        @method('put')
                                        @endif
                                        @csrf
                                        <div class="row g-gs">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">Fullname *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" name="fullname" id="fullname"
                                                            class="form-control" placeholder="Fullname"
                                                            value="{{ $user->fullname ?? ''}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">Email *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="email" name="email" id="email" class="form-control"
                                                            placeholder="Email" value="{{ $user->email ?? ''}}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">Phone *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="tel" name="phone" id="phone" class="form-control"
                                                            placeholder="Phone" value="{{ $user->phone ?? ''}}"
                                                            disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">Status *</label>
                                                    <select class="form-select" name="status" id="status"
                                                        data-placeholder="-- Select Status --" required>
                                                        <option value="1" {{ !empty($user) && $user->status == 1 ?
                                                            'selected' : '' }}>Active</option>
                                                        <option value="0" {{ !empty($user) && $user->status == 0 ?
                                                            'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">Postal *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" name="postal" id="postal"
                                                            class="form-control" placeholder="Postal"
                                                            value="{{ $user->postal ?? ''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">Address *</label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" name="address" id="address"
                                                            class="form-control" placeholder="Address"
                                                            value="{{ $user->address ?? ''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label" for="fv-full-name">Block Reason
                                                        <small>(Required if status is inactive)</small></label>
                                                    <div class="form-control-wrap">
                                                        <input type="text" name="block_reason" id="block_reason"
                                                            class="form-control" placeholder="Block Reason"
                                                            value="{{ $user->block_reason ?? ''}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <input type="submit" class="btn btn-success" value={{ !empty($user)
                                                        ? "Update" : "Save" }}>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        <div class="card card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table" id="ordersdatatable">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Order Time</th>
                                            <th>Delivery Address</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div><!-- .nk-block -->
                </div><!-- .components-preview -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="orderView" tabindex="-1" role="dialog" aria-labelledby="orderViewLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>

        </div>
    </div>
</div>
@endsection
@push('scripts')
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.9/jquery.datetimepicker.full.min.js">
</script> --}}
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


    $(document).ready(function() {
        if ($.fn.DataTable.isDataTable('#ordersdatatable')) {
            $('#ordersdatatable').DataTable().destroy();
        }
        $('#ordersdatatable').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: "{{ route('admin.orders.list.get') }}",
                type: 'post',
                dataSrc: function(response) {
                    return response.data;
                },
                error: function(xhr, error, thrown) {
                    console.error(xhr.responseText);
                }
            },
            columns: [
                { data: 'order_number',
                    render: function (data, type, row) {
                        return `<a href="javascript:void(0)" title="Copy Order Number" class="order-number-span"><span class="badge badge-xs badge-dim bg-outline-secondary d-none d-md-inline-flex">${data}</span></a>`;
                    }
                },
                { data: 'order_time'},
                { data: 'address'},
                {
                    data: 'status',
                    render: function (data, type, row) {
                    if (data == 'pending')
                    $html = `<span class="badge badge-xs badge-dim bg-outline-warning d-none d-md-inline-flex">PENDING</span>`;
                    else if (data == 'accepted')
                    $html = `<span class="badge badge-xs badge-dim bg-outline-secondary d-none d-md-inline-flex">ACCEPTED</span>`;
                    else if (data == 'pickedup')
                    $html = ` <span class="badge badge-xs badge-dim bg-outline-primary d-none d-md-inline-flex">PICKED UP</span>`;
                    else if (data == 'delivered')
                    $html =  `<span class="badge badge-xs badge-dim bg-outline-success d-none d-md-inline-flex">DELIVERED</span>`;
                    else
                    $html =  `<span class="badge badge-xs badge-dim bg-outline-danger d-none d-md-inline-flex">DELIVERED</span>`;
                    return $html;
                    }
                },
                {
                    data: 'amount',
                    render: function (data, type, row) {
                        return 'SAR ' + data
                    }
                },
                {
                render: function (data, type, row) {
                    $html = `<div class="btn-group" role="group" aria-label="Basic example">`;
                    $html += `<a title="View Order Details" class="btn btn-warning btn-sm" onclick="orderView(${row.id})"><em class="icon ni ni-eye"></em></a>`;
                    $html += `</div>`;
                    return $html;
                }
            },
            ]

        });
    });

    $(document).on('click', '.order-number-span', function () {
        var titleValue = $(this).find('span').text();
        var tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(titleValue);
        tempInput.select();
        document.execCommand('copy');
        tempInput.remove();
        alert("Order Number " + titleValue+ " Copied!" );
    });






//         $('#end_date').datetimepicker();
//         $('.select2').select2({
//             placeholder: function () {
//                 $(this).data('placeholder');
//             },
//         });
    });
    function orderView(id){
        let url = "{{ route('admin.orders.view', ['orderID' => '__orderID__']) }}";
        url = url.replace('__orderID__', id);
        $.ajax({
            url: url,
            success: function(response) {
                if(response.status == 200){
                    $('#orderView').find('.modal-content .modal-body').html(response.content)
                    $('#orderView').modal('show');
                }
            },
            error: function(xhr, error, thrown) {
                console.error(xhr.responseText);
            },
            complete: function(){
            }
        })
    }
</script>
@endpush
