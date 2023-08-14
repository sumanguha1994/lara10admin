@extends('admin.auth.layout.authlayout')
@section('page_title', 'Log in')
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
                    <div class="account-logo-box">
                        <div class="text-center">
                            <a href="{{route('admin.login')}}"></a>
                                <img src="{{ asset('public/admin/images/logo-dark.png') }}" alt="" height="30">
                            
                        </div>
                        <h5 class="text-uppercase mb-1 mt-4">Sign In</h5>
                        <p class="mb-0">Login to your Admin account</p>
                    </div>
                    <div class="account-content mt-4">
                        <form class="form-horizontal" action="{{route('admin.login')}}" method="POST" id="loginform">
                            @csrf
                            <div class="form-group row">
                                <div class="col-12">
                                    <label for="emailaddress">Email address</label>
                                    <input class="form-control" type="email" name="email" id="emailaddress" placeholder="john@deo.com">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <a href="{{route('admin.forgot.password')}}" class="text-muted float-right"><small>Forgot your password?</small></a>
                                    <label for="password">Password</label>
                                    <input class="form-control" type="password" name="password" required="" id="password" placeholder="Enter your password">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="checkbox checkbox-success">
                                        <input id="remember" type="checkbox" name="rememberme" checked="">
                                        <label for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>  
                            </div>
                            <div class="form-group row text-center mt-2">
                                <div class="col-12">
                                    <button class="btn btn-md btn-block btn-primary waves-effect waves-light" type="button" onclick="loader('show');$('#loginform').submit();">Sign In</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($errors->has('email')) <script>toastr.warning("{{ ucwords($errors->first('email')) }}")</script>@endif
@if($errors->has('password')) <script>toastr.warning("{{ ucwords($errors->first('password')) }}")</script>@endif
@push('footer_script')
@endpush
@endsection