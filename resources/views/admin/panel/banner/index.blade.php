@extends('admin.panel.layout.panellayout')
@section('page_title', 'Setting')
@push('head_script')
<link href="{{asset('public/admin/libs/slick-slider/slick.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('public/admin/libs/slick-slider/slick-theme.css')}}" rel="stylesheet" type="text/css"/>
<link href="{{asset('public/admin/libs/rwd-table/rwd-table.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/admin/libs/jquery-toast/jquery.toast.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<style>
    .error{
        color: red;
    }
    .img{
        height: 30px;
        width: 60px;
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
                                <li class="breadcrumb-item active">Banner</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Banner</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">                    
                    <div class="card-box">
                        <img class="icon-colored" data-toggle="tooltip" title="Add Banner" onclick="openmodal()" src="{{asset('public/admin/images/icons/plus.svg')}}" title="gallery.svg" style="margin: 0px !important;">
                        <img class="icon-colored" data-toggle="tooltip" title="Show Banner" onclick="showmodal()" src="{{asset('public/admin/images/icons/stack_of_photos.svg')}}" title="stack_of_photos.svg" style="margin: 0px !important;">
                        <h4 class="header-title mb-3">Banner Images</h4>
                        <div class="slider-syncing-for" dir="ltr">
                            @foreach($banners as $b)
                            <div>
                                <img src="{{$b->avatar}}" alt="slider-img" class="img-fluid" style="width: 1017px;height: 378px;opacity: {{$b->status == 'I' ? '0.5' : '1'}}">
                            </div>
                            @endforeach
                        </div>

                        <div class="slider-syncing-nav" dir="ltr">
                            @foreach($banners as $b)
                            <div>
                                <img src="{{$b->avatar}}" alt="slider-img" class="img-fluid" style="width: 174px;height: 65px;opacity: {{$b->status == 'I' ? '0.5' : '1'}}">
                            </div>
                            @endforeach
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
                <h4 class="modal-title">Banner Modal</h4>
            </div>
            <div class="modal-body">
                <form class="parsley-examples" novalidate="" action="{{route('admin.banner')}}" method="post" enctype="multipart/form-data" id="bannerform">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="banner_heading">Heading <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{old('banner_heading')}}" name="banner_heading" id="banner_heading" placeholder="Banner Heading" parsley-trigger="change" required>
                            @if($errors->has('banner_heading')) <span class="error"><small>{{ ucwords($errors->first('banner_heading')) }}</small></span> @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="banner_image">Image </label>
                            <input type="file" class="form-control" name="banner_image" id="banner_image" accept="image/*">
                            @if($errors->has('banner_image')) <span class="error"><small>{{ ucwords($errors->first('banner_image')) }}</small></span> @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea  class="form-control" value="{{old('description')}}" name="description" id="description" placeholder="Banner Description" parsley-trigger="change" required>{{old('description')}}</textarea>
                            @if($errors->has('description')) <span class="error"><small>{{ ucwords($errors->first('description')) }}</small></span> @endif
                        </div>
                    </div>
                    <input type="hidden" name="rowid" id="rowid">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="$('#bannerform').submit();">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- list modal  -->
<div id="show-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Banner List</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card-box">
                            <div class="table-rep-plugin">
                                <div class="table-responsive mb-0" data-pattern="priority-columns">
                                    <table id="tech-companies-1" class="table  table-striped">
                                        <thead>
                                            <tr>
                                                <th>Heading</th>
                                                <th data-priority="1">Image</th>
                                                <th data-priority="2">description</th>
                                                <th data-priority="3" class="text-center">status</th>
                                                <th data-priority="4" class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($banners as $b)
                                            <tr>
                                                <th>{{ucwords($b->heading)}}</th>
                                                <td><a target="_blank" href="{{$b->avatar}}"><img class="img" src="{{$b->avatar}}" alt=""></a></td>
                                                <td style="white-space: pre-line;">{!! $b->description !!}</td>
                                                <td class="text-center {{$b->status == 'A' ? 'text-primary' : 'text-warning'}}">
                                                    @if($b->status == 'A')
                                                        <a href="javascript:void(0);" data-toggle="tooltip" title="Change to inactive" class="text-info" onclick="changestatus('{{base64_encode($b->id)}}', 'banners', 'I')"><i class="fe-toggle-right"></i></a>
                                                    @else
                                                        <a href="javascript:void(0);" data-toggle="tooltip" title="Change to active" class="text-warning" onclick="changestatus('{{base64_encode($b->id)}}', 'banners', 'A')"><i class="fe-toggle-left"></i></a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="javascript:void(0);" data-toggle="tooltip" title="Edit {{ucwords($b->heading)}}" class="text-secondary" onclick="edit('{{base64_encode($b->id)}}')"><i class="fe-edit"></i></a>
                                                    <a href="javascript:void(0);" data-toggle="tooltip" title="Delete" class="text-danger" onclick="changestatus('{{base64_encode($b->id)}}', 'banners', 'D')"><i class="fe-trash-2"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('footer_script')
    <script src="{{asset('public/admin/libs/slick-slider/slick.min.js')}}"></script>
    <script src="{{asset('public/admin/js/pages/slick-slider.init.js')}}"></script>
    <script src="{{asset('public/admin/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('public/admin/js/pages/form-validation.init.js')}}"></script>
    <script src="{{asset('public/admin/libs/rwd-table/rwd-table.min.js')}}"></script>
    <script src="{{asset('public/admin/js/pages/responsive-table.init.js')}}"></script>
    <script src="{{asset('public/admin/libs/jquery-toast/jquery.toast.min.js')}}"></script>
    <script src="{{asset('public/admin/js/pages/toastr.init.js')}}"></script>
    <script>
        setTimeout(() => {
            $('.error').remove();
        }, 3500);
        function openmodal(){
            resetmodal();
            $('#con-close-modal').modal({backdrop: 'static', keyboard: false}, 'show');
        }
        function showmodal(){
            $('#show-modal').modal({backdrop: 'static', keyboard: false}, 'show');
        }
        function edit(rowid){
            let url = "{{route('admin.get.single')}}/banner/" + rowid;
            $.get(url, function(res){
                if(res.code == '200'){                    
                    $('#show-modal').modal('hide');
                    openmodal();
                    putvalue(res.data);
                }
            });
        }
        function resetmodal(){
            $('#bannerform').find('#banner_heading').val('');
            $('#bannerform').find('#description').val('');
            $('#bannerform').find('#rowid').val('');
        }
        function putvalue(res){
            $('#bannerform').find('#banner_heading').val(res.heading);
            $('#bannerform').find('#description').val(res.description);
            $('#bannerform').find('#rowid').val(res.id);
        }
    </script>
    <script>
        function changestatus(rowid, tbl, status){
            let url = "{{route('admin.status.change')}}";
            $.confirm({
                title: 'Status Change',
                content: '',
                type: 'red',
                typeAnimated: true,
                buttons: {
                    tryAgain: {
                        text: 'change',
                        btnClass: 'btn-red',
                        action: function(){
                            $.ajax({
                                url: url,
                                data: {'_token': '{{csrf_token()}}', rowid: rowid, tbl: tbl, status: status},
                                type: 'post',
                                success: function(res){
                                    if(res.code == '200'){
                                        location.reload();
                                    }
                                },
                                error: function(err){
                                    console.log(err);
                                }
                            });
                        }
                    },
                    close: function () {}
                }
            });
        }
    </script>
@if(@$errors->has('banner_err'))
    <script>openmodal();</script>
@endif
@endpush
@endsection