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

@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">DETAIL WALLET</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">DETAIL WALLET</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div>
                                        <h3 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            Detail Wallet</h3>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="fa fa-user" aria-hidden="true"></i>
                                                                ID</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Money_ID }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="fa fa-users" aria-hidden="true"></i>
                                                                User ID</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Money_User }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="fa fa-envelope" aria-hidden="true"></i>
                                                                Email</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->User_Email }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10"><i
                                                                    class="mdi mdi-radioactive" aria-hidden="true"></i>
                                                                Action</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->MoneyAction_Name }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="mdi mdi-timer" aria-hidden="true"></i>
                                                                Time</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ date('Y/m/d H:i:s', $detail->Money_Time) }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="icon-diamond" aria-hidden="true"></i>
                                                                Amount</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Money_USDT }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="icon-diamond" aria-hidden="true"></i>
                                                                Currency</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Currency_Name }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="icon-diamond" aria-hidden="true"></i>
                                                                Amount Coin</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Money_CurrentAmount }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left">- Fee</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Money_USDTFee }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="mdi mdi-comment" aria-hidden="true"></i>
                                                                Comment</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Money_Comment }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="control-label mb-10"><i
                                                                    class="mdi mdi-emoticon-excited-outline"
                                                                    aria-hidden="true"></i>
                                                                Status</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ ($detail->Money_MoneyAction == 2) ? ($detail->Money_Confirm == 1 ? 'Success' : 'Processing') : (($detail->Money_MoneyStatus==0) ? 'Cancel' : 'Success') }}">
                                                        </div>
                                                        @if($detail->Money_MoneyAction == 2)
                                                        <div class="form-group">
                                                            <label class="control-label mb-10 text-left"><i
                                                                    class="mdi mdi-pencil-outline"
                                                                    aria-hidden="true"></i>
                                                                Address</label>
                                                            <input type="text" class="form-control" readonly=""
                                                                value="{{ $detail->Money_Address }}">
                                                        </div>
                                                        @endif
                                                        <div class="seprator-block"></div>
                                                        @if($detail->Money_MoneyAction == 2 &&
                                                        Session('user')->User_Level == 1 && $detail->Money_Confirm == 0
                                                        )
                                                        <form method="GET" action="" id="confirm-wallet">
                                                            <div class="form-actions mt-10">
                                                                <input type="hidden" name="confirm" id="input-confirm"
                                                                    value="">
                                                                <button type="button"
                                                                    class="btn btn-success mr-10 btn-confirm"
                                                                    data-confirm="1"><i class="fa fa-check-square-o"
                                                                        aria-hidden="true"></i>
                                                                    Confirm</button>
                                                                <button type="button"
                                                                    class="btn btn-danger  mr-10 btn-confirm"
                                                                    data-confirm="-1"><i class="fa fa-flus"
                                                                        aria-hidden="true"></i>
                                                                    Cancel</button>
                                                            </div>
                                                        </form>
                                                        @endif
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
    var today = new Date();
    var currentDate = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
</script>
@endsection