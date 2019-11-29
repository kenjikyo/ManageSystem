@extends('System.Layouts.Master')
@section('title', 'Admin-Edit User')
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
                    <h4 class="pull-left page-title">Edit User</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Edit User</li>
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
                                            Edit User</h3>
                                    </div>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">

                                                    <form method="POST" action="{{route('system.admin.postEditUser')}}"
                                                        id="confirm-wallet">
                                                        @csrf
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-users" aria-hidden="true"></i>
                                                                    Name</label>
                                                                <input type="text" name="name" class="form-control"
                                                                    value="{{ $user_list->User_Name }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-envelope" aria-hidden="true"></i>
                                                                    Email</label>
                                                                <input type="text" name="email" class="form-control"
                                                                    value="{{ $user_list->User_Email }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Status Mail</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="status_mail">
                                                                    <option selected value="0"
                                                                        {{$user_list->User_EmailActive == '0' ? 'selected' : ''}}>
                                                                        Not Active</option>
                                                                    <option selected value="1"
                                                                        {{$user_list->User_EmailActive == '1' ? 'selected' : ''}}>
                                                                        Active</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                    <label class="control-label mb-10"
                                                                        for="exampleInputpwd_1"><i class="fa fa-users"
                                                                            aria-hidden="true"></i> Agency Level</label>
                                                                    <select id="inputState" class="form-control"
                                                                        name="agency_level">
                                                                        @foreach ($user_agency_level as $item)
                                                                            <option value="{{$item->user_agency_level_ID}}"
                                                                            {{$user_list->User_Level == $item->user_agency_level_ID ? 'selected' : ''}}>
                                                                            {{$item->user_agency_level_Name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="mdi mdi-timer" aria-hidden="true"></i>
                                                                    Phone</label>
                                                                <input type="text" name="phone" class="form-control"
                                                                    value="{{ $user_list->User_Phone }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="icon-diamond" aria-hidden="true"></i>
                                                                    Parent</label>
                                                                <input type="text" name="parent" class="form-control"
                                                                    value="{{ $user_list->User_Parent }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="icon-diamond" aria-hidden="true"></i>
                                                                    Tree</label>
                                                                <input name="tree" type="text" class="form-control"
                                                                    value="{{ $user_list->User_Tree }}">
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Level</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="level">
                                                                    @foreach ($user_level as $item)
                                                                        <option value="{{$item->User_Level_ID}}"
                                                                        {{$user_list->User_Level == $item->User_Level_ID ? 'selected' : ''}}>
                                                                        {{$item->User_Level_Name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputpwd_1"><i class="fa fa-users"
                                                                        aria-hidden="true"></i> Status</label>
                                                                <select id="inputState" class="form-control"
                                                                    name="status">
                                                                    <option value="0"
                                                                        {{$user_list->User_Level == '0' ? 'selected' : ''}}>
                                                                        Block</option>
                                                                    <option value="1"
                                                                        {{$user_list->User_Level == '1' ? 'selected' : ''}}>
                                                                        Active</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"><i
                                                                        class="mdi mdi-emoticon-excited-outline"
                                                                        aria-hidden="true"></i>
                                                                    New Password</label>
                                                                <input name="new_password" type="text" class="form-control">
                                                            </div>

                                                            <div class="form-actions mt-10">
                                                                <input type="hidden" name="id"
                                                                    value="{{$user_list->User_ID}}">
                                                                <button type="submit"
                                                                    class="btn btn-success mr-10 btn-confirm"
                                                                    data-confirm="1"><i class="fa fa-save"
                                                                        aria-hidden="true"></i>
                                                                    Save</button>
                                                                <a href="{{route('system.admin.getMemberListAdmin')}}"
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
