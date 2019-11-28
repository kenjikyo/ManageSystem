@extends('System.Layouts.Master')
@section('title')
Transfer
@endsection
@section('css')

<style>
    span.message-custom.success {
        color: #009688;
    }

    span.message-custom.error {
        color: #F44336;
    }

    .icon-spin {
        position: absolute;
        top: 22%;
        right: 3%;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Transfer</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active">Transfer</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
							<div class="pull-left">
								<h3 class="panel-title">Transfer</h3>
							</div>
							<div class="pull-right">
								<h4 class="control-label text-white m-0">Balance:
									<span class="balance_coin text-yellow"></span></h4>
							</div>
						</div>
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('system.postTransfer') }}" method="POST" id="form-transfer">
                            @csrf
                            <div class="form-group">
								<label class="control-label">Select coin</label>
								<select id="currency" name="currency" class="select form-control custom-select"
									style="width: 100%; height:36px;">
									<option value="5">USDX</option>
									<option value="8">SOX</option>
								</select>
							</div>
                            <div class="form-group">
                                <label class="control-label">Member ID</label>
                                <input type="text" id="userID" name="userID" class="form-control d-block"
                                    placeholder="..." required>
                                <div class="alert-custom mb-2"></div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Amount</label>
                                <input class="form-control" id="amount-transfer" type="text" name="amount"
                                    placeholder="0" required />
                            </div>
                            <div class="form-group">
                                <label class="control-label">Authentication Code</label>
                                <input type="text" name="otp" class="form-control" placeholder="Google Authenticator"
                                    required>
                            </div>
                            <div class="text-center m-t-15 mb-3">
                                <button type="submit" class="btn btn-primary waves-effect waves-light"><i
                                        class="fa fa-paper-plane" aria-hidden="true"></i> Transfer</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- container -->

</div> <!-- content -->

@endsection

@section('script')
<script>
    $(document).ready(function () {
    _balance = { 5: '{{ $balance->USD }}', 8: '{{ $balance->SOX }}' };
    
    _symbol_coin = { 5: 'USDX', 8: 'SOX' }
    _selected_balance =  $('#currency').val();

    Balance(_selected_balance);
    function Balance(name_balance){
        $get_balance = _balance[name_balance];
        $symbol_balance = _symbol_coin[name_balance];
        $('.balance_coin').html($get_balance+' '+$symbol_balance);
        $('.symbol_coin').html($symbol_balance);
    }

    $('#currency').change(function(){
        _selected_balance =  $(this).val();
        Balance(_selected_balance);

    });
});


</script>
<script>
    $('#form-transfer #userID').blur(function(){
        
        $.ajax({
        type: "GET",
        url: "{{ route('system.getAjaxUser')}}",
        data: {
            userID : $('#userID').val(),
        },
        success: function (data) {

            //remove
            $('.message-custom').remove();

            $('.alert-custom').after('<span class="message-custom '+data.class+'">'+data.message+'</span>');
        }
        })
    });
    $('#form-transfer').submit(function(e){
        e.preventDefault();
        swal.fire({
            title: 'Transfer to '+$('#userID').val(),
            text: 'Are You Sure Transfer '+$('#amount-transfer').val()+' '+_symbol_coin[_selected_balance]+ ' ?' ,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Submit',
            confirmButtonClass: 'btn btn-confirm',
            cancelButtonClass: 'btn btn-cancel',
            closeOnConfirm: true
        }).then(function (confirm) {
            console.log(confirm);
            if(confirm.value == true){
                document.forms[0].submit();
            }
        });  
    });


</script>
@endsection