@extends('System.Layouts.Master')
@section('title', 'Commission History')
@section('css')
<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />

<!-- DataTables -->
<link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/buttons.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/fixedHeader.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/scroller.bootstrap.min.css" rel="stylesheet" type="text/css" />
<style>
    a:hover {
        cursor: pointer;
    }
</style>
@endsection
@section('content')
<div class="content">

    <div class="container">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Commission History</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active">Commission History</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <!-- Row -->
                    <div class="row">

                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <h6 class="panel-title txt-light"><i class="fa fa-history" aria-hidden="true"></i>
                                        Commission History</h6>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            {{$walletHistory->appends(request()->input())->links('System.Layouts.Pagination')}}
                                            <div style="clear:both"></div>
                                            <table id="wallet-table"
                                                class="table table-striped table-bordered dt-responsive"
                                                cellspacing="0" width="100%">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            ID
                                                        </th>
                                                        <th>
                                                            AMOUNT
                                                        </th>
                                                        <th>
                                                            FEE
                                                        </th>
                                                        <th>
                                                            RATE
                                                        </th>
                                                        <th>
                                                            CURRENCY
                                                        </th>
                                                        <th>
                                                            ACTION
                                                        </th>
                                                        <th>
                                                            COMMENT
                                                        </th>
                                                        <th>
                                                            TIME
                                                        </th>
                                                        <th>
                                                            STATUS
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($walletHistory as $item)
                                                    <tr>
                                                        <td>{{$item->Money_ID}}</td>
                                                        <td>{{ $item->Money_USDT+0}}</td>
                                                        <td>{{ $item->Money_USDTFee}}</td>
                                                        <td>{{number_format($item->Money_Rate, 5)}}</td>
                                                        <td>{{$item->Currency_Symbol}}</td>
                                                        <td>{{$item->MoneyAction_Name}}</td>
                                                        <td>{{$item->Money_Comment}}</td>
                                                        <td>{{date('Y-m-d H:i:s',$item->Money_Time)}}</td>
                                                        <td>
                                                            <span class="badge badge-success">Confirmed</span>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            {{$walletHistory->appends(request()->input())->links('System.Layouts.Pagination')}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')

<!-- Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="assets/plugins/datatables/buttons.bootstrap.min.js"></script>
<script src="assets/plugins/datatables/jszip.min.js"></script>
<script src="assets/plugins/datatables/pdfmake.min.js"></script>
<script src="assets/plugins/datatables/vfs_fonts.js"></script>
<script src="assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="assets/plugins/datatables/buttons.print.min.js"></script>
<script src="assets/plugins/datatables/dataTables.fixedHeader.min.js"></script>
<script src="assets/plugins/datatables/dataTables.keyTable.min.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>
<script src="assets/plugins/datatables/dataTables.scroller.min.js"></script>

<!-- Datatable init js -->
<script src="assets/pages/datatables.init.js"></script>
<script>
    $('#wallet-table').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false,
        "order": [0,'desc']
        });
</script>
@endsection