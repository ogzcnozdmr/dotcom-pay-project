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
                'name' => 'Banka Ayar'
            ]
        ]
    ])
    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label>İşlem yapılacak banka</label>
                            <div>
                                <select id="select_banka" class="custom-select">
                                    @foreach($bank_info as $value)
                                        <option value="{{ $value['bank_id'] }}" name="option"{{ $select === $value['bank_id'] ? 'selected' : '' }}>{{ strtoupper($value['bank_name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                @if($bank_detail['virtual_pos_type'] === 1)
                                    Name
                                @elseif($bank_detail['virtual_pos_type'] === 2)
                                    MerchantId
                                @elseif($bank_detail['virtual_pos_type'] === 3)
                                    Terminal ID
                                @endif
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ $bank_detail_api['name'] }}">
                        </div>
                        <div class="form-group">
                            <label>
                                @if($bank_detail['virtual_pos_type'] === 1 || $bank_detail['virtual_pos_type'] === 2)
                                    Password
                                @elseif($bank_detail['virtual_pos_type'] === 3)
                                    Provision Password
                                @endif
                            </label>
                            <input type="text" name="password" class="form-control" value="{{ $bank_detail_api['password'] }}">
                        </div>
                        <div class="form-group">
                            <label>
                                @if($bank_detail['virtual_pos_type'] === 1)
                                    ClientId
                                @elseif($bank_detail['virtual_pos_type'] === 2)
                                    TerminalNo
                                @elseif($bank_detail['virtual_pos_type'] === 3)
                                    MerchantID
                                @endif
                            </label>
                            <input type="text" name="client_id" class="form-control" value="{{ $bank_detail_api['client_id'] }}">
                        </div>
                        @if($bank_detail['virtual_pos_type'] === 3)
                            <div class="form-group">
                                <label>
                                    ProvUserID
                                </label>
                                <input type="text" name="user_prov_id" class="form-control" value="{{ $bank_detail_api['user_prov_id'] }}">
                            </div>
                        @endif
                        <div class="form-group">
                            <label>Maximum Taksit</label>
                            <select id="max_taksit" class="form-control">
                                @foreach($installment_data as $key => $value)
                                    <option value="{{ $value['installment_number'] }}"{{ $value['installment_number'] === $bank_detail['max_installment'] ? ' selected' : '' }}>{{ $value['installment_number'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                Minimum Taksit Limit
                            </label>
                            <input type="number" name="min_taksit_miktar" class="form-control" value="{{ $bank_detail['min_installment_amount'] }}">
                        </div>
                        <div class="form-group">
                            <label>Banka Aktif</label>
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="banka_aktif" data-parsley-multiple="groups"
                                           data-parsley-mincheck="2" value="{{ $bank_detail['bank_id'] }}" name="input"{{ $bank_detail['bank_visible'] === 1 ? ' checked' : '' }}>
                                    <label class="custom-control-label" for="banka_aktif">Bankanın aktiflik durumu</label>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="form-group">
                        <div>
                            <button id="onayla" type="button" class="btn btn-primary waves-effect waves-light">
                                Onayla
                            </button>
                        </div>
                    </div>
                    <div class="form-group info hide">
                        <p class="error"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('assets/css/pages/bank.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <script src="{{ asset('assets/js/pages/bank.js') }}"></script>
@endsection