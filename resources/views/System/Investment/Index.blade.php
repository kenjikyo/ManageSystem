@extends('System.Layouts.Master')
@section('title', 'Investment')
@section('css')
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

    .text-red {
        color: #F44336;
    }
</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Investment</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active">Investment</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-md-3 col-sm-0"></div>
            <div class="col-md-6 col-sm-12">
                <div class="panel panel-default card-view">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="pull-left">
                                <h3 class="panel-title">INVESTMENT PLAN</h3>
                            </div>
                            <div class="pull-right">
                                <h4 class="control-label text-white m-0">Balance:
                                    <span class="balance_coin text-yellow"></span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-wrapper collapse in">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form method="post" action="{{route('system.postInvestment')}}"
                                        id="form-investment">
                                        @csrf
                                        
                                        <div class="form-wrap">
                                            <div class="form-group">

                                                <label class="control-label mb-10">Currency</label>
                                                <div class="form-group">
                                                    <select name="currency" id="currency" class="form-control">
                                                        <option value="5">USDX</option>
                                                        <option value="8">SOX</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-10">Amount
                                                    <span class="symbol_coin_USDX text-red">USDX</span></label>
                                                <div class="form-group">
                                                    <input type="text" name="investment_amount_USDX"
                                                        id="investment_amount_USDX" class="form-control" step="any">
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-10">Amount
                                                    <span class="symbol_coin_SOX text-red">SOX</span></label>
                                                <div class="form-group">
                                                    <input type="text" name="investment_amount_SOX"
                                                        id="investment_amount_SOX" class="form-control" step="any">
                                                </div>

                                            </div>
                                            <div class="form-group">
                                                <label class="control-label mb-10">Choose Month</label>
                                                <div class="form-group">
                                                    <select name="investment_month" id="investment_month"
                                                        class="form-control">
                                                        @foreach ($package_time as $item)
                                                        <option value="{{ $item->time_Month}}">{{ $item->time_Month}}
                                                            Months </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group text-center">
                                                <button type="submit"
                                                    class="btn btn-bd1 waves-effect btn-lg1 btn-success"
                                                    id="btn-invest"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                                                    Invest

                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">History</h3>
                    </div>
                    <div class="panel-body">
                        <table id="dt-dashboard" class="table table-striped table-bordered dt-responsive nowrap"
                            cellspacing="0" width="100%">
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
                                    <td>{{ $item->investment_Rate}}</td>
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

                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container -->

</div> <!-- content -->

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
    $(document).ready(function () {
    Main();
    function Main(){
        _balance = { 5: '{{ $balance->USD }}', 8: '{{ $balance->SOX }}' };
        _rate = {5: '{{ $rate['USD'] }}', 8: '{{ $rate['SOX'] }}'};
        _symbol_coin = { 5: 'USDX', 8: 'SOX' }
        _selected_balance =  $('#currency').val();
    }

    Balance(_selected_balance);
    function Balance(name_balance){
        Main();
        $get_balance = _balance[name_balance];
        $symbol_balance = _symbol_coin[name_balance];
        $('.balance_coin').html($get_balance+' '+$symbol_balance);
        $('.symbol_coin').html($symbol_balance);
    }

    $('#currency').change(function(){
        Main();
        _selected_balance =  $(this).val();
        Balance(_selected_balance);

    });
    $('#investment_amount_USDX').keyup(function(){
        Main();
        $('#investment_amount_SOX').val( $(this).val() / _rate[8]);
        
        
    });
    $('#investment_amount_SOX').keyup(function(){

        Main();
        $('#investment_amount_USDX').val( $(this).val() * _rate[8]);
        
        
    });
});


</script>
<script>
    $('#form-investment').submit(function(e){
        e.preventDefault();
        $amount_coin = 0;
        if(_selected_balance == 8){
           $amount_coin =  $('#investment_amount_SOX').val();
        }else{
            $amount_coin = $('#investment_amount_USDX').val();
        }
        swal.fire({
            title: 'Confirm Investment',
            text: 'Are You Sure Investment '+$amount_coin+' '+_symbol_coin[_selected_balance]+' ?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            confirmButtonClass: 'btn btn-confirm',
            cancelButtonClass: 'btn btn-cancel',
            closeOnConfirm: true
        }).then(function (confirm) {
            console.log(confirm);
            if(confirm.value == true){
                document.forms[0].submit();
            }
        });
    });

</script>
<script>
    $('#dt-dashboard').DataTable({
          "bLengthChange": false,
        "searching": false,
          "paging": false,
          "order": [0, 'desc']
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