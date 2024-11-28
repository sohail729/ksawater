@extends('admin.layouts.dashboard')

@section('content')
<div class="nk-content ">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h2 class="nk-block-title fw-normal">Subscription Plans</h2>
                            <nav>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ env('APP_NAME') }}</a></li>
                                    <li class="breadcrumb-item active">Subscription Plans</li>
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
                        <hr>
                        <h3 class="nk-block-title page-title text-center">Basic Plans</h3>
                        <hr>
                        <div class="card card-preview">
                            <div class="card-inner">
                                <a href="javascript:;" class="btn btn-md btn-outline-primary float-end openBasicModal">New Plan</a>
                                <table class="datatable-init nowrap table" id="basicPlansTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Duration</th>
                                            <th>Packages</th>
                                            <th>Basic/Featured</th>
                                            <th>Featured Duration</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $plans as $plan )
                                        @php
                                            $packages = json_decode($plan->packages, true);
                                            if ($plan->is_featured) {
                                                $featured_packages = json_decode($plan->featured_packages, true);
                                            }
                                        @endphp
                                        <tr id="rowID-{{ $loop->index }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$plan->period }} {{  $plan->period == 1 ? 'Month' : 'Months' }} </td>
                                            <td>
                                               <table class="datatable-basic nowrap table limit-price">
                                                <thead>
                                                    <th>Upload Limit</th>
                                                    <th>Price</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($packages as $key => $package)
                                                    <tr>
                                                        <td data-value="{{ str_replace("upto", "", $key) }}">
                                                           Upto {{ str_replace("upto", "", $key) }}
                                                        </td>
                                                        <td data-value="{{ $package }}">
                                                            &euro; {{ $package }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                               </table>
                                            </td>
                                            <td>
                                                @if ($plan->is_featured)
                                                <span class="badge badge-sm bg-outline-success">Featured</span>
                                                @else
                                                    <span class="badge badge-sm bg-outline-warning">Basic</span>
                                                @endif


                                            </td>
                                            <td>{{ $plan->featured_duration }} {{ $plan->featured_duration == 1 ? "Day" : "Days"}}</td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-info btn-sm toggle-update2"
                                                    data-href='{{ route("admin.plans.update", ['type' => 'basic' ,'id' => $plan->id]) }}' data-duration="{{ $plan->period }}" data-is_featured="{{ $plan->is_featured }}" data-featured_duration="{{ $plan->featured_duration }}"><em
                                                        class="icon ni ni-edit"></em></a>
                                                        <a class="btn btn-danger btn-sm delete" href='javascript:void(0)' data-id='{{ $plan->id }}' data-route='{{ route("admin.plan.destroy", ['id' => $plan->id, 'type' => 'basic']) }}'><em class="icon ni ni-trash"></em></a>
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
                <div class="components-preview  mx-auto my-3">
                    <div class="nk-block nk-block-lg">
                        <hr>
                        <h3 class="nk-block-title page-title text-center">Featured Plans</h3>
                        <hr>
                        <div class="card card-preview">

                            <div class="card-inner">
                                <a href="javascript:;" class="btn btn-md btn-outline-primary float-end openFeaturedModal">New Plan</a>
                                <table class="datatable-init nowrap table" id="featuredPlansTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Duration</th>
                                            <th>Upload Limit</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach( $featured_plans as $plan )
                                        <tr id="rowID-{{ $loop->index }}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$plan->duration }} {{  $plan->duration == 1 ? 'Day' : 'Days' }} </td>
                                            <td>
                                                Upto {{ str_replace("upto", "", $plan->limit) }}
                                            </td>
                                            <td>
                                               &euro; {{ $plan->price }}
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group" aria-label="Basic example">
                                                    <a class="btn btn-info btn-sm toggle-update"
                                                    data-href='{{ route("admin.plans.update", ['type' => 'featured' ,'id' => $plan->id]) }}' data-duration="{{ $plan->duration }}" data-limit="{{ str_replace("upto", "", $plan->limit) }}" data-price="{{ $plan->price }}"><em
                                                        class="icon ni ni-edit"></em></a>
                                                    <a class="btn btn-danger btn-sm delete" href='javascript:void(0)' data-id='{{ $plan->id }}' data-route='{{ route("admin.plan.destroy", ['id' => $plan->id, 'type' => 'featured']) }}'><em class="icon ni ni-trash"></em></a>
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
<div class="modal fade" id="editPlanModal" tabindex="-1" role="dialog" aria-labelledby="editPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
           <form action="" method="post">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Update Featured Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <div class="row g-gs">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Duration * <small>(In Days)</small></label>
                            <div class="form-control-wrap">
                                <input type="number" name="duration" id="duration" placeholder="E.g 1, 3, 7 or 30" class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Upload Limit *</label>
                            <div class="form-control-wrap">
                                <input type="number" name="limit" id="limit" placeholder="E.g 5, 7 or 10" class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Price *  <small>(In &euro;)</small></label>
                            <div class="form-control-wrap">
                                <input type="number" name="price" id="price" step=".01" placeholder="E.g 12, 16, or 20" class="form-control" value="" required>
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
<div class="modal fade" id="editPlanModal2" tabindex="-1" role="dialog" aria-labelledby="editPlanModal2Label" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
           <form action="" method="post">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Update Basic Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <div class="row g-gs">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Duration * <small>(In Months)</small></label>
                            <div class="form-control-wrap">
                                <input type="number" name="period" id="period" placeholder="E.g 1, 3, 6 or 12" class="form-control" value="" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Has Featured?</label>
                                <div class="preview-block">
                                    <div class="custom-control  custom-control-lg custom-switch">
                                        <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" value="1">
                                        <label class="custom-control-label" for="is_featured"></label>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label" for="fv-full-name">Featured Duration * <small>(In Days)</small></label>
                            <div class="form-control-wrap">
                               <select class="select2 form-select" name="featured_duration" id="featured_duration" required disabled>
                                <option value="" selected hidden>-- Select Duration --</option>
                                {{-- <option value="1">1</option>
                                <option value="7">7</option>
                                <option value="14">14</option>
                                <option value="30">30</option> --}}
                                @if (!empty($featured_plans->groupBy('duration')->keys()->toArray()))
                                    @foreach ($featured_plans->groupBy('duration')->keys()->toArray() as $duration)
                                    <option value="{{ $duration }}">{{ $duration }}</option>
                                    @endforeach
                                @endif
                               </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                       <div class="d-flex justify-content-between">
                        <h5>Packages</h5>
                        <a href="javascript:;" title="Add More" class="h5" id="addMorePackage"><em class="icon ni ni-plus-c"></em></a>
                       </div>
                       <div id="packages-wrapper">
                        <div class="row my-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="fv-full-name">Upload Limit *</label>
                                    <div class="form-control-wrap">
                                        <input type="number" name="limit[]" id="limit" placeholder="E.g 5, 7 or 10" class="form-control" value="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 ">
                                <div class="form-group">
                                    <label class="form-label" for="fv-full-name">Price *  <small>(In &euro;)</small></label>
                                    <div class="form-control-wrap">
                                        <input type="number" name="price[]" id="price" step=".01" placeholder="E.g 12, 16, or 20" class="form-control" value="" required>
                                    </div>
                                </div>
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
        let modal = $('#editPlanModal');
        let modal2 = $('#editPlanModal2');
        $('.openFeaturedModal').on('click', function(e){
            modal.find('.modal-title').text('New Featured Plan');
            modal.find('form').attr('action', "{{ route('admin.plans.update', ['type' => 'featured']) }}");
            modal.find('[name="duration"]').val('');
            modal.find('[name="limit"]').val('');
            modal.find('[name="price"]').val('');
            modal.modal('show')
        })
        $('.toggle-update').on('click', function(e){
            modal.find('.modal-title').text('Update Featured Plan');
            modal.find('form').attr('action', $(this).data('href'));
            modal.find('[name="duration"]').val($(this).data('duration'));
            modal.find('[name="limit"]').val($(this).data('limit'));
            modal.find('[name="price"]').val($(this).data('price'));
            modal.modal('show')
        })

        // ------------------------------------

        $('.openBasicModal').on('click', function(e){
            modal2.find('.modal-title').text('New Basic Plan');
            modal2.find('form').attr('action', "{{ route('admin.plans.update', ['type' => 'basic']) }}");
            modal2.find('[name="period"]').val('');
            modal2.find('[name="is_featured"]').prop('checked', false);
            modal2.find('[name="featured_duration"]').prop('disabled', true);
            modal2.find('[name="featured_duration"]').val('');
            $('#packages-wrapper').html('');
            html = '<div class="row my-2"><div class="col-md-6"><div class="form-group"><label class="form-label" for="fv-full-name">Upload Limit *</label><div class="form-control-wrap"><input type="number" name="limit[]" id="limit" placeholder="E.g 5, 7 or 10" class="form-control" value="" required></div></div></div><div class="col-md-6"><div class="form-group"><label class="form-label" for="fv-full-name">Price * <small>(In &euro;)</small></label><div class="form-control-wrap"><input type="number" name="price[]" id="price" step=".01" placeholder="E.g 12, 16, or 20" class="form-control" value="" required></div></div></div></div>';
            $('#packages-wrapper').append(html)
            modal2.modal('show')
        })

        $('.toggle-update2').on('click', function(e){
            var dataArray = [];
            rows = $(this).closest('tr').find('.limit-price tbody tr');
            $('#packages-wrapper').html('');
            rows.each(function() {
                var limitValue = $(this).find('td:eq(0)').data('value');
                var priceValue = $(this).find('td:eq(1)').data('value');
                html = '<div class="row my-2"><a href="javascript:;" title="Remove" class="removePackage"><em class="icon ni ni-minus-c"></em></a><div class="col-md-6"><div class="form-group"><label class="form-label" for="fv-full-name">Upload Limit *</label><div class="form-control-wrap"><input type="number" name="limit[]" id="limit" placeholder="E.g 5, 7 or 10" class="form-control" value="'+limitValue+'" required></div></div></div><div class="col-md-6"><div class="form-group"><label class="form-label" for="fv-full-name">Price * <small>(In &euro;)</small></label><div class="form-control-wrap"><input type="number" name="price[]" id="price" step=".01" placeholder="E.g 12, 16, or 20" class="form-control" value="'+priceValue+'" required></div></div></div></div>';
                $('#packages-wrapper').append(html)
            });
            modal2.find('.modal-title').text('Update Basic Plan');
            modal2.find('[name="period"]').val($(this).data('duration'));
            modal2.find('form').attr('action', $(this).data('href'));
            if($(this).data('is_featured') == 1){
                modal2.find('[name="is_featured"]').prop('checked', true);
                modal2.find('[name="featured_duration"]').prop('disabled', false);
                modal2.find('[name="featured_duration"]').val($(this).data('featured_duration'));
            }else{
                modal2.find('[name="is_featured"]').prop('checked', false);
                modal2.find('[name="featured_duration"]').prop('disabled', true);
                modal2.find('[name="featured_duration"]').val('');
            }
            modal2.modal('show')
        })

        $('#addMorePackage').on('click', function(e){
            html = '<div class="row my-2"><a href="javascript:;" title="Remove" class="removePackage"><em class="icon ni ni-minus-c"></em></a><div class="col-md-6"><div class="form-group"><label class="form-label" for="fv-full-name">Upload Limit *</label><div class="form-control-wrap"><input type="number" name="limit[]" id="limit" placeholder="E.g 5, 7 or 10" class="form-control" value="" required></div></div></div><div class="col-md-6"><div class="form-group"><label class="form-label" for="fv-full-name">Price * <small>(In &euro;)</small></label><div class="form-control-wrap"><input type="number" name="price[]" id="price" step=".01" placeholder="E.g 12, 16, or 20" class="form-control" value="" required></div></div></div></div>';
            $('#packages-wrapper').append(html)
        })
        $(document).on('click', '.removePackage' ,function(e){
            $(this).closest('.row').remove()
        })

        $('#is_featured').change(function() {
            $('#featured_duration').prop('disabled', !this.checked);
            if (!this.checked) {
                $('#featured_duration').val('').trigger('change');
            }
        });
    </script>
@endpush
