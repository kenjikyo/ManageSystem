@extends('System.Layouts.Master')
@section('title', 'Admin-Interest')
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
<link
    href="datetime/plugins/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css"
    rel="stylesheet" />
<link href="datetime/plugins/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css"
    rel="stylesheet" />
<link href="datetime/plugins/boootstrap-datepicker/bootstrap-datepicker3.min.css"
    rel="stylesheet" />
<link href="datetime/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css"
    rel="stylesheet" />
<link href="datetime/plugins/bootstrap-daterange/daterangepicker.css"
    rel="stylesheet" />
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

    table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before,
    table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before {
        top: 20px !important;
    }
</style>

@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Interest</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Interest</li>
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
                        <div class="col-md-12">
                            <form method="GET" action="{{route('system.admin.getInterest')}}">
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
                                                                        <option value="">---select---</option>
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
                                                                    <option value="">---selected---</option>
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
                                                                    <a href="{{ route('system.admin.getInterest') }}"
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
                                            List Interest Table</h3>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <div style="float:left">
                                                    <button onclick="postCheck(1)"
                                                    id="btn-confirm-list" class="btn-filler btn btn-lg1 btn-success mr-20 btn-ajax"><i
                                                            class="fa fa-upload" aria-hidden="true"></i> Confirm
                                                        List</button>
                                                    <button onclick="postCheck(-1)"
                                                    id="btn-cancel-list" class="btn-filler btn btn-lg1 btn-danger mr-20 btn-ajax"><i
                                                            class="fa fa-upload" aria-hidden="true"></i> Cancel
                                                        List</button>
                                                    <button id="btn-check-all-show" onclick="checkAll()"
                                                        class="btn-filler btn btn-lg1 btn-success  mr-20"><i
                                                            class="fa fa-check" aria-hidden="true"></i><span
                                                            id="btn-check-all"> Check All</span></button>
                                                </div>
                                                <div style="clear:both"></div>
                                                {{$walletList->appends(request()->input())->links('System.Layouts.Pagination')}}
                                                <div style="clear:both"></div>
                                                <table id="dttable-wallet"
                                                    class="dt-responsive table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <?php
                                                        $check_money_confirm = 0;
                                                    ?>
                                                    <thead>
                                                        <tr>
                                                            <th data-priority="1">
                                                                Check
                                                            </th>
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
                                                        @foreach($walletList as $item)
                                                        <tr>
                                                            <td>
                                                                @if($item->Money_Confirm == 0)
                                                                    <?php
                                                                        $check_money_confirm++;
                                                                    ?>
                                                                    <div id="check_temp_{{$item->Money_ID}}" class="form-check">
                                                                        <input class="check_id"
                                                                            data-id="{{$item->Money_ID}}" type="checkbox"
                                                                            class="form-check-input"
                                                                            id="materialUnchecked-{{$item->Money_ID}}">
                                                                        <label class="form-check-label"
                                                                            for="materialUnchecked-{{$item->Money_ID}}"></label>
                                                                    </div>
                                                                @endif
                                                            </td>
                                                            @if($item->User_Level == 0)
                                                            <td><span data-id="{{$item->Money_ID}}"
                                                                    @if($item->Money_Confirm == 0)
                                                                        class="id_check_inte"
                                                                    @endif
                                                                    >{{$item->Money_ID}}</span>
                                                            </td>
                                                            <td>User</td>
                                                            @elseif($item->User_Level == 1)
                                                            <td class="bg-success"><span data-id="{{$item->Money_ID}}"
                                                                    @if($item->Money_Confirm == 0)
                                                                        class="id_check_inte"
                                                                    @endif
                                                                    >{{$item->Money_ID}}</span>
                                                            </td>
                                                            <td>Admin</td>
                                                            @elseif($item->User_Level == 2)
                                                            <td class="bg-info"><span data-id="{{$item->Money_ID}}"
                                                                    @if($item->Money_Confirm == 0)
                                                                        class="id_check_inte"
                                                                    @endif
                                                                    >{{$item->Money_ID}}</span>
                                                            </td>
                                                            <td>Finance</td>
                                                            @else
                                                            <td class="bg-warning"><span data-id="{{$item->Money_ID}}"
                                                                    @if($item->Money_Confirm == 0)
                                                                        class="id_check_inte"
                                                                    @endif
                                                                    >{{$item->Money_ID}}</span>
                                                            </td>
                                                            <td>Customer</td>
                                                            @endif
                                                            <td>{{$item->Money_User}}</td>
                                                            <td>{{number_format($item->Currency_Symbol != 'DBC' ? $item->Money_USDT : $item->Money_USDT*$item->Money_Rate,2)}}
                                                            </td>
                                                            <td>{{$item->Currency_Symbol == 'DBC' ? $item->Money_USDT : $item->Money_CurrentAmount}}
                                                            </td>
                                                            <!--<td>{{number_format($item->Money_USDT*$item->Money_Rate, 2)}}</td>-->
                                                            <td>{{number_format($item->Money_USDTFee, 2)}}</td>
                                                            <td>{{number_format($item->Money_Rate, 3)}}</td>
                                                            <td>{{$item->Currency_Symbol}}</td>
                                                            <td>{{$item->MoneyAction_Name}}</td>
                                                            <td>{{$item->Money_Comment}}</td>
                                                            <td>{{date('Y-m-d H:i:s',$item->Money_Time)}}</td>
                                                            <td>
                                                                @if($item->Money_MoneyStatus == 2)
                                                                @if($item->Money_Confirm == 0)
                                                                <span class="badge badge-warning">Pending</span>
                                                                @elseif($item->Money_Confirm == 1)
                                                                <span class="badge badge-success">Confirmed</span>
                                                                @else
                                                                <span class="badge badge-danger">Canceled</span>
                                                                @endif
                                                                @else
                                                                <span class="badge badge-danger">Error</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <a class="btn btn-rounded btn-primary btn-xs"
                                                                    href="{{ route('system.admin.getWalletDetail', $item->Money_ID) }}">Detail</a>
                                                                <a class="btn btn-rounded btn-success btn-xs"
                                                                    href="{{ route('system.admin.getWalletDetail', $item->Money_ID) }}?confirm=1">Confirm</a>
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
@endsection

@section('script')

<!-- THIS PAGE LEVEL JS -->
<script src="datetime/plugins/momentjs/moment.js"></script>
<script
    src="datetime/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js">
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
          "order": [[ 1, "desc" ]],
      });
      let arr_check = [];

      $('.check_id').on('click',function(){
          let check_id = $(this).data('id');
          let index = arr_check.indexOf(check_id);
          if( index == -1){
            arr_check.push(check_id);
          }
          else{
            arr_check.splice(index, 1) 
          }
      })
      let check_all = false;
      function checkAll(){
            if(!check_all){
                $('.id_check_inte').map(function(){
                    let check_id = $(this).data('id');
                    let index = arr_check.indexOf(check_id);
                    if( index == -1){
                        arr_check.push(check_id);
                        document.getElementById("materialUnchecked-"+check_id).checked = true;
                    }
                    // else{
                    //     arr_check.splice(index, 1);
                    //     document.getElementById("materialUnchecked-"+check_id).checked = false;
                    // }
                }).get();
                check_all = true;
                if($('.id_check_inte').length == arr_check.length)
                {
                    $('#btn-check-all').text(" Uncheck All");
                }
            }
            else{
                $('.id_check_inte').map(function(){
                    let check_id = $(this).data('id');
                    let index = arr_check.indexOf(check_id);
                    if( index != -1){
                        arr_check.splice(index, 1);
                        document.getElementById("materialUnchecked-"+check_id).checked = false;
                    }
                }).get();
                check_all = false;
                if($('.id_check_inte').length != arr_check.length)
                {
                    $('#btn-check-all').text(" Check All");
                }
            }
      }
      $(document)
        .ajaxStart(function () {
			$('.btn-ajax').attr('disabled', 'disabled');
        })
		.ajaxStop(function () {
			$('.btn-ajax').removeAttr('disabled');
        });
        let check_money_confirm = {{$check_money_confirm}};
        if(!check_money_confirm){
            $('#btn-confirm-list').hide();
            $('#btn-cancel-list').hide();
            $('#btn-check-all-show').hide();
        }
      function postCheck(status){
        $.each( arr_check, function( key, value ) {
            check_money_confirm--;
            document.getElementById("check_temp_"+value).remove();
        });
        if(check_money_confirm == 0){
            $('#btn-confirm-list').hide();
            $('#btn-cancel-list').hide();
            $('#btn-check-all-show').hide();
        }
	    //status = 1: confirm, 2: cancel
        $.ajax({
            url : "{{route('system.admin.postCheckInterestList')}}",
            type : "POST",
            dataType:"json",
            data : {
                _token : "{{ csrf_token() }}",
                arr_check : arr_check,
                type : status
            },
            success : function (result){
                arr_check = [];
                if(result.status == false){
			        toastr.error(result.message, 'Error!', {timeOut: 6000});
                }else{
                    toastr.success(result.message, 'Success!', {timeOut: 6000});
                }
            }
        });
      }
</script>
@endsection