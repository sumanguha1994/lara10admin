@extends('admin.panel.layout.panellayout')
@section('page_title', 'Dashboard')
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
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            </ol>
                        </div>
                        <h4 class="page-title">Dashboard</h4>
                    </div>
                </div>
            </div>     
            <div class="row">
                <div class="col-xl-3 col-sm-6">
                    <div class="card-box widget-box-two widget-two-custom">
                        <div class="media">
                            <div class="avatar-lg rounded-circle bg-primary widget-two-icon align-self-center">
                                <i class="mdi mdi-currency-usd avatar-title font-30 text-white"></i>
                            </div>

                            <div class="wigdet-two-content media-body">
                                <p class="m-0 text-uppercase font-weight-medium text-truncate" title="Statistics">Total Revenue</p>
                                <h3 class="font-weight-medium my-2">$ <span data-plugin="counterup">65,841</span></h3>
                                <p class="m-0">Jan - Apr 2019</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    @include('admin.panel.layout.footer') 
</div>
@if($errors->has('email')) <script>toastr.warning("{{ ucwords($errors->first('email')) }}")</script>@endif
@if($errors->has('password')) <script>toastr.warning("{{ ucwords($errors->first('password')) }}")</script>@endif
@push('footer_script')
@endpush
@endsection