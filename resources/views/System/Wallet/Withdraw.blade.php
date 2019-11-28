@extends('System.Layouts.Master')
@section('title')
Withdraw
@endsection
@section('css')
<style>
    .text-red {
        color: #F44336;
    }

    .d--none {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Withdraw</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active">Withdraw</li>
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
                                <h3 class="panel-title">Withdraw</h3>
                            </div>
                            <div class="pull-right">
                                <h4 class="control-label text-white m-0">Balance:
                                    <span class="balance_coin text-yellow"></span></h4>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <form id="form-withdraw" method="post" action="{{route('system.postWithdraw')}}">
                            @csrf
                            <div class="form-group">
                                <label class="control-label">From Wallet</label>
                                <select id="form_wallet" name="form_wallet" class="form-control">
                                    <option value="5">USDX</option>
                                    <option value="8">SOX</option>
                                </select>
                                <span class="coin-can-withdraw text-red"></span>
                            </div>


                            <div class="form-group">
                                <label class="control-label">Withdrawal</label>
                                <select id="coin_want_withdraw" name="coin_want_withdraw" class="form-control">
                                    <option class="coin-1" value="1">BTC</option>
                                    <option class="coin-2" value="2">ETH</option>
                                    <option class="coin-8" value="8">SOX</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Address Wallet</label>
                                <input type="text" class="form-control" name="address" placeholder="Wallet Address"
                                    value="{{Session('user')->User_WalletAddress}}" required>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Amount <span class="to_wallet_name"></span></label>
                                <input type="number" step="any" id="amount-input" name="amount" class="form-control"
                                    placeholder="Amount" required>

                            </div>
                            <div class="form-group">
                                <label class="control-label">Amount Withdraw <span
                                        class="withdraw_coin_name">Coin</span></label>

                                <input type="number" step="any" id="amount-coin" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Fee <span class="txt-red">(Fee withdraw
                                        <span class="feeWithdraw"></span>%)</span></label>
                                <input type="number" step="any" id="amount-fee" readonly="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label class="control-label">Authentication Code</label>
                                <input type="text" name="otp" class="form-control" placeholder="Google Authenticator"
                                    required>

                            </div>
                            <div class="text-center m-t-15 mb-3">
                                <button type="submit" class="btn btn-primary waves-effect waves-light"><i
                                        class="fa fa-paper-plane" aria-hidden="true"></i> Withdraw</button>
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
        Variable();
        function Variable(){
            _feeWithdraw = '{{ $feeWithdraw }}';
            _balance = { 5: '{{ $balance->USD }}', 8: '{{ $balance->SOX }}' };
            _rate = {5: '{{ $rate['USD'] }}', 1: '{{ $rate['BTC'] }}', 2: '{{ $rate['ETH'] }}', 8: '{{ $rate['SOX'] }}'};
            _symbol_coin = {1: 'BTC', 2: 'ETH', 5: 'USDX', 8: 'SOX' }
            _selected_balance =  $('#form_wallet').val();
            _coin_want_withdraw = $('#coin_want_withdraw').val();
            _amount = $('#amount-input').val();
        }
        $('#coin_want_withdraw').change(function(){
            Variable();
            _coin_want_withdraw =  $(this).val();
            Coin_Want_Withdraw(_coin_want_withdraw);
            Amount_Withdraw();
            Amount_Fee();
        });
        Coin_Want_Withdraw(_coin_want_withdraw);
        function Coin_Want_Withdraw(id){
            Variable();
            $('.withdraw_coin_name').html(_symbol_coin[id]);
            Amount_Withdraw();
            Amount_Fee();
        }
        
        Balance(_selected_balance);
        function Balance(name_balance){
            Variable();
            $get_balance = _balance[name_balance];
            $symbol_balance = _symbol_coin[name_balance];
            $('.balance_coin').html($get_balance+' '+$symbol_balance);
            $('.to_wallet_name').html($symbol_balance);
            Amount_Withdraw();
            Amount_Fee();
        }
        Option_Coin_Withdraw();
        function Option_Coin_Withdraw(){
            
            Variable();
            //remove
            console.log(_selected_balance);
            $('.d--none').removeClass('d--none');
            if(_selected_balance == 8){
                $("#coin_want_withdraw").val("8").change();
                $('#coin_want_withdraw .coin-'+1).addClass('d--none');
                $('#coin_want_withdraw .coin-'+2).addClass('d--none');
            }
            else{
                $("#coin_want_withdraw").val("1").change();
                $('#coin_want_withdraw .coin-'+8).addClass('d--none');
            }
        }
        $('#form_wallet').change(function(){
            Variable();
            _selected_balance =  $(this).val();
            Balance(_selected_balance);
            Amount_Withdraw();
            Amount_Fee();
            if(_selected_balance == 8){
                $('.coin-can-withdraw').html('You can only withdraw: SOX');
                
            }
            else{
                $('.coin-can-withdraw').html('You can only withdraw: BTC, ETH');
            }
            Option_Coin_Withdraw();
    
        });
        $('#amount-input').keyup(function(){
            Variable();
            Amount_Withdraw();
            Amount_Fee();
        });

        Amount_Withdraw();
        function Amount_Withdraw(){
            Variable();
            _amount = $('#amount-input').val();
            if(_selected_balance == 5){
                $('#amount-coin').val(_amount/_rate[_coin_want_withdraw]);
            }
            else{
                if(_selected_balance == 8){
                    if(_coin_want_withdraw == 1 || _coin_want_withdraw == 2){

                        $('#amount-coin').val(_amount * _rate[_selected_balance] / _rate[_coin_want_withdraw]);
                    }
                    else{
                        //8
                        $('#amount-coin').val(_amount);
                    }
                }

            }
        }
        Amount_Fee();
        function Amount_Fee(){
            $('.feeWithdraw').html(_feeWithdraw*100);
            $('#amount-fee').val(_amount*_feeWithdraw);
        }
    });
    
    
</script>

<script>
    $('#amount-input').blur(function(){
	    if(parseFloat(_amount) + parseFloat(_amount * _feeWithdraw) > parseFloat(_balance[_selected_balance])){
		    toastr.error('Your Balance Isn\t Enough', 'Error!', {timeOut: 3500});
	    }
    });

    $('#form-withdraw').submit(function(e){
        e.preventDefault();
        swal.fire({
            title: 'Confirm Withdrawal',
            text: 'Are You Sure Withdraw '+$('#amount-coin').val()+' '+ _symbol_coin[_coin_want_withdraw]+ ' ~ '+ $('#amount-input').val()+' '+_symbol_coin[$('#form_wallet').val()]+' ?',
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