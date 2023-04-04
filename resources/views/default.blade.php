<!DOCTYPE html>
<html lang="tr">
<head>
    @include('general.meta')
    @include('dependencies.css')
    @yield('css')
</head>
<body>
    <div id="wrapper">
        @include('general.header')
        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('general.footer')
        </div>
    </div>
    @include('dependencies.js')
    @yield('js')
</body>
</html>
