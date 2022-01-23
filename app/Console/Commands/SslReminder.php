<?php

namespace App\Console\Commands;

use App\Mail\SslReminderEmail;
use App\Models\SslList;
use Carbon\Carbon;
use Illuminate\Console\Command;

use Illuminate\Support\Facades\Mail;

class SslReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ssl:reminder';

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
        //rerun checker in scheduler before sending email
        $data = SslList::all();
        $now = new Carbon();
        $list = $data->filter(function($item) use ($now) {
            if($now->gt($item->expire_date->subMonths(1))) {
                return $item;
            }
        });

        if($list->isNotEmpty()) {
            $this->sendReminder($list);
        }

        return 0;
    }

    public function sendReminder($list)
    {
        Mail::to('bryan@airtightdesign.com')->send(new SslReminderEmail($list));
    }
}
