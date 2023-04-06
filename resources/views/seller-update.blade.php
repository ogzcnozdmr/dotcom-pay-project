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
                'name' => 'Bayi Güncelle'
            ]
        ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Bayi Güncelle</h4>
                    <p class="sub-title">Bayi bilgilerini değiştirebilirsiniz..</p>
                    <form id="bayi-guncelle-form">
                        <div class="form-group">
                            <label>Adı</label>
                            <input type="text" name="name" class="form-control" required placeholder="Adı" value="{{ $user_get['user_name'] }}" />
                        </div>
                        <div class="form-group">
                            <label>E-mail Adresi</label>
                            <input type="text" name="email" class="form-control" required placeholder="E-mail Adresi" value="{{ $user_get['user_email'] }}"/>
                        </div>
                        <div class="form-group">
                            <label>Telefon Numarası</label>
                            <input type="text" name="phone" class="form-control" required placeholder="Telefon Numarası" value="{{ $user_get['user_phone'] }}"/>
                        </div>
                        <div class="form-group">
                            <label>Kullanıcı Adı</label>
                            <input type="text" name="username" class="form-control" required placeholder="Kullanıcı Adı" value="{{ $user_get['user_username'] }}"/>
                        </div>
                        <div class="form-group">
                            <label>Kullanıcı Şifresi</label>
                            <input type="password" name="password" class="form-control" placeholder="Kullanıcı Şifresi"/>
                            <input type="password" name="password2" class="form-control m-t-10" placeholder="Kullanıcı Şifresi Tekrar"/>
                            <p>Eğer Şifre girilmez ise kullanıcı şifresi değişmeyecek</p>
                        </div>
                        <div class="form-group">
                            <label>Yetki</label>
                            <select class="custom-select">
                                @foreach ($authority_all as $value):
                                    <option{{ $value['authority_name'] === $user_get['user_authority']) ? ' selected' : '' }} value="{{ $value['authority_name'] }}">{{ strtoupper($value['authority_name']) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Yetkili Bayi</label>
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="yetkili_bayi" data-parsley-multiple="groups"
                                           data-parsley-mincheck="2" name="input"{{ $user_get['official_distributor'] === '1' ? ' checked' : '' }}>
                                    <label class="custom-control-label" for="yetkili_bayi">Bayi yetki durumu</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button id="guncelle" type="button" class="btn btn-primary waves-effect waves-light">
                                    Güncelle
                                </button>
                            </div>
                        </div>
                        <div class="form-group info hide">
                            <div class="col-12">
                                <label>Hata</label>
                                <p class="error"></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('assets/js/pages/seller/update.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/seller/update.js') }}"></script>
@endsection