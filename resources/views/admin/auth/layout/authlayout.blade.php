<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>{{ env('APP_NAME') }} | @yield('page_title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Coderthemes" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{ asset('public/admin/images/favicon.ico') }}">
    <link href="{{ asset('public/admin/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet" />
    <link href="{{ asset('public/admin/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/admin/css/app.min.css') }}" rel="stylesheet" type="text/css"  id="app-stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" integrity="sha512-3pIirOrwegjM6erE5gPSwkUzO+3cTjpnV9lexlNZqvupR64iZBnOOTiiLPb9M36zpMScbmUNIcHUqKD47M719g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    @stack('head_script')
    <style>
        #myDiv {
            position: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0.7;
            background-color: #fff;
            z-index: 9999;
        }
    </style>
</head>

<body class="authentication-bg bg-primary authentication-bg-pattern d-flex align-items-center pb-0 vh-100">
    <div id="myDiv">
        <img style="height:180px;" src="https://mir-s3-cdn-cf.behance.net/project_modules/max_1200/bc0c6b69321565.5b7d0cbe723b5.gif" alt="AdminLTELogo">
    </div>
    <div class="home-btn d-none d-sm-block">
        <a href="{{route('admin.login')}}"><i class="fas fa-home h2 text-white"></i></a>
    </div>
    <div class="account-pages w-100 mt-5 mb-5">
        <div class="container">
            @yield('auth_content')
        </div>
    </div>
    <script src="{{ asset('public/admin/js/vendor.min.js') }}"></script>
    <script src="{{ asset('public/admin/js/app.min.js') }}"></script>    
    <script>
        $(document).ready(function(){
            $('#myDiv').hide();
        });         
        function loader(loadtype){
            if(loadtype == 'show'){
                $('#myDiv').show();
            }else{
                $('#myDiv').hide();
            }
        }
    </script>
    @stack('footer_script')
</body>
</html>