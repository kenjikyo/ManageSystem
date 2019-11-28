@extends('System.Layouts.Master')
@section('title', 'Profile')
@section('css')
<link data-require="sweet-alert@*" data-semver="0.4.2" rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
<link href="assets/css/dropify.min.css" rel="stylesheet" type="text/css">
@endsection
@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="page-header-title">
                    <h4 class="pull-left page-title">Profile</h4>
                    <ol class="breadcrumb pull-right">
                        <li><a href="javascript:void(0);">DAPP</a></li>
                        <li class="active" style="color:#fff">Profile</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <div class="col-md-12">
                    <div class="panel panel-primary text-center">
                        <div class="panel-heading">
                            <h4 class="panel-title">Profile</h4>
                        </div>
                        <div class="panel-body">
                            <div class="profile-box">
                                <div class="profile-cover-pic">
                                </div>
                                <div class="profile-info text-center mb-15">
                                    <div class="profile-img-wrap">
                                        <img class="inline-block mb-10" src="assets/images/users/userProfile.png"
                                            alt="user" style="width: 124px;" />
                                    </div>
                                    <h5 class="block mt-10 mb-5 weight-500 capitalize-font txt-light">
                                        {{$user->User_Name}}</h5>
                                    <h6 class="block capitalize-font txt-light mb-5">ID:
                                        <span>{{$user->User_ID}}</span></h6>
                                </div>
                                <div class="social-info">
                                    <button class="btn btn-success btn-block btn-anim" data-toggle="modal"
                                        data-target="#myModal"><span class="btn-text">Change Password</span></button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="panel panel-primary text-center">
                        <div class="panel-heading">
                            <h4 class="panel-title">Profile</h4>
                        </div>
                        <div class="panel-body">
                            <div class="panel panel-default card-view">
                                <div class="panel-heading">
                                    <div class="pull-left">
                                        <h6 class="panel-title txt-light text-center">
                                            <div><img src="dist/img/authentic.png" width="50"></div>
                                            <div>Google Authentication</div>
                                            <div>Used for withdrawals and security modifications</div>
                                        </h6>
                                    </div>
                                    <button class="btn btn-{{ ($Enable) ? 'danger' : 'success' }} btn-block  btn-anim "
                                        data-toggle="modal" data-target="#m-a-a"><span
                                            class="btn-text">{{ ($Enable) ? 'Disable Auth' : 'Enable Auth' }}</span></button>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">USER INFORMATION</h4>
                        </div>
                        <div class="panel-body">
                            <form method="post" action="{{route('postProfile')}} " enctype="multipart/form-data">
                                @csrf
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body pa-0">
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="form-wrap">
                                                <div class="form-body overflow-hide">
                                                    <div class="form-group">
                                                        <label class="control-label mb-10"
                                                            for="exampleInputContact_01">Wallet SonicX</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-google-wallet" aria-hidden="true"></i>
                                                            </div>
                                                            <input type="text" required class="form-control"
                                                                id="address" name="address"
                                                                placeholder="Enter address wallet SOX"
                                                                value="{{Session('user')->User_WalletAddress}}">
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label mb-10"
                                                            for="exampleInputEmail_01">Google
                                                            authenticator</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-google-plus" aria-hidden="true"></i>
                                                            </div>
                                                            <input type="text" class="form-control" id="otp" name="otp"
                                                                required placeholder="Enter Google Authenticator">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions" style="margin-bottom: 20px;float: right;">
                                            <button type="submit" class="btn btn-success btn-bd1  mr-10" id=""><i
                                                    class="fa fa-save" aria-hidden="true"></i>
                                                Save</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">

                            <h4 class="panel-title">VERIFICATION</h4>
                        </div>
                        <div class="panel-body">
                            <form method="post" id="post-profile" action="{{route('system.user.PostKYC')}} "
                                enctype="multipart/form-data">
                                @csrf
                                <h5 class="mb-3 text-uppercase bg-light p-2"><i class="fas fa-address-book mr-1"></i>
                                    Info</h5>
                                <div class="form-group">
                                    <label for="address"><i class="fa fa-google-wallet"></i>
                                        ID/Passport Number</label>
                                    <input type="text" class="form-control" name="passport" id="passport"
                                        placeholder="ID/Passport Number"
                                        value="{{ $kycProfile ? $kycProfile->Profile_Passport_ID : ''}}">
                                </div>
                                <div class="form-group">
                                    <label for="address"><i class="fa fa-google-wallet"></i>
                                        ID/Passport</label>
                                    <br>
                                    <small style="color: #F44336;">Make sure the
                                        image is full and clear and the format
                                        is jpg, jpeg.</small>
                                    <br>    
                                    <i style="color: #F44336; font-weight: bold;">Please use image up to maximum 2MB size</i>

                                    <input type="file" name="passport_image" id="passport-image" class="dropify bg-dark"
                                        data-default-file="{{ $kycProfile ? 'http://media.dafco.org/'.$kycProfile->Profile_Passport_Image : ''}}"
                                        accept="image/*" />
                                </div>
                                <div class="form-group">
                                    <label for="address"><i class="fa fa-google-wallet"></i>
                                        Selfie</label>
                                    <br>
                                    <small style="color: #F44336;">Make sure the
                                        image is full and clear and the format
                                        is jpg, jpeg.</small>
                                    <br>
                                    <small style="color: #F44336;">
                                        <i class="fa fa-caret-right" aria-hidden="true"></i> Your face
                                        <br>
                                        <i class="fa fa-caret-right" aria-hidden="true"></i> Your
                                        ID/Passport
                                    </small>
                                    <br>    
                                    <i style="color: #F44336; font-weight: bold;">Please use image up to maximum 2MB size</i>
                                    <input type="file" name="passport_image_selfie" id="passport_image_selfie"
                                        class="dropify bg-dark"
                                        data-default-file="{{ $kycProfile ? 'http://media.dafco.org/'.$kycProfile->Profile_Passport_Image_Selfie : ''}}"
                                        accept="image/*" />
                                </div>
                                <div class="text-right">
                                    <button id='btn-save-profile' type="submit"
                                        class="btn btn-success waves-effect waves-light mt-2"><i
                                            class="mdi mdi-content-save"></i>
                                        Save</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div id="myModal" class="modal fade in" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{route('postChangePassword')}}" method="post">
                @csrf
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h5 style="margin: 10px 0px 0px 10px;" class="modal-title" id="myModalLabel">
                        Change Password</h5>
                </div>
                <div class="modal-body">
                    <!-- Row -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="">
                                <div class="panel-wrapper collapse in">
                                    <div class="panel-body pa-0">
                                        <div class="col-sm-12 col-xs-12">
                                            <div class="form-wrap">
                                                <div class="form-body overflow-hide">
                                                    <div class="form-group">
                                                        <label class="mb-10 text-dark" for="exampleInputpwd_1">Current
                                                            Password</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="icon-lock"></i>
                                                            </div>
                                                            <input type="password" class="form-control"
                                                                name="current_password"
                                                                placeholder="Enter current password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="mb-10 text-dark" for="exampleInputpwd_1">New
                                                            Password</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="icon-lock"></i>
                                                            </div>
                                                            <input type="password" class="form-control"
                                                                name="new_password" placeholder="Enter new password">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="mb-10 text-dark" for="exampleInputpwd_1">Password
                                                            confirm</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="icon-lock"></i>
                                                            </div>
                                                            <input type="password" class="form-control"
                                                                name="password_confirm"
                                                                placeholder="Enter password confirm">
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
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success waves-effect"><i class="fa fa-floppy-o"
                            aria-hidden="true"></i>
                        Save</button>
                    <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal"><i
                            class="fa fa-times" aria-hidden="true"></i>
                        Cancel</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div id="m-a-a" class="modal fade animate" data-backdrop="true">
    <div class="modal-dialog" id="animate">
        <div class="modal-content">
            <div class="modal-header">
                <h4 style=" margin: 9px 0px 0px 20px;" class="modal-title" id="myModalLabel">
                    {{ ($Enable) ? 'Disable Authenticator' : 'Enable Authenticator' }}
                </h4>
            </div>
            <!-- Modal Body -->
            <div class="modal-body text-center">
                <form role="form" action="{{route('postAuth')}}" method="POST" style="color:black!important;">
                    {{csrf_field()}}
                    @if(!$Enable)
                    Authenticator Secret Code: <b>{{ $secret }}</b>
                    <img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl={{ $inlineUrl }}&choe=UTF-8">
                    @endif
                    <label>Enter the 2-step verification code provided by your authentication
                        app</label>
                    <input type="text" name="verifyCode" class="form-control" id="exampleInputuname_01" placeholder=""
                        value="">
                    <div class="input-group m-t-5">
                        <button type="submit"
                            class="btn btn-primary btn-block  btn-anim">{{ ($Enable) ? 'Disable' : 'Enable' }}</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>
@endsection

@section('script')
<script src="assets/js/dropify.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    /*FileUpload Init*/
        $(document).ready(function() {
            "use strict";

            /* Basic Init*/
            $('.dropify').dropify();

            /* Translated Init*/
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-dÃ©posez un fichier ici ou cliquez',
                    replace: 'Glissez-dÃ©posez un fichier ou cliquez pour remplacer',
                    remove:  'Supprimer',
                    error:   'DÃ©solÃ©, le fichier trop volumineux'
                }
            });

            /* Used events */
            //
            var drEvent = $('#input-file-events').dropify();

            drEvent.on('dropify.beforeClear', function(event, element){
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });

            drEvent.on('dropify.afterClear', function(event, element){
                alert('File deleted');
            });

            drEvent.on('dropify.errors', function(event, element){
                console.log('Has Errors');
            });

            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e){
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            });

        });
        $('#post-profile').submit(function() {
        $(this).find("button[type='submit']").prop('disabled',true);
        $('#btn-save-profile').text('Loading...');
    });
</script>
@endsection