@extends('System.Layouts.Master')
@section('title', 'Admin-Edit Investment')
@section('css')


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


</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Edit Investment</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Edit Investment</li>
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
                                            Edit Investment</h3>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">

                                                    <form method="POST" action="{{route('system.admin.postEditInvestment')}}"
                                                        id="confirm-wallet">
                                                        @csrf
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-users" aria-hidden="true"></i>
                                                                    User ID</label>
                                                                <input type="text" name="investment_User"
                                                                    class="form-control"
                                                                    value="{{ $info_invest->investment_User }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-envelope" aria-hidden="true"></i>
                                                                    Amount</label>
                                                                <input type="number" step=any name="investment_Amount"
                                                                    class="form-control"
                                                                    value="{{ $info_invest->investment_Amount }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Package</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="investment_Package">
                                                                    @foreach ($package as $item)
                                                                    <option value="{{$item->package_ID}}"
                                                                        {{$info_invest->investment_Package == "$item->package_ID" ? 'selected' : ''}}>
                                                                        {{number_format($item->package_Min)}}$ -
                                                                        {{number_format($item->package_Max)}}$ ({{$item->package_Interest*100}}%/Month)
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Currency</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="investment_Currency">
                                                                    @foreach ($currency as $item)
                                                                    <option value="{{$item->Currency_ID}}"
                                                                        {{$info_invest->investment_Currency == $item->Currency_ID ? 'selected' : ''}}>
                                                                        {{$item->Currency_Name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="icon-diamond" aria-hidden="true"></i>
                                                                    Rate</label>
                                                                <input name="investment_Rate" type="number" step=any
                                                                    class="form-control"
                                                                    value="{{ $info_invest->investment_Rate }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Package Time</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="investment_Package_Time">
                                                                    @foreach ($package_time as $item)
                                                                    <option value="{{$item->time_Month}}"
                                                                        {{$info_invest->investment_Package_Time == $item->time_Month ? 'selected' : ''}}>
                                                                        {{$item->time_Month}} ( FEE: {{$item->time_Fee*100}}% ) </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"><i
                                                                        class="mdi mdi-emoticon-excited-outline"
                                                                        aria-hidden="true"></i>
                                                                    Time</label>
                                                                <input id="datetimeedit" name="investment_Time" type="datetime"
                                                                    value="{{date('Y-m-d H:i:s', ($info_invest->investment_Time))}}"
                                                                    class="form-control">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Status</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="investment_Status">
                                                                    <option value="0"
                                                                        {{$info_invest->investment_Status == 0 ? 'selected' : ''}}>
                                                                        Waiting</option>
                                                                    <option value="1"
                                                                        {{$info_invest->investment_Status == 1 ? 'selected' : ''}}>
                                                                        Active</option>
                                                                        <option value="2"
                                                                        {{$info_invest->investment_Status == 2 ? 'selected' : ''}}>
                                                                        Refunded</option>
                                                                        <option value="-1"
                                                                        {{$info_invest->investment_Status == -1 ? 'selected' : ''}}>
                                                                        Admin Cancel</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-actions mt-10">
                                                                <input type="hidden" name="investment_ID"
                                                                    value="{{$info_invest->investment_ID}}">
                                                                <button type="submit"
                                                                    class="btn btn-success mr-10 btn-confirm"
                                                                    data-confirm="1"><i class="fa fa-save"
                                                                        aria-hidden="true"></i>
                                                                    Save</button>
                                                                <a href="{{route('system.admin.InvestmentList')}}"
                                                                    type="button"
                                                                    class="btn btn-danger  mr-10 btn-confirm"
                                                                    data-confirm="-1"><i class="fa fa-angle-left"
                                                                        aria-hidden="true"></i>
                                                                    Back</a>
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
    $('#datetimeedit').bootstrapMaterialDatePicker({ format : 'YYYY-MM-DD HH:mm:ss', time: true, clearButton: true });

</script>
@endsection
