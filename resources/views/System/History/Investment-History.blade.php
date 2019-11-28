@extends('System.Layouts.Master')
@section('title', 'Investment History')
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

    .pagination {
        float: right;
    }
</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Investment History</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active">Investment History</li>
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
                                        INVESTMENT HISTORY</h6>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            {{$history_invest->appends(request()->input())->links('System.Layouts.Pagination')}}
                                            <div style="clear:both"></div>
                                            <table id="invesment-table"
                                                class="table table-striped table-bordered dt-responsive" cellspacing="0"
                                                width="100%">
                                                <thead>

                                                    <tr>
                                                        <th>#</th>
                                                        <th>Amount</th>
                                                        <th>Interest/Month(%)</th>
                                                        <th>Month Refund</th>
                                                        <th>Currency</th>
                                                        <th>Rate</th>
                                                        <th>Time</th>
                                                        <th>Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($history_invest as $item)
                                                    <tr>
                                                        <td>{{ $item->investment_ID}}</td>
                                                        <td>{{ number_format($item->investment_Amount, 4) }}</td>
                                                        <td>{{ $item->package_Interest * 100 }}%</td>
                                                        <td>{{ $item->investment_Package_Time}}</td>
                                                        <td>{{ $item->Currency_Name}}</td>
                                                        <td>{{ $item->investment_Rate+0}}</td>
                                                        <td>{{ Date('Y-m-d H:i:s', $item->investment_Time)}}</td>
                                                        <td>
	                                                        @if($item->investment_Status == 1)
                                                            <span class="badge badge-success">Active</span>
                                                            @elseif($item->investment_Status == 2)
                                                            <span class="badge badge-info">Refunded</span>
                                                            @elseif($item->investment_Status == 0)
                                                            <span class="badge badge-warning">Waiting</span>
                                                            @else
                                                            <span class="badge badge-danger">Canceled</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                        @if($item->investment_Status == 0)
                                                            <form class="refund-{{$item->investment_ID}}" action="{{ route('postActionRefund', $item->investment_ID) }}" method="POST" style="margin-bottom: 10px;">
                                                                @csrf @method('PUT')
                                                                <button type="button" class="btn-refund btn btn-success" data-invest-id="{{ $item->investment_ID}}">Refund</button>
                                                            </form>

                                                            <form class="reinvest-{{$item->investment_ID}}" action="{{ route('postActionReinvestment', $item->investment_ID) }}" method="POST">
                                                                @csrf @method('PUT')
                                                                <button type="button" class="btn-reinvest btn btn-danger" data-invest-id="{{ $item->investment_ID}}">Reinvestment</button>
                                                            </form>
                                                        @endif
                                                        </td>
                                                        
                                                    </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                            {{$history_invest->appends(request()->input())->links('System.Layouts.Pagination')}}
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
    $('#invesment-table').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false,
        "order": [0,'desc']
    });
</script>
<script>
$(document).ready(function () {
    $('.btn-refund').click(function(){
        let invest_id = $(this).data('invest-id');
        console.log(invest_id);
        swal.fire({
            title: 'Confirm Refund Investment',
            text: 'Are You Sure Refund Investment',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            confirmButtonClass: 'btn btn-confirm',
            cancelButtonClass: 'btn btn-cancel',
            closeOnConfirm: true
        }).then(function (confirm) {
            console.log(confirm);
            if(confirm.value == true){
                $('.refund-'+invest_id).submit();

            }
        });
    })


    $('.btn-reinvest').click(function(){
        let invest_id = $(this).data('invest-id');
        console.log(invest_id);
        swal.fire({
            title: 'Confirm Reinvestment',
            text: 'Are You Sure Reinvestment',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            confirmButtonClass: 'btn btn-confirm',
            cancelButtonClass: 'btn btn-cancel',
            closeOnConfirm: true
        }).then(function (confirm) {
            console.log(confirm);
            if(confirm.value == true){
                $('.reinvest-'+invest_id).submit();

            }
        });
    })
});

</script>
@endsection