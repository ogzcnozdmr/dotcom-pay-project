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
                'name' => 'İşlem Kısıt'
            ]
        ]
    ])
    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label>İşlem yapılan</label>
                            <div>
                                <select class="custom-select">
                                    @foreach ($authority_get as $value)
                                        <option value="{{ $value['authority_id'] }}" name="option"{{ $id === $value['authority_id'] ? ' selected' : '' }}>{{ strtoupper($value['authority_name']) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Erişebileceği Sayfalar</label>
                            <div>
                                @foreach ($authority_pages_get as $value)
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="{{ $value['authority_pages_name'] }}" data-parsley-multiple="groups"
                                               data-parsley-mincheck="2" value="{{ $value['authority_pages_id'] }}" name="input"{{ in_array($value['authority_pages_id'], $authority_area_get) ? ' checked' : '' }}>
                                        <label class="custom-control-label" for="{{ $value['authority_pages_name'] }}">{{ $value['authority_pages_name'] }}</label>
                                    </div>
                                @endforeach
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

@section('js')
    <script src="{{ asset('assets/js/pages/authority.js') }}"></script>
@endsection

@section('css')
    <link href="{{ asset('assets/css/pages/authority.css') }}" rel="stylesheet" type="text/css">
@endsection