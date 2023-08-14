@extends('admin.panel.layout.panellayout')
@section('page_title', 'Setting')
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
<?php
    $errtype = 'site_err';
    if($errors->has('site_err')){
        $errtype = $errors->first('site_err_type');
    }
?>
<div class="content-page">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{env('APP_NAME')}}</a></li>
                                <li class="breadcrumb-item active">Setting</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Setting</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <div class="card-box">
                        <ul class="nav nav-tabs tabs-bordered">
                            <li class="nav-item">
                                <a href="#home-b1" data-toggle="tab" aria-expanded="false" class="nav-link <?php echo $errtype == 'site_err' ? 'active' : ''?>">
                                    <span class="d-block d-sm-none"><i class="mdi mdi-home-variant"></i></span>
                                    <span class="d-none d-sm-block">Site</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#messages-b1" data-toggle="tab" aria-expanded="false" class="nav-link <?php echo $errtype == 'smtp_err' ? 'active' : ''?>">
                                    <span class="d-block d-sm-none"><i class="mdi mdi-email-outline"></i></span>
                                    <span class="d-none d-sm-block">Smtp</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane <?php echo $errtype == 'site_err' ? 'show active' : ''?>" id="home-b1">
                                <div class="col-md-10">
                                    <form class="parsley-examples" novalidate="" action="{{route('admin.setting', ['type'=>'sitesetting'])}}" method="post" enctype="multipart/form-data" id="settingform">
                                        @csrf
                                        <div class="form-group row">
                                            <div class="col-md-10">
                                                <label for="site_name">Site name <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="site_name" id="site_name" placeholder="Site Name" parsley-trigger="change" required>
                                                @if($errors->has('site_name')) <span class="error"><small>{{ ucwords($errors->first('site_name')) }}</small></span> @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-5">
                                                <label for="contact_mail">Contact mail <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="contact_mail" id="contact_mail" placeholder="Contact Mail-ID" parsley-trigger="change" required>
                                                @if($errors->has('contact_mail')) <span class="error"><small>{{ ucwords($errors->first('contact_mail')) }}</small></span> @endif
                                            </div>
                                            <div class="col-md-5">
                                                <label for="logo">Site Logo</label>
                                                <input type="file" class="form-control" name="logo" id="logo" accept="image/*">
                                                @if($errors->has('logo')) <span class="error"><small>{{ ucwords($errors->first('logo')) }}</small></span> @endif
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-10">
                                                <label for="address">Address <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="address" id="address" placeholder="Full Address" placeholder="Site Name" parsley-trigger="change" required>
                                                @if($errors->has('address')) <span class="error"><small>{{ ucwords($errors->first('address')) }}</small></span> @endif
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-10">
                                                <button type="button" class="btn btn-lg btn-success waves-effect waves-light mr-1" onclick="$('#settingform').submit();">Site info save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            <div class="tab-pane <?php echo $errtype == 'smtp_err' ? 'show active' : ''?>" id="messages-b1">
                                <form class="parsley-examples" novalidate="" action="{{route('admin.setting', ['type'=>'smtpsetting'])}}" method="post" enctype="multipart/form-data" id="smtpform">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-4">
                                            <label for="smtp_host">SMTP Host <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="smtp_host" id="smtp_host" placeholder="Host" parsley-trigger="change" required>
                                            @if($errors->has('smtp_host')) <span class="error"><small>{{ ucwords($errors->first('smtp_host')) }}</small></span> @endif
                                        </div>
                                        <div class="col-md-6">
                                            <label for="smtp_from_name">SMTP From Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="smtp_from_name" id="smtp_from_name" placeholder="From Name" parsley-trigger="change" required>
                                            @if($errors->has('smtp_from_name')) <span class="error"><small>{{ ucwords($errors->first('smtp_from_name')) }}</small></span> @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-5">
                                            <label for="smtp_username">SMTP Username <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="smtp_username" id="smtp_username" placeholder="Username" parsley-trigger="change" required>
                                            @if($errors->has('smtp_username')) <span class="error"><small>{{ ucwords($errors->first('smtp_username')) }}</small></span> @endif
                                        </div>
                                        <div class="col-md-5">
                                            <label for="smtp_password">SMTP Password <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="smtp_password" id="smtp_password" placeholder="Password" parsley-trigger="change" required>
                                            @if($errors->has('smtp_password')) <span class="error"><small>{{ ucwords($errors->first('smtp_password')) }}</small></span> @endif
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-10">
                                            <label for="smtp_from_address">SMTP From Address <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="smtp_from_address" id="smtp_from_address" placeholder="From Address" parsley-trigger="change" required>
                                            @if($errors->has('smtp_from_address')) <span class="error"><small>{{ ucwords($errors->first('smtp_from_address')) }}</small></span> @endif
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-10">
                                            <button type="button" class="btn btn-lg btn-success waves-effect waves-light mr-1" onclick="$('#smtpform').submit();">Smtp info save</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    @include('admin.panel.layout.footer') 
</div>
@push('footer_script')
    <script src="{{asset('public/admin/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('public/admin/js/pages/form-validation.init.js')}}"></script>
    <script>
        setTimeout(() => {
            $('.error').remove();
        }, 3500);
    </script>
@if(@$errors->has('site_err'))
    <script></script>
@endif
@endpush
@endsection