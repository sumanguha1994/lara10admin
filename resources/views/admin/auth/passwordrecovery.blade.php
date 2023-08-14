@extends('admin.auth.layout.authlayout')
@section('page_title', 'Password Recovery')
@push('head_script')
@endpush
@section('auth_content')
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
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6 col-xl-5">                                                                                                              
        <div class="card mb-0">
            <div class="card-body p-4">
                <div class="account-box">
                    <div class="text-center account-logo-box">
                        <div>
                            <a href="index.html">
                                <img src="{{ asset('public/admin/images/logo-dark.png') }}" alt="" height="30">
                            </a>
                        </div>
                    </div>
                    <div class="account-content mt-4">
                        <div class="text-center">
                            <p class="text-muted mb-0 mb-3">Enter your email address and we'll send you an email with instructions to reset your password.  </p>
                        </div>
                        <form class="form-horizontal" action="{{route('admin.password.recovery')}}" method="POST" id="passwordrecoveryform"> 
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control" name="recovery_mail" value="{{@$user->email}}" readonly type="email" id="emailaddress" required="" placeholder="john@deo.com">
                                </div>
                                <div class="col-12">
                                    <label for="emailaddress">Password</label>
                                    <input class="form-control" name="password" type="password" id="password" required="" placeholder="Password">
                                </div>
                                <div class="col-12">
                                    <label for="emailaddress">Confoim Password</label>
                                    <input class="form-control" name="confirm_password" type="text" id="confpassword" required="" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="form-group row text-center mt-2">
                                <div class="col-12">
                                    <button class="btn btn-md btn-block btn-primary waves-effect waves-light" type="button" onclick="loader('show');$('#passwordrecoveryform').submit()">Reset Password</button>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>

                        <div class="row mt-4">
                            <div class="col-sm-12 text-center">
                                <p class="text-muted mb-0">Back to <a href="{{route('admin.login')}}" class="text-dark ml-1"><b>Sign In</b></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($errors->has('recovery_mail')) <script>toastr.warning("{{ ucwords($errors->first('recovery_mail')) }}")</script>@endif
@if($errors->has('password')) <script>toastr.warning("{{ ucwords($errors->first('password')) }}")</script>@endif
@if($errors->has('confirm_password')) <script>toastr.warning("{{ ucwords($errors->first('confirm_password')) }}")</script>@endif
@push('footer_script')
@endpush
@endsection