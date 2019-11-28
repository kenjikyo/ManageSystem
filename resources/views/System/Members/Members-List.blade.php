@extends('System.Layouts.Master')
@section('title', 'Member List')
@section('css')
<!-- DataTables -->
<link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<style>
    a:hover {
        cursor: pointer;
    }
    table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child:before{
        text-indent: 0px;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="container">

        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Member List</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="#">DAPP</a></li>
                        <li class="active">Member List</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>


        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Link Ref</h3>
                </div>
                <div class="panel-body">

                    <div class="input-group m-b-15">

                        <div class="bootstrap-timepicker">
                            <input id="linkRef" placeholder="ID"
                                value="{{route('getRegister')}}?ref={{Session('user')->User_ID}}" type="text"
                                class="form-control">
                        </div>
                        <span class="input-group-addon btn-success">
                            <a class="copytooltip " id="tooltiptext" onclick="copyToClipboard()"
                                onmouseout="hoverCopyTooltip()"><i class="fa fa-clone"></i> Copy</a>
                        </span>

                    </div><!-- input-group -->
                    <div class="form-group" style="opacity: 0;">
                        <div>
                            <button type="button" class="btn btn-primary waves-effect waves-light">TTTT
                            </button>
                            <button type="button" class="btn btn-danger waves-effect m-l-5">TTTT
                            </button>
                        </div>
                    </div>
                </div> <!-- panel-body -->
            </div> <!-- panel -->
        </div>

        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Add Member</h3>
                </div>
                <div class="panel-body">

                    <div class="">
                        <form action="{{route('system.user.postMemberAdd')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email">
                                        <span class="input-group-addon btn-success bg-custom b-0"><i
                                                class="mdi mdi-email"></i></span>
                                        <input type="hidden" name="sponser" value="{{session('user')->User_ID}}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                                        Add
                                    </button>  <button type="reset" class="btn btn-danger waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div> <!-- panel-body -->
            </div> <!-- panel -->
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Member List</h3>
                    </div>
                    <div class="panel-body">

                        <table id="list-member-table" class="table table-striped table-bordered dt-responsive nowrap">
                            <thead>
                                <tr>
                                    <th data-hide="phone,tablet">
                                        Level
                                    </th>
                                    <th data-hide="phone,tablet">
                                        User ID
                                    </th>
                                    <th data-hide="phone,tablet">
                                        Email
                                    </th>
                                    <th data-hide="phone,tablet">
                                        Sponser
                                    </th>
                                    <th>Amount Investment</th>
                                    <th>Sales</th>
                                    <th>Currency</th>
                                    <th data-hide="phone,tablet">
                                        Created Date
                                    </th>
                                    <th>Verification</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user_list as $v)
                                <tr style="color:black!important">
                                    
                                    <td style="text-align: center">F{{ (int)$v->f }}</td>
                                    <th class="text-center"><b>{{ $v->User_ID }}</b></th>
                                    <td>{{ $v->User_Email }}</td>
                                    <td class="text-center">{{ $v->User_Parent }}</td>
                                    <td>
                                        {{ number_format($v->aaa,2) }}

                                        
                                    </td>
                                    <td>
                                        {{ number_format($v->total_invest_branch,2)}}
                                    </td>
                                    <th>USDX</th>
                                    <td>{{ $v->User_RegisteredDatetime }}</td>
                                    <td>
                                        @if($v->Profile_Status == 1)
                                        <span class="badge badge-success r-3">Verified</span>

                                        @else
                                        <span class="badge badge-danger r-3">Unverify</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div> <!-- End Row -->


    </div> <!-- container -->

</div>
@endsection
@section('script')
<!-- Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<script>
    function copyToClipboard() {
        var copyText = document.getElementById("linkRef");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        document.execCommand("copy");
        var tooltip = document.getElementById("tooltiptext");
        tooltip.innerText = "Copied";
        alert(copyText.value);
    }
    function hoverCopyTooltip() {
        var tooltip = document.getElementById("myTooltip");
        tooltip.innerHTML = "Copy";
    }

    $('#list-member-table').DataTable({
        "bLengthChange": true,
        "paging": true
    });
    
</script>
<script>
    $('#dt-dashboard').DataTable({
            "bLengthChange": false,
        "searching": false,
            "paging": false,
            "order": [0, 'desc']
        });
</script>
@endsection