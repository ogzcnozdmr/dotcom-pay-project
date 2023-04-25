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
                'name' => 'Bayi Ödemeleri'
            ]
        ]
    ])
    <div class="row">
        <div class="col-12">
            <div class="card m-b-30">
                <div class="card-body table-responsive">
                    <h4 class="mt-0 header-title">Bayi Listesi</h4>
                    <p class="sub-titleplugins">Sisteminizde kayıtlı bütün bayilerin listesini görebilirsiniz.
                    </p>
                    <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Sıra</th>
                                <th>Firma</th>
                                <th>Kart Sahibi</th>
                                <th>Tutar</th>
                                <th>Tarih</th>
                                <th>Sonuç</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            {{ $row = 0 }}
                            @foreach($pay_list as $value)
                                <tr o_id="{{ $value['pay_id'] }}">
                                    <td>{{ ++$row }}</td>
                                    <td>{{ $value['seller_name'] }}</td>
                                    <td>{{ $value['pay_card_owner'] }}</td>
                                    <td>{{ $value['order_total'] }}</td>
                                    <td>{{ $value['pay_date'] }}</td>
                                    <td>{{ __pay_result_titles($value['pay_result']) }}</td>
                                    <td class="text-center"><button class="btn btn-primary cikti">Çıktı Al</button></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <!-- DataTables -->
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('js')
    <!--Morris Chart-->
    <script src="{{ asset('plugins/morris/morris.min.js') }}"></script>
    <script src="{{ asset('plugins/raphael/raphael.min.js') }}"></script>
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Buttons examples -->
    <script src="{{ asset('plugins/datatables/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/buttons.colVis.min.js') }}"></script>
    <!-- Datatable init js -->
    <script src="{{ asset('assets/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('assets/js/sweetalert.js') }}"></script>
    <script src="{{ asset('assets/js/pages/seller/list.js') }}"></script>
@endsection