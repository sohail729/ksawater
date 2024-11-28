@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Contact US Queries</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active">Contact US Queries</li>
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
                                <table class="datatable-init table">
                                    <thead>
                                        <tr>
                                            <th width="18%">Date</th>
                                            <th width="20%">Email</th>
                                            <th width="20%">Subject</th>
                                            <th>Message</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $queries as $query )
                                        <tr id="rowID-{{ $loop->index }}">
                                            <td>{{ \Carbon\Carbon::parse($query->created_at)->format('jS M, Y - H:i a') }}</td>
                                            <td>{{$query->email }}</td>
                                            <td>{{$query->subject }}</td>
                                            <td>{!! $query->message !!}</td>
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

</script>
@endpush
