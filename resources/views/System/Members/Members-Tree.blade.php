@extends('System.Layouts.Master')
@section('title', 'Member Tree')
@section('css')
<style>
    a:hover {
        cursor: pointer;
    }
</style>

<style>
    .copytooltip {
        position: relative;
        display: inline-block;
    }

    .copytooltip .tooltiptext {
        visibility: hidden;
        width: 100px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px;
        position: absolute;
        z-index: 1;
        bottom: 150%;
        left: 50%;
        margin-left: -75px;
        opacity: 0.5;
        transition: opacity 0.3s;
    }

    .copytooltip .tooltiptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
    }

    .copytooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 0.5;
    }

    .dt-buttons {
        margin-top: 15px;
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
                    <h4 class="pull-left page-title">Member Tree</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAFF</a></li>
                        <li class="active">Member Tree</li>
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
                    <div class="form-group" style="opacity: 0">
                        <div>
                            <button type="button" class="btn btn-primary waves-effect waves-light">
                                TTT
                            </button>
                            <button type="button" class="btn btn-danger waves-effect m-l-5">
                                TTT
                            </button>
                        </div>
                    </div>
                </div> <!-- panel-body -->
            </div> <!-- panel -->
        </div>

        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Adding User</h3>
                </div>
                <div class="panel-body">

                    <div class="">
                        <form action="{{route('system.user.postMemberAdd')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div>
                                    <div class="input-group">
                                        <input type="email" name="email" class="form-control" placeholder="Email">
                                        <span class="input-group-addon bg-custom btn-success b-0"><i
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
                                    </button>
                                    <button type="reset" class="btn btn-danger waves-effect m-l-5">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div> <!-- panel-body -->
            </div> <!-- panel -->
        </div>
        {{-- <div class="row">
            <div class="col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Search User ID</h3>
                    </div>
                    <div class="panel-body">

                        <div class="">
                            <form method="get">
                                <div class="form-group">
                                    <input type="email" name="userEmail" class="form-control" placeholder="Email">
                                </div>
                                <div class="form-group">
                                    <input type="text" name="userID" class="form-control" placeholder="User ID">
                                </div>
                                <div class="form-group">
                                    <div>
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div> <!-- panel-body -->
                </div> <!-- panel -->
            </div> --}}
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <div class="tree-view" id="treeview4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
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
</script>
<!-- Data table JavaScript -->
<script src="assets/js/bootstrap-treeview.min.js" type="text/javascript"></script>
<script>
    $(function() {
        "use strict";
        var json = {!!$Children!!}
        console.log(json);
        $('#treeview4').treeview({
            data: json
        });
    });
</script>

@endsection