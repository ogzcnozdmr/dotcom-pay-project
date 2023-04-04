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
                'name' => '+ Taksit Ayar'
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
                                <select name="banka" class="custom-select">
                                    @foreach($bank_all_get as $value)
                                        <option value="{{ $value['bank_id'] }}" name="option"{{ $id === $value['bank_id'] ? ' selected' : '' }}>{{ strtoupper($value['bank_name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>+ Taksit</label>
                            <select name="taksit" class="form-control">
                                @foreach ($installment_get as $value)
                                    <option value="{{ $value['installment_number'] }}"{{ $value['installment_number'] === $selected_bank['plus_installment'] ? ' selected' : '' }}>{{ $value['installment_number'] }}</option>
                                @endforeach
                            </select>
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

@section('js')
    <script src="{{ asset('assets/js/pages/installment.js') }}"></script>
@endsection

@section('css')
    <link href="{{ asset('assets/css/pages/installment.css') }}" rel="stylesheet" type="text/css">
@endsection