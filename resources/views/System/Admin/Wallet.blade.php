@extends('System.Layouts.Master')
@section('title', 'Admin-Wallet')
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

<!--THIS PAGE LEVEL CSS-->
<link href="datetime/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
    rel="stylesheet" />
<link href="datetime/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css" rel="stylesheet" />
<link href="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker3.min.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" />
<link href="datetime/plugins/bootstrap-daterange/daterangepicker.css" rel="stylesheet" />
<link href="datetime/plugins/clockface/css/clockface.css" rel="stylesheet" />
<link href="datetime/plugins/clockpicker/clockpicker.css" rel="stylesheet" />
<!--REQUIRED THEME CSS -->
<link href="datetime/assets/css/style.css" rel="stylesheet">
<link href="datetime/assets/css/themes/main_theme.css" rel="stylesheet" />
<style>
    .dtp-btn-cancel {
        background: #9E9E9E;
    }

    .dtp-btn-ok {
        background: #009688;
    }

    .dtp-btn-clear {
        color: black;
    }

    .btn-filler {
        margin-bottom: 10px;
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
                    <h4 class="pull-left page-title">Wallet</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Wallet</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <!-- /Title -->
                    <div class="row">
                        <div class="col-md-4">
                            <form method="POST" id="post-deposit" action="{{route('system.admin.postDepositAdmin')}}">
                                @csrf
                                <div class="panel panel-default card-view">
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><i class="fa fa-users"></i>
                                                                User ID</label>
                                                            <input type="text" class="form-control" name="user"
                                                                id="exampleInputEmail1" placeholder="Enter User ID">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="exampleInputEmail1"><i class="fa fa-money"></i>
                                                                Amount</label>
                                                            <input type="number" step="any" name="amount"
                                                                class="form-control" placeholder="Enter amount USD">
                                                        </div>
                                                        <label><i class="fa fa-hand-o-down"></i> Currency</label>
                                                        <div class="form-group row">
                                                            <div class="col-sm-12">
                                                                <select class="form-control c-select" name="coin">
                                                                    <option value="5" selected="">USDX</option>
                                                                    <option value="2">ETH</option>

                                                                    <option value="8">SOX</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="m-t-43">
                                                            <button type="submit" class="btn btn-success"
                                                                id="btn-deposit"><i class="fa fa-paper-plane"
                                                                    aria-hidden="true"></i>
                                                                Deposit</button>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-8">
                            <form method="GET" action="{{route('system.admin.getWallet')}}">
                                @csrf
                                <div class="panel panel-default card-view">
                                    <div class="panel-wrapper collapse in">
                                        <div class="panel-body">
                                            <div class="form-wrap">
                                                <div class="form-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-user"
                                                                        aria-hidden="true"></i> User ID</label>
                                                                <input type="text" name="user_id" class="form-control"
                                                                    placeholder="Enter User ID"
                                                                    value="{{request()->input('user_id')}}">
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputuname_1"><i
                                                                        class="fa fa-chevron-down"
                                                                        aria-hidden="true"></i>
                                                                    Action</label>
                                                                <div class="form-group">
                                                                    <select name="action" class="form-control">
                                                                        <option value="">--- Select ---</option>
                                                                        @foreach($action as $a)
                                                                        <option value="{{$a->MoneyAction_ID}}"
                                                                            {{request()->input('action') == $a->MoneyAction_ID ? 'selected' : ''}}>
                                                                            {{$a->MoneyAction_Name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    From</label>
                                                                <input type='text' name="datefrom" id="datefrom"
                                                                    class="form-control"
                                                                    value="{{request()->input('datefrom')}}" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Status</label>
                                                                <select name="status" class="form-control">
                                                                    <option value="">--- Select ---</option>
                                                                    <option value="2"
                                                                        {{request()->input('status') == 2 ? 'selected' : ''}}>
                                                                        Pending</option>
                                                                    <option value="1"
                                                                        {{request()->input('status') == 1 ? 'selected' : ''}}>
                                                                        Confirmed</option>
                                                                    <option value="-1"
                                                                        {{request()->input('status') == -1 ? 'selected' : ''}}>
                                                                        Canceled</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    To</label>
                                                                <input type='text' name="dateto" id="dateto"
                                                                    class="form-control"
                                                                    value="{{request()->input('dateto')}}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit"
                                                                        class="btn-filler btn btn-lg1 btn-primary"><i
                                                                            class="fa fa-search" aria-hidden="true"></i>
                                                                        Search
                                                                    </button>
                                                                    <button type="submit" name="export" value="1"
                                                                        class="btn-filler btn btn-lg1 btn-success  mr-10"><i
                                                                            class="fa fa-file-excel-o"
                                                                            aria-hidden="true"></i> Export</button>
                                                                    <a href="{{ route('system.admin.getWallet') }}"
                                                                        class="btn-filler btn btn-default mr-10">Cancel</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div>
                                        <h3 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            List Wallet Table</h3>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                {{$walletList->appends(request()->input())->links('System.Layouts.Pagination')}}
                                                <div style="clear:both"></div>
                                                <table id="dttable-wallet"
                                                    class="table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th data-toggle="true">
                                                                ID
                                                            </th>
                                                            <th data-hide="phone">
                                                                LEVEL
                                                            </th>
                                                            <th data-hide="phone">
                                                                USER ID
                                                            </th>
                                                            <th data-hide="phone">
                                                                AMOUNT
                                                            </th>
                                                            <th data-hide="phone">
                                                                AMOUNT COIN
                                                            </th>
                                                            <th data-hide="phone">
                                                                FEE
                                                            </th>
                                                            <th data-hide="phone">
                                                                RATE
                                                            </th>
                                                            <th data-hide="phone">
                                                                CURRENCY
                                                            </th>
                                                            <th data-hide="phone">
                                                                ACTION
                                                            </th>
                                                            <th data-hide="phone">
                                                                COMMENT
                                                            </th>
                                                            <th data-hide="phone">
                                                                TIME
                                                            </th>
                                                            <th data-hide="phone">
                                                                STATUS
                                                            </th>
                                                            <th data-hide="phone">
                                                                ACTION
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $arr=[
                                                        ['bg'=>'','name'=>'User'],
                                                        ['bg'=>'success','name'=>'Admin'],
                                                        ['bg'=>'warning','name'=>'Finance'],
                                                        ['bg'=>'info','name'=>'Support'],
                                                        ['bg'=>'text-light bg-dark','name'=>'Customer'],
                                                        ['bg'=>'text-light bg-primary','name'=>'Bot'],
                                                        ];
                                                        @endphp
                                                        @foreach($walletList as $item)
                                                        <tr>
                                                            <td class="{{$arr[$item->User_Level]['bg']}}">
                                                                {{$item->Money_ID}}</td>
                                                            <td>{{$arr[$item->User_Level]['name']}}</td>
                                                            <td>{{$item->Money_User}}</td>
                                                            <td>{{number_format($item->Currency_Symbol != 'SOX' ? $item->Money_USDT : $item->Money_USDT*$item->Money_Rate,2)}}
                                                            </td>
                                                            <td>{{$item->Currency_Symbol == 'SOX' ? $item->Money_USDT : $item->Money_CurrentAmount}}
                                                            </td>
                                                            <!--<td>{{number_format($item->Money_USDT*$item->Money_Rate, 2)}}</td>-->
                                                            <td>{{number_format($item->Money_USDTFee, 2)}}</td>
                                                            <td>{{number_format($item->Money_Rate, 3)}}</td>
                                                            <td>{{$item->Currency_Symbol}}</td>
                                                            <td>{{$item->MoneyAction_Name}}</td>
                                                            <td>{{$item->Money_Comment}}</td>
                                                            <td>{{date('Y-m-d H:i:s',$item->Money_Time)}}</td>
                                                            <td>
                                                                @if($item->Money_MoneyStatus == 1)
                                                                @if($item->Money_MoneyAction == 2 &&
                                                                $item->Money_Confirm == 0)
                                                                <span class="badge badge-warning">Pending</span>

                                                                @else
                                                                <span class="badge badge-success">Confirmed</span>
                                                                @endif
                                                                @else
                                                                <span class="badge badge-warning">Error</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-rounded btn-primary btn-xs"
                                                                    href="{{ route('system.admin.getWalletDetail', $item->Money_ID) }}">Detail</a>
                                                                <button style="margin-top: 8px;"
                                                                    class="btn__detail btn-rounded btn-light btn-xs"
                                                                    data-action="{{ route('putEditDataMoney' , $item->Money_ID)}}"
                                                                    data-show="{{ route('getFetchDataMoney' , $item->Money_ID)}}">Update</button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{$walletList->appends(request()->input())->links('System.Layouts.Pagination')}}
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
</div>
<div id="modalDetail" class="modal fade animate" data-backdrop="true">
    <div class="modal-dialog" id="animate">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style=" margin: 9px 0px 0px 20px;" class="modal-title" id="myModalLabel">
                    Change Money
                </h4>
            </div>
            <!-- Modal Body -->
            <div class="modal-body">
                <form id="formUpdate" method="POST">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>Money_ID</label>
                        <input type="text" class="form-control" name="Money_ID" id="Money_ID">
                    </div>
                    <div class="form-group">
                        <label>Money_User</label>
                        <input type="text" class="form-control" name="Money_User" id="Money_User">
                    </div>
                    <div class="form-group">
                        <label>Money_USDT</label>
                        <input type="text" class="form-control" name="Money_USDT" id="Money_USDT">
                    </div>

                    <div class="form-group">
                        <label>Money_USDTFee</label>
                        <input type="text" class="form-control" name="Money_USDTFee" id="Money_USDTFee">
                    </div>
                    <div class="form-group">
                        <label>Money_SaleBinary</label>
                        <input type="text" class="form-control" name="Money_SaleBinary" id="Money_SaleBinary">
                    </div>

                    <div class="form-group">
                        <label>Money_Investment</label>
                        <input type="text" class="form-control" name="Money_Investment" id="Money_Investment">
                    </div>
                    <div class="form-group">
                        <label>Money_Borrow</label>
                        <input type="text" class="form-control" name="Money_Borrow" id="Money_Borrow">
                    </div>

                    <div class="form-group">
                        <label>Money_Time</label>
                        <input type="text" class="form-control" name="Money_Time" id="Money_Time">
                    </div>
                    <div class="form-group">
                        <label>Money_Comment</label>
                        <input type="text" class="form-control" name="Money_Comment" id="Money_Comment">
                    </div>

                    <div class="form-group">
                        <label>Money_MoneyAction</label>
                        <select name="Money_MoneyAction" id="Money_MoneyAction" class="form-control">

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Money_MoneyStatus</label>
                        <input type="text" class="form-control" name="Money_MoneyStatus" id="Money_MoneyStatus">
                    </div>
                    <div class="form-group">
                        <label>Money_Token</label>
                        <input type="text" class="form-control" name="Money_Token" id="Money_Token">
                    </div>
                    <div class="form-group">
                        <label>Money_TXID</label>
                        <input type="text" class="form-control" name="Money_TXID" id="Money_TXID">
                    </div>
                    <div class="form-group">
                        <label>Money_Address</label>
                        <input type="text" class="form-control" name="Money_Address" id="Money_Address">
                    </div>
                    <div class="form-group">
                        <label>Money_Currency</label>
                        <select name="Money_Currency" id="Money_Currency" class="form-control">

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Money_CurrentAmount</label>
                        <input type="text" class="form-control" name="Money_CurrentAmount" id="Money_CurrentAmount">
                    </div>
                    <div class="form-group">
                        <label>Money_Rate</label>
                        <input type="text" class="form-control" name="Money_Rate" id="Money_Rate">
                    </div>
                    <div class="form-group">
                        <label>Money_Confirm</label>
                        <input type="text" class="form-control" name="Money_Confirm" id="Money_Confirm">
                    </div>
                    <div class="form-group">
                        <label>Money_Confirm_Time</label>
                        <input type="text" class="form-control" name="Money_Confirm_Time" id="Money_Confirm_Time">
                    </div>
                    <div class="input-group m-t-5">
                        <button type="submit" class="btn btn-primary btn-block  btn-anim">Update</button>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
@endsection

@section('script')

<!-- THIS PAGE LEVEL JS -->
<script src="datetime/plugins/momentjs/moment.js"></script>
<script src="datetime/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
</script>
<script src="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker.min.js">
</script>
<script src="datetime/plugins/bootstrap-datetime-picker/js/bootstrap-datetimepicker.js">
</script>
<script src="datetime/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js">
</script>
<script src="datetime/plugins/bootstrap-daterange/daterangepicker.js"></script>
<script src="datetime/plugins/clockface/js/clockface.js"></script>
<script src="datetime/plugins/clockpicker/clockpicker.js"></script>

<script src="datetime/assets/js/pages/forms/date-time-picker-custom.js"></script>
<script>
    $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });

  $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', time: false, clearButton: true });
</script>
<script>
    var e=$("#demo-foo-col-exp");
    $("#demo-input-search2").on("input",function(o){o.preventDefault(),e.trigger("footable_filter",{filter:$(this).val()})})
</script>

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
    var today = new Date();
    var currentDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
    $('#revenue-product').DataTable({
    dom: 'Bfrtip',
    "order": [[ 7, "desc" ]],
    buttons: [
    {
    extend: 'excelHtml5',
    title: "Wallet-"+currentDate
    }
    ]
    });
    $('#dttable-wallet').DataTable({
          "bLengthChange": false,
        "searching": false,
          "paging": false,
          "order": [0,'desc']
      });
    $('#post-deposit').submit(function() {
        $(this).find("button[type='submit']").prop('disabled',true);
    });
</script>
<script>
    $('#Money_Time').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD HH:mm:ss', time: true, clearButton: true });
    $('#Money_Confirm_Time').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD HH:mm:ss', time: true, clearButton: true });
    $(document).ready(function () {
        
        $('.btn__detail').click(function(){
            let action = $(this).data('action');
            $('#formUpdate').attr('action', action);

            let show = $(this).data('show');

            $.ajax({
                type: "GET",
                url: show,
                success: function (data) {

                    if(data.status == 200){
                        $('#modalDetail').modal('show');
                        let list = data.list;
                        $('#Money_ID').val(list.Money_ID);
                        $('#Money_User').val(list.Money_User);
                        $('#Money_USDT').val(list.Money_USDT);
                        $('#Money_USDTFee').val(list.Money_USDTFee);
                        $('#Money_SaleBinary').val(list.Money_SaleBinary);
                        $('#Money_Investment').val(list.Money_Investment);
                        $('#Money_Borrow').val(list.Money_Borrow);
                        $('#Money_Time').val(list.Money_Time);
                        $('#Money_Comment').val(list.Money_Comment);
                        
                        $('#Money_MoneyStatus').val(list.Money_MoneyStatus);
                        $('#Money_Token').val(list.Money_Token);
                        $('#Money_TXID').val(list.Money_TXID);
                        $('#Money_Address').val(list.Money_Address);
                        
                        $('#Money_CurrentAmount').val(list.Money_CurrentAmount);
                        $('#Money_Rate').val(list.Money_Rate);
                        $('#Money_Confirm').val(list.Money_Confirm);
                        $('#Money_Confirm_Time').val(list.Money_Confirm_Time);
                        let actionmoney = data.action;
                        let temp_html_action = '';
                        Object.keys(actionmoney).forEach(function(key){
                            console.log(actionmoney[key].MoneyAction_ID);
                            temp_html_action += `<option value="`+ actionmoney[key].MoneyAction_ID+`" `+ (list.Money_MoneyAction == actionmoney[key].MoneyAction_ID ? 'selected' : '') +`>`+ actionmoney[key].MoneyAction_Name +`</option>`;
                        })
                        $('#Money_MoneyAction').append(temp_html_action);
                        let currency = data.currency;
                        let temp_html_currency = '';
                        Object.keys(currency).forEach(function(key){
                            temp_html_currency += `<option value="`+ currency[key].Currency_ID+`" `+ (list.Money_Currency == currency[key].Currency_ID ? 'selected' : '') +`>`+ currency[key].Currency_Name +`</option>`;
                        })
                        $('#Money_Currency').append(temp_html_currency);
                    }
                    else{
                        alert('Data Fail!');
                    }
                }
            });
        });
    });

</script>
@endsection