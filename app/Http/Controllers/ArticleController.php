<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function __construct(){
        $this->module_name = 'Articles ES';
        parent::__construct();
    }

    public function index(){

        Article::createIndex($shards = null, $replicas = null);

    Article::putMapping($ignoreConflicts = true);

    Article::addAllToIndex();
        $articles = Article::get();
        return view('articles.index', compact('articles'));
    }

    public function show($id){

    }
}
