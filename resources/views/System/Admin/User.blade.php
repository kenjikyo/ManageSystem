@extends('System.Layouts.Master')
@section('title', 'Admin User')
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

    .btn-filler {
        margin-bottom: 10px;
    }

    .pagination {
        float: right;
    }
</style>

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

    .edit-email-input {
        padding-left: 10px;
        border: 2px dotted #f5b61a;
        width: 79%;
    }
</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Member</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Member</li>
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
                            <form method="GET" action="{{route('system.admin.getMemberListAdmin')}}">
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
                                                                <input class="form-control" type="text"
                                                                    placeholder="User ID"
                                                                    value="{{request()->input('UserID')}}"
                                                                    name="UserID">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Email</label>
                                                                <input class="form-control" type="text"
                                                                    placeholder="Email"
                                                                    value="{{request()->input('Email')}}" name="Email">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Created Date</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Registration Time" name="datetime"
                                                                    id="datetime"
                                                                    value="{{request()->input('datetime')}}" />
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Sponsor</label>
                                                                <input class="form-control" type="text"
                                                                    placeholder="Sponsor"
                                                                    value="{{request()->input('sponsor')}}"
                                                                    name="sponsor">
                                                            </div>
                                                        </div>


                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Tree</label>
                                                                <input class="form-control" type="text"
                                                                    placeholder="Tree"
                                                                    value="{{request()->input('tree')}}" name="tree">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Status Mail</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="status_email">
                                                                    <option selected value=""
                                                                        {{request()->input('status_email') == '' ? 'selected' : ''}}>
                                                                        --- Select ---</option>
                                                                    <option value="1"
                                                                        {{request()->input('status_email') == '1' ? 'selected' : ''}}>
                                                                        Active</option>
                                                                    <option value="0"
                                                                        {{request()->input('status_email') == '0' ? 'selected' : ''}}>
                                                                        Not Active</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                    <label class="control-label mb-10"
                                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                                            aria-hidden="true"></i> Level</label>
                                                                    <select id="inputState" class="form-control"
                                                                        name="level">
                                                                        <option selected value=""
                                                                        {{request()->input('level') == '' ? 'selected' : ''}}>
                                                                        --- Select ---</option>
                                                                        @foreach ($user_level as $item)
                                                                            <option value="{{$item->User_Level_ID}}"
                                                                            {{request()->input('level') == "$item->User_Level_ID" ? 'selected' : ''}}>
                                                                            {{$item->User_Level_Name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                    <label class="control-label mb-10"
                                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                                            aria-hidden="true"></i> Agency Level</label>
                                                                    <select id="inputState" class="form-control"
                                                                        name="agency_level">
                                                                        <option selected value=""
                                                                        {{request()->input('agency_level') == '' ? 'selected' : ''}}>
                                                                        --- Select ---</option>
                                                                        @foreach ($user_agency_level as $item)
                                                                            <option value="{{$item->user_agency_level_ID}}"
                                                                            {{request()->input('agency_level') == "$item->user_agency_level_ID" ? 'selected' : ''}}>
                                                                            {{$item->user_agency_level_Name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                        </div>


                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <div class="form-actions mt-10">
                                                                {{--                                                        <button type="submit" class="btn btn-lg1 btn-success  mr-10"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>--}}
                                                                <button type="submit"
                                                                    class="btn-filler btn btn-lg1 btn-primary waves-effect"><i
                                                                        class="fa fa-search" aria-hidden="true"></i>
                                                                    Search
                                                                </button>
                                                                <button type="submit"
                                                                    class="btn-filler btn btn-success waves-effect"
                                                                    style="" name="export" value="1"><i
                                                                        class="fa fa-print" aria-hidden="true"></i>
                                                                    Export</button>
                                                                <a href="{{ route('system.admin.getMemberListAdmin') }}"
                                                                    class="btn-filler btn btn-default mr-10">Cancel</a>
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
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            List User</h6>
                                    </div>
                                </div>

                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                {{$user_list->appends(request()->input())->links('System.Layouts.Pagination')}}
                                                <div style="clear:both"></div>
                                                <table id="member-list-table"
                                                    class=" dt-responsive table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th data-toggle="true">
                                                                ID
                                                            </th>
                                                            <th data-toggle="true">
                                                                Name
                                                            </th>
                                                            <th>
                                                                MAIL
                                                            </th>
                                                            <th data-toggle="true">
                                                                Phone
                                                            </th>
                                                            <th data-hide="phone">
                                                                REGISTERED DATE
                                                            </th>
                                                            <th data-hide="phone">
                                                                PARENT
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                TREE
                                                            </th>
                                                            <th data-hide="phone">
                                                                Level
                                                            </th>
                                                            <th data-hide="phone">
                                                                Agency Level
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                STATUS
                                                            </th>
                                                            <th data-hide="phone">
                                                                AUTH
                                                            </th>
                                                            <th data-hide="phone">
                                                                ACTION
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($user_list as $v)
                                                        <tr>
                                                            <td><b>{{ $v->User_ID }}</b></td>
                                                            <td>{{$v->User_Name}}</td>
                                                            <td>{{$v->User_Email}}</td>
                                                            <td>{{$v->User_Phone}}</td>
                                                            <td>{{ $v->User_RegisteredDatetime }}</td>
                                                            <td>{{ $v->User_Parent }}</td>
                                                            <td width="200px">
                                                                <div
                                                                    style="overflow:auto;width:300px!important;height:60px">
                                                                    {{ str_replace(',',', ', $v->User_Tree) }}</div>
                                                            </td>
                                                            <td>{{ $v->User_Level_Name }}</td>
                                                            <td>{{ $v->User_Agency_Level }}</td>
                                                            <td>
                                                                @if($v->User_EmailActive == 0)
                                                                <span class="badge badge-danger r-3 blink">Not
                                                                    Active Mail</span>
                                                                @else
                                                                <span class="badge badge-warning r-3">Active Mail</span>
                                                                @endif
                                                                @php
                                                                $enableKYC = App\Model\Profile::where('Profile_User',
                                                                $v->User_ID)->where('Profile_Status', 1)->first();
                                                                @endphp
                                                                @if(isset($enableKYC))
                                                                <span class="badge badge-primary r-3">Verification
                                                                    turned on</span>
                                                                @else
                                                                <span class="badge badge-danger r-3">Verification not
                                                                    enabled</span>
                                                                @endif
                                                                @if($v->User_Status == 0)
                                                                <span class="badge badge-danger r-3 blink">Block</span>
                                                                @else
                                                                <span class="badge badge-success r-3">Active</span>
                                                                @endif

                                                            </td>
                                                            <td>
                                                                @if($v->google2fa_User)
                                                                <a href="{{ route('system.admin.getDisableAuth', $v->User_ID) }}"
                                                                    class="btn btn-danger btn-xs waves-effect waves-light"><i
                                                                        class="fa fa-trash"> Delete</i></a>
                                                                @else
                                                                <span
                                                                    class="btn btn-secondary btn-xs waves-effect waves-light"><i
                                                                        class="fa fa-ban"> None</i></span>
                                                                @endif
                                                            </td>
                                                            <td>

                                                                <a href="{{ route('system.admin.getEditUser', $v->User_ID) }}"
                                                                    class="bt-loginID btn btn-warning btn-xs waves-effect waves-light"
                                                                    data-toggle="tooltip"><i class="fa fa-edit">
                                                                        Edit</i></a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{$user_list->appends(request()->input())->links('System.Layouts.Pagination')}}
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
<script>
    $('#member-list-table').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false
        });
    $(document).ready(function() {
        $('.bt-loginID').click(function(e) {
            if ($('.bt-loginID').hasClass("disabled")) {
                event.preventDefault();
            }
            $('.bt-loginID').addClass("disabled");
        });
        var arr_email = [];
        $('#member-list-table').on('click', '.btn-edit-mail', function(){
            let id_user = $(this).data('id_user');
            var html_edit_mail = "<input id=\"input-mail-"+id_user+"\" type=\"text\" class=\"edit-email-input\" value=\""+$('#input-email-'+id_user).text()+"\">";
            arr_email[id_user] = $('#input-email-'+id_user).text();
            let html_action_mail = "<a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-disable-mail btn btn-warning btn-xs waves-effect waves-light\"><i class=\"fa fa-edit\"> </i></a>  <a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-save-mail btn btn-success btn-xs waves-effect waves-light\"><i class=\"fa fa-save\"> </i></a>"
            $('#action-email-'+id_user).html(html_action_mail);
            $('#input-email-'+id_user).html(html_edit_mail);
        });
        $('#member-list-table').on('click', '.btn-disable-mail', function(){
            let id_user = $(this).data('id_user');
            let html_edit_mail =  arr_email[id_user];
            let html_action_mail = "<a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-edit-mail btn btn-warning btn-xs waves-effect waves-light\"><i class=\"fa fa-edit\"> </i></a>"
            $('#action-email-'+id_user).html(html_action_mail);
            $('#input-email-'+id_user).html(html_edit_mail);
        });
        $('#member-list-table').on('click', '.btn-save-mail', function(){
            let id_user = $(this).data('id_user');
            let html_edit_mail = $('#input-mail-'+id_user).val();
            let html_action_mail = "<a data-id_user='"+id_user+"' href=\"javascript:void(0)\" class=\"btn-edit-mail btn btn-warning btn-xs waves-effect waves-light\"><i class=\"fa fa-edit\"> </i></a>"
            $('#action-email-'+id_user).html(html_action_mail);
            $.ajax({
                url : '{{ route('system.admin.getEditMailByID') }}',
                type : "POST",
                dataType:"json",
                data : {
                    _token: "{{ csrf_token() }}",
                    id_user : id_user,
                    new_email : html_edit_mail
                },
                success : function (result){
                    if(!result){
                        html_edit_mail = arr_email[id_user];
                        toastr.error('Email Already Exists', 'Error!', {timeOut: 3500});
                    }
                    else{
                        if(result == -1){
                            html_edit_mail = arr_email[id_user];
                            toastr.error('ID Does Not Exist', 'Error!', {timeOut: 3500});
                        }
                        else{
                            html_edit_mail = $('#input-mail-'+id_user).val();
                            toastr.success('Updated Email', 'Success!', {timeOut: 3500});
                        }
                    }
                    $('#input-email-'+id_user).html(html_edit_mail);
                }
            });
        });
    });
</script>

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
    $('#datetime').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
</script>
@endsection
