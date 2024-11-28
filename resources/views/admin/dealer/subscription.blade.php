@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Subscriptions</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dealer.index') }}">Dealers</a></li>
                                    <li class="breadcrumb-item active">Subscriptions</li>
                                </ul>
                            </nav>
                            <div class="nk-block-des text-soft mt-3">
                                <ul>
                                    <li>Username: <span class="text-base">{{ $dealer->username }}</span></li>
                                    <li>Email Address: <span class="text-base">{{ $dealer->email }}</span></li>
                                </ul>
                            </div>
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
                                            <th>Package</th>
                                            <th title="VAT inclusive">Amount <small>(&euro; + VAT)</small></th>
                                            <th>Payment Date</th>
                                            <th>Subscription End</th>
                                            <th>Stripe Payment Intent</th>
                                            <th>Payment Link</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $subscriptions as $subscription )
                                        @php
                                            $package = explode('/', $subscription->package);
                                        @endphp
                                        <tr id="rowID-{{ $subscription->id }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>  {{ $package[0] }}
                                                @if ($package[0] == '1')
                                                    Month
                                                @else
                                                    Months
                                                @endif
                                                /
                                                {{ str_replace("upto", "upto ", $package[1]) }}
                                            </td>
                                            <td>{{  $subscription->paid_amount ?? 'N/a' }}</td>
                                            <td>
                                                @if ($subscription->payment_date)
                                                {{ \Carbon\Carbon::parse($subscription->payment_date)->format('h:i a - jS M, Y') }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($subscription->subscription_end)
                                                {{ \Carbon\Carbon::parse($subscription->subscription_end)->format('h:i a - jS M, Y') }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($subscription->payment_intent))
                                                <a href="javascript:;" class="copy-paylink" title="{{$subscription->payment_intent}}"><em class="icon ni ni-copy"></em> Copy Payment ID</a>
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($subscription->payment_link))
                                                <a href="javascript:;" class="copy-paylink" title="{{$subscription->payment_link}}"><em class="icon ni ni-copy"></em> Copy Link</a>
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @if ($subscription->status == 'paid')
                                                <span class="badge badge-sm bg-outline-success">Paid</span>
                                                @elseif($subscription->status == 'giveaway')
                                                <span class="badge badge-sm bg-info">Giveaway</span>
                                                @else
                                                <span class="badge badge-sm bg-outline-danger">Unpaid</span>
                                                @endif
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

    $(document).ready(function () {
        $('.copy-paylink').on('click', function () {
            var titleValue = $(this).attr('title');
            var tempInput = $('<input>');
            $('body').append(tempInput);
            tempInput.val(titleValue);
            tempInput.select();
            document.execCommand('copy');
            tempInput.remove();
            alert(titleValue);
        });
    });
</script>
@endpush
