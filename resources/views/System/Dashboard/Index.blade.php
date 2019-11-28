@extends('System.Layouts.Master')
@section('title', 'Dashboard')
@section('css')

<!-- DataTables -->
<link href="assets/plugins/datatables/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="assets/plugins/datatables/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css" />
<!--Morris Chart CSS -->
<link rel="stylesheet" href="assets/plugins/morris/morris.css">
@endsection
@section('content')
<div class="content">
	<div class="container">

		<!-- Page-Title -->
		<div class="row">
			<div class="col-sm-12">
				<div class="page-header-title">
					<h4 class="pull-left page-title">Dashboard</h4>
					<ol class="breadcrumb pull-right">
						<li><a href="javascript:void(0);">DAPP</a></li>
						<li class="active">Dashboard</li>
					</ol>
					<div class="clearfix"></div>
				</div>
			</div>
		</div>


		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-4">
				<div class="panel panel-primary text-center">
					<div class="panel-heading">
						<h4 class="panel-title">Balance</h4>
						<img src="assets/logo/Logo-DAFCO-1.png">
					</div>
					<div class="panel-body">
						<h3 class="line-bottom text-green"><b>{{ number_format($balance->USD,2) }} USDX</b></h3>
						<a href="{{ route('system.getDeposit') }}" class="btn btn-success waves-effect waves-light"><i
								class="fa fa-usd"></i> Deposit</a>
					</div>
				</div>
			</div>

			<div class="col-md-4">
				<div class="panel panel-primary text-center">
					<div class="panel-heading">
						<h4 class="panel-title">Balance</h4>
						<img src="assets/images/coin/money-bag.png">
					</div>
					<div class="panel-body">
						<h3 class="line-bottom text-green"><b>{{ number_format($balance->SOX, 4) }} SOX</b></h3>
						<a href="{{ route('system.getDeposit') }}" class="btn btn-success waves-effect waves-light"><i
								class="fa fa-usd"></i> Deposit</a>
					</div>
				</div>
			</div>

			{{-- <div class="col-lg-6">
				<div class="panel panel-border panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Price Token</h3>
					</div>
					<div class="panel-body">
						<div id="morris-line-example" style="height: 300px"></div>
					</div>
				</div>
			</div> --}}
			<div class="col-md-12">
				<div class="panel panel-primary">
					<div class="panel-heading">
						<h3 class="panel-title">Investment History</h3>
					</div>
					<div class="panel-body">
						<table id="dt-dashboard" class="table table-striped table-bordered dt-responsive nowrap"
							cellspacing="0" width="100%">
							<thead>

								<tr>
									<th>#</th>
									<th>Amount</th>
									<th>Interest/Month(%)</th>
									<th>Month Refund</th>
									<th>Currency</th>
									<th>Rate</th>
									<th>Time</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($history_invest as $item)
								<tr>
									<td>{{ $item->investment_ID}}</td>
									<td>{{ number_format($item->investment_Amount, 4) }}</td>
									<td>{{ $item->package_Interest * 100 }}%</td>
									<td>{{ $item->investment_Package_Time}}</td>
									<td>{{ $item->Currency_Name}}</td>
									<td>{{ $item->investment_Rate}}</td>
									<td>{{ Date('Y-m-d H:i:s', $item->investment_Time)}}</td>
									<td>
										@if($item->investment_Status == 1)
										<span class="badge badge-success">Active</span>
										@elseif($item->investment_Status == 2)
										<span class="badge badge-info">Refunded</span>
										@elseif($item->investment_Status == 0)
										<span class="badge badge-warning">Waiting</span>
										@else
										<span class="badge badge-danger">Canceled</span>
										@endif
									</td>
									<td>
									@if($item->investment_Status == 0)
										<form class="refund-{{$item->investment_ID}}" action="{{ route('postActionRefund', $item->investment_ID) }}" method="POST" style="margin-bottom: 10px;">
											@csrf @method('PUT')
											<button type="button" class="btn-refund btn btn-success" data-invest-id="{{ $item->investment_ID}}">Refund</button>
										</form>

										<form class="reinvest-{{$item->investment_ID}}" action="{{ route('postActionReinvestment', $item->investment_ID) }}" method="POST">
											@csrf @method('PUT')
											<button type="button" class="btn-reinvest btn btn-danger" data-invest-id="{{ $item->investment_ID}}">Reinvestment</button>
										</form>
									@endif
									</td>
								</tr>
								@endforeach

							</tbody>
						</table>

					</div>
				</div>
			</div>

		</div>
		<!-- End Row -->


	</div> <!-- container -->

</div> <!-- content -->
@endsection
@section('script')
<!-- Chart JS -->

<!-- Datatables-->
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/dataTables.bootstrap.js"></script>
<script src="assets/plugins/datatables/dataTables.responsive.min.js"></script>
<script src="assets/plugins/datatables/responsive.bootstrap.min.js"></script>

<!--Morris Chart-->
{{-- <script src="assets/plugins/morris/morris.min.js"></script>
<script src="assets/plugins/raphael/raphael-min.js"></script>
<script src="assets/pages/morris.init.js"></script> --}}


<script>
	$('#dt-dashboard').DataTable({
        "bLengthChange": false,
        "searching": false,
        "paging": false,
		"order": [0, 'desc']
    });
</script>
<script>
$(document).ready(function () {
	$('.btn-refund').click(function(){
		let invest_id = $(this).data('invest-id');
		console.log(invest_id);
		swal.fire({
			title: 'Confirm Refund Investment',
			text: 'Are You Sure Refund Investment',
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Submit',
			confirmButtonClass: 'btn btn-confirm',
			cancelButtonClass: 'btn btn-cancel',
			closeOnConfirm: true
		}).then(function (confirm) {
			console.log(confirm);
			if(confirm.value == true){
				$('.refund-'+invest_id).submit();

			}
		});
	})

	
	$('.btn-reinvest').click(function(){
		let invest_id = $(this).data('invest-id');
		console.log(invest_id);
		swal.fire({
			title: 'Confirm Reinvestment',
			text: 'Are You Sure Reinvestment',
			type: 'warning',
			showCancelButton: true,
			confirmButtonText: 'Submit',
			confirmButtonClass: 'btn btn-confirm',
			cancelButtonClass: 'btn btn-cancel',
			closeOnConfirm: true
		}).then(function (confirm) {
			console.log(confirm);
			if(confirm.value == true){
				$('.reinvest-'+invest_id).submit();

			}
		});
	})
});

</script>
@endsection