<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Intervention\Image\ImageManagerStatic as Image;
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
        // \Image::make($src_url)->insert('img/logo-chanhtuoi.png', 'bottom-right', 10, 10)
        //         ->save('assets/img/result.jpg');

        $src_url = 'https://cdn.chanhtuoi.com/uploads/2021/07/61c32cd7c37f7bfe7b176a4e031992ea-jpg-720x720q80-jpg.jpg';

        // $src_url = 'https://cdn.chanhtuoi.com/uploads/2021/07/may-lam-sua-hat-mishxsddio-02.png';
        // $src_url = 'https://cdn.chanhtuoi.com/uploads/2021/07/may-lam-sua-hat-mishio-02xs.png';
        // $src_url = 'https://cdn.chanhtuoi.com/uploads/2021/07/may-lam-sua-hat-mssishio-05.jpg';
        // $src_url = 'https://cdn.chanhtuoi.com/uploads/2021/07/nen-mua-laptop-hang-nao-tot-s05.jpg';

        $image = \Image::make($src_url);
        $watermark = \Image::make('img/logo-chanhtuoi.png');
        $new_watermark = $watermark->resize($image->width() / 2, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        $image->insert($new_watermark, 'bottom-right', 10, 10)->save('assets/img/result.jpg');
        return view('watermark.index', compact('src_url'));
    }

    public function run(){
        $src_url = 'https://cdn.chanhtuoi.com/uploads/2021/07/may-lam-sua-hat-mishio-02xs.png';
        if(strpos($src_url, 'cdn.chanhtuoi.com') !== false){
            $path_img = str_replace('https://cdn.chanhtuoi.com/', '', $src_url);
            $upload = \Storage::disk(config('app.storage_type'));
            $image = \Image::make($src_url);
            $watermark = \Image::make('img/logo-chanhtuoi.png');
            $new_watermark = $watermark->resize($image->width() / 4, null, function ($constraint) {
                $constraint->aspectRatio();
            });

            $image->insert($new_watermark, 'bottom-right', 10, 10)->stream();
            $upload->put($path_img, $image->__toString(), 'public');
            return 'added watermark';
        }
        return 'done';
    }
}
