<?php

namespace App\Console\Commands;

use App\Models\SslList;
use Illuminate\Console\Command;

class sslRemove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl:remove {site}';

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
        $site_id = $this->argument('site');

        if(is_string($site_id)) {
            $site_name = $this->checkUrl($site_id);
            $site = SslList::where('site',$site_name)->first();
        }
        if(is_numeric($site_id)){
            $site = SslList::find($site_id);
        }

        if($site === null) {
            $this->error('Invalid ID');
            return 1;
        }

        if($this->confirm("Are you sure you want to remove {$site->site} ?")) {
            $site->delete();
            $this->info('Site removed');
        }
        return 0;
    }

    public function checkUrl($site)
    {
        $url_parts = parse_url($site);
        if(!empty($url_parts['path'])) {
            return "https://{$url_parts['path']}";
        }

        if(!empty($url_parts['host'])) {
            return "https://{$url_parts['path']}";
        }
    }
}
