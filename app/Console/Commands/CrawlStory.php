<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Unility\CrawlStoryTool;

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
            $host = strtolower(parse_url($link)['host'] ?? '');
            switch($host){
                case 'truyenfull.vn': CrawlStoryTool::crawlTruyenFullChapters($link); break;
                case 'truyen.tangthuvien.vn': CrawlStoryTool::crawlTruyenTangThuVienChapters($link); break;
                default:
                    // $this->error("$host chua co tool crawl");
                    CrawlStoryTool::test($link);
                    $this->info("$link duoc tang view!");
                    return 0;
            }
            DB::commit();
            $this->info("Da crawl $link thanh cong");
        }catch(\Exception $e){
            \Log::error($e);
            $this->error('Loi Crawl: ' . $e->getMessage());
            DB::rollback();
        }
    }
}
