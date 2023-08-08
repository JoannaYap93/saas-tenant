<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RunningNumber extends Model
{
    protected $table = 'tbl_setting_no';
    protected $primaryKey = 'setting_no_id';
    public $timestamps = false;

    protected $fillable = [
        'setting_no_slug',
        'setting_no_year',
        'setting_no_month',
        'setting_no',
        'company_id',
        'user_id'
    ];

    public static function get_running_code($type = 'delivery_order')
    {
        $month = date('n');
        $year = date('Y');
        $user = auth()->user();

        $running_number = RunningNumber::where([
            'setting_no_slug' => $type,
            'setting_no_year' => $year,
            'setting_no_month' => $month,
            'company_id' => $user->company_id,
            'user_id' => $user->user_id
        ])->first();

        if ($running_number) {
            $current_no = $running_number->setting_no;
            $new_no = $current_no + 1;
            $number = substr($running_number->setting_no_year, -2) . sprintf("%02d", $running_number->setting_no_month) . sprintf("%04d", $new_no);
            $running_number->update([
                'setting_no' => $new_no
            ]);
        } else {
            $create_no = RunningNumber::create([
                'setting_no_slug' => $type,
                'setting_no_year' => $year,
                'setting_no_month' => $month,
                'company_id' => $user->company_id,
                'setting_no' => 1,
                'user_id' => $user->user_id
            ]);

            $number = substr($create_no->setting_no_year, -2) . sprintf("%02d", $create_no->setting_no_month) . sprintf("%04d", $create_no->setting_no);
        }
        return $number;
    }

    public static function get_running_code_invoice($type = 'invoice', $month)
    {
        // $month = date('n');
        $year = date('Y');
        $user = auth()->user();

        $running_number = RunningNumber::where([
            'setting_no_slug' => $type,
            'setting_no_year' => $year,
            'setting_no_month' => $month,
            'company_id' => $user->company_id,
            // 'user_id' => $user->user_id
        ])->first();

        if ($running_number) {
            $current_no = $running_number->setting_no;
            $new_no = $current_no + 1;
            $number = substr($running_number->setting_no_year, -2) . sprintf("%02d", $running_number->setting_no_month) . sprintf("%04d", $new_no);
            $running_number->update([
                'setting_no' => $new_no
            ]);
        } else {
            $create_no = RunningNumber::create([
                'setting_no_slug' => $type,
                'setting_no_year' => $year,
                'setting_no_month' => $month,
                'company_id' => $user->company_id,
                'setting_no' => 1,
                // 'user_id' => $user->user_id
            ]);

            $number = substr($create_no->setting_no_year, -2) . sprintf("%02d", $create_no->setting_no_month) . sprintf("%04d", $create_no->setting_no);
        }
        return $number;
    }

    public static function get_running_code_company_expense($type = 'company_expense')
    {
        $month = date('n');
        $year = date('Y');
        $user = auth()->user();

        $running_number = RunningNumber::where([
            'setting_no_slug' => $type,
            'setting_no_year' => $year,
            'setting_no_month' => $month,
            'company_id' => $user->company_id,
            'user_id' => $user->user_id
        ])->first();

        if ($running_number) {
            $current_no = $running_number->setting_no;
            $new_no = $current_no + 1;
            $number = substr($running_number->setting_no_year, -2) . sprintf("%02d", $running_number->setting_no_month) . sprintf("%04d", $new_no);
            $running_number->update([
                'setting_no' => $new_no
            ]);
        } else {
            $create_no = RunningNumber::create([
                'setting_no_slug' => $type,
                'setting_no_year' => $year,
                'setting_no_month' => $month,
                'company_id' => $user->company_id,
                'setting_no' => 1,
                'user_id' => $user->user_id
            ]);

            $number = substr($create_no->setting_no_year, -2) . sprintf("%02d", $create_no->setting_no_month) . sprintf("%04d", $create_no->setting_no);
        }
        return $number;
    }
    public static function get_running_code_expense($type = 'claim')
    {
        $month = date('n');
        $year = date('Y');
        $user = auth()->user();

        $running_number = RunningNumber::where([
            'setting_no_slug' => $type,
            'setting_no_year' => $year,
            'setting_no_month' => $month,
            'company_id' => $user->company_id,
            'user_id' => $user->user_id
        ])->first();

        if ($running_number) {
            $current_no = $running_number->setting_no;
            $new_no = $current_no + 1;
            $number = substr($running_number->setting_no_year, -2) . sprintf("%02d", $running_number->setting_no_month) . sprintf("%04d", $new_no);
            $running_number->update([
                'setting_no' => $new_no
            ]);
        } else {
            $create_no = RunningNumber::create([
                'setting_no_slug' => $type,
                'setting_no_year' => $year,
                'setting_no_month' => $month,
                'company_id' => $user->company_id,
                'setting_no' => 1,
                'user_id' => $user->user_id
            ]);

            $number = substr($create_no->setting_no_year, -2) . sprintf("%02d", $create_no->setting_no_month) . sprintf("%04d", $create_no->setting_no);
        }
        return $number;
    }
}
