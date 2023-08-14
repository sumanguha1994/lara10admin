<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Mail\GeneralMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use DB;

class AuthController extends Controller
{
    public function login(Request $request){
        switch (true) {
            case $request->isMethod('GET'):
                if(Auth::check()){
                    return redirect()->route('admin.dashboard');
                }
                return view('admin.auth.login');
            break;

            case $request->isMethod('POST'):
                $rules = [
                    'email' => 'required|email',
                    'password' => 'required',
                ];        
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }else{            
                    $remember = ($request->rememberme == 'on') ? true : false;
                    if(Auth::validate(['email' => $request->email, 'password' => $request->password])){
                        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)){
                            Auth::logoutOtherDevices($request->password);
                            return redirect()->route('admin.dashboard')->withSuccess('Logged in Successfully.');
                        }
                        return redirect()->back()->withError('Account not activated.');
                    }
                    return redirect()->back()->withError('Invalid credentials.');
                }
            break;
        }
    }

    public function forgotpassword(Request $request){
        switch (true) {
            case $request->isMethod('GET'):
                return view('admin.auth.forgotpassword');
            break;

            case $request->isMethod('POST'):
                $rules = [
                    'forgot_mail' => 'required|email|exists:users,email',
                ];        
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                }else{   
                    $user = User::where('email', $request->input('forgot_mail'))->first();
                    $subject = ucwords(str_replace('-', ' ', env('APP_NAME'))).' - Password Recovery';
                    $mailFromId = (config()->get('mail.from.address') != '') ? config()->get('mail.from.address') : env('MAIL_FROM_ADDRESS');
                    $txt = '';
                    $txt .= '<p>Welcome '.$user->name.'</p>';
                    $txt .= '<p>Password Recovery For Your Email-iD: '.$request->forgot_mail.'.<br>Please </p>';  
                    $txt .= '<p><a href="'.route('admin.password.recovery', ['rowid' => Crypt::encryptString($user->id)]).'">Click here & Change You Password From Here....</a></p>';
                    try {
                        Mail::to($request->forgot_mail)->send(new GeneralMail($user->name, $mailFromId, $txt, $subject));
                        User::where('email', $request->input('forgot_mail'))->update(['email_verified_at' => date('Y-m-d H:i:s')]);
                        return redirect()->back()->withSuccess('A password recovery mail sent to '.$request->forgot_mail);
                    } catch (\Throwable $th) {
                        return redirect()->back()->withError('SMTP Eror occured.Email not Send. '.$th->getMessage());
                    }
                }
            break;
        }
    }

    public function passwordrecovery(Request $request, $rowid = null){
        switch (true) {
            case $request->isMethod('GET'):
                $rowid = Crypt::decryptString($rowid);
                $data['user'] = $user = User::find($rowid);
                if(!empty($user) && ($user->email_verified_at != '')){
                    $tkn = date('Y-m-d h:i', strtotime($user->email_verified_at));
                    $exptkn = date('Y-m-d h:i', strtotime('+3 minutes', strtotime($user->email_verified_at)));
                    $current = date('Y-m-d h:i');
                    if(strtotime($current) >= strtotime($tkn) && strtotime($current) <= strtotime($exptkn)){
                        return view('admin.auth.passwordrecovery', @$data);
                    }else{
                        return redirect()->route('admin.login')->withError('Link Expired');
                    }
                }
                return redirect()->back()->withError('Something went wrong.');
            break;

            case $request->isMethod('POST'):
                $rules = [
                    'recovery_mail' => 'required|email|exists:users,email',
                    'password' => 'required|min:6|required_with:confirm_password|same:confirm_password',
                    'confirm_password' => 'required|same:password'
                ];
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    return redirect()->back()->withInputs($request->all())->withErrors($validator->errors());
                }else{
                    if(User::where([['id', '=', $request->rowid], ['email', '=', $request->recovery_mail]])->update([
                        'password' => Hash::make($request->password)
                    ])){
                        return redirect()->route('admin.login')->withSuccess('Password Successfully Reset.');
                    }
                    return redirect()->back()->withError('Something went wrong. Password not reset');
                }
            break;

            default:
                abort(403, 'Unauthorized action.');
            break;
        }
    }

    public function logoutscreen(){
        return view('admin.auth.logout');
    }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        return redirect()->route('admin.logoutscreen')->withSuccess('You are now logged out of the system.');
    }
}
