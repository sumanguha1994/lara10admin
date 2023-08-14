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
use Carbon\Carbon;
use Exception;
use DB;
use stdClass;
use App\Models\Page;
use App\Models\Pagecontent;
use Illuminate\Support\Str;

class CmsController extends Controller
{
    public function page(Request $request){
        switch(true){
            case $request->isMethod('GET'):
                $data['pages'] = Page::where([['status', '!=', 'D']])->get();
                return view('admin.panel.cms.page', @$data);
            break;

            case $request->isMethod('POST'):
                $rules = [ 
                    'page_name' => 'required|min:2',
                    'page_type' => 'required|in:single,multiple',
                ];        
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    $validator->errors()->add('page_err', '1');
                    $validator->errors()->add('page_err_rowid', @$request->rowid);
                    return redirect()->back()->withInputs($request->all())->withErrors($validator->errors());
                }else{
                    if(Page::updateOrCreate(['id' => $request->rowid], [
                        'page_name' => $request->page_name,
                        'page_slug' => Str::slug($request->page_name),
                        'page_type' => $request->page_type,
                    ])){
                        return redirect()->back()->withSuccess('Page Info Saved.');
                    }
                    return redirect()->back()->withError('Something went wrong.');
                }
            break; 

            default:
                abort(403, 'Unauthorized action.');
            break;
        } 
    }
    public function content(Request $request){
        switch(true){
            case $request->isMethod('GET'):
                $data['pages'] = Page::where([['status', '=', 'A']])->get();
                $data['content'] = Pagecontent::with('page')->where([['status', '!=', 'D']])->get();
                return view('admin.panel.cms.content', @$data);
            break;

            case $request->isMethod('POST'):
                $rules = [ 
                    'page_id' => 'required',                    
                ];      
                $pageinfo = Page::where('id', $request->page_id)->first();
                if($pageinfo->page_type == 'single'){
                    $extra = array(
                        'description' => 'required|min:5',
                        'page_image' => 'nullable|image|mimes:png,jpg,jpeg,webp'
                    );
                    $rules = array_merge($rules, $extra);
                }else{
                    $extra = array(
                        'detail' => 'required|array',
                        'detail.*' => 'required|min:3',
                        'multiple_page_image' => 'nullable|array',
                        'multiple_page_image.*' => 'nullable|image|mimes:png,jpg,jpeg,webp'
                    );
                    $rules = array_merge($rules, $extra);
                }
                $validator = Validator::make($request->all(), $rules);
                if($validator->fails()){
                    $validator->errors()->add('page_content_err', '1');
                    $validator->errors()->add('page_id_err', $request->page_id);
                    $validator->errors()->add('page_content_err_rowid', @$request->rowid);
                    return redirect()->back()->withInputs($request->all())->withErrors($validator->errors());
                }else{                    
                    if(!empty($pageinfo)){
                        if($pageinfo->page_type == 'single'){
                            $filename = 'noimg.png';
                            if($request->hasFile('page_image')){
                                $upPath = 'uploads/page/';
                                $file = $request->file('page_image');
                                $filename = Carbon::now()->timestamp.'_'.$file->getClientOriginalName();
                                if(!file_exists(public_path($upPath))){
                                    mkdir(public_path($upPath), 0777, true);
                                }
                                $file->move(public_path($upPath), $filename);
                            }else{
                                $pagecontent = @Pagecontent::where([['id', '=', @$request->rowid]])->first();
                                $filename = empty($pagecontent) ? 'noimg.png' : $pagecontent->page_image;
                            }
                            if(Pagecontent::updateOrCreate(['id' => $request->rowid], [
                                'page_id' => $request->page_id,
                                'page_image' => $filename,
                                'description' => $request->description,
                            ])){
                                return redirect()->back()->withSuccess('Page Content Saved.');
                            }
                        }
                        if($pageinfo->page_type == 'multiple'){
                            $entry = false;
                            for($i = 0;$i < count($request->detail);$i++):
                                $filename = 'noimg.png';
                                if($request->hasFile('multiple_page_image')){
                                    $upPath = 'uploads/page/';
                                    $file = $request->file('multiple_page_image')[$i];
                                    $filename = Carbon::now()->timestamp.'_'.$file->getClientOriginalName();
                                    if(!file_exists(public_path($upPath))){
                                        mkdir(public_path($upPath), 0777, true);
                                    }
                                    $file->move(public_path($upPath), $filename);
                                }else{
                                    $filename = 'noimg.png';
                                }                                
                                if(Pagecontent::updateOrCreate(['id' => $request->rowid], [
                                    'page_id' => $request->page_id,
                                    'page_image' => $filename,
                                    'description' => $request->detail[$i],
                                ])){
                                    $entry = true;
                                }else{
                                    $entry = false;
                                    break;
                                }
                            endfor;
                            if($entry){
                                return redirect()->back()->withSuccess('Page Content Saved.');
                            }
                        }                        
                    }                    
                    return redirect()->back()->withError('Something went wrong.');
                }
            break; 

            default:
                abort(403, 'Unauthorized action.');
            break;
        } 
    }
}
