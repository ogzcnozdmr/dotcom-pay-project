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
                'name' => 'Bayi Ekle'
            ]
        ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title">Bayi Ekle</h4>
                    <p class="sub-title">Bayi bilgilerini giriniz..</p>
                    <form id="bayi-ekle-form">
                        <div class="form-group">
                            <label>Bayi Adı</label>
                            <input type="text" name="ad" class="form-control" required placeholder="Bayi Adı"/>
                        </div>
                        <div class="form-group">
                            <label>E-mail Adresi</label>
                            <input type="text" name="email" class="form-control" required placeholder="E-mail Adresi"/>
                        </div>
                        <div class="form-group">
                            <label>Telefon Numarası</label>
                            <input type="number" name="tel" class="form-control" required placeholder="Telefon Numarası"/>
                        </div>
                        <div class="form-group">
                            <label>Kullanıcı Adı</label>
                            <input type="text" name="kad" class="form-control" required placeholder="Kullanıcı Adı"/>
                        </div>
                        <div class="form-group">
                            <label>Kullanıcı Şifresi</label>
                            <input type="password" name="ksifre" class="form-control" required placeholder="Kullanıcı Şifresi"/>
                            <input type="password" name="ksifre2" class="form-control m-t-10" required placeholder="Kullanıcı Şifresi Tekrar"/>
                        </div>
                        <div class="form-group">
                            <label>Yetki</label>
                            <select class="custom-select">
                                @foreach ($authority_all as $value):
                                    <option value="{{ $value['authority_name'] }}">{{ strtoupper($value['authority_name']) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Yetkili Bayi</label>
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="yetkili_bayi" data-parsley-multiple="groups"
                                           data-parsley-mincheck="2" name="input">
                                    <label class="custom-control-label" for="yetkili_bayi">Bayi yetki durumu</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button id="ekle" type="button" class="btn btn-primary waves-effect waves-light">
                                    Ekle
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

@section('js')
    <script src="{{ asset('assets/js/pages/seller/add.js') }}"></script>
@endsection