<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Articles\SearchRepository;


class ArticleController extends Controller
{
    public function __construct(){
        $this->module_name = 'Articles ES';
        parent::__construct();
    }

    public function index(SearchRepository $searchRepository){

    //     Article::createIndex($shards = null, $replicas = null);

    // Article::putMapping($ignoreConflicts = true);

    // Article::addAllToIndex();
        // $articles = Article::get();
        $articles = $searchRepository->search('tivi tiết kiệm điện');
        return view('articles.index', compact('articles'));
    }

    public function show($id){

    }
}
