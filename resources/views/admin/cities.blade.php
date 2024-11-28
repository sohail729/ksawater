@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Cities</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active">Cities</li>
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
                                <table class="datatable-init nowrap table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $cities as $city )
                                        <tr id="rowID-{{ $loop->index }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$city->name }}</td>
                                            <td>
                                                @if ($city->status)
                                                <span class="badge badge-sm bg-outline-success">Enabled</span>
                                                @else
                                                <span class="badge badge-sm bg-outline-secondary">Disabled</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-info btn-xs toggle-update"
                                                    data-href='{{ route("admin.update.city", ['city_id' => $city->id]) }}' data-city="{{$city->name }}" data-status="{{$city->status }}"><em
                                                        class="icon ni ni-edit"></em></a>
                                                </div>
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
<div class="modal fade" id="updateCityModal" tabindex="-1" role="dialog" aria-labelledby="updateCityModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
           <form action="">
            <div class="modal-header">
                <h5 class="modal-title">Update City Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <div class="row g-gs">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Name *</label>
                            <div class="form-control-wrap">
                                <input type="text" name="cityName" id="cityName" class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Status</label>
                            <div class="preview-block">
                                <div class="custom-control  custom-control-lg custom-switch">
                                    <input type="checkbox" name="cityStatus" class="custom-control-input" id="cityStatus" value="1">
                                    <label class="custom-control-label" for="cityStatus"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success save">Save</button>
              </div>
        </div>
           </form>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        let modal = $('#updateCityModal');

        $('.toggle-update').on('click', function(e){
            modal.find('form').attr('action', $(this).data('href'));
            modal.find('[name="cityName"]').val($(this).data('city'));
            if($(this).data('status')){
                modal.find('[name="cityStatus"]').prop('checked', true);
            }
            $('#updateCityModal').modal('show')
        })

        // $('.save').on('click', function(e){
        //     e.preventDefault();
        //     cityName = modal.find('[name="cityName"]').val()

        //     $.ajax({
        //     url:"{{ route('create-car.post') }}",
        //     data: formData,
        //     contentType: false,
        //     processData: false,
        //     success: function(data) {
        //         if(data.status == 200){
        //             Swal.fire("", data.message,"success");
        //         }else{
        //             Swal.fire("", data.message,"danger");
        //         }
        //     },
        //     error: function(error) {
        //         alert('Something went wrong!')

        //     },
        //     complete: function(){
        //         $('.loadermain').hide()
        //     }
        // });
        // })
    </script>
@endpush
