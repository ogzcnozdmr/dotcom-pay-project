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
                                <select name="bank" class="custom-select">
                                    @foreach($bank_all_get as $value)
                                        <option value="{{ $value['bank_id'] }}" name="option"{{ $id === $value['bank_id'] ? ' selected' : '' }}>{{ strtoupper($value['bank_name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>+ Taksit</label>
                            <select name="installment" class="form-control">
                                @foreach ($installment_get as $value)
                                    <option value="{{ $value['installment_number'] }}"{{ $value['installment_number'] === $selected_bank['plus_installment'] ? ' selected' : '' }}>{{ $value['installment_number'] }}</option>
                                @endforeach
                            </select>
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

@section('js')
    <script src="{{ asset('assets/js/pages/installment.js') }}"></script>
@endsection

@section('css')
    <link href="{{ asset('assets/css/pages/installment.css') }}" rel="stylesheet" type="text/css">
@endsection