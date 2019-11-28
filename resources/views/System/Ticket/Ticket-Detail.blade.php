@extends('System.Layouts.Master')
@section('title', 'Ticket detail')
@section('css')
<style>
    .media,
    .content-cmt {
        margin: 2%;
    }

    .img-cmt {
        border-radius: 50%;
		float: left;
		margin-right: 2%;
		background: #f5a32b;
		padding: 5px;
		border: 2px #f5a827 solid;
    }

    .content-cmt {
        background: linear-gradient(to top, #f5b61a, #f58345) !important;
		padding: 15px 25px;
		color: white;
		border-radius: 5px;
    }

    .info-user {
        background: #f3f3f3;
        padding: 10px 20px;
		font-size: 18px;
		border-radius: 5px;
    }
    .info-user span{
		color: #0a8e88;
		font-weight: 600;
	}
    .info-user small{
		color: #0a8e88;
    }
    textarea {
        resize: none;
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
                    <!-- Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading" style="margin-bottom: 3em;">
                                    <div class="row">
                                        <div class="pull-left">
                                            <h3 class="panel-title txt-light">TICKET ID:{{$ticket[0]->ticket_ID}} </h3>
                                            <p>{{$ticket[0]->ticket_subject_name}}</p>
                                        </div>
                                    </div>
                                </div>
                                @foreach($ticket as $t)
                                <div class="media mb-4 mt-1">
                                    <img class="img-cmt d-flex mr-2 rounded-circle avatar-sm"
                                        src="assets/images/users/userProfile.png" width="50px"
                                        alt="Generic placeholder image">

                                    <div class="media-body info-user">
                                        <span class="float-right">{{ $t->ticket_Time }}</span>
                                        <h6 class="m-0 font-14">From:
                                            {{$t->User_Level == 1 ? 'Admin DAFCO' : $t->User_Email}}</h6>
                                        <small class="text-muted">ID: {{$t->ticket_User}}</small>
                                    </div>
                                </div>
                                <p class="content-cmt"><i class="fa fa-angle-right" aria-hidden="true"></i> {!! $t->ticket_Content !!}</p>

                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end inbox-rightbar-->
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading" style="margin-bottom: 3em;">
                                    <div class="row">
                                        <div class="pull-left">
                                            <h3 class="panel-title txt-light">REPLY TICKET ID: {{$ticket[0]->ticket_ID}} </h3>
                                        </div>
                                    </div>
                                </div>
                                <form action="{{route('postTicket')}}" method="post" class="ticket-comment-form">
                                    @csrf
                                    <input type="hidden" name="subject" value="{{$ticket[0]->ticket_Subject}}">
                                    <input type="hidden" name="replyID" value="{{$ticket[0]->ticket_ID}}">
                                    <div class="media mb-0">
                                        <img class="d-flex mr-3 rounded-circle avatar-sm img-cmt" width="50px"
                                            src="assets/images/users/userProfile.png" alt="Generic placeholder image">
                                        <div class="media-body">
                                            <div class="mb-2">

                                                <textarea name="content" cols="30" rows="10" class="form-control"
                                                    placeholder="Enter Content"></textarea>

                                            </div> <!-- end reply-box -->
                                        </div> <!-- end media-body -->
                                    </div> <!-- end medi-->

                                    <div class="text-right">
                                        <button style="margin: 2%;" type="submit" class="btn btn-primary btn-rounded width-sm">Send</button>
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

@endsection
@section('script')
@endsection