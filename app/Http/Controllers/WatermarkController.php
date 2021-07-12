<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
// use Intervention\Image\ImageManager;
// use Image;


class WatermarkController extends Controller
{
    public function __construct(){
        $this->module_name = 'Chèn watermark vào ảnh';
        parent::__construct();
    }
    public function index(){
        // Image::make('assets/img/anh-nuoc-hoa.jpg')->insert('logo-chanhtuoi.png', 'bottom-right', 10, 10)
        //         ->save('assets/img/result.jpg');
        $src_url = 'https://cdn.chanhtuoi.com/uploads/2021/07/61c32cd7c37f7bfe7b176a4e031992ea-jpg-720x720q80-jpg.jpg';
        Image::make($src_url)->insert('logo-chanhtuoi.png', 'bottom-right', 10, 10)
                ->save('assets/img/result.jpg');
        return view('watermark.index', compact('src_url'));
    }
}
