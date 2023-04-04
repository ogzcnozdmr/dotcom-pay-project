<!DOCTYPE html>
<html lang="en">
<head>
    @include('general.meta')
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/metismenu.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <style type="text/css">
        .hide{
            display: none;
        }
    </style>
</head>
<body>
    <div class="accountbg"></div>
    <div class="wrapper-page">
        <div class="card card-pages shadow-none">
            <div class="card-body">
                <div class="text-center m-t-0 m-b-15">
                    <a href="{{ route('home.start') }}" class="logo logo-admin">
                        <img src="{{ asset('assets/images/logo/light2.png') }}" alt="" height="80">
                    </a>
                </div>
                <form id="login-form" class="form-horizontal m-t-30">
                    <div class="form-group">
                        <div class="col-12">
                            <label>Kullanıcı Adı</label>
                            <input class="form-control" type="text" placeholder="" required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-12">
                            <label>Şifre</label>
                            <input class="form-control" type="password" placeholder="" required="">
                        </div>
                    </div>
                    <div class="form-group info hide">
                        <div class="col-12">
                            <p class="error"></p>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-12">
                            <button class="btn btn-danger btn-block btn-lg waves-effect waves-light" type="submit">Giriş Yap</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- jQuery  -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/metismenu.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('assets/js/waves.min.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- Pages js -->
    <script src="{{ asset('assets/js/notify.js') }}"></script>
    <script src="{{ asset('assets/js/function.js') }}"></script>
    <script src="{{ asset('assets/js/pages/login.js') }}"></script>
</body>
</html>
