<?php

namespace App\Console\Commands;
use App\Models\Category;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'DemoCron:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Every recently add users delete category';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cat_id = "basic programming";
        $category = DB::table('categories')->where($category_description= null)->update(array('category_description' => $cat_id)); 
    }
}