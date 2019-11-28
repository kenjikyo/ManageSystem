@extends('System.Layouts.Master')
@section('title', 'Admin Confirm Profit')
@section('css')
<meta name="_token" content="{!! csrf_token() !!}" />
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<link href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css" rel="stylesheet" />
<link rel="stylesheet" href=" https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css">
<style>
    .select-bg {
        background: #fff0;
        border: none;
    }

    .select-bg option {
        background: #fff;
        color: #000;
    }

    .waiting-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(255, 255, 255, .8) url('http://i.stack.imgur.com/FhHRx.gif') 50% 50% no-repeat;
    }

    /* When the body has the loading class, we turn
        the scrollbar off with overflow:hidden */
    body.loading .waiting-modal {
        overflow: hidden;
    }

    /* Anytime the body has the loading class, our
        modal element will be visible */
    body.loading .waiting-modal {
        display: block;
    }
</style>

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
                            <form method="GET" action="{{route('System.Admin.getPayDailyInterest')}}">
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
                                                                    id="exampleInputpwd_1" placeholder="Enter ID"
                                                                    value="{{ app('request')->input('id') }}">
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
                                                                        name="status" value="">
                                                                        <option value=""
                                                                            {{app('request')->input('status') == null ? 'selected' : '' }}>
                                                                            ---select---</option>
                                                                        <option value="1"
                                                                            {{app('request')->input('status') == 1 ? 'selected' : '' }}>
                                                                            Confirmed</option>
                                                                        <option value="0"
                                                                            {{app('request')->input('status') == 0 ? 'selected' : '' }}>
                                                                            Pending</option>
                                                                        <option value="-1"
                                                                            {{app('request')->input('status') == -1 ? 'selected' : '' }}>
                                                                            Cancel</option>
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
                                                                    id="exampleInputpwd_1" placeholder="Enter User ID"
                                                                    value="{{ app('request')->input('user_id') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    From</label>
                                                                <input type='date' class="form-control" name="datefrom"
                                                                    value="{{ app('request')->input('datefrom') }}" />
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
                                                                    placeholder="Enter User Email"
                                                                    value="{{ app('request')->input('email') }}">

                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10 text-left"><i
                                                                        class="fa fa-calendar" aria-hidden="true"></i>
                                                                    To</label>
                                                                <input type='date' class="form-control" name="dateto"
                                                                    value="{{ app('request')->input('dateto') }}" />
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label class="control-label mb-10"
                                                                    for="exampleInputuname_1">
                                                                    <i class="fa fa-chevron-down"
                                                                        aria-hidden="true"></i>
                                                                    Wallet Status</label>
                                                                <div class="form-group">
                                                                    <select class="form-control" tabindex="1"
                                                                        name="wallet_status"
                                                                        value="{{ app('request')->input('wallet_status') }}">
                                                                        <option value="">---select---</option>
                                                                        <option value="1"
                                                                            {{app('request')->input('wallet_status') == 1 ? 'selected': ''}}>
                                                                            Updated</option>
                                                                        <option value="0"
                                                                            {{app('request')->input('wallet_status') == 0 ? 'selected': ''}}>
                                                                            None</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label class="control-label text-left"
                                                                    style="opacity: 0">
                                                                    To</label>
                                                                <div class="form-actions mt-10">
                                                                    <button type="submit"
                                                                        class="btn btn-lg1 btn-primary"><i
                                                                            class="fa fa-search" aria-hidden="true"></i>
                                                                        Search
                                                                    </button>
                                                                    <button type="submit" name="export" value="1"
                                                                        class="btn btn-lg1 btn-success  mr-10"><i
                                                                            class="fa fa-file-excel-o"
                                                                            aria-hidden="true"></i> Export</button>
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
                                    <div class="">
                                        <h6 class="panel-title txt-light"><i class="fa fa-table" aria-hidden="true"></i>
                                            Interest daily confirm</h6>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-center text-warning">Amount of trusts left: <b
                                            class="text-danger">{{$walletBalance}}</b> Trusts</h4>
                                </div>
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body">
                                        <div class="table-wrap">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-bordered table-responsive"
                                                    cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <div
                                                                    class="pretty p-default state p-success inline-block">
                                                                    <input type="checkbox" id="check-all-interest" />
                                                                    <div class="state p-success">
                                                                        <label></label>
                                                                    </div>
                                                                </div>
                                                                <div class="inline-block">
                                                                    <select class="select-bg" name="select-action"
                                                                        id="select-action">
                                                                        <option value="" selected>Action</option>
                                                                        <option value="1">Confirm</option>
                                                                        <option value="-1">Cancel</option>
                                                                    </select>
                                                                </div>
                                                            </th>
                                                            <th>Interest ID</th>
                                                            <th>User ID</th>
                                                            <th>User Level</th>
                                                            <th>Interest Amount</th>
                                                            <th>Money Rate</th>
                                                            <th>Interest Time</th>
                                                            <th>Confirm Time</th>
                                                            <th>Update Wallet</th>
                                                            <th>Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($profitCofirm as $item)
                                                        <tr>
                                                            <th>
                                                                @if($item->Money_Confirm == 0 && $item->User_Level != 4
                                                                && $item->User_WalletGTC)
                                                                <div class="pretty p-default state p-success">
                                                                    <input type="checkbox" name="money-id-selected"
                                                                        value="{{$item->Money_ID}}"
                                                                        class="item-check-interest" />
                                                                    <div class="state p-success">
                                                                        <label></label>
                                                                    </div>
                                                                </div>
                                                                @else
                                                                @endif
                                                            </th>
                                                            @if($item->User_Level ==1)
                                                            <th>{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th class="bg-success">Admin</th>
                                                            @elseif($item->User_Level ==0)
                                                            <th>{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th>User</th>
                                                            @elseif($item->User_Level == 2)
                                                            <th>{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th class="bg-warning">Finance</th>
                                                            @elseif($item->User_Level == 4)
                                                            <th>{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th class="bg-info">Customer</th>
                                                            @else
                                                            <th>{{$item->Money_ID}}</th>
                                                            <th>{{$item->Money_User}}</th>
                                                            <th class="bg-danger">Test</th>
                                                            @endif
                                                            <th>{{number_format($item->Money_USDT, 2)}}</th>
                                                            <th>{{number_format($item->Money_Rate, 2)}}</th>
                                                            <th>{{date('Y-m-d H:i:s',$item->Money_Time)}}</th>
                                                            <th>{{$item->Money_Confirm_Time}}</th>
                                                            @if($item->User_WalletGTC)
                                                            <th> Updated</th>
                                                            @else
                                                            <th>None</th>
                                                            @endif
                                                            @if($item->Money_Confirm == 1)
                                                            <th>Confirmed</th>
                                                            @elseif($item->Money_Confirm == -1)
                                                            <th>Cancel</th>
                                                            @else
                                                            @if($item->User_Level != 4 && $item->User_WalletGTC)
                                                            <th>
                                                                <a
                                                                    href="{{route('System.Admin.adminPayDailyInterest', [$item->Money_ID, 1])}}"><button
                                                                        class="btn btn-success btn-post-confirm-withdraw"
                                                                        data-value="{{$item->Money_ID}}"><i
                                                                            class="fa fa-server"
                                                                            aria-hidden="true"></i>Confirm</button></a>
                                                                <a
                                                                    href="{{route('System.Admin.adminPayDailyInterest', [$item->Money_ID, -1])}}"><button
                                                                        class="btn btn-danger btn-post-cancel-withdraw"
                                                                        data-value="{{$item->Money_ID}}"><i
                                                                            class="fa fa-times" aria-hidden="true"
                                                                            data-value="$item->Money_ID"></i>Cancel</button></a>
                                                            </th>
                                                            @else
                                                            <th>Pending</th>
                                                            @endif
                                                            @endif
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{$profitCofirm->appends(request()->input())->links()}}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<script>
    $('#revenue-product').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'excel', 'print'
            ],
        });
        $('#check-all-interest').change(function() {
            if(this.checked) {
                $('.item-check-interest').prop('checked', true);
            }
            if(!this.checked) {
                $('.item-check-interest').prop('checked', false);
            }
        });
        $('.item-check-interest').change(function() {
            if (!this.checked) {
                $('#check-all-interest').prop('checked', false);
            }
        });
        $('#select-action').change(function () {

            var optionSelected = $("option:selected", this).val();
            var listMoneyIDSelected = [];

            var list = $('.item-check-interest');
            $('.item-check-interest').each(function(){
                listMoneyIDSelected.push(this.value);
            });
            $.ajax({
                url: '{{route('System.Admin.adminPaySelectDailyInterest')}}',
                type: 'post',
                data: {list_id:listMoneyIDSelected, _token: "{{ csrf_token() }}", action_selected:optionSelected},
                success: function(response){
                    toastr.success(response.message, 'Error!', {timeOut: 3500});
                }
            });


        });
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); location.reload(); }
        });

</script>
@endsection
