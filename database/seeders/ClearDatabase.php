<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClearDatabase extends Seeder
{
    protected $excludedTables = [
        'migrations',
        'failed_jobs',
        'sessions',
        'subscription_log',
        'tbl_user',
        'tbl_tenant_user',
        'tbl_tenant_company',
        'tbl_user_password_reset',
        'personal_access_tokens',
        'tbl_user_role',
        'tbl_user_permission',
        'tbl_user_model_has_role',
        'tbl_user_model_has_permission',
        'tbl_user_role_has_permission',
        'tbl_product_status',
        'tbl_worker_status',
        'tbl_worker_role',
        'tbl_worker_type',
        'tbl_company_land_tree_action',
        'tbl_invoice_status',
        'tbl_delivery_order_status',
        'tbl_delivery_order_type',
        'tbl_claim_approval_step',
        'tbl_claim_status',
    ]; // Add table names to exclude here

    public function run()
    {
        // Disable foreign key checks to avoid issues with truncation
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // Truncate all tables except excluded ones
        $tables = DB::select('SHOW TABLES');
        foreach ($tables as $table) {
            $table_name = reset($table);
            if (!in_array($table_name, $this->excludedTables)) {
                DB::table($table_name)->truncate();
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
