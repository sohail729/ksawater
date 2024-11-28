@if($errors->any())
    <div class="alert alert-pro alert-danger">
        <div class="alert-text">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

@if(Session::has('alert-success'))
    <div class="alert alert-pro alert-success">
        {{ Session::get('alert-success') }}
    </div>
@endif

@if(Session::has('alert-danger'))
    <div class="alert alert-pro alert-danger">
        {{ Session::get('alert-danger') }}
    </div>
@endif
@if(Session::has('alert-info'))
    <div class="alert alert-pro alert-info">
        {{ Session::get('alert-info') }}
    </div>
@endif
@if(Session::has('alert-warning'))
    <div class="alert alert-pro alert-warning">
        {{ Session::get('alert-warning') }}
    </div>
@endif
