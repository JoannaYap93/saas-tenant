<?php

namespace App\Console\Commands;

use App\Model\PaymentUrl;
use App\Model\PaymentUrlLog;
use App\Model\Setting;
use Illuminate\Console\Command;
use PDO;
use Illuminate\Support\Str;

class PaymentUrlCancellation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PaymentUrlCancellation:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto cancel Payment URL after expired';

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
        $today = date('Y-m-d');
        $setting_expiry_date = Setting::get_by_slug('payment_url_expiry_period');
        $expiry_date = date('Y-m-d', strtotime("$today - $setting_expiry_date day"));
        // dd($expiry_date);
        $payment_url = PaymentUrl::query()->get();
        foreach($payment_url as $key => $value){
          $created_date = substr($value->payment_url_created, 0, 10);
          if($created_date == $expiry_date){
            if($value->payment_url_status == 'Pending'){
              PaymentUrl::where('payment_url_id', $value->payment_url_id)->update([
                'payment_url_status' => 'Cancelled',
              ]);
              PaymentUrlLog::insert([
                'payment_url_id' => $value->payment_url_id,
                'payment_url_log_action' => 'Cancelled',
                'payment_url_log_description' => 'Exipred Payment Url',
                'payment_url_log_created' => now(),
                'user_id' => $value->user_id,
                'customer_id' => $value->customer_id,
              ]);
            }
          }
          // dd($created_date);
        }
        // dd($payment_url);
        exit;
    }
}
