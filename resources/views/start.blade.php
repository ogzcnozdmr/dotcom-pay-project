@extends('default')

@section('content')
    <div class="row">
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-heading p-4">
                    <div class="mini-stat-icon float-right">
                        <i class="mdi mdi-cube-outline bg-primary  text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-16">Kayıtlı Firma</h5>
                    </div>
                    <h3 class="mt-4">{{ $registered_company }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-heading p-4">
                    <div class="mini-stat-icon float-right">
                        <i class="mdi mdi-briefcase-check bg-success text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-16">Toplam Satış</h5>
                    </div>
                    <h3 class="mt-4">{{ $total_sales }} TL</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-heading p-4">
                    <div class="mini-stat-icon float-right">
                        <i class="mdi mdi-tag-text-outline bg-warning text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-16">Ödeme İsteği</h5>
                    </div>
                    <h3 class="mt-4">{{ $payment_request }}</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card">
                <div class="card-heading p-4">
                    <div class="mini-stat-icon float-right">
                        <i class="mdi mdi-buffer bg-danger text-white"></i>
                    </div>
                    <div>
                        <h5 class="font-16">Başarılı Ödeme</h5>
                    </div>
                    <h3 class="mt-4">{{ $successful_payment }}%</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-12">
            <div class="card m-b-30">
                <div class="card-body">
                    <h4 class="mt-0 header-title mb-4">Satış istatistigi(Son 6 ay)</h4>
                    <div id="morris-area-example" class="morris-charts morris-chart-height"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!--Morris Chart-->
    <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('assets/pages/dash.js') }}"></script>
@endsection