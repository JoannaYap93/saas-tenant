<?php

use App\Model\Invoice;
use App\Model\Setting;

function sidebar()
{
    $query = Invoice::query()
        ->join('tbl_invoice_log as il', 'il.invoice_log_created', '=', 'tbl_invoice.invoice_updated')
        ->where('company_id', auth()->user()->company_id)
        ->where('is_approved', 0)
        ->where('il.invoice_log_action', 'Payment')
        ->get();

    return $query;
}

function get_logo()
{
    $logo = Setting::get_by_slug('admin_site_logo');
    if ($logo) {
        return $logo;
    } else {
        return asset('images/huaxin_logo.png');
    }
}

function get_icon()
{
    $logo = Setting::get_by_slug('website_icon');
    if ($logo) {
        return $logo;
    } else {
        return asset('images/huaxin_logo_transparent.png');
    }
}
