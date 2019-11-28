@extends('System.Layouts.Master')
@section('title', 'Ticket')
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
                        <li class="active">Ticket</li>
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
                        <div class="col-md-4">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Ticket</h3>
                                </div>
                                <div class="panel-body">
                                    <form action="{{route('postTicket')}}" method="post" class="parsley-examples">

                                        @csrf
                                        <div class="form-group">
                                            <label class="control-label mb-10"><i class="fa fa-hand-o-down"
                                                    aria-hidden="true"></i> Subject</label>
                                            <select name="subject" class="form-control" required>
                                                @foreach($subject as $s)
                                                <option value="{{$s->ticket_subject_id}}">
                                                    {{$s->ticket_subject_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Content * :</label>
                                            <textarea placeholder="Description your problems..." name="content"
                                                id="commenttextarea" cols="30" rows="5" class="form-control"
                                                required></textarea>
                                        </div>
                                        <div class="form-group mb-0">
                                            <button type="submit"
                                                class="ladda-button btn btn-success waves-effect waves-light"
                                                data-style="slide-down">
                                                <span class="btn-label"><i class="fa fa-paper-plane"></i> </span>Send
                                            </button>
                                        </div>

                                    </form>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="panel panel-primary">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Ticket</h3>
                                </div>
                                <div class="panel-body">
                                    <table id="dt-ticket"
                                        class="dt-responsive table mb-0 table-bordered toggle-arrow-tiny"
                                        data-page-size="10">
                                        <thead>
                                            <tr>
                                                <th data-toggle="true">
                                                    Ticket ID
                                                </th>
                                                <th>
                                                    Subjects
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
                                            $findlastRep =
                                            DB::table('ticket')->where('ticket_ReplyID',$t->ticket_ID)->orderBy('ticket_ID',
                                            'DESC')->first();
                                            if(!$findlastRep){
                                            $status = 'Waiting';
                                            $class = 'warning';
                                            }else{
                                            $getInfo = App\Model\User::whereIn('User_Level',
                                            [1,2,3])->where('User_ID',
                                            $findlastRep->ticket_User)->first();
                                            if($getInfo){
                                            $status = 'Replied';
                                            $class = 'success';
                                            }else{
                                            $status = 'Waiting';
                                            $class = 'warning';
                                            }
                                            }
                                            @endphp
                                            <tr>
                                                <td>{{$t->ticket_ID}}</td>

                                                <td>{{$t->ticket_subject_name}}</td>
                                                <td>
                                                    <button class="btn btn-rounded btn-{{$class}}">{{$status}}</button>
                                                </td>
                                                <td>{{$t->ticket_Time}}</td>

                                                <td><a href="{{route('getTicketDetail',$t->ticket_ID)}}"
                                                        class="btn btn-primary btn-rounded">Details</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="active">
                                                <td colspan="5">
                                                    <div class="text-right">
                                                        <ul
                                                            class="pagination pagination-split justify-content-end footable-pagination m-t-10">
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>

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
    $('#dt-ticket').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false
    });
</script>

{{-- <script src="assets\libs\parsleyjs\parsley.min.js"></script>
<!-- Validation init js-->
<script src="assets\js\pages\form-validation.init.js"></script> --}}
@endsection