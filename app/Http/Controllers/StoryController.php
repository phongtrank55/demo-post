<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Story;
use App\Models\StoryDetail;

class StoryController extends Controller
{
    public function __construct(){
        $this->module_name = 'Truyện';
        parent::__construct();
    }

    public function index(){
        // $url = 'https://truyen.tangthuvien.vn/doc-truyen/chue-te/1715547-chuong-66';
        // dd(\App\Unility\CrawlStoryTool::crawlTruyenTangThuVienContent($url));
        // $url = 'https://truyen.tangthuvien.vn/doc-truyen/chue-te';
        // dd(\App\Unility\CrawlStoryTool::crawlTruyenTangThuVienChapters($url));
        // $url = 'https://truyenyy.vip/truyen/tieu-dao-tieu-thu-sinh-dich/';
        // $url = 'https://bachngocsach.com/reader/tieu-dao-tieu-thu-sinh-convert';
        // dd(\App\Unility\CrawlStoryTool::crawlBachNgocSachChapters($url));

        // return 'ok';
        $stories = Story::get();
        return view('stories.index', compact('stories'));

    }

    public function show($id){
        $story = Story::findOrFail($id);

        $this->module_name = 'Truyện ' . $story->name;
        $chapters = StoryDetail::where('story_id', $id)
                    // ->where('id', '>', 6253)
                    // ->where('content', 'like', '%sư sư%')
                    // ->where('content', 'like', '%quyên nhi%')
                    // ->where('content', 'like', '%ở rể%')
                    ->where('content', 'like', '%Văn nhân bất nhị%')
                    ->get();
        if(empty($chapters)){
            return 'Không có chap';
        }
        return view('stories.show', compact('chapters', 'story'));
    }
}
