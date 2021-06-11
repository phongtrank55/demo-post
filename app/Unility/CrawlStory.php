<?php

namespace App\Unility;

use App\Models\Story;
use App\Models\StoryDetail;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class CrawlStory{

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
}