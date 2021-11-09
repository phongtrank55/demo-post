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
        $max_page = $crawler->filter('#total-page')->attr('value');

        for($i = 1; $i <= $max_page; $i++){
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
            \Log::info("Crawl: ". $chapter['link']);
            $content = self::crawlTruyenFullContent($chapter['link']);
            $detail = StoryDetail::updateOrCreate([
                'link' => $chapter['link']
            ],[
                'name' => $chapter['name'],
                'story_id' => $chapter['story_id'],
                'content' => $content,
                // 'audio' => SpeechVietnamese::speech($content)
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
        // return str_replace("\n", '<br />', $content->html());
        return $content->html();
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
        $html = $content->html();
        // $html = preg_replace("/[\n\r]/", '<br />', $content->html());
        // $html = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $html);
        return $html;
    }

    public static function crawlBachNgocSachChapters($link){
        $client = new Client();
        $crawler = $client->request('GET', $link);
        $name = $crawler->filter('#truyen-title')->text();
        $author = $crawler->filter('#tacgia a')->text();

        $story = Story::updateOrCreate([
            'link' => $link
        ],[
            'name' => $name ?? '',
            'author' => $author ?? ''
        ]);

        $url_host = parse_url($link);
        $url_host = $url_host['scheme'] . '://' . $url_host['host'];

        $link_chapters = "$link/muc-luc?page=all";
        $chapters = [];

        $crawler = $client->request('GET', $link_chapters);
        $crawler->filter('ul li.mucluc-row .mucluc-chuong a')
                ->each(function (Crawler $node) use($url_host, &$chapters, $story){
                    $chapters[] = [
                                    'name' => $node->text(),
                                    'link' => $url_host . $node->attr('href'),
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
                        'content' => self::crawlBachNgocSachContent($chapter['link'])
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

    public static function crawlBachNgocSachContent($link_chapter){
        $client = new Client();
        $crawler = $client->request('GET', $link_chapter);
        return $crawler->filter('#noi-dung')->html();
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
