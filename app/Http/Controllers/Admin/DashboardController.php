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
use App\Models\Banner;
use App\Models\Page;
use App\Models\Pagecontent;
use Carbon\Carbon;
use Exception;
use DB;
use stdClass;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('admin.panel.dashboard');
    }
    #banner
    public function banner(Request $request){
        switch(true){
            case $request->isMethod('GET'):
                $data['banners'] = Banner::where([['status', '!=', 'D']])->latest()->get();
                return view('admin.panel.banner.index', @$data);
            break;

            case $request->isMethod('POST'):
                $rules = [ 
                    'banner_heading' => 'required|min:3',
                    'description' => 'required|min:5',
                    'banner_image' => 'nullable|image|mimes:png,jpeg,jpg,webp'
                ];        
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    $validator->errors()->add('banner_err', '1');
                    $validator->errors()->add('banner_err_rowid', @$request->rowid);
                    return redirect()->back()->withInputs($request->all())->withErrors($validator->errors());
                }else{
                    $filename = 'noimg.png';
                    if($request->hasFile('banner_image')){
                        $upPath = 'uploads/banner/';
                        $file = $request->file('banner_image');
                        $filename = Carbon::now()->timestamp.'_'.$file->getClientOriginalName();
                        if(!file_exists(public_path($upPath))){
                            mkdir(public_path($upPath), 0777, true);
                        }
                        $file->move(public_path($upPath), $filename);
                    }else{
                        $banner = @Banner::where([['id', '=', @$request->rowid]])->first();
                        $filename = empty($banner) ? 'noimg.png' : $banner->image;
                    }
                    if(Banner::updateOrCreate(['id'=> @$request->rowid], [
                        'heading' => $request->banner_heading,
                        'image' => $filename,
                        'description' => $request->description,
                    ])){
                        return redirect()->back()->withSuccess('Banner info saved.');
                    }
                    return redirect()->back()->withError('Something went wrong.');
                }
            break; 

            default:
                abort(403, 'Unauthorized action.');
            break;
        } 
    }
    #profile
    public function profile(Request $request, $type = null){
        switch(true){
            case $request->isMethod('GET'):
                return view('admin.panel.profile.index');
            break;

            case $request->isMethod('POST'):
                if($type != null){
                    #profile upadte
                    if($type == 'profile'){                        
                        $rules = [
                            'name' => 'required|min:3',
                            'phone' => 'required|unique:users,phone,'.Auth::user()->id,
                            'info' => 'nullable|min:5|max:555',
                            'image' => 'nullable|image|mimes:png,jpeg,jpg,webp'
                        ];        
                        $validator = Validator::make($request->all(), $rules);
                        if($validator->fails()){
                            $validator->errors()->add('profile_err', '1');
                            return redirect()->back()->withInputs($request->all())->withErrors($validator->errors());
                        }else{
                            $filename = 'noimg.png';
                            if($request->hasFile('image')){
                                $upPath = 'uploads/user/';
                                $file = $request->file('image');
                                $filename = Carbon::now()->timestamp.'_'.$file->getClientOriginalName();
                                if(!file_exists(public_path($upPath))){
                                    mkdir(public_path($upPath), 0777, true);
                                }
                                $file->move(public_path($upPath), $filename);
                            }else{
                                $user = @User::where([['id', '=', Auth::user()->id]])->first();
                                $filename = empty($user) ? 'noimg.png' : $user->profile_image;
                            }
                            if(User::where('id', Auth::id())->update([
                                'name' => $request->name,
                                'phone' => $request->phone,
                                'profile_image' => $filename,
                                'info' => $request->info,
                            ])){
                                return redirect()->back()->withSuccess('Profile info Updated.');
                            }
                            return redirect()->back()->withError('Something went wrong.');
                        }
                    }
                    #change password
                    if($type == 'password'){
                        $rules = [
                            'old_password' => ['required', function ($attribute, $value, $fail) {
                                if (!Hash::check($value, Auth::user()->password)) {
                                    return $fail(__('The current password is incorrect.'));
                                }
                            }],
                            'new_password' => 'required|min:6|required_with:confirm_password',
                            'confirm_password' => 'required|min:6|same:new_password',
                        ];        
                        $validator = Validator::make($request->all(), $rules);
                        if($validator->fails()){
                            $validator->errors()->add('password_err', '1');
                            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                        }else{
                            if(User::where('id', Auth::user()->id)->update([
                                'password' => Hash::make($request->new_password),
                            ])){
                                return redirect()->back()->withSuccess('User Password Updated.');
                            }
                            return redirect()->back()->withError('Something went wrong.');
                        }
                    }
                }
                return redirect()->back()->withError('Something went wrong.');
            break; 

            default:
                abort(403, 'Unauthorized action.');
            break;
        }
        
    }
    #setting
    public function setting(Request $request, $type = null){
        switch(true){
            case $request->isMethod('GET'):
                return view('admin.panel.setting.index');
            break;

            case $request->isMethod('POST'):
                if($type != null){
                    if($type == 'sitesetting'){
                        $rules = [
                            'site_name' => 'required',
                            'contact_mail' => 'required|email',
                            'logo' => 'nullable|image|mimes:jpeg,webp,jpg,gif',
                            'address' => 'required',
                        ];        
                        $validator = Validator::make($request->all(), $rules);
                        if($validator->fails()){
                            $validator->errors()->add('site_err', '1');
                            $validator->errors()->add('site_err_type', 'site_err');
                            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                        }else{
                            dd($request->all());
                            return redirect()->back()->withError('Something went wrong.');
                        }
                    }
                    if($type == 'smtpsetting'){
                        $rules = [
                            'smtp_host' => 'required|min:4',
                            'smtp_from_name' => 'required',
                            'smtp_username' => 'required',
                            'smtp_password' => 'required',
                            'smtp_from_address' => 'required|email',
                        ];        
                        $validator = Validator::make($request->all(), $rules);
                        if($validator->fails()){
                            $validator->errors()->add('site_err', '1');
                            $validator->errors()->add('site_err_type', 'smtp_err');
                            return redirect()->back()->withInput($request->all())->withErrors($validator->errors());
                        }else{
                            dd($request->all());
                            return redirect()->back()->withError('Something went wrong.');
                        }
                    }
                }
                return redirect()->back()->withError('Something went wrong.');
            break; 

            default:
                abort(403, 'Unauthorized action.');
            break;
        }        
    }

    #all status change
    public function statuschange(Request $request){
        $rowid = empty($request->rowid) ? null : base64_decode($request->rowid);
        if($rowid != null){
            if(DB::table($request->tbl)->where([['id', '=', $rowid]])->update(['status' => $request->status])){
                return response()->json(['code' => 200]);
            }
        }
        return response()->json(['code' => 500]);
    }
    #all get single
    public function getsingle($type = null, $rowid = null){
        $rowid = empty($rowid) ? null : base64_decode($rowid);
        if($rowid != null){
            $data = new stdClass;
            if($type == 'banner'){
                $data = Banner::where('id', $rowid)->first();
            }
            if($type == 'page'){
                $data = Page::where('id', $rowid)->first();
            }
            if($type == 'pagecontent'){
                $data = Pagecontent::with('page')->where('id', $rowid)->first();
            }
            return response()->json(['code' => 200, 'data' => $data]);
        }
        return response()->json(['code' => 500]);
    }
}
