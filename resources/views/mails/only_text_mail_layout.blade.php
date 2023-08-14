<body>
    
@component('mail::message')
{{-- <center><img src="{{asset('uploads/'.config()->get('setting.logo'))}}" style="width: 200px; height: 50px;" type="image/x-icon"></center> --}}

# Hi {{$mail_arr['name']}},<br>
<hr>
{!! $mail_arr['txt'] !!}

<hr>

Thanks,<br>
# {{ config('app.name') }}
@endcomponent

</body>