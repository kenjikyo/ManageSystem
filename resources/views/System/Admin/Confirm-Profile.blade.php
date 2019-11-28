@extends('System.Layouts.Master')
@section('title', 'Admin Confirm Profile')
@section('css')
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
    .pagination {
        float: right;
    }
</style>
<meta name="_token" content="{!! csrf_token() !!}" />
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
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
</style>

@endsection
@section('content')

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">KYC</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">KYC</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <!-- Search -->
                    <div class="row">
                        <div class="col-md-12">
                            <form method="GET" action="">
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
                                                                <input type="text" name="UserID" class="form-control"
                                                                    placeholder="Enter User ID"
                                                                    value="{{request()->input('UserID')}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-user"
                                                                        aria-hidden="true"></i> Email</label>
                                                                <input type="text" class="form-control" name="Email"
                                                                    placeholder="Enter Email"
                                                                    value="{{request()->input('Email')}}">
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
                                                        <!--/span-->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputuname_1"><i
                                                                        class="fa fa-chevron-down"
                                                                        aria-hidden="true"></i>
                                                                    Status</label>
                                                                <div class="form-group">
                                                                    <select name="status" class="form-control">
                                                                        <option value="">--- Select ---</option>
                                                                        <option value="0"
                                                                            {{request()->input('status') == '0' ? 'selected' : ''}}>
                                                                            Pedding
                                                                        </option>
                                                                        <option value="1"
                                                                            {{request()->input('status') == '1' ? 'selected' : ''}}>

                                                                            Confirmed</option>
                                                                        <option value="-1"
                                                                            {{request()->input('status') == '-1' ? 'selected' : ''}}>
                                                                            Error
                                                                        </option>
                                                                    </select>
                                                                </div>
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
                                                                    <a href="{{ route('system.admin.getProfile') }}"
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
                    <!-- !Search-->
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div>
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            Profile Confirm</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                {{$profileList->appends(request()->input())->links('System.Layouts.Pagination')}}
                                                <div style="clear:both"></div>
                                                <table id="profileList"
                                                    class="dt-responsive table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Profile User</th>
                                                            <th>Passport ID</th>
                                                            <th>Update time</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($profileList as $item)
                                                        <tr>
                                                            <td>{{$item->Profile_ID}}</td>
                                                            <td>{{$item->Profile_User}}</td>
                                                            <td>{{$item->Profile_Passport_ID}}</td>
                                                            <td>{{$item->Profile_Time}}</td>
                                                            <td id="list-profile-action-{{$item->Profile_ID}}">
                                                                @if($item->Profile_Status == 0)
                                                                <button data-id="{{$item->Profile_ID}}" type="button"
                                                                    class="view-detail btn btn-success btn-rounded waves-effect waves-light"
                                                                    data-toggle="modal"
                                                                    data-target="#profile_info">Detail</button>
                                                                @elseif($item->Profile_Status == 1)
                                                                <label class="bagde bagde-success">

                                                                    <span class="badge badge-info">Confirmed</span>
                                                                </label>
                                                                @else
                                                                <label class="bagde bagde-light">Cancel</label>

                                                                @endif
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{$profileList->appends(request()->input())->links('System.Layouts.Pagination')}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Modal show profile -->

                </div>
            </div>

        </div>
    </div>
</div>
<div id="profile_info" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="modal-profile-header"></h4>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('postProfile')}} " enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-12 col-lg-12" style="margin: auto;">
                            <div class="form-wrap">
                                <div class="form-body overflow-hide">
                                    <div class="form-group">
                                        <label class="control-label mb-10" for="exampleInputuname_01"
                                            style="color: #0088ce">ID/Passport Number</label>
                                        <div class="input-group">
                                            <div class="input-group-addon btn-success"><i class="fa fa-user"></i></div>
                                            <input type="text" class="form-control " name="passport_id"
                                                id="modal-passport-id" placeholder="" value="" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-wrap">
                                <div class="form-body overflow-hide">
                                    <div class="form-group mb-30">
                                        <label class="control-label mb-10 text-left"
                                            style="color: #0088ce">ID/Passport</label>
                                        <div class="panel panel-default card-view">
                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body">
                                                    <img src="" width="100%" id="img-passport">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-wrap">
                                <div class="form-body overflow-hide">
                                    <div class="form-group mb-30">
                                        <label class="control-label mb-10 text-left"
                                            style="color: #0088ce">Selfie</label>
                                        <div class="panel panel-default card-view">
                                            <div class="panel-wrapper collapse in">
                                                <div class="panel-body">
                                                    <img src="" width="100%" id="img-passport-selfie">
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
            <div class="modal-footer" id="div-modal-footer">
                <button type="button" class="btn btn-success" id="profile-accept">Accept</button>
                <button type="button" class="btn btn-warning" id="profile-disagree">Disagree</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
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
    $('#datefrom').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true , time: false });
    $('#dateto').bootstrapMaterialDatePicker({ format : 'YYYY/MM/DD', clearButton: true, time: false });
</script>
<script>
    var token = '{{ csrf_token() }}';

    $(document).ready(function () {
        
        $('table').on('click', '.view-detail', function(){
            _info = $(this).attr('data-id');
            console.log(_info);
            _dataList = @json($profileList);
            _inforPerson = jQuery.grep(_dataList.data, function (obj) {
                if(obj.Profile_ID == _info){
                    return obj;
                }
    //                 return obj.Profile_ID == _info;
            });
            _serverImage = "http://media.dafco.org/";
            $('#modal-passport-id').val(_inforPerson[0].Profile_Passport_ID);

            $('#profile-accept').attr('data-value', _info);
            $('#profile-disagree').attr('data-value', _info);

            $('#img-passport').attr('src', _serverImage + _inforPerson[0].Profile_Passport_Image);
            $('#img-passport-selfie').attr('src', _serverImage + _inforPerson[0].Profile_Passport_Image_Selfie);
        });
        $('#profile-accept').click(function () {

            profileID = $(this).attr('data-value');
            if (profileID) {
                $.ajax({
                    url: '{{ route('system.admin.confirmProfile') }}',
                    type: "POST",
                    dataType: "json",
                    data: {_token: token, id: profileID, action: 1},
                    success: function (data) {
                        if (data.status == 'success') {
                            $('#list-profile-action-' + profileID).html("<label class=\"bagde bagde-success\"><span class=\"badge badge-info\">Confirmed</span></label>");
                            $('#profile_info').modal('hide');
                            toastr.success('Success', 'Success!', {timeOut: 3500});
                        } else {
                            $('#profile_info').modal('hide');
                            toastr.error('Error', 'Error!', {timeOut: 3500});
                        }
                    }
                });
            }
        });
        $('#profile-disagree').click(function () {
            // profile-disagree
            $(this).prop('disabled',true);
            $(this).text('Loading...')
            profileID = $(this).attr('data-value');
            if (profileID) {
                $.ajax({
                    url: '{{ route('system.admin.confirmProfile') }}',
                    type: "POST",
                    dataType: "json",
                    data: {_token: token, id: profileID, action: -1},
                    success: function (data) {
                        if (data.status == 'success') {
                            $('#list-profile-action-' + profileID).html("<span class=\"badge badge-danger\">Cancel</span>");
                            $('#profile_info').modal('hide');
                            toastr.success('Success', 'Success!', {timeOut: 3500});

                        } else {
                            $('#profile_info').modal('hide');
                            toastr.error('Error', 'Error!', {timeOut: 3500});
                        }
                    }
                });
            }
        });
});
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $('#profileList').DataTable({
          "bLengthChange": false,
        "searching": false,
          "paging": false,
          "order": [0,'desc']
      });
</script>
@endsection