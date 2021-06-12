<?php

namespace App\Unility;

use App\Models\Story;
use App\Models\StoryDetail;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlStoryTool{

    public static function crawlTruyenFullChapters($link){
        $client = new Client();
        $crawler = $client->request('GET', $link);
        $name = $crawler->filter('.col-truyen-main .title')->text();
        $author = $crawler->filter('.info-holder .info a[itemprop="author"]')->text();
        $story = Story::updateOrCreate([
            'link' => $link
        ],[
            'name' => $name ?? '',
            'author' => $author ?? ''
        ]);
        $chapters = [];
        for($i = 1; $i <= 14; $i++){
            $crawler = $client->request('GET', "$link/trang-$i");
            $crawler->filter('ul.list-chapter li a')
                    ->each(function (Crawler $node) use($story, &$chapters){
                        $chapters[] = [
                                        'name' => $node->attr('title'),
                                        'link' => $node->attr('href'),
                                        'story_id' => $story->id
                                    ];
                    });
        }
        // StoryDetail::insert($chapters);
        foreach($chapters as $chapter){
            $detail = StoryDetail::updateOrCreate([
                'link' => $chapter['link']
            ],[
                'name' => $chapter['name'],
                'story_id' => $chapter['story_id'],
                'content' => self::crawlTruyenFullContent($chapter['link'])
            ]);
        }
    }

    public static function crawlTruyenFullContent($link_chapter){
        $client = new Client();
        $crawler = $client->request('GET', $link_chapter);
        $content = $crawler->filter('#chapter-c');
        // Remove div ads tags
        $content->filter('div')->each(function (Crawler $node){
            foreach($node as $n){
                $n->parentNode->removeChild($n);
            }
        });
        return str_replace("\n", '<br />', $content->html());
    }

    public static function crawlTruyenTangThuVienChapters($link){
        $client = new Client();
        $crawler = $client->request('GET', $link);
        $name = $crawler->filter('.book-information .book-info h1')->text();
        $author = $crawler->filter('.book-information .book-info p.tag a:first-child')->text();

        $story = Story::updateOrCreate([
            'link' => $link
        ],[
            'name' => $name ?? '',
            'author' => $author ?? ''
        ]);
        $chapters = [];
        $crawl_story_id = $crawler->filter('#story_id_hidden')->attr('value');
        $link_chapters = "https://truyen.tangthuvien.vn/doc-truyen/page/$crawl_story_id?page=0&limit=2000&web=1";
        $crawler = $client->request('GET', $link_chapters);
        $crawler->filter('ul.cf li a')
                ->each(function (Crawler $node) use($story, &$chapters){
                    $chapters[] = [
                                    'name' => $node->attr('title'),
                                    'link' => $node->attr('href'),
                                    'story_id' => $story->id
                                ];
                });

        foreach($chapters as $chapter){
            // \Log::info("Crawl: ". $chapter['link']);
            $retry = 3;
            while(1){
                \Log::info("Crawl: ". $chapter['link']);
                try{
                    $detail = StoryDetail::updateOrCreate([
                        'link' => $chapter['link']
                    ],[
                        'name' => $chapter['name'],
                        'story_id' => $chapter['story_id'],
                        'content' => self::crawlTruyenTangThuVienContent($chapter['link'])
                    ]);
                    break;
                } catch(\Exception $e){
                    \Log::error("Loi crawl: ". $e->getMessage());
                    $retry--;
                    if($retry == 0){
                        throw $e;
                    }
                }
            }
        }
    }

    public static function crawlTruyenTangThuVienContent($link_chapter){
        $client = new Client();
        $crawler = $client->request('GET', $link_chapter);
        $content = $crawler->filter('.chapter-c-content .box-chap');
        $html = preg_replace("/[\n\r]/", '<br />', $content->html());
        $html = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $html);
        return $html;
    }

    public static function test($url){
        $i = 1000;
        // $client = new Client();
        while($i>0){
            $client = new Client();
            $crawler = $client->request('GET', $url);
            $i--;
        }
    }

}