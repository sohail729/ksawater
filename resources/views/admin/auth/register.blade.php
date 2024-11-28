@extends('layouts.app')

@push('styles')

<style>
    .nk-auth-body, .nk-auth-footer{
        max-width: 450px;
    }
    .text-red {
        color: #EF3D49;
    }


    .toggle-break-lg {
        background-color: none;
        background-image: linear-gradient(105deg,
                hsl(0deg 0% 100%) 0%,
                hsl(2deg 91% 89%) 47%,
                hsl(0deg 79% 77%) 100%);
    }


    .form-control-md,
    .form-control-lg {
        border-radius: 12px
    }

    .btn-login {
        background-color: #EF3D49;
        border-radius: 20px;
        border: none;
        transition: opacity 0.3s
    }

    .btn-login:hover {
        background-color: #EF3D49;
        opacity: 0.6;
    }

    .overline-title-sap {
        font-size: 20px;
    }

    .overline-title-sap:before,
    .overline-title-sap:after {
        width: 160px;
    }

    .slick-dots {
        justify-content: end;
    }

    .slick-slide img {
        display: unset;
    }

    .login-container {
        text-align: center;
    }

    .login-button {
        width: 100%;
        background-color: #f1f1f1;
        display: inline-block;
        color: #000;
        border-radius: 20px;
        margin: 10px 0;
        padding: 8px 0;
        text-decoration: none;
    }

    .login-button .icon {
        display: inline-block;
        width: 18px;
        margin-right: 10px;
    }

    #package-total{
        padding: 12px;
        font-size: 30px;
        font-weight: 600;
        text-align: center;
        text-align: -webkit-center;
    }

    #package-total #vat-text{
        font-size: 12px;
        margin-left: 10px;
    }

</style>
@endpush

@section('content')
<div>
    <div class="nk-app-root">
        <div class="nk-main ">
            <div class="nk-wrap nk-wrap-nosidebar">
                <div class="nk-content ">
                    <div class="nk-split nk-split-page nk-split-lg">
                        <div class="nk-split-content nk-split-stretch bg-lighter d-flex toggle-break-lg toggle-slide toggle-slide-right"
                            data-toggle-body="true" data-content="athPromo" data-toggle-screen="lg"
                            data-toggle-overlay="true">
                            <div class="slider-wrap w-100 w-max-550px p-3 p-sm-5 m-auto">
                                <div class="slider-init"
                                    data-slick='{"dots":true, "arrows":false, "autoplay": true, "autoplaySpeed": 1000}'>
                                    <div class="slider-item">
                                        <div class="nk-feature nk-feature-center">
                                            <div class="nk-feature-img">
                                                <img loading="lazy" class="img-fluid" src="{{ asset('images/red.png') }}"
                                                    srcset="{{ asset('images/red.png') }} 2x" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider-item">
                                        <div class="nk-feature nk-feature-center">
                                            <div class="nk-feature-img">
                                                <img loading="lazy" class="img-fluid" src="{{ asset('images/red.png') }}"
                                                    srcset="{{ asset('images/red.png') }} 2x" alt="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="slider-item">
                                        <div class="nk-feature nk-feature-center">
                                            <div class="nk-feature-img">
                                                <img loading="lazy" class="img-fluid" src="{{ asset('images/red.png') }}"
                                                    srcset="{{ asset('images/red.png') }} 2x" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="slider-dots"></div>
                                <div class="slider-arrows"></div>
                                <div class="row mt-5">
                                    <div class="col-md-12">
                                        <h3 class="text-red">Best Car Rental</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="nk-split-content nk-block-area nk-block-area-column nk-auth-container bg-white w-lg-45">
                            <div class="nk-block nk-block-middle nk-auth-body">
                                <div class="nk-block-head">
                                    <div class="nk-block-head-content">
                                        <h3 class="nk-block-title text-red">Let's Go</h3>
                                        <p>Create a dealer account</p>
                                    </div>
                                </div>
                                @include('partials.alerts')

                                @if (!empty(session('payment_link')))
                                <div class="alert alert-pro alert-info">
                                    You've already signed-up. Please proceed with the payment to start using our service. <span class="badge bg-success"><a class="text-white" href="{{ session('payment_link') }}">Pay Now!</a></span>
                                </div>
                                @endif
                                <form action="{{ url('/register') }}" method="post" id="signupForm">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-5 pe-0">

                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control form-control-md"
                                                        id="username" name="username" placeholder="Enter Username *" required>
                                                </div>
                                            </div>
                                            <div class="col-md-7">

                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control form-control-md" id="email"
                                                        name="email" placeholder="Enter your email address *" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 pe-0">
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control form-control-md"
                                                        id="fullname" name="fullname" placeholder="Enter Full Name *" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-control-wrap">
                                                    <input type="tel" class="form-control form-control-md" id="phone"
                                                        name="phone" placeholder="Enter Phone Number *" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6 pe-0">
                                                <div class="form-control-wrap">
                                                    <a tabindex="-1" href="javascript:void(0)"
                                                        class="form-icon form-icon-right passcode-switch lg"
                                                        data-target="password" onclick="togglePassword(this)">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <input type="password"
                                                        class="form-control form-control-md password-field"
                                                        id="password" placeholder="Enter password *" name="password" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-control-wrap">

                                                    <a tabindex="-1" href="javascript:void(0)"
                                                        class="form-icon form-icon-right passcode-switch lg"
                                                        data-target="password-confirm" onclick="togglePassword(this)">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <input type="password"
                                                        class="form-control form-control-md password-field"
                                                        id="password-confirm" placeholder="Confirm password *"
                                                        name="password_confirmation" required>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <input type="text" class="form-control form-control-md" id="address"
                                                name="address" placeholder="Enter Showroom/Garage Address *" required>
                                        </div>
                                    </div>
                                    {{-- <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select class="form-select form-control-md" id="country" name="country">
                                                    <option value="" selected hidden>-- Select Country --</option>
                                                    @foreach ($countries as $key => $country)
                                                    <option value="{{ $key }}">{{ $key }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select class="form-select form-control-md" id="city" name="city">
                                                    <option value="" selected hidden>-- Select City --</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="city" id="city" class="form-control select2"
                                                    data-placeholder="Select City *" required>
                                                    <option value="" selected hidden></option>
                                                    @foreach ($cities as $key => $city )
                                                    <option value="{{ strtolower($city['name']) }}">{{ $city['name'] }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control form-control-md" id="area"
                                                    name="area" placeholder="Enter Area (optional)">
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <select name="package[duration]" id="package-duration" class="form-control select2"
                                                data-placeholder="Select Duration *" required>
                                                <option value="" selected hidden></option>
                                                <option value="1">1 Month</option>
                                                <option value="3">3 Months</option>
                                                <option value="6">6 Months</option>
                                                <option value="12">12 Months</option>
                                            </select>
                                            </div>
                                            <div class="col-md-6">
                                                <select name="package[range]" id="package-range" class="form-control select2"
                                                    data-placeholder="Select Package *" required disabled>
                                                    <option value="" selected hidden></option>
                                                    <option value="upto5">upto 5 Cars</option>
                                                    <option value="upto11">upto 11 Cars</option>
                                                    <option value="upto20">upto 20 Cars</option>
                                                    <option value="upto30">upto 30 Cars</option>
                                                </select>
                                        <p class="text-danger float-end"><small><a href="{{ route('packages') }}"><u>View packages detail</u></a></small></p>

                                            </div>
                                        </div>
                                    </div>
                                    <div id="package-total">
                                        <span>&euro;</span><span id="amount">0.00</span><small id="vat-text">(Including 21% VAT)</small>
                                        <input type="hidden" name="amount">

                                    </div>
                                    <div class="form-group">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-lg btn-primary btn-block btn-login"><span>Signup</span> <i
                                                class="bi bi-box-arrow-in-right mx-1"></i></button>
                                    </div>
                                </form>
                            </div>
                            <div class="nk-block nk-auth-footer nk-block py-2">
                                <div class="text-center">
                                    <p class="m-0">Already a member? <a class="text-red"
                                            href="{{ route('login') }} ">Log in</a></p>
                                    {{-- <p class="m-0">Rent / sell a car? <a class="text-red"
                                            href="javascript:void(0)">Become Dealer</a></p> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.select2').select2();

    let packages = @json($packages);
    {{-- let countries = @json($countries); --}}
    function togglePassword(el){
        const passwordField = $(el).next('.password-field');
        const icon = $(el).find('i');
        if (passwordField.attr('type') === 'password') {
            passwordField.attr('type', 'text');
            icon.removeClass('bi-eye').addClass('bi-eye-slash');
        } else {
            passwordField.attr('type', 'password');
            icon.removeClass('bi-eye-slash').addClass('bi-eye');
        }
    }
    $('#package-duration').on('change', function(){
        let duration = $(this).val();
        let range = $('#package-range').val();
        if(range == ''){
            range = 'upto5';
            $('#package-range').val(range).select2();
        }
        $('#package-range').prop('disabled', false);
        amount = parseFloat(packages[duration][range]);
        amount = parseFloat(amount + (amount * 0.21)).toFixed(2)
        $('[name="amount"]').val(amount)
        $('#package-total #amount').text(amount)
    })

    $('#package-range').on('change', function(){
        let range = $(this).val();
        let duration = $('#package-duration').val();
        if(duration == ''){
            duration = '1';
            $('#package-range').val(duration).select2();
        }
        amount = parseFloat(packages[duration][range]);
        amount = parseFloat(amount + (amount * 0.21)).toFixed(2)
        $('[name="amount"]').val(amount)
        $('#package-total #amount').text(amount)
    })

    $("[name='username']").on('input', function() {
        var inputValue = $(this).val();
        if (inputValue.length > 10) {
            $(this).val(inputValue.slice(0, 10));
        }
    });

    $(".btn-login").on('click', function(e) {
        var allFieldsFilled = true;
        $("[required]").each(function() {
            if ($(this).val() === "") {
                allFieldsFilled = false;
                return false;
            }
        });

        if(allFieldsFilled){
            $(this).text('Processing...')
            $(this).prop('disabled', true)
            $('#signupForm').submit()
        }
    });

    {{-- $('#country').on('change', function(){
       let country = $(this).val()
       $('#city').html('').append('<option value="" selected hidden>-- Select City --</option>')
       countries[country].forEach(element => {
        $('#city').append('<option value="'+element+'">'+element+'</option>')
       });
    }) --}}
</script>
@endpush
