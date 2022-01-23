<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SslList;
use Carbon\Carbon;

class sslChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl:checker {url?} {--A|all}'; //AD FLAG TO CHECK ALL URLS IN THE DB

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
        if($this->option('all')) {
            $sites = SslList::all();

            foreach ($sites as $site) {
                $ssl_expire_date = $this->getCertExperation($site->site);
                if($ssl_expire_date->ne($site->expire_date)) {
                    $site->expire_date = $ssl_expire_date->toDateTimeString();
                    $site->save();
                    $this->line('Site updated');
                }else{
                    $this->line('No update');
                }
            }
            $this->line('Check Complete');
            return 0;
        }
        $site = $this->argument('url');
        $site = $this->checkUrl($site);
        $ssl_expire_date = $this->getCertExperation($site);

        if(!SslList::where('site', $site)->exists()){
            $entry = new SslList();
            $entry->site = $site;
            $entry->expire_date = $ssl_expire_date->toDateTimeString();
            $entry->save();
            $this->info('Site Stored');
        }else{
            $this->info('Site Exists');
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

    public function getCertExperation($site)
    {
        $call = curl_init();
        $settings = [
            CURLOPT_URL => $site,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_NOBODY => true,
            CURLOPT_AUTOREFERER => true,
            CURLOPT_CERTINFO => true
        ];

        curl_setopt_array($call, $settings);

        curl_exec($call);
        $info = curl_getinfo($call);
        curl_close($call);

        return new Carbon($info['certinfo'][0]['Expire date']);
    }

    public function storeSite()
    {

    }
}
