@extends('default')

@section('content')
    @include('general.breadcrumb', [
        'data' => [
            [
                'route' => route('home.start'),
                'name' => 'Panel'
            ],
            [
                'route' => '#',
                'name' => 'Profile'
            ]
        ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Profil</h4>
                    <p class="sub-title">Profil bilgilerinizi düzenleyebilirsiniz..</p>
                    <form id="profil-form">
                        <div class="form-group">
                            <label>Kullanıcı Adı</label>
                            <input type="text" class="form-control" disabled value="{{ $data['user_username'] }}"/>
                        </div>
                        <div class="form-group">
                            <label>Bayi Adı</label>
                            <input type="text" name="ad" class="form-control" required value="{{ $data['user_name'] }}"/>
                        </div>
                        <div class="form-group">
                            <label>E-mail Adresi</label>
                            <input type="text" name="email" class="form-control" required value="{{ $data['user_email'] }}"/>
                        </div>
                        <div class="form-group">
                            <label>Telefon Numarası</label>
                            <input type="number" name="tel" class="form-control" required value="{{ $data['user_phone'] }}"/>
                        </div>
                        <div class="form-group">
                            <div>
                                <button id="guncelle" type="button" class="btn btn-primary waves-effect waves-light">
                                    Güncelle
                                </button>
                            </div>
                        </div>
                        <div class="form-group info hide">
                            <p class="error"></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('assets/css/pages/profile.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/profile.js') }}"></script>
@endsection