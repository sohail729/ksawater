@extends('admin.layouts.auth')

<style>
    .nk-wrap-nosidebar .nk-content {
        /* background: black;
        background-image: url("{{ asset('images/dashboard-bg.webp') }}");
        background-repeat: no-repeat;
        background-size: cover; */
    }
</style>
<div class="nk-app-root">
    <!-- main @s -->
    <div class="nk-main ">
        <!-- wrap @s -->
        <div class="nk-wrap nk-wrap-nosidebar">
            <!-- content @s -->
            <div class="nk-content ">
                <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                    <div class="brand-logo pb-4 text-center">
                        {{-- <a href="html/index.html" class="logo-link">
                            <a href="/"><img loading="lazy" src="{{ asset('img/logo2.png') }}" alt="" class="custom-logo"></a>
                        </a> --}}
                    </div>
                    <div class="card card-bordered">
                        <div class="card-inner card-inner-lg">

                            <div class="nk-block-head">
                                <div class="nk-block-head-content">
                                    <h4 class="nk-block-title">Control Panel Log-in</h4>
                                </div>
                            </div>
                            @include('admin.partials.alerts')
                            <form action="{{ route('admin.login.post') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="default-01">Email</label>
                                    </div>
                                    <div class="form-control-wrap">
                                        <input type="text" name="email" class="form-control form-control-lg"
                                            id="default-01" placeholder="Enter your email address" required>
                                        @error('email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="form-label-group">
                                        <label class="form-label" for="password">Password</label>
                                        {{-- <a class="link link-primary link-sm"
                                            href="html/pages/auths/auth-reset-v2.html">Forgot Password?</a> --}}
                                    </div>
                                    <div class="form-control-wrap">
                                        <a href="javascript:;" class="form-icon form-icon-right passcode-switch lg"
                                            data-target="password">
                                            <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                            <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                        </a>
                                        <input type="password" name="password" class="form-control form-control-lg"
                                            id="password" placeholder="Enter your password" required>
                                        @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-md btn-primary btn-block">Log in&nbsp;<em
                                            class="icon ni ni-signin"></em></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- wrap @e -->
        </div>
        <!-- content @e -->
    </div>
    <!-- main @e -->
</div>
<!-- app-root @e -->
<script>
    function togglePassword(el) {
    const closestFormGroup = findClosest(el, '.form-group');
    const passwordField = closestFormGroup.querySelector('.form-control');
    const icon = el.querySelector('.passcode-icon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        icon.classList.remove('ni-eye');
        icon.classList.add('ni-eye-off');
    } else {
        passwordField.type = 'password';
        icon.classList.remove('ni-eye-off');
        icon.classList.add('ni-eye');
    }
}

// Attach the click event to the passcode-switch element
document.querySelectorAll('.passcode-switch').forEach(function (element) {
    element.addEventListener('click', function () {
        togglePassword(this);
    });
});

// Helper function to find the closest parent element with a specific class
function findClosest(element, selector) {
    while (element && !element.matches(selector)) {
        element = element.parentElement || element.parentNode;
    }
    return element;
}

</script>
