<?php

namespace App\Console\Commands;

use App\Models\SslList;
use Illuminate\Console\Command;

class sslDisplay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl:list';

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
        $headers = ['id', 'url', 'cert expire'];
        $list = SslList::all('id', 'site', 'expire_date')->toArray();
        $this->table($headers, $list);
        return 0;
    }
}
