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
                                <select id="select_bank" class="custom-select">
                                    @foreach($bank_info as $value)
                                        <option value="{{ $value['bank_id'] }}" name="option"{{ $select === $value['bank_id'] ? ' selected' : '' }}>{{ strtoupper($value['bank_name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" value="{{ $bank_detail_api['name'] }}" class="form-control" required/>
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="text" name="password" value="{{ $bank_detail_api['password'] }}" class="form-control" required/>
                        </div>

                        <div class="form-group">
                            <label>Client</label>
                            <input type="text" name="client" value="{{ $bank_detail_api['client'] }}" class="form-control" required/>
                        </div>

                        <div class="form-group">
                            <label>Store Key</label>
                            <input type="text" name="storekey" value="{{ $bank_detail_api['storekey'] }}" class="form-control" required/>
                        </div>

                        <div class="form-group">
                            <label>Store Type</label>
                            <input type="text" name="storetype" value="{{ $bank_detail_api['storetype'] }}" class="form-control" readonly/>
                        </div>
                        <div class="form-group">
                            <label>Maximum Taksit</label>
                            <select id="max_installment" class="form-control">
                                @foreach($installment_data as $key => $value)
                                    <option value="{{ $value['installment_number'] }}"{{ $value['installment_number'] === $bank_detail['max_installment'] ? ' selected' : '' }}>{{ $value['installment_number'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>
                                Minimum Taksit Limit
                            </label>
                            <input type="number" name="min_installment_amount" class="form-control" value="{{ $bank_detail['min_installment_amount'] }}">
                        </div>
                        <div class="form-group">
                            <label>Banka Aktif</label>
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="bank_visible" data-parsley-multiple="groups"
                                           data-parsley-mincheck="2" value="{{ $bank_detail['bank_id'] }}" name="input"{{ $bank_detail['bank_visible'] === '1' ? ' checked' : '' }}>
                                    <label class="custom-control-label" for="bank_visible">Bankanın aktiflik durumu</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">
                                Onayla
                            </button>
                        </div>
                    </form>
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