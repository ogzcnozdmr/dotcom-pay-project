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
                'name' => session()->get('users')['authority'] === "yonetici" ? "Sanal Pos" : "Ödeme Yap"
            ]
        ]
    ])
    <input type="hidden" id="payResult" data-result="" data-result="">
    <input type="hidden" id="min_installment_count" data-value="{{ $bank[0]['min_installment_amount'] }}">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <h4 class="mt-0 header-title">+ Taksit Bilgileri</h4>
                        <p class="sub-title">+ Taksit bilgilerinizi görebilirsiniz.</p>
                    </div>
                    <div class="form-group">
                        <table class="table table-striped table-bordered nowrap">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Taksit Sayısı</th>
                                <th>+ Taksit Miktarı</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($bank as $value)
                                    <tr>
                                        <td><img width="60" src="{{ asset($value['bank_photo']) }}"></th>
                                        <td>{{ $value['max_installment'] }}</th>
                                        <td style="font-weight:800;">{{ $value['plus_installment'] == 0 ? '-' : '+' }}{{ $value['plus_installment'] }}</th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <form action="{{ route('pay.request') }}" method="POST">
        <div class="row">
            <div class="col-lg-6">
                <div class="card m-b-30">
                    <div class="card-body">
                        <div class="form-group">
                            <h4 class="mt-0 header-title">Satış Bilgileri</h4>
                            <p class="sub-title">Satış bilgilerinizi giriniz.</p>
                        </div>
                        <div class="form-group">
                            <label>Kredi Kartınızı Seçiniz</label>
                            <select name="order_bank" class="custom-select">
                                @foreach ($bank as $value)
                                    <option value="{{ $value['bank_variable'] }}">
                                        {{ $value['bank_name'] }} -> <span>+{{ $value['plus_installment'] }} Taksit
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tutar</label>
                            <input value="0.1" type="number" name="order_total" class="form-control" placeholder="Tutar" required/>
                        </div>
                        <div class="form-group hide">
                            <label>Taksit</label>
                            <select name="order_installment" class="form-control">
                                @for ($i = 1;$i <= $bank[0]['max_installment'];$i++)
                                    <option value="{{ $i }}">{{ $i == 1 ? 'Tek Çekim' : $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card m-b-30">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Kart Bilgileri</h4>
                        <p class="sub-title">Kredi kartı bilgilerinizi giriniz.</p>
                        <div class="form-group">
                            <label>Kart Numarası</label>
                            <input value="5235290172235122" type="text" name="card_number" class="form-control bank-number" placeholder="Kart Numaranız" required/>
                        </div>
                        <div class="form-group">
                            <label>Kart Üzerindeki Ad/Soyad</label>
                            <input value="oğuzcan özdemir" type="text" name="card_name_surname" class="form-control bank-inputname" placeholder="Kart Üzerindeki Ad/Soyad" required/>
                        </div>
                        <div class="form-group">
                            <label>Son Kullanma Tarihi</label>
                            <input value="04/29" type="text" name="card_expiration" class="form-control bank-expire" placeholder="Son Kullanma Tarihi" required/>
                        </div>
                        <div class="form-group">
                            <label>Güvenlik Numarası</label>
                            <input value="" type="text" name="card_cvv" class="form-control bank-ccv" placeholder="Güvenlik Numarası" required/>
                        </div>
                        <div class="form-group">
                            <label>Kart Tipi</label>
                            <select name="card_type" class="custom-select">
                                <option value="1">VISA</option>
                                <option value="2" selected>Master Card</option>
                            </select>
                        </div>
                        <div class="kartsistemi_ac">
                            <div class="form-group">
                                <div>
                                    <button type="sumbit" class="btn btn-primary waves-effect waves-light odeme-yap">
                                        Ödeme Yap
                                    </button>
                                </div>
                            </div>
                            <div class="form-group info hide">
                                <div class="col-12">
                                    <label>Hata</label>
                                    <p class="error"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mt-0 header-title">Müşteri Bilgileri</h4>
                        <p class="sub-title">Müşteri bilgilerinizi giriniz.</p>
                        <div class="form-group">
                            <label>Firma / Müşteri Adı</label>
                            <input value="Oğuzcan Özdemir" type="text" name="customer_name" class="form-control" {{ session()->get('users')['authority'] !=="admin" ? 'readonly' : 'required' }} placeholder="Firma / Müşteri Adı" value="{{ session()->get('users')['authority'] !=="admin" ? $login_get['user_name'] : '' }}" required/>
                        </div>
                        <div class="form-group">
                            <label>Telefon Numarası</label>
                            <input value="5466458003" type="number" name="customer_phone" class="form-control" {{ session()->get('users')['authority'] !=="admin" ? 'readonly' : 'required' }} placeholder="Telefon Numarası" value="{{ session()->get('users')['authority'] !=="admin" ? $login_get['user_phone'] : '' }}" required/>
                        </div>
                        <div class="form-group">
                            <label>Email Adresi</label>
                            <input value="o.ozdmr.40@gmail.com" type="text" name="customer_email" class="form-control" {{ session()->get('users')['authority'] !=="admin" ? 'readonly' : 'required' }} placeholder="Email Adresi" value="{{ session()->get('users')['authority'] !=="admin" ? $login_get['user_email'] : '' }}" required/>
                        </div>
                        <div class="form-group kartsistemi_kapat">
                            <div>
                                <button type="button" class="btn btn-primary waves-effect waves-light odeme-yap">
                                    Ödeme Yap
                                </button>
                            </div>
                        </div>
                        <div class="form-group info hide">
                            <div class="col-12">
                                <label>Hata</label>
                                <p class="error"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card kartsistemi_ac" style="margin-top:34px;">
                    <div class="card-body">
                        <div class="bank-card" style="margin-top:60px;">
                            <div class="bank-front">
                                <div class="bank-type">
                                    <img class="bank-bankid"/>
                                </div>
                                <span class="bank-chip"></span>
                                <span class="bank-card_number">&#x25CF;&#x25CF;&#x25CF;&#x25CF; &#x25CF;&#x25CF;&#x25CF;&#x25CF; &#x25CF;&#x25CF;&#x25CF;&#x25CF; &#x25CF;&#x25CF;&#x25CF;&#x25CF; </span>
                                <div class="bank-date"><span class="bank-date_value">AA / YY</span></div>
                                <span class="bank-fullname">AD SOYAD</span>
                            </div>
                            <div class="bank-back">
                                <div class="bank-magnetic"></div>
                                <div class="bank-bar"></div>
                                <span class="bank-seccode">&#x25CF;&#x25CF;&#x25CF;</span>
                                <span class="bank-chip"></span><span class="bank-disclaimer">Banka bilgileri</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body text-center">
                    <img width="60" class="payment-icon-image" src="/assets/images/credit-cards/mastercard.svg" alt="mastercard">
                    <img width="60" class="payment-icon-image" src="/assets/images/credit-cards/visa.svg" alt="visa">
                    <img width="60" class="payment-icon-image" src="/assets/images/credit-cards/paypal.svg" alt="paypal">
                    <img width="60" class="payment-icon-image" src="/assets/images/credit-cards/maestro.svg" alt="maestro">
                </div>
                <div class="card-body text-center">
                    <img width="60" class="payment-icon-image" src="/assets/images/secured-by/mcafee.svg" alt="mcafee">
                    <img width="60" class="payment-icon-image" src="/assets/images/secured-by/norton.svg" alt="norton">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('assets/css/bank-card.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/pages/pay-screen.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('js')
    <script src="{{ asset('assets/js/bank-card.js') }}"></script>
    <script src="{{ asset('assets/js/pages/pay-screen.js') }}"></script>
@endsection
