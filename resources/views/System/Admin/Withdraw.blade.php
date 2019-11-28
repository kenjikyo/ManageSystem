@extends('System.Layouts.Master')
@section('title', 'Admin Withdraw')
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
</style>
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <!-- /Title -->
                    <div class="row">
                        <div class="col-md-12">
                            <form method="GET" action="{{route('System.Admin.Withdraw')}}">
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
                                                                        aria-hidden="true"></i> ID</label>
                                                                <input type="name" name="id" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter ID">
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
                                                                    <select class="form-control" tabindex="1"
                                                                        name="status">
                                                                        <option value="2">---select---</option>
                                                                        <option value="1">Confirmed</option>
                                                                        <option value="0">Pending</option>
                                                                        <option value="-1">Error</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> User ID</label>
                                                                <input type="name" name="user_id" class="form-control"
                                                                    id="exampleInputpwd_1" placeholder="Enter User ID">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    From</label>
                                                                <input type='date' class="form-control"
                                                                    name="datefrom" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputuname_1"><i class="fa fa-envelope"
                                                                        aria-hidden="true"></i>
                                                                    Email</label>
                                                                <input type="email" name="email" class="form-control"
                                                                    id="exampleInputpwd_1"
                                                                    placeholder="Enter User Email">

                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    To</label>
                                                                <input type='date' class="form-control" name="dateto" />
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <div class="form-actions mt-10">
                                                                    {{--                                                        <button type="submit" class="btn btn-lg1 btn-success  mr-10"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Export</button>--}}
                                                                    <button type="submit"
                                                                        class="btn btn-lg1 btn-primary"><i
                                                                            class="fa fa-search" aria-hidden="true"></i>
                                                                        Search
                                                                    </button>
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
                                    <div class="pull-left">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table"
                                                aria-hidden="true"></i>Withdraw confirm</h6>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Withdrawal ID</th>
                                                            <th>Withdrawal User</th>
                                                            <th>User Level</th>
                                                            <th>Withdrawal Amount</th>
                                                            <th>Money Rate</th>
                                                            <th>Withdraw Time</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($withdrawCofirm as $item)
                                                        <tr class="tx-jus">

                                                            @if($item->User_Level == 0)
                                                            <th>{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th>User</th>
                                                            @elseif($item->User_Level == 1)
                                                            <th class="bg-success">{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th>Admin</th>
                                                            @elseif($item->User_Level == 2)
                                                            <th class="bg-warning">{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th>Finance</th>
                                                            @else
                                                            <th class="bg-danger">{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th>Test</th>
                                                            @endif
                                                            <th>{{number_format(abs($item->Money_USDT))}}</th>
                                                            <th>{{number_format($item->Money_Rate, 3)}}</th>
                                                            <td>{{date('Y-m-d H:i:s', $item->Money_Time)}}</td>
                                                            <th>
                                                                @if($item->Money_Confirm == 0)
                                                                <div id="status-{{$item->Money_ID}}">
                                                                    <button
                                                                        class="btn btn-success btn-post-confirm-withdraw"
                                                                        data-value="{{$item->Money_ID}}"><i
                                                                            class="fa fa-server"
                                                                            aria-hidden="true"></i>Confirm</button>
                                                                    <button
                                                                        class="btn btn-danger btn-post-cancel-withdraw"
                                                                        data-value="{{$item->Money_ID}}"><i
                                                                            class="fa fa-times" aria-hidden="true"
                                                                            data-value="$item->Money_ID"></i>Cancel</button>
                                                                </div>
                                                                @elseif($item->Money_Confirm == 1)
                                                                confirmed
                                                                @elseif($item->Money_Confirm == 2)
                                                                Error
                                                                @else
                                                                Cancel
                                                                @endif
                                                            </th>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    <tfoot align="right">
                                                    </tfoot>
                                                </table>
                                                {{$withdrawCofirm->appends(request()->input())->links()}}
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

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $('.btn-post-confirm-withdraw').click(function () {
            var withdrawID = $(this).attr('data-value');
            swal({
                title: "Are you sure confirm ID:"+withdrawID+"??",
                buttons: true,
                dangerMode: true,
            }).then(function(){
                var _token = $('meta[name="_token"]').attr('content');
                $.ajax({
                    url: '{{ route('System.Admin.postConfirmWithdraw') }}',
                    type: "POST",
                    dataType: "json",
                    data:{_token:_token, id:withdrawID},
                    success: function (data) {
                        divBTN = '#status-'+withdrawID;
                        if(data.status == 'success') {
                            swal("Confirmed!", {
                                icon: "success",
                            });
                            divBTN = '#status-'+withdrawID;
                            $(divBTN).html('Confirm');
                        }
                        else {
                            $(divBTN).html('Error');
                            swal("Error", data.message+"!", "error");
                        }
                    }
                });

            });
        });

        $('.btn-post-cancel-withdraw').click(function () {
            var withdrawID = $(this).attr('data-value');
            swal({
                title: "Are you sure confirm ID:"+withdrawID+"??",
                buttons: true,
                dangerMode: true,
            }).then(function(){
                var _token = $('meta[name="_token"]').attr('content');
                $.ajax({
                    url: '{{ route('System.Admin.postCancelWithdraw') }}',
                    type: "POST",
                    dataType: "json",
                    data:{_token:_token, id:withdrawID},
                    success: function (data) {
                        divBTN = '#status-'+withdrawID;
                        if(data.status == 'success') {
                            swal("Canceled!", {
                                icon: "success",
                            });
                            divBTN = '#status-'+withdrawID;
                            $(divBTN).html('Canceled');
                        }
                        else {
                            $(divBTN).html('Error');
                            swal("Error", data.message+"!", "error");
                        }
                    }
                });

            });
        });
        $('#revenue-product').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print'
            ],
        });
</script>
@endsection
