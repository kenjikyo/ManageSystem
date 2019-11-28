@extends('System.Layouts.Master')
@section('title', 'Admin Ticket')
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
                    <h4 class="pull-left page-title">Ticket</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Ticket</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <!-- /Title -->
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                        List Ticket
                                    </h6>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                {{$ticket->appends(request()->input())->links('System.Layouts.Pagination')}}
                                                <div style="clear:both"></div>
                                                <table id="myTable1"
                                                    class="dt-responsive table table-striped table-bordered table-responsive">
                                                    <thead>
                                                        <tr>
                                                            <th data-toggle="true">
                                                                Ticket ID
                                                            </th>
                                                            <th>
                                                                Subjects
                                                            </th>
                                                            <th>
                                                                Email
                                                            </th>
                                                            <th data-hide="phone">
                                                                Status
                                                            </th>
                                                            <th data-hide="phone,tablet">
                                                                DateTime
                                                            </th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($ticket as $t)
                                                        @php
                                                        $hide = 1;
                                                        $checkHideStatus =
                                                        DB::table('ticket')->where('ticket_ID',$t->ticket_ID)->where('ticket_Status',-1)->first();
                                                        if($checkHideStatus){
                                                        $hide = 0;
                                                        $messNum = 0;
                                                        $status = 'Hidden';
                                                        $class = 'light';
                                                        }
                                                        else {
                                                        $findlastRep =
                                                        DB::table('ticket')->where('ticket_ReplyID',$t->ticket_ID)->orderBy('ticket_ID',
                                                        'DESC')->first();
                                                        $messNum = 0;
                                                        $getComment = [];
                                                        if(!$findlastRep){
                                                        $getComment =
                                                        DB::table('ticket')->Where('ticket_ID',$t->ticket_ID)->where('ticket_Status',0)->get();
                                                        $messNum = count($getComment);
                                                        $status = 'Waiting';
                                                        $class = 'warning';

                                                        }else{
                                                        $getInfo = App\Model\User::where('User_Level',
                                                        1)->where('User_ID',$findlastRep->ticket_User)->first();

                                                        if($getInfo){
                                                        $messNum = 0;
                                                        $status = 'Replied';
                                                        $class = 'success';
                                                        }else{
                                                        $keyItem = 1;
                                                        $getListReplyed =
                                                        DB::table('ticket')->Where('ticket_ReplyID',$t->ticket_ID)->orderBy('ticket_ID',
                                                        'DESC')->get();
                                                        foreach ($getListReplyed as $item) {
                                                        $findUserAdmin = App\Model\User::where('User_Level',
                                                        1)->where('User_ID',$item->ticket_User)->first();
                                                        if(!$findUserAdmin){
                                                        //don't User Admin
                                                        $messNum = $keyItem;
                                                        $keyItem++;
                                                        }
                                                        else {
                                                        //is User Admin
                                                        break;
                                                        }
                                                        }
                                                        $status = 'Waiting';
                                                        $class = 'warning';
                                                        }
                                                        }
                                                        }
                                                        @endphp
                                                        <tr>
                                                            <td>{{$t->ticket_ID}}</td>
                                                            <td>{{$t->ticket_subject_name ? $t->ticket_subject_name : ''}}
                                                            </td>
                                                            <td>{{$t->User_Email}}</td>
                                                            <td>
                                                                <span
                                                                    class="label label-rounded label-{{$class}}">{{$status}}</span>
                                                            </td>
                                                            <td>{{$t->ticket_Time}}</td>
                                                            <td>
                                                                <a href="{{route('getTicketDetail',$t->ticket_ID)}}"
                                                                    class="btn btn-primary btn-rounded">Details <span
                                                                        class="badge badge-danger">{{$messNum}}</span></a>
                                                                <a onclick="javascript:return confirm('ID: #{{$t->ticket_ID}} Are you sure?');"
                                                                    href="{{route('getStatusTicketAdmin',$t->ticket_ID)}}"
                                                                    class="btn btn-danger btn-rounded"
                                                                    style="width:auto;">{{ $hide == 1 ? 'Allow Hidden' : 'Been Hidden'}}</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{$ticket->appends(request()->input())->links('System.Layouts.Pagination')}}
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
@endsection