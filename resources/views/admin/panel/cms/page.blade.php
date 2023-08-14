@extends('admin.panel.layout.panellayout')
@section('page_title', 'Setting')
@push('head_script')
<link href="{{asset('public/admin/libs/jquery-toast/jquery.toast.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/admin/libs/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/admin/libs/datatables/buttons.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('public/admin/libs/datatables/responsive.bootstrap4.css')}}" rel="stylesheet" type="text/css" />
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
                                <li class="breadcrumb-item active">Cms</li>
                                <li class="breadcrumb-item active">Page</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Page Name</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="table-responsive">
                            <button type="button" class="btn btn-dark" onclick="pageadd()"><i class="mdi mdi-backspace-reverse"></i>&nbsp;add page</button>
                            <table id="datatable-buttons" class="mt-2 table m-0 table-colored-bordered table-bordered-primary dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Page Name</th>
                                        <th>Type</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pages as $key => $p)
                                    <tr>
                                        <th scope="row">{{$key+1}}</th>
                                        <td>{{ucwords($p->page_name)}}</td>
                                        <td>{{$p->page_type}}</td>
                                        <td class="text-center {{$p->status == 'A' ? 'text-primary' : 'text-warning'}}">
                                            @if($p->status == 'A')
                                                <a href="javascript:void(0);" data-toggle="tooltip" title="Change to inactive" class="text-info" onclick="changestatus('{{base64_encode($p->id)}}', 'pages', 'I')"><i class="fe-toggle-right"></i></a>
                                            @else
                                                <a href="javascript:void(0);" data-toggle="tooltip" title="Change to active" class="text-warning" onclick="changestatus('{{base64_encode($p->id)}}', 'pages', 'A')"><i class="fe-toggle-left"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Edit {{ucwords($p->page_name)}}" class="text-secondary" onclick="edit('{{base64_encode($p->id)}}')"><i class="fe-edit"></i></a>
                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Delete" class="text-danger" onclick="changestatus('{{base64_encode($p->id)}}', 'pages', 'D')"><i class="fe-trash-2"></i></a>
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
    @include('admin.panel.layout.footer') 
</div>
<!-- edit modal  -->
<div id="con-close-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Page Modal</h4>
            </div>
            <div class="modal-body">
                <form class="parsley-examples" novalidate="" action="{{route('admin.cms.page')}}" method="post" enctype="multipart/form-data" id="bannerform">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="page_name">Page Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" value="{{old('page_name')}}" name="page_name" id="page_name" placeholder="Page Name" parsley-trigger="change" required>
                            @if($errors->has('page_name')) <span class="error"><small>{{ ucwords($errors->first('page_name')) }}</small></span> @endif
                        </div>
                        <div class="col-md-12">
                            <label for="page_type">Page Type <span class="text-danger">*</span></label>
                            <select name="page_type" id="page_type" class="form-control" parsley-trigger="change" required>
                                <option selected readonly disabled>choose one</option>
                                <option value="single">Single Content</option>
                                <option value="multiple">multiple Content</option>
                            </select>
                            @if($errors->has('page_type')) <span class="error"><small>{{ ucwords($errors->first('page_type')) }}</small></span> @endif
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
@push('footer_script')
    <script src="{{asset('public/admin/libs/parsleyjs/parsley.min.js')}}"></script>
    <script src="{{asset('public/admin/js/pages/form-validation.init.js')}}"></script>
    <!-- Required datatable js -->
    <script src="{{asset('public/admin/libs/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/datatables/dataTables.bootstrap4.min.js')}}"></script>
    <!-- Buttons examples -->
    <script src="{{asset('public/admin/libs/datatables/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/datatables/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('public/admin/libs/datatables/buttons.html5.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/datatables/buttons.print.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/datatables/buttons.colVis.js')}}"></script>
    <!-- Responsive examples -->
    <script src="{{asset('public/admin/libs/datatables/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('public/admin/libs/datatables/responsive.bootstrap4.min.js')}}"></script>
    <!-- Datatables init -->
    <script src="{{asset('public/admin/js/pages/datatables.init.js')}}"></script>
    <script>
        setTimeout(() => {
            $('.error').remove();
        }, 3500);
        function pageadd(){
            resetmodal();
            $('#con-close-modal').modal({backdrop: 'static', keyboard: false}, 'show');
        }
        function edit(rowid){
            let url = "{{route('admin.get.single')}}/page/" + rowid;
            $.get(url, function(res){
                if(res.code == '200'){                    
                    $('#show-modal').modal('hide');
                    pageadd();
                    putvalue(res.data);
                }
            });
        }
        function resetmodal(){
            $('#bannerform').find('#page_name').val('');
            $('#bannerform').find('#page_type').val($("#page_type option:first").val());
            $('#bannerform').find('#rowid').val('');
            $('#bannerform').find('#bannerform').trigger('reset');
            
        }
        function putvalue(res){
            $('#bannerform').find('#page_name').val(res.page_name);
            $('#bannerform').find('#page_type').val(res.page_type);
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
@if(@$errors->has('page_err'))
    <script>pageadd();</script>
@endif
@endpush
@endsection