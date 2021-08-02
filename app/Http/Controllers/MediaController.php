<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;
use Response;
use Image;
use Storage;

class MediaController extends Controller
{
    /**
     * @var array mảng các size theo chiều rộng
     */
    protected $imageSize = [
        // 'large' => 600,
        // 'medium' => 300,
        // 'small' => 150,
        // 'tiny' => 80
    ];

    /**
     * @var array mảng các phần mở rộng được chấp nhận
     */
    protected $allowed_extension = ['jpg','jpeg','png','gif','svg', 'webp'];

    /**
     * @var int kích thước file cho phép (Byte) (2097152 B = 2 MB)
     */
    protected $allowed_size = 2097152;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('media.index');
    }

    /**
     * Display a listing of the resource in popup.
     *
     * @return \Illuminate\Http\Response
     */
    public function library(Request $request)
    {
        $view = $request->get('view','add');//add | list
        $type = $request->get('type','single');//single | tinymce
        $element = $request->get('element','image');// id của thẻ html muốn chèn
        $text = $request->get('text','Chèn ảnh vào');// chữ hiển thị ở button
        $selected_ids = $request->get('selected_ids','0');// Danh sách id được select sẵn
        $data = DB::table('media');
        $data = $data->orderBy('updated_at','desc')->paginate(50);
        $querystringArray = ['view' => 'list', 'type' => $type, 'element' => $element, 'text' => $text, 'selected_ids' => $selected_ids];
        $data->appends($querystringArray);
        return view('media.library',compact('data','view','type','element','text','selected_ids'));
    }
    /**
     * Tìm ảnh theo tên, tiêu đề, caption
     * @return list html item
     */
    public function search() {
        $keyword = $request->get('keyword','');
        $keyword = str_replace(' ','%',$keyword);
        $data = DB::table('media');
        $data = $data->where('name','like','%'.$keyword.'%')
            ->orwhere('title','like','%'.$keyword.'%')
            ->orwhere('caption','like','%'.$keyword.'%')
            ->orderBy('updated_at','desc')->limit(20)->get();
        if(count($data) > 0) {
            $html = view('media.list')->with(['data'=>$data,'selected_ids'=>0])->render();
            return json_encode(array('status'=>1,'message'=>'Ok','html'=>$html));
        }else{
            return json_encode(array('status'=>0,'message'=>'Không có ảnh nào khớp với từ khóa bạn nhập.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('media.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input_data = $request->all();

        $validator = Validator::make(
            $input_data, [
                'files.*' => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:2000'
            ],[
                'files.*.required' => 'Vui lòng chọn ảnh để tải lên',
                'files.*.mimes' => 'Chỉ chấp nhận các định dạng jpg,jpeg,png,gif,svg',
                'files.*.max' => 'Kích thước tối đa cho phép là 2MB',
            ]
        );

        if ($validator->fails()) {
            return response()->json(array(
                'success' => false,
                'message' => $validator->getMessageBag()->toArray()

            ), 400);
        }


        $files = $request->file('files');

        $upload_success = false;
        $upload_result = [];
        foreach ($files as $file) {
            $upload_result_item = [];
            $upload_result_item['id'] = 0;
            $upload_result_item['error'] = '';
            $month = date('m');
            $year = date('Y');
            $path = $year.'/'.$month.'/';
            $file_pathinfo = pathinfo($file->getClientOriginalName());
            $file_name = $file_pathinfo['filename'];
            $file_name = $this->beautiful_name($file_name);
            $file_extension = $file_pathinfo['extension'];
            $file_size = filesize($file);

            //Kiểm tra phần mở rộng
            if(!in_array(strtolower($file_extension),$this->allowed_extension)) {
                $upload_result_item['error'] .= ' | File sai định dạng (chỉ chấp nhận jpg, jpeg, png, gif)';
            }
            //Kiểm tra kích thước
            if($file_size > $this->allowed_size) {
                $upload_result_item['error'] .= ' | File có kích thước quá lớn (chỉ chấp nhận file có dung lượng < 2MB)';
            }

            //Tên kèm đường dẫn
            $imageName = $file_name.'.'.$file_extension;

            if(config('app.storage_type') == 'objectstorage') {//nếu cấu hình lưu trữ trên object storage z.com
                $upload = Storage::disk('sop');
                $i=0;
                while($upload->fileExists($path.$imageName)) {
                    $i++;
                    $imageName = $file_name.'-'.$i.'.'.$file_extension;
                }
                if ($upload_result_item['error'] == '') {
                    $image = Image::make($file)->stream();
                    try {
                        $upload->filePut2($image->__toString(),$path.$imageName);

                        //resize
                        foreach($this->imageSize as $key=>$value) {
                            $image_resize = Image::make($file)->widen($value);
                            $image_resize = $image_resize->stream($file_extension, 60);
                            $image_resize_path = $path.$file_name;
                            if($i > 0) $image_resize_path .= '-'.$i;
                            $image_resize_path .= '-'.$key;
                            $image_resize_path .= '.'.$file_extension;
                            $upload->filePut2($image_resize->__toString(),$image_resize_path);
                        }

                        $image_id = $this->image_to_db($imageName);

                        if ($image_id) {
                            $upload_success = true;
                            $upload_result_item['id'] = $image_id;
                            $upload_result_item['name'] = $imageName;
                        }
                    } catch (\Exception $e) {
                        return json_encode(array('status'=>0,'message'=>'Có lỗi xảy ra, vui lòng thử lại sau !','error2'=>$e->getMessage()));
                    }
                }
                $upload_result[] = $upload_result_item;
            }elseif (config('app.storage_type') == 'digitalocean') {
                $upload = Storage::disk('do');
                $path = config('filesystems.disks.do.folder').'/'.$path;//bên do không chia bucket mà chia folder cho từng project trong 1 bucket
                $i=0;
                while($upload->exists($path.$imageName)) {
                    $i++;
                    $imageName = $file_name.'-'.$i.'.'.$file_extension;
                }
                if ($upload_result_item['error'] == '') {
                    $image = Image::make($file)->stream();
                    try {
                        $upload->put($path.$imageName, $image->__toString(), 'public');
                        //resize
                        foreach($this->imageSize as $key=>$value) {
                            $image_resize = Image::make($file)->widen($value);
                            $image_resize = $image_resize->stream($file_extension, 80);
                            $image_resize_path = $path.$file_name;
                            if($i > 0) $image_resize_path .= '-'.$i;
                            $image_resize_path .= '-'.$key;
                            $image_resize_path .= '.'.$file_extension;
                            $upload->put($image_resize_path, $image_resize->__toString(), 'public');
                        }
                    } catch (\Exception $e) {
                        return json_encode(array('status'=>0,'message'=>'Có lỗi xảy ra, vui lòng thử lại sau !','error2'=>$e->getAwsErrorCode()));
                    }

                    $image_id = $this->image_to_db($imageName);

                    if ($image_id) {
                        $upload_success = true;
                        $upload_result_item['id'] = $image_id;
                        $upload_result_item['name'] = $imageName;
                    }
                }
                $upload_result[] = $upload_result_item;
            }elseif (config('app.storage_type') == 'linode') {
                $upload = Storage::disk('linode');
                $path = config('filesystems.disks.linode.folder').'/'.$path;//bên do không chia bucket mà chia folder cho từng project trong 1 bucket
                $i=0;
                while($upload->exists($path.$imageName)) {
                    $i++;
                    $imageName = $file_name.'-'.$i.'.'.$file_extension;
                }
                if ($upload_result_item['error'] == '') {
                    $image = Image::make($file)->stream();
                    try {
                        $upload->put($path.$imageName, $image->__toString(), 'public');
                        //resize
                        foreach($this->imageSize as $key=>$value) {
                            $image_resize = Image::make($file)->widen($value);
                            $image_resize = $image_resize->stream($file_extension, 80);
                            $image_resize_path = $path.$file_name;
                            if($i > 0) $image_resize_path .= '-'.$i;
                            $image_resize_path .= '-'.$key;
                            $image_resize_path .= '.'.$file_extension;
                            $upload->put($image_resize_path, $image_resize->__toString(), 'public');
                        }
                    } catch (\Exception $e) {
                        return json_encode(array('status'=>0,'message'=>'Có lỗi xảy ra, vui lòng thử lại sau !','error2'=>$e->getAwsErrorCode()));
                    }

                    $image_id = $this->image_to_db($imageName);

                    if ($image_id) {
                        $upload_success = true;
                        $upload_result_item['id'] = $image_id;
                        $upload_result_item['name'] = $imageName;
                    }
                }
                $upload_result[] = $upload_result_item;
            }elseif (config('app.storage_type') == 'vhost') {
                $upload = Storage::disk(config('app.storage_type'));
                $path = config('filesystems.disks.'.config('app.storage_type').'.folder').'/'.$path;//bên do không chia bucket mà chia folder cho từng project trong 1 bucket
                $i=0;
                // while($upload->exists($path.$imageName)) {
                //     $i++;
                //     $imageName = $file_name.'-'.$i.'.'.$file_extension;
                // }
                if ($upload_result_item['error'] == '') {
                    // $image = Image::make($file)->insert('img/logo-chanhtuoi.png', 'bottom-right', 10, 10)->stream();
                    $image = Image::make($file)->stream();
                    try {
                        $upload->put($path.$imageName, $image->__toString(), 'public');
                        \Log::debug($path);
                        \Log::debug($imageName);
                        \Log::debug($path.$imageName);
                        \Log::debug($image->__toString());
                        //resize
                        foreach($this->imageSize as $key=>$value) {
                            $image_resize = Image::make($file)->insert('img/logo-chanhtuoi.png', 'bottom-right', 10, 10)->widen($value);
                            $image_resize = $image_resize->stream($file_extension, 80);
                            $image_resize_path = $path.$file_name;
                            if($i > 0) $image_resize_path .= '-'.$i;
                            $image_resize_path .= '-'.$key;
                            $image_resize_path .= '.'.$file_extension;
                            $upload->put($image_resize_path, $image_resize->__toString(), 'public');
                        }
                    } catch (\Exception $e) {
                        \Log::error($e);
                        return json_encode(array('status'=>0,'message'=>'Có lỗi xảy ra, vui lòng thử lại sau !','error2'=>$e->getAwsErrorCode()));
                    }

                    $image_id = $this->image_to_db($imageName);

                    if ($image_id) {
                        $upload_success = true;
                        $upload_result_item['id'] = $image_id;
                        $upload_result_item['name'] = $imageName;
                    }
                }
                $upload_result[] = $upload_result_item;
            }else {//cấu hình lưu trên local
                $upload = Storage::disk('local');
                $i=0;
                while($upload->exists($path.$imageName)) {
                    $i++;
                    $imageName = $file_name.'-'.$i.'.'.$file_extension;
                }
                if ($upload_result_item['error'] == '') {
                    $image = Image::make($file)->stream();
                    try {
                        $upload->put($path.$imageName, $image->__toString());
                        //resize
                        foreach($this->imageSize as $key=>$value) {
                            $image_resize = Image::make($file)->widen($value);
                            $image_resize = $image_resize->stream($file_extension, 60);
                            $image_resize_path = $path.$file_name;
                            if($i > 0) $image_resize_path .= '-'.$i;
                            $image_resize_path .= '-'.$key;
                            $image_resize_path .= '.'.$file_extension;
                            $upload->put($image_resize_path, $image_resize->__toString());
                        }
                    } catch (\Exception $e) {
                        return json_encode(array('status'=>0,'message'=>'Có lỗi xảy ra, vui lòng thử lại sau !','error2'=>$e->getAwsErrorCode()));
                    }

                    $image_id = $this->image_to_db($imageName);

                    if ($image_id) {
                        $upload_success = true;
                        $upload_result_item['id'] = $image_id;
                        $upload_result_item['name'] = $imageName;
                    }
                }
                $upload_result[] = $upload_result_item;
            }
        }

        if( $upload_success ) {
            return response()->json(array(
                'success' => true,
                'message' => 'Upload thành công',
                'result' => $upload_result

            ), 200);
        } else {
            return response()->json(array(
                'success' => false,
                'message' => 'Có lỗi xảy ra',
                'result' => $upload_result

            ), 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $id = $request->get('id',0);
        $title = $request->get('title');
        $caption = $request->get('caption');
        if($id == 0 || !is_numeric($id)){
            return json_encode(array('status'=>0,'message'=>'ID ảnh không hợp lệ'));
        }else{
            $check = DB::table('media')->where('id',$id)->first();
            if(!$check){
                return json_encode(array('status'=>0,'message'=>'Lỗi không tìm thấy file ảnh.'));
            }else{
                $insert = DB::table('media')->where('id',$id)->update(array('title'=>$title,'caption'=>$caption));
                return json_encode(array('status'=>1,'message'=>'Cập nhật thành công.'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    protected function get_admin_id(){
        // if(Auth::guard('admin')->check()){
        //     return Auth::guard('admin')->user()->id;
        // }
        return 0;
    }

    protected function image_to_db($name)
    {
        $time = date('Y/m/d H:i:s',time());
        return DB::table('media')->insertGetId(array('user_id'=>$this->get_admin_id(),'name'=>$name,'created_at'=>$time,'updated_at'=>$time));
    }

    protected function beautiful_name($string) {
        $coDau = array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
            "ằ","ắ","ặ","ẳ","ẵ",
            "è","é","ẹ","ẻ","ẽ","ê","ề" ,"ế","ệ","ể","ễ",
            "ì","í","ị","ỉ","ĩ",
            "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
        ,"ờ","ớ","ợ","ở","ỡ",
            "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
            "ỳ","ý","ỵ","ỷ","ỹ",
            "đ",
            "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
        ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
            "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
            "Ì","Í","Ị","Ỉ","Ĩ",
            "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
        ,"Ờ","Ớ","Ợ","Ở","Ỡ",
            "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
            "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
            "Đ","ê","ù","à");
        $khongDau = array("a","a","a","a","a","a","a","a","a","a","a"
        ,"a","a","a","a","a","a",
            "e","e","e","e","e","e","e","e","e","e","e",
            "i","i","i","i","i",
            "o","o","o","o","o","o","o","o","o","o","o","o"
        ,"o","o","o","o","o",
            "u","u","u","u","u","u","u","u","u","u","u",
            "y","y","y","y","y",
            "d",
            "A","A","A","A","A","A","A","A","A","A","A","A"
        ,"A","A","A","A","A",
            "E","E","E","E","E","E","E","E","E","E","E",
            "I","I","I","I","I",
            "O","O","O","O","O","O","O","O","O","O","O","O"
        ,"O","O","O","O","O",
            "U","U","U","U","U","U","U","U","U","U","U",
            "Y","Y","Y","Y","Y",
            "D","e","u","a");
        $string = str_replace($coDau,$khongDau,$string);
        $string  =  trim(preg_replace("/[^A-Za-z0-9]/i"," ",$string)); // khong dau
        $string  =  str_replace(" ","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("--","-",$string);
        $string = str_replace("/","-",$string);
        return strtolower($string);
    }
}
