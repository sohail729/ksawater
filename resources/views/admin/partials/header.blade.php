<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.9/css/select2.min.css"/>

    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.1/css/lightbox.min.css">
    <link rel="stylesheet" href="{{ asset('css/theme-red.css') }}">
    <!-- Option 1: Include in HTML -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Now 2 Rent | Car Rental</title>
</head>

<style>
      .navbar {
        background-color: #F2F2F2;
    }


    .footer{
        height: 240px;
        background-color: #F2F2F2;
    }

    .footer .list-group .list-group-item{
        background: none !important;
        border: none;
    }
    .footer .list-group a{
        color: #2e1a1a;
        text-decoration: none;
    }

</style>
<body>


<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <div class="row w-100 align-items-center">
            <div class="col-md-8">
                <a class="navbar-brand" href="/"><img loading="lazy" src="{{ asset('images/logo.png') }}" alt="Logo" width="50"></a>
            </div>
            <div class="col-md-4">
                <div class="float-end">
                    <button type="button" class="btn btn-outline-danger mx-2">Download App</button>

                    @if ( auth()->check())
                    <a href="{{ route('profile') }}" class="btn btn-danger"><span class="px-1"><i
                        class="bi bi-person-fill"></i></span><span>{{  auth()->user()->username }}</span></a>
                    @else
                    <a href="{{ route('login') }}" class="btn btn-danger"><span class="px-1"><i
                        class="bi bi-person-fill"></i></span><span>Login/Signup</span></a>
                    @endif

                </div>
            </div>
        </div>
    </div>
    </div>
</nav>

