<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unility\SpeechVietNamese;
use App\Unility\CrawlStoryTool;

class SpeechController extends Controller
{
    public function index(){
        // $text = 'Lý Minh Châu cầm lấy cái muỗng, nếm một ngụm, suy tư và mỉm cười!';
        // $data = SpeechVietnamese::speech($text);
        $content = CrawlStoryTool::crawlTruyenFullContent('https://truyenfull.vn/yeu-nu-xin-tu-trong/chuong-1/');
        // dd($content);
        $data = SpeechVietnamese::speech($content);
        return view('speech.index', compact('data'));
    }
}
