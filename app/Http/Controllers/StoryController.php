<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Unility\CrawlStory;

class StoryController extends Controller
{
    public function __construct(){
        $this->module_name = 'Crawl truyện';
        parent::__construct();
    }

    public function index(){
        // $story = Story::findOrFail(1);
        // $story->crawlChapters();
        // return 'done';
        // $storyDetail = StoryDetail::findOrFail(1);
        // $url = 'https://truyenfull.vn/o-re-chue-te';
        // CrawlStory::crawlTruyenFullChapters($url);
    }
}
