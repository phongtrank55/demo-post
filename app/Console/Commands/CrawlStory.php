<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Unility\CrawlStory as CrawlStoryTool;

class CrawlStory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crawl:story {link}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            $link = $this->argument('link');
            DB::beginTransaction();
            CrawlStoryTool::crawlTruyenFullChapters($link);
            $this->info('Da crawl thanh cong');
            DB::commit();
        }catch(\Exception $e){
            \Log::error($e);
            $this->error('Crawl: ', $e->getMessage());
            DB::rollback();
        }
    }
}
