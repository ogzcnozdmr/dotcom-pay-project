<!DOCTYPE html>
<html lang="en">
<head>
    @include('general.meta')
    @include('dependencies.css')
</head>
<body>
    <div class="error-bg"></div>
    <div class="home-btn d-none d-sm-block">
        <a href="{{ route('home.start') }}" class="text-white"><i class="fas fa-home h2"></i></a>
    </div>
    <div class="account-pages">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-8">
                    <div class="card shadow-lg">
                        <div class="card-block">
                            <div class="text-center p-3">
                                <h1 class="error-page mt-4"><span>404!</span></h1>
                                <h4 class="mb-4 mt-5">Üzgünüz, sayfa bulunamadı</h4>
                                <a class="btn btn-primary mb-4 waves-effect waves-light" href="{{ route('home.start') }}"><i class="mdi mdi-home"></i> Ana Sayfaya Dön</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('dependencies.js')
</body>
</html>