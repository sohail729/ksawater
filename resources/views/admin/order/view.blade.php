<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Order Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <p><strong>Order ID:</strong> {{ $order->order_number }}</p>
                <p><strong>Customer Name:</strong> {{ $order->fullname }}</p>
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
                <p><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Postal Code:</strong> {{ $order->postal }}</p>
                <p><strong>Order Time:</strong> {{ \Carbon\Carbon::parse($order->order_time)->format('h:i a d/M/Y')  }}</p>
                <p><strong>Delivery Instructions:</strong> <span class="text-danger">{{ $order->delivery_instructions }}</span></p>

            </div>
            <div class="col-md-6">
                <div class="d-flex align-items-center">
                    <strong class="me-1">Order Status: </strong>
                    @if ($order->status == 'pending')
                        <span class="badge badge-xs badge-dim bg-outline-warning d-none d-md-inline-flex">PENDING</span>
                    @elseif ($order->status == 'accepted')
                        <span class="badge badge-xs badge-dim bg-outline-secondary d-none d-md-inline-flex">ACCEPTED</span>
                    @elseif ($order->status == 'pickedup')
                        <span class="badge badge-xs badge-dim bg-outline-primary d-none d-md-inline-flex">PICKED UP</span>
                    @elseif ($order->status == 'delivered')
                        <span class="badge badge-xs badge-dim bg-outline-success d-none d-md-inline-flex">DELIVERED</span>
                        @else
                        <span class="badge badge-xs badge-dim bg-outline-danger d-none d-md-inline-flex">DELIVERED</span>
                    @endif

                    <div class="ms-3">
                        <div class="form-group">
                            <select class="form-select form-select-sm" name="status" id="status">
                                <option value="" selected hidden>-- Change Status --</option>
                                <option value="pending">PENDING</option>
                                <option value="accepted">ACCEPTED</option>
                                <option value="pickedup">PICKED UP</option>
                                <option value="delivered">DELIVERED</option>
                                <option value="rejected">REJECTED</option>
                            </select>
                        </div>
                    </div>
                </div>

                <p><strong>Total Amount:</strong> SAR {{ $order->amount }}</p>
                <p><strong>Delivery Address:</strong> {{ $order->address }}</p>
                <p><strong>Payment Status:</strong>
                    @if ($order->payment_status == 'paid')
                    <span class="badge badge-xs badge-dim bg-outline-success d-none d-md-inline-flex">PAID</span>
                    @else
                    <span class="badge badge-xs badge-dim bg-outline-warning d-none d-md-inline-flex">UNPAID</span>
                    @endif
                </p>
                @if (!empty($order->rider_name))
                <p><strong>Rider Name:</strong> {{ $order->rider_name ?? "" }}</p>
                <p><strong>Rider Phone:</strong> {{ $order->rider_phone ?? "" }}</p>
                @else
                <p><strong>Rider:</strong> Not Assigned</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Order Items Table -->
<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Order Items</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($order->detail as $orderDetail)
                    <tr>
                        <td>  {{ $loop->iteration }}  </td>
                        <td>  {{ $orderDetail->product_name }}  </td>
                        <td> {{ $orderDetail->qty }} </td>
                        <td>SAR {{ $orderDetail->price }} </td>
                        <td>SAR {{ $orderDetail->total }} </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light">
                    <tr>
                        <td colspan="4" class="text-end"><strong>Total Amount:</strong></td>
                        <td><strong>SAR {{ $order->amount }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Delivery Logs</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Status</th>
                        <th>Time</th>
                        <th>Action By</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->delivery_logs as $delivery_log)
                    <tr>
                        <td>  {{ $loop->iteration }}  </td>
                        <td>  {{ Str::ucfirst($delivery_log->status) }}  </td>
                        <td> {{ \Carbon\Carbon::parse($delivery_log->created_at)->format('h:i a d/M/Y')}} </td>
                        <td> {{ Str::ucfirst($delivery_log->rider->fullname) }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
