@extends('admin.panel.layout.panellayout')
@section('page_title', 'Profile')
@push('head_script')
@endpush
@section('content')
<style>
    .error{
        color: red;
    }
</style>
@if(session('error'))
    <script>toastr.error("{{session('error')}}")</script>
@endif
@if(session('success'))
    <script>toastr.success("{{session('success')}}")</script>
@endif
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{env('APP_NAME')}}</a></li>
                                <li class="breadcrumb-item active">Profile</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Profile</h4>
                    </div>
                </div>
            </div>     
            <div class="row">
                <div class="col-sm-12">
                    <?php $bgimg = asset('public/admin/images/bg-profile.jpg');?>
                    <div class="profile-bg-picture" style="background-image:url('{{$bgimg}}')">
                        <span class="picture-bg-overlay"></span>
                    </div>
                    <div class="profile-user-box">
                        <div class="row">
                            <div class="col-sm-6">
                                <span class="float-left mr-3"><img src="{{Auth::user()->avatar}}" alt="" class="avatar-xl rounded-circle"></span>
                                <div class="media-body">
                                    <h4 class="mt-1 mb-1 font-18 ellipsis">{{ucwords(Auth::user()->name)}}</h4>
                                    <p class="font-13"> <a href="mailto:{{Auth::user()->email}}">{{Auth::user()->email}}</a></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-right">
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="profileedit()">
                                        <i class="mdi mdi-account-settings-variant mr-1"></i> Edit Profile
                                    </button>
                                    <button type="button" class="btn btn-success waves-effect waves-light" onclick="passwordedit()">
                                        <i class="mdi mdi-account-settings-variant mr-1"></i> Edit Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card-box">
                        <h4 class="header-title mt-0 mb-12">Personal Information</h4>
                        <div class="panel-body">
                            <p class="text-muted font-13">{{ ucfirst(Auth::user()->info) }}</p>
                            <hr/>
                            <div class="text-left">
                                <p class="text-muted font-13"><strong>Full Name :</strong> <span class="ml-3">{{ucwords(Auth::user()->name)}}</span></p>
                                <p class="text-muted font-13"><strong>Mobile :</strong> <a href="tel:{{Auth::user()->phone}}"><span class="ml-3">{{Auth::user()->phone}}</span></a></p>
                                <p class="text-muted font-13"><strong>Email :</strong> <a href="mailto:{{Auth::user()->email}}"><span class="ml-3">{{Auth::user()->email}}</span></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    @include('admin.panel.layout.footer') 
</div>
<!-- edit modal  -->
<div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Edit Profile</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.profile', ['type'=>'profile'])}}" method="post" enctype="multipart/form-data" id="profileform">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="control-label">Full Name</label>
                                <input type="text" name="name" value="{{old('name', Auth::user()->name)}}" class="form-control" id="name" placeholder="John Doe">
                                @if($errors->has('name')) <span class="error"><small>{{ ucwords($errors->first('name')) }}</small></span> @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone" class="control-label">Phone</label>
                                <input type="text" name="phone" value="{{old('phone', Auth::user()->phone)}}" class="form-control" id="phone" placeholder="0123456789">
                                @if($errors->has('phone')) <span class="error"><small>{{ ucwords($errors->first('phone')) }}</small></span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="image" class="control-label">Profile Image</label>
                                <input type="file" name="image" class="form-control" id="image" accept="image/*">
                                @if($errors->has('image')) <span class="error"><small>{{ ucwords($errors->first('image')) }}</small></span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group no-margin">
                                <label for="info" class="control-label">Personal Info</label>
                                <textarea class="form-control" value="{{old('info', Auth::user()->info)}}" name="info" id="info" placeholder="Write something about yourself">{{old('info', Auth::user()->info)}}</textarea>
                                @if($errors->has('info')) <span class="error"><small>{{ ucwords($errors->first('info')) }}</small></span> @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="loader('show');$('#profileform').submit();">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- password modal  -->
<div id="password-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Change Passowrd</h4>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.profile', ['type'=>'password'])}}" method="post" enctype="multipart/form-data" id="passwordform">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="emailid" class="control-label">Email-ID</label>
                                <input type="text" class="form-control" readonly value="{{Auth::user()->email}}" id="emailid" placeholder="John Doe">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="old_password" class="control-label">Password</label>
                                <input type="password" name="old_password" class="form-control" id="old_password" placeholder="Password">
                                @if($errors->has('old_password')) <span class="error"><small>{{ ucwords($errors->first('old_password')) }}</small></span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_password" class="control-label">New Password</label>
                                <input type="password" name="new_password" class="form-control" id="new_password" placeholder="New Password">
                                @if($errors->has('new_password')) <span class="error"><small>{{ ucwords($errors->first('new_password')) }}</small></span> @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="confirm_password" class="control-label">Confirm Password</label>
                                <input type="text" name="confirm_password" class="form-control" id="confirm_password" placeholder="Confirm New Password">
                                @if($errors->has('confirm_password')) <span class="error"><small>{{ ucwords($errors->first('confirm_password')) }}</small></span> @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="loader('show');$('#passwordform').submit();">Save changes</button>
            </div>
        </div>
    </div>
</div>
@push('footer_script')
<script>
    function profileedit(){
        $('#con-close-modal').modal({backdrop: 'static', keyboard: false}, 'show');
    }
    function passwordedit(){
        resetpasswordform();
        $('#password-modal').modal({backdrop: 'static', keyboard: false}, 'show');
    }
    function resetpasswordform(){
        $('#passwordform').find('#old_password').val('');
        $('#passwordform').find('#new_password').val('');
        $('#passwordform').find('#confirm_password').val('');
    }
    setTimeout(() => {
        $('.error').remove();
    }, 3500);
</script>
@if(@$errors->has('profile_err'))
    <script>profileedit();</script>
@endif
@if(@$errors->has('password_err'))
    <script>passwordedit();</script>
@endif
@endpush
@endsection