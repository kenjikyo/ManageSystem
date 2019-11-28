@extends('System.Layouts.Master')
@section('title', 'Admin Log')
@section('css')
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
                    <h4 class="pull-left page-title">Log</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Log</li>
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
                            <form method="GET" action="{{route('system.admin.getLogMail')}}">
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
                                                                        aria-hidden="true"></i> Content</label>
                                                                <input class="form-control" type="text"
                                                                    placeholder="Content"
                                                                    value="{{request()->input('Content')}}" name="Content">
                                                            </div>
                                                        </div>
                                                        {{-- 
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Created Date</label>
                                                                <input type="text" class="form-control"
                                                                    placeholder="Created Date" name="datetime"
                                                                    id="datetime"
                                                                    value="{{request()->input('datetime')}}" />
                                                    </div>
                                                </div> --}}

                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <div class="form-actions mt-10">
                                                        <button type="submit"
                                                            class="btn-filler btn btn-lg1 btn-primary waves-effect"><i
                                                                class="fa fa-search" aria-hidden="true"></i>
                                                            Search
                                                        </button>
                                                        {{-- <button type="submit"
                                                                    class="btn-filler btn btn-success waves-effect"
                                                                    style="" name="export" value="1"><i
                                                                        class="fa fa-print" aria-hidden="true"></i>
                                                                    Export</button> --}}
                                                        <a href="{{ route('system.admin.getLogMail') }}"
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
                            <div>
                                <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                    Log</h6>
                            </div>
                        </div>
                        <div class="panel-wrapper collapse in">
                            <div class="panel-body">
                                <div class="table-wrap">
                                    <div class="table-responsive">
                                        {{$logMails->appends(request()->input())->links('System.Layouts.Pagination')}}
                                        <div style="clear:both"></div>
                                        <table id="dt-log-mail"
                                            class="dt-responsive table table-striped table-bordered table-responsive"
                                            cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>User ID</th>
                                                    <th>Email</th>
                                                    <th>Content</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($logMails as $item)
                                                <tr>
                                                    <td>{{$item->Log_ID}}</td>
                                                    <td>{{$item->Log_User}}</td>
                                                    <td>{{$item->User_Email}}</td>
                                                    <td>{{$item->Log_Comment}}</td>
                                                    <td>{{$item->Log_CreatedAt}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        {{$logMails->appends(request()->input())->links('System.Layouts.Pagination')}}
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
    $('#dt-log-mail').DataTable({
    "bLengthChange": false,
        "searching": false,
    "paging": false,
    "order": [4,'desc']
});
</script>
@endsection