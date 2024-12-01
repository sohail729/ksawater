@extends('admin.layouts.dashboard')
@push('styles')
<style>
    .card .table tr:first-child td{
        align-content: center;
    }
</style>

@endpush

@section('content')
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Order</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active">Order</li>
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
                                <table class="datatable-init nowrap table" id="ordersdatatable">
                                    <thead>
                                        <tr>
                                            <th>Order ID</th>
                                            <th>Order Time</th>
                                            <th>Customer</th>
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
                    </div> <!-- nk-block -->
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>

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
                {
                    data: 'fullname',
                    render: function (data, type, row) {
                        $html = row.fullname + '<br>' + row.phone
                        return $html;
                    }
                    },
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
