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
                                <li class="breadcrumb-item active">Content</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Page Content</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <div class="table-responsive">
                            <button type="button" class="btn btn-dark" onclick="openmodal()"><i class="mdi mdi-backspace-reverse"></i>&nbsp;add page content</button>
                            <table id="datatable-buttons" class="mt-2 table m-0 table-colored-bordered table-bordered-primary dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Page Name</th>
                                        <th>Image</th>
                                        <th>Description</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($content as $key => $c)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{ucwords($c->page->page_name)}}</td>
                                        <td><img src="{{$c->avatar}}" class="img" alt=""></td>
                                        <td>{!! strlen($c->description) > 35 ? strip_tags(substr($c->description, 0, 35)).' ...' : ucfirst($c->description) !!}</td>
                                        <td class="text-center {{$c->status == 'A' ? 'text-primary' : 'text-warning'}}">
                                            @if($c->status == 'A')
                                                <a href="javascript:void(0);" data-toggle="tooltip" title="Change to inactive" class="text-info" onclick="changestatus('{{base64_encode($c->id)}}', 'pagecontents', 'I')"><i class="fe-toggle-right"></i></a>
                                            @else
                                                <a href="javascript:void(0);" data-toggle="tooltip" title="Change to active" class="text-warning" onclick="changestatus('{{base64_encode($c->id)}}', 'pagecontents', 'A')"><i class="fe-toggle-left"></i></a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Edit {{ucwords($c->page->page_name)}}" class="text-secondary" onclick="edit('{{base64_encode($c->id)}}')"><i class="fe-edit"></i></a>
                                            <a href="javascript:void(0);" data-toggle="tooltip" title="View {{ucwords($c->page->page_name)}}" class="text-success" onclick="view('{{base64_encode($c->id)}}')"><i class="fe-eye"></i></a>
                                            <a href="javascript:void(0);" data-toggle="tooltip" title="Delete" class="text-danger" onclick="changestatus('{{base64_encode($c->id)}}', 'pagecontents', 'D')"><i class="fe-trash-2"></i></a>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Page Content Modal</h4>
            </div>
            <div class="modal-body">
                <form class="parsley-examples" novalidate="" action="{{route('admin.cms.content')}}" method="post" enctype="multipart/form-data" id="contentform">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="page_id">Page <span class="text-danger">*</span></label>
                            <select name="page_id" id="page_id" class="form-control" parsley-trigger="change" required onchange="pagetype(this)">
                                <option selected readonly disabled>choose one</option>
                                @foreach($pages as $p)
                                    <option value="{{$p->id}}" data-page_type="{{$p->page_type}}">{{ucwords($p->page_name)}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('page_id')) <span class="error"><small>{{ ucwords($errors->first('page_id')) }}</small></span> @endif
                        </div>
                        <div class="col-md-6 single_page_type">
                            <label for="page_image">Image </label>
                            <input type="file" class="form-control" name="page_image" id="page_image" accept="image/*">
                            @if($errors->has('page_image')) <span class="error"><small>{{ ucwords($errors->first('page_image')) }}</small></span> @endif
                        </div>
                    </div>
                    <div class="form-group row single_page_type">
                        <div class="col-md-12">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea id="editor" class="form-control" value="{{old('description')}}" name="description" id="description" placeholder="Page Description"></textarea>
                            @if($errors->has('description')) <span class="error"><small>{{ ucwords($errors->first('description')) }}</small></span> @endif
                        </div>
                    </div>
                    <div class="form-group row multiple_page_type multidiv_0">
                        <div class="col-md-3">
                            <label for="multiple_page_image">Page Image</label>
                            <input type="file" class="form-control" name="multiple_page_image[]" id="multiple_page_image" accept="image/*">
                            @if($errors->has('multiple_page_image')) <span class="error"><small>{{ ucwords($errors->first('multiple_page_image')) }}</small></span> @endif
                        </div>
                        <div class="col-md-7">
                            <label for="detail">Details</label>
                            <input type="text" class="form-control" value="{{old('detail')}}" name="detail[]" id="detail" placeholder="Page detail">
                            @if($errors->has('detail')) <span class="error"><small>{{ ucwords($errors->first('detail')) }}</small></span> @endif
                        </div>
                        <div class="col-md-2"><button type="button" class="btn btn-sm btn-primary" style="margin-top: 33%;" onclick="addrow(0)"><i class="mdi mdi-gamepad-round-outline"></i></button></div>
                    </div>
                    <input type="hidden" name="rowid" id="rowid">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info waves-effect waves-light" onclick="$('#contentform').submit();">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- view modal  -->
<div id="view-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Page Content View</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        Page Type: <h4 id="page_type"></h4>
                    </div>
                    <div class="col-md-6" id="page_img"><img src="" alt="" style="height: 80px;"></div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12" id="page_desc"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection
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
    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>
    <script>
        let rowcount = 1;
        let description;
        setTimeout(() => {
            $('.error').remove();
        }, 3500);
        $(document).ready(function(){
            $('.multiple_page_type').hide();
            ClassicEditor.create( document.querySelector( '#editor' ) )
            .then( editor => { editor = description } )
            .catch( error => { console.error( error ) } );
        });
        function pagetype(elem){
            let type = $(elem).find(':selected').attr('data-page_type');
            if(type == 'single'){
                $('.single_page_type').show();
                $('.multiple_page_type').hide();
            }else{
                $('.single_page_type').hide();
                $('.multiple_page_type').show();
            }
        }
        function openmodal(){
            resetmodal();
            $('#con-close-modal').modal({backdrop: 'static', keyboard: false}, 'show');
        }
        function edit(rowid){
            let url = "{{route('admin.get.single')}}/banner/" + rowid;
            $.get(url, function(res){
                if(res.code == '200'){               
                    openmodal();
                    putvalue(res.data);
                }
            });
        }
        function view(rowid){
            let url = "{{route('admin.get.single')}}/pagecontent/" + rowid;
            $.get(url, function(res){
                if(res.code == '200'){  
                    resetviewmodal();
                    $('#view-modal').find('#page_type').text(res.data.page.page_name);
                    $('#view-modal').find('#page_img').find('img').attr('src', res.data.avatar);
                    $('#view-modal').find('#page_desc').html(res.data.description);
                    $('#view-modal').modal('show');
                }
            });
        }
        function resetviewmodal(){
            $('#view-modal').find('#page_type').text('');
            $('#view-modal').find('#page_img').find('img').removeAttr('src');
            $('#view-modal').find('#page_desc').html('');
            $('#view-modal').modal('hide');
        }
        function resetmodal(){

        }
        function putvalue(res){

        }
        function addrow(rowid){
            let html = `<div class="form-group row multiple_page_type multidiv_${rowcount}">
                <div class="col-md-3">
                    <input type="file" class="form-control" name="multiple_page_image[]" id="multiple_page_image_${rowcount}" accept="image/*">
                </div>
                <div class="col-md-7">
                    <input type="text" class="form-control" value="{{old('detail')}}" name="detail[]" id="detail_${rowcount}" placeholder="Page detail">
                </div>
                <div class="col-md-2"><button type="button" class="btn btn-sm btn-danger" style="margin-top: 7%;" onclick="removerow(${rowcount})"><i class="mdi mdi-close-box-multiple"></i></button></div>
            </div>`;
            rowcount++;
            $('.multidiv_'+rowid).after(html);
        }
        function removerow(rowid){
            $('.multidiv_'+rowid).remove();
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
@if(@$errors->has('page_content_err'))
    <script>
        let page_id_err = "{{@$errors->first('page_id_err')}}";
        openmodal();
        setTimeout(() => {
            $('#page_id').val(page_id_err).trigger('change');
        }, 300);       
    </script>
@endif
@endpush