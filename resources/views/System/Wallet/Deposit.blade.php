@extends('System.Layouts.Master')

@section('title')
Deposit
@endsection
@section('css')

@endsection

@section('content')

<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Deposit</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active">Deposit</li>
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
                        <h3 class="panel-title">Deposit</h3>
                    </div>
                    <div class="panel-body">
                        <div class="form-group" style="display: flex;">
							<select class="deposit select form-control custom-select" style="width: 100%; height:36px;">
								<option value="1">BTC</option>
								<option value="2">ETH</option>
								<option value="8">SOX</option>
							</select>
						</div>
                        <div class="form-group" style="display: flex;">
                            <input type="text" id="linkRef" name="example-input1-group2" class="form-control"
                                placeholder="Loading..." readonly>
                            <button type="button" id="tooltiptext" class="btn btn-primary"
                                onclick="copyToClipboard()"><i class="fa fa-clone" aria-hidden="true"></i>
                                Copy</button>

                        </div>
                        <div class="text-center">
                            <img id="QR" src="" style="width: 40%;">
                        </div>
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
        getAddress($('.deposit').val());
        $('.deposit').change(function(){
            _coin = $(this).val();
            getAddress(_coin);
        });
        function getAddress(_coin){
            $.getJSON( "{{ route('system.json.getAddress') }}?coin="+_coin, function( data ) {
                $('#linkRef').val(data['address']);
                $('#QR').attr('src', data['Qr']);
            });
        }
        
    });
</script>
<script>
    function copyToClipboard() {
		var copyText = document.getElementById("linkRef");
		copyText.select();
		copyText.setSelectionRange(0, 99999);
		console.log(copyText.setSelectionRange(0, 99999));
		document.execCommand("copy");
		var tooltip = document.getElementById("tooltiptext");
		alert(copyText.value);
	}
	
</script>
@endsection