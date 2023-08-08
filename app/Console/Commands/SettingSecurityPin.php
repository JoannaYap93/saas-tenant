<?php

namespace App\Console\Commands;

use App\Model\Setting;
use Illuminate\Console\Command;
use PDO;
use Illuminate\Support\Str;

class SettingSecurityPin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'SettingSecurityPin:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset security pin every week';

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
        $security_pin = rand(100000, 999999);
        $update = Setting::where('setting_slug', '=', 'security_pin')->update([
          'setting_value' => $security_pin
        ]);
        // dd($update);
        exit;
    }
}
