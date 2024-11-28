
<style>
    .breadcrumb-main{
        box-shadow: 0 0 10px #ececec inset;
        /* border-top: 1px solid #adadad; */
    }
    .breadcrumb-main ul li span , .breadcrumb-main ul li i{
        color: #ff0000;
        font-size: 12px;
    }
    .breadcrumb-main ul li.current span{
        color: #adadad;
    }
    .breadcrumb-main .search-input{
        position: relative;
    }
    .breadcrumb-main .search-input i{
        position: absolute;
        left: 15px;
        top: 8px;
        z-index: 999;
        color: #adadad;
    }
    .breadcrumb-main .search-input input{
        width: 100%;
        text-align: center;
        color: #adadad;
        background: #ececec;
        border: 1px solid #adadad;
        border-radius: 50px;
        padding: 5px 10px;
        height: 40px;
    }
    .breadcrumb-main .search-input input::placeholder{
        text-align: center;
        color: #adadad;
        background: #ececec;
    }
</style>

    {{-- <nav class="breadcrumb-main navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <div class="row m-0 w-100 d-flex justify-content-between align-items-center">
                <div class="col-md-6">
                    <ul class="my-2 list-unstyled d-flex">
                        <li class="mx-1">
                            <span>Home</span>
                            <i class="bi bi-chevron-right"></i>
                        </li>
                        <li class="mx-1 current">
                            <span>Search</span>
                        </li>
                    </ul>
                </div>

                <div class="col-md-3">
                    <div class="w-100">
                        <div class="search-input">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search" aria-label="search">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav> --}}
