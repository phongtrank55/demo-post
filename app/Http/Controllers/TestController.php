<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class TestController extends Controller
{
    public function index(){
        $article = Article::find(1);
        $article->title = 'TEST 1 Nên mua Tivi nào tốt nhất phù hợp với gia đình bạn năm 2017';
        $article->body = 'Test 1 tivi bằng thoại với ứng dụng LG dam bảo khả năng tiết kiệm điện năng tối ưu.';
        $article->save();
        $article = Article::find(2);
        $article->title = 'TEST 2 Nên mua Tivi ';
        $article->body = 'test 2 tivi bằng điện thoại với ứng dụng tiết kiệm tối ưu.';
        $article->save();
        $article = Article::find(3);
        $article->title = 'TEST 3 Nên mua ';
        $article->body = 'test 3 bằng điện thoại với ứng dụng tiết kiệm tối ưu.';
        $article->save();
        $article = Article::find(4);
        $article->title = '5tr';
        $article->body = 'accđ';
        $article->save();
        $article = Article::find(5);
        $article->title = 'Phone with LG application is an energy saving tv';
        $article->body = 'xxxx';
        $article->save();
        $article = Article::find(6);
        $article->title = 'That tv made by energy saving LG applications';
        $article->body = 'xxxx';
        $article->save();
        $article = Article::find(7);
        $article->title = 'TEST 7 Phone with LG application ensures optimal energy saving';
        $article->body = 'xxx';
        $article->save();
        return 'ok done';
    }
}
