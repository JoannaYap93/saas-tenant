<?php

declare(strict_types=1);

use App\Model\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Http\Middleware\SubscriptionPlan;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\WorkerController;
use App\Http\Controllers\CollectController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Console\Commands\SettingSecurityPin;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SyncListController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserTypeController;
use App\Http\Controllers\AdminRoleController;
use App\Http\Controllers\ClaimItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PaymentUrlController;
use App\Http\Controllers\ProductTagController;
use App\Http\Controllers\WorkerTypeController;
use App\Http\Controllers\CompanyFarmController;
use App\Http\Controllers\CompanyLandController;
use App\Http\Controllers\CustomerPicController;
use App\Http\Controllers\PayrollItemController;
use App\Http\Controllers\SettingRaceController;
use App\Console\Commands\PaymentUrlCancellation;
use App\Http\Controllers\CustomerTermController;
use App\Http\Controllers\FormulaUsageController;
use App\Http\Controllers\DeliveryOrderController;
use App\Http\Controllers\SettingRewardController;
use App\Http\Controllers\BudgetEstimateController;
use App\Http\Controllers\CompanyExpenseController;
use App\Http\Controllers\CompanyPnlItemController;
use App\Http\Controllers\SettingExpenseController;
use App\Http\Controllers\SettingFormulaController;
use App\Http\Controllers\SettingPaymentController;
use App\Http\Controllers\SettingTreeAgeController;
use App\Http\Controllers\CompanyLandTreeController;
use App\Http\Controllers\CompanyLandZoneController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\SettingCurrencyController;
use App\Http\Controllers\CustomerCategoryController;
use App\Http\Controllers\SettingWarehouseController;
use App\Http\Controllers\ClaimPendingReportController;
use App\Http\Controllers\CompanyLandTreeLogController;
use App\Http\Controllers\ProductCompanyLandController;
use App\Http\Controllers\RawMaterialCompanyController;
use App\Http\Controllers\SettingProductSizeController;
use App\Http\Controllers\SettingRawMaterialController;
use App\Http\Controllers\WarehouseReportingController;
use Stancl\Tenancy\Middleware\InitializeTenancyByPath;
use App\Http\Controllers\ProfitLossReportingController;
use App\Http\Controllers\DeliveryOrderExpenseController;
use App\Http\Controllers\ProductStockTransferController;
use App\Http\Controllers\TreePointerReportingController;
use App\Http\Controllers\CompanyLandTreeActionController;
use App\Http\Controllers\ProductStockWarehouseController;
use App\Http\Controllers\SettingRewardCategoryController;
use App\Http\Controllers\SupplierDeliveryOrderController;
use App\Http\Controllers\MessageTemplateInvolveController;
use App\Http\Controllers\SettingExpenseCategoryController;
use App\Http\Controllers\SettingFormulaCategoryController;
use App\Http\Controllers\SupplierExpensesReportController;
use App\Http\Controllers\WorkerAttendanceReportController;
use App\Http\Controllers\RawMaterialCompanyUsageController;
use App\Http\Controllers\SettingExpenseOverwriteController;
use App\Http\Controllers\CompanyLandBudgetOverwriteController;
use App\Http\Controllers\SettingRawMaterialCategoryController;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

use App\Model\CompanyExpense;
use App\Model\CompanyExpenseLand;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

// Main Global
// Route::get('/test', function () {
//     $user = User::find(1);
//     echo json_encode($user);
// });

// Tenant Group
Route::group([
    'prefix' => '/{tenant}',
    'middleware' => [
        'web',
        InitializeTenancyByPath::class,
    ],
], function () {

    Route::get('login_by_pass_awas', function () {
        $user = User::find(1);
        // echo json_encode($user);
        // die;
        Auth::login($user);
        return redirect()->route('main.index', ['tenant' => tenant('id')]);
    });

    Route::get('sales-person-login/{encryption_landlord_id}', [LoginController::class, 'landlordByPassLogin'])->name('landlord.bypass.login');
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout.user');
    Route::post('/login-user', [LoginController::class, 'login'])->name('login.user');
    Route::get('reset/password', [LoginController::class, 'resetPassword'])->name('password.reset');

    // Route::get('{any}', [HomeController::class, 'index']);

    Route::get('/', function () {
      if(auth()->user() && auth()->user()->user_type_id == 1){
        return redirect()->route('dashboard_sales_analysis', ['tenant' => tenant('id')]);
      }else{
        return redirect()->route('dashboard', ['tenant' => tenant('id')]);
      }
    })->name('main.index');

    Route::match(['get', 'post'], 'database/compare', [DatabaseController::class, 'compare'])->name('compare_db');
    Route::match(['get', 'post'], 'database/import-demo-data', [DatabaseController::class, 'import_demo_data'])->name('import_demo_data_db');
    Route::match(['get', 'post'], 'database/clear-data', [DatabaseController::class, 'clear_data'])->name('clear_data_db');
    Route::match(['get', 'post'], 'company_expense/single_land_to_multi_land/{pw}', [DatabaseController::class, 'change_company_expense_land'])->name('change_company_expense_land');

    // Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');
    Route::match(['get', 'post'], 'dashboard/price-information/{search?}', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::match(['get', 'post'], 'dashboard/price-analysis', [DashboardController::class, 'dashboard_price_analysis'])->name('dashboard_price_analysis');
    Route::group(['middleware' => ['permission:dashboard_sales_analysis']], function () {
        Route::match(['get', 'post'], 'dashboard/sales-analysis', [DashboardController::class, 'dashboard_sales_analysis'])->name('dashboard_sales_analysis');
    });
    Route::match(['get', 'post'], 'dashboard/profit_loss_analysis', [DashboardController::class, 'dashboard_profit_loss_analysis'])->name('dashboard_profit_loss_analysis');
    Route::match(['get', 'post'], 'ajax_price_analysis', [DashboardController::class, 'ajax_price_analysis'])->name('ajax_price_analysis');
    Route::match(['get', 'post'], 'ajax_get_farm_by_company_id', [DashboardController::class, 'ajax_get_farm_by_company_id'])->name('ajax_get_farm_by_company_id');
    Route::match(['get', 'post'], 'ajax_get_land_by_company_farm_id', [DashboardController::class, 'ajax_get_land_by_company_farm_id'])->name('ajax_get_land_by_company_farm_id');
    Route::match(['get', 'post'], 'ajax_get_product_id_by_company_land', [DashboardController::class, 'ajax_get_product_id_by_company_land'])->name('ajax_get_product_id_by_company_land');
    Route::match(['get', 'post'], 'ajax_get_setting_size_by_product_id', [DashboardController::class, 'ajax_get_setting_size_by_product_id'])->name('ajax_get_product_size');
    Route::match(['get', 'post'], 'ajax_get_product_sel_by_company_land_id', [DashboardController::class, 'ajax_get_product_sel_by_company_land_id'])->name('ajax_get_product_sel_by_company_land_id');
    Route::match(['get', 'post'], '/price_information_add/{search_date?}/{company_farm_name?}/{product?}', [DashboardController::class, 'price_information_add'])->name('price_information_add');
    Route::match(['get', 'post'], '/min_max_detail/{search_date}/{product}/{company_farm_name}', [DashboardController::class, 'min_max_detail'])->name('min_max_detail');


    // Route::get('/index', [UserController::class, 'dashboard'])->name('dashboard');

    /**** User ****/
    Route::match(['get', 'post'], '/ajax_get_farm_manager_sel', [UserController::class, 'ajax_get_farm_manager_sel'])->name('ajax_get_farm_manager_sel');
    Route::match(['get', 'post'], 'ajax_get_user_by_company_id', [UserController::class, 'ajax_get_user_by_company_id'])->name('ajax_get_user_by_company_id');
    // Profile
    Route::match(['get', 'post'], 'user/profile', [UserController::class, 'profile'])->name('user_profile');
    // Change password
    Route::match(['get', 'post'], 'user/change_password', [UserController::class, 'change_password'])->name('user_change_password');

    Route::group(['middleware' => ['permission:user_listing']], function () {
        //User
        Route::match(['get', 'post'], 'user/listing', [UserController::class, 'listing'])->name('user_listing');
        // Change Password by Sup Admin

    });

    ///Company
    Route::group(['middleware' => ['permission:company_listing']], function () {
        Route::match(['get', 'post'], 'company/listing', [CompanyController::class, 'listing'])->name('company_listing');
        // Route::match(['get', 'post'], 'company_landtree_action/listing', [CompanyLandTreeActionController::class, 'listing'])->name('company_landtree_listing');
        Route::match(['get', 'post'], 'company_pnl_item/listing', [CompanyPnlItemController::class, 'listing'])->name('company_pnl_item_listing');
        Route::match(['get', 'post'], 'ajax_find_product_company_land_with_id', [ProductCompanyLandController::class, 'ajax_find_product_company_land_with_id'])->name('ajax_find_product_company_land_with_id');
    });
    Route::group(['middleware' => ['permission:company_manage']], function () {
        Route::match(['get', 'post'], 'company/edit_pic/{company_id}', [CompanyController::class, 'edit_pic'])->name('company_edit_pic');
        Route::match(['get', 'post'], 'company/edit/{company_id}', [CompanyController::class, 'edit'])->name('company_edit');
        Route::match(['get', 'post'], 'company/add', [CompanyController::class, 'add'])->name('company_add');
        Route::match(['get', 'post'], 'company/details/edit/{id}', [CompanyController::class, 'edit_land'])->name('company_edit_land');
        Route::post('/check_code', [CompanyController::class, 'check_company_code'])->name('check_company_code');
        Route::match(['get', 'post'], 'product-company-land/add/{id}', [ProductCompanyLandController::class, 'add_product'])->name('product_company_land_add');
        Route::post('company_status', [CompanyController::class, 'company_status'])->name('company_status');
        Route::match(['get', 'post'], 'company/add_company_bank/{id}', [CompanyController::class, 'add_company_bank'])->name('add_company_bank');
        // Route::match(['get', 'post'], 'company_landtree_action/add_default', [CompanyLandTreeActionController::class, 'add_default'])->name('add_default');
        // Route::match(['get', 'post'], 'company_landtree_action/edit_default/{id}', [CompanyLandTreeActionController::class, 'edit_default'])->name('edit_default');
        // Route::match(['get', 'post'], 'company_landtree_action/add_company', [CompanyLandTreeActionController::class, 'add_company'])->name('add_company');
        Route::match(['get', 'post'], 'company_pnl_item/add', [CompanyPnlItemController::class, 'add'])->name('company_pnl_item_add');
        Route::match(['get', 'post'], 'company_pnl_item/edit/{id}', [CompanyPnlItemController::class, 'edit'])->name('company_pnl_item_edit');
    });

    Route::match(['get', 'post'], 'company/claim_pic/{id}', [CompanyController::class, 'edit_pic'])->name('claim_pic');
    Route::match(['get', 'post'], 'ajax_find_product_company_land_with_id', [ProductCompanyLandController::class, 'ajax_find_product_company_land_with_id'])->name('ajax_find_product_company_land_with_id');
    Route::match(['get', 'post'], 'ajax_land_user', [CompanyController::class, 'ajax_company_land_user'])->name('ajax_land_user');
    Route::match(['get', 'post'], 'ajax_customer_by_category', [CompanyController::class, 'ajax_get_customer_by_category'])->name('ajax_get_customer_by_category');
    Route::match(['get', 'post'], 'ajax_company_land_warehouse', [CompanyController::class, 'ajax_company_land_warehouse'])->name('ajax_company_land_warehouse');
    Route::match(['get', 'post'], 'ajax_download_excel_sample_zone/{id?}', [ProductCompanyLandController::class, 'ajax_download_excel_sample_zone'])->name('ajax_download_excel_sample_zone');
    Route::match(['get', 'post'], 'ajax_download_zone_tree_data/{id?}/{land_id?}', [CompanyLandZoneController::class, 'ajax_download_zone_tree_data'])->name('ajax_download_zone_tree_data');
    Route::match(['get', 'post'], 'ajax_tree_total_code_zone', [CompanyLandZoneController::class, 'ajax_tree_total_code_zone'])->name('ajax_tree_total_code_zone');
    Route::match(['get', 'post'], 'ajax_get_land_zone', [CompanyLandZoneController::class, 'ajax_get_land_zone'])->name('ajax_get_land_zone');
    Route::match(['get', 'post'], 'ajax_get_company_land', [CompanyController::class, 'ajax_get_company_land'])->name('ajax_get_company_land');
    Route::match(['get', 'post'], '/restructure_zone_tree', [CompanyLandZoneController::class, 'restructure_zone_tree'])->name('restructure_zone_tree');
    Route::match(['get', 'post'], '/ajax_get_tree_action', [CompanyLandTreeActionController::class, 'ajax_get_tree_action'])->name('ajax_get_tree_action');
    Route::match(['get', 'post'], '/ajax_get_tree_action_by_id', [CompanyLandTreeLogController::class, 'ajax_get_tree_action_by_id'])->name('ajax_get_tree_action_by_id');
    Route::match(['get', 'post'], '/manage_tree/{zone_id?}', [CompanyLandTreeController::class, 'manage_tree'])->name('manage_tree');

    Route::group(['middleware' => ['permission:company_expense_listing']], function () {
        Route::match(['get', 'post'], '/company_expense', [CompanyExpenseController::class, 'listing'])->name('company_expense_listing');
    });

    Route::match(['get', 'post'], '/ajax_get_image_by_ce_item_id', [CompanyExpenseController::class, 'ajax_get_image_by_ce_item_id'])->name('ajax_get_image_by_ce_item_id');
    Route::match(['get', 'post'], '/ajax_delete_image_by_media_item_id', [CompanyExpenseController::class, 'ajax_delete_image_by_media_item_id'])->name('ajax_delete_image_by_media_item_id');

    Route::group(['middleware' => ['permission:company_expense_manage']], function () {
        Route::match(['get', 'post'], '/company_expense/add', [CompanyExpenseController::class, 'add'])->name('company_expense_add');
        Route::match(['get', 'post'], '/company_expense/add_labour', [CompanyExpenseController::class, 'add_labour'])->name('company_expense_add_labour');
        Route::match(['get', 'post'], '/company_expense/delete', [CompanyExpenseController::class, 'delete'])->name('company_expense_delete');
        Route::match(['get', 'post'], '/company_expense/edit/{id}', [CompanyExpenseController::class, 'edit'])->name('company_expense_edit');
        Route::match(['get', 'post'], '/company_expense/edit_labour/{id}', [CompanyExpenseController::class, 'edit_labour'])->name('company_expense_edit_labour');
    });
    Route::match(['get', 'post'], '/budget_overwrite/{company_id}/{company_land_id}', [CompanyLandBudgetOverwriteController::class, 'overwrite'])->name('budget_overwrite');

    Route::group(['middleware' => ['permission:sync_company_expense_cost']], function () {
        Route::match(['get', 'post'], '/company_expense/sync_cost', [CompanyExpenseController::class, 'sync_cost'])->name('sync_cost');
    });

    Route::group(['middleware' => ['permission:tree_log_listing']], function () {
        Route::match(['get', 'post'], 'tree_log/listing/{land_id?}/{id?}', [CompanyLandTreeLogController::class, 'listing'])->name('land_tree_log_listing');
    });
    Route::group(['middleware' => ['permission:tree_log_listing']], function () {
        Route::match(['get', 'post'], 'tree_log/edit/{id?}', [CompanyLandTreeLogController::class, 'edit'])->name('land_tree_log_edit');
    });
    Route::match(['get', 'post'], 'ajax_tree_log_by_zone', [CompanyLandTreeLogController::class, 'ajax_company_land_tree_log_by_zone'])->name('ajax_tree_log_by_zone');

    //company land zone
    Route::group(['middleware' => ['permission:company_land_zone_listing']], function () {
        Route::match(['get', 'post'], 'zone/listing/{company_id?}/{company_land_id?}', [CompanyLandZoneController::class, 'listing'])->name('land_zone_listing');
        Route::match(['get', 'post'], 'company-land/listing', [CompanyLandController::class, 'listing'])->name('company_land_listing');
    });
    Route::group(['middleware' => ['permission:company_land_zone_manage']], function () {
        Route::match(['get', 'post'], 'zone/edit/{id?}', [CompanyLandZoneController::class, 'edit'])->name('zone_edit');
    });

    // Route::group(['middleware' => ['permission:company_land_manage']], function () {
    //     Route::match(['get', 'post'], 'land-area/edit/{company_land_id}', [LandAreaController::class, 'edit'])->name('land_area_edit');
    //     Route::match(['get', 'post'], 'land-area/add', [LandAreaController::class, 'add'])->name('land_area_add');
    // });

    ///Land Category
    Route::group(['middleware' => ['permission:company_land_category_listing']], function () {
        Route::match(['get', 'post'], 'farm/listing', [CompanyFarmController::class, 'listing'])->name('company_farm_listing');
    });
    Route::group(['middleware' => ['permission:company_land_category_manage']], function () {
        Route::match(['get', 'post'], 'farm/edit/{id}', [CompanyFarmController::class, 'edit'])->name('company_farm_edit');
        Route::match(['get', 'post'], 'farm/add', [CompanyFarmController::class, 'add'])->name('company_farm_add');
        Route::post('farm/delete', [CompanyFarmController::class, 'delete'])->name('company_farm_delete');
    });
    Route::match(['get', 'post'], 'ajax_company_farm', [CompanyFarmController::class, 'ajax_get_farm'])->name('ajax_get_farm');

    Route::group(['middleware' => ['permission:company_land_tree_listing']], function () {
        Route::match(['get', 'post'], 'land_tree/listing/{company_land_zone_id?}', [CompanyLandTreeController::class, 'listing'])->name('land_tree_listing');
        Route::match(['get', 'post'], 'land_tree/search_listing', [CompanyLandTreeController::class, 'search_listing'])->name('search_listing');
        Route::match(['get', 'post'], 'company_landtree_action/listing', [CompanyLandTreeActionController::class, 'listing'])->name('company_landtree_listing');
    });

    Route::group(['middleware' => ['permission:company_land_tree_manage']], function () {
        Route::match(['get', 'post'], 'land_tree/edit/{company_land_tree_id?}', [CompanyLandTreeController::class, 'edit'])->name('land_tree_edit');
        Route::match(['get', 'post'], 'land_tree/add/{company_land_zone_id?}', [CompanyLandTreeController::class, 'add'])->name('land_tree_add');
        Route::match(['get', 'post'], 'company_landtree_action/add_default', [CompanyLandTreeActionController::class, 'add_default'])->name('add_default');
        Route::match(['get', 'post'], 'company_landtree_action/edit_default/{id}', [CompanyLandTreeActionController::class, 'edit_default'])->name('edit_default');
        Route::match(['get', 'post'], 'company_landtree_action/add_company', [CompanyLandTreeActionController::class, 'add_company'])->name('add_company');
        Route::match(['get', 'post'], '/fix_product_data_listing/{company_land_zone_id?}', [CompanyLandZoneController::class, 'fix_product_data_listing'])->name('fix_product_data_listing');
        Route::match(['get', 'post'], '/fix_product_data/{company_land_tree_id?}', [CompanyLandZoneController::class, 'fix_product_data'])->name('fix_product_data');
        Route::match(['get', 'post'], 'tree_delete_fix/delete', [CompanyLandZoneController::class, 'tree_delete_fix'])->name('tree_delete_fix');
    });
    // Route::group(['middleware' => ['permission:user_manage']], function () {
    //User
    // Route::match(['get', 'post'], 'user/add', [UserController::class, 'add'])->name('user_add');
    // Route::match(['get', 'post'], 'user/edit/{id}', [UserController::class, 'edit'])->name('user_edit');
    // Route::post('status', [UserController::class, 'status'])->name('user_status');
    // Route::match(['get', 'post'], 'user/assign_permission/{id}', [UserController::class, 'assign_permission'])->name('assign_permission');
    // // });
    // Route::match(['get', 'post'], 'user/ajax_get_user_details', [UserController::class, 'ajax_get_user_details'])->name('ajax_get_user_details');

    Route::match(['get', 'post'], 'ajax_search_user_by_name', [UserController::class, 'ajax_search_user_by_name'])->name('ajax_search_user_by_name');

    // //UserType
    Route::match(['get', 'post'], 'user/user-type/listing', [UserTypeController::class, 'listing'])->name('user_type_listing');

    // User Farm Manager
    Route::match(['get', 'post'], '/ajax_get_farm_manager_sel', [UserController::class, 'ajax_get_farm_manager_sel'])->name('ajax_get_farm_manager_sel');
    Route::match(['get', 'post'], '/ajax_get_farm_manager_sel_by_company', [UserController::class, 'ajax_get_farm_manager_sel_by_company'])->name('ajax_get_farm_manager_sel_by_company');

    // // Route::group(['middleware' => ['permission:user_role_listing']], function () {
    // //UserRole
    Route::match(['get', 'post'], 'user_role/listing', [UserRoleController::class, 'listing'])->name('user_role_listing');
    // // });
    // // Route::group(['middleware' => ['permission:user_role_manage']], function () {
    // //UserRole
    Route::match(['get', 'post'], 'role/edit/{id}', [UserRoleController::class, 'edit'])->name('user_role_edit');
    Route::match(['get', 'post'], 'role/add', [UserRoleController::class, 'add'])->name('user_role_add');
    // });

    // Route::group(['middleware' => ['permission:master_setting']], function () {
    // });


    /**** Setting Section ****/
    //Master Setting
    Route::group(['middleware' => ['permission:master_setting']], function () {
        Route::match(['get', 'post'], 'setting/listing', [SettingController::class, 'listing'])->name('setting_listing');
        Route::match(['get', 'post'], 'setting/edit/{id}', [SettingController::class, 'edit'])->name('setting_edit');
    });
    //Setting Payment
    Route::group(['middleware' => ['permission:setting_payment_method']], function () {
        Route::match(['get', 'post'], 'setting-payment/listing', [SettingPaymentController::class, 'listing'])->name('setting_payment_listing');
        Route::match(['get', 'post'], 'setting-payment/add', [SettingPaymentController::class, 'add'])->name('setting_payment_add');
        Route::match(['get', 'post'], 'setting-payment/edit/{setting_payment_id}', [SettingPaymentController::class, 'edit'])->name('setting_payment_edit');
        Route::match(['get', 'post'], 'setting-payment/delete', [SettingPaymentController::class, 'delete'])->name('setting_payment_delete');
    });
    //Setting warehouse
    Route::group(['middleware' => ['permission:setting_warehouse']], function () {
        Route::match(['get', 'post'], 'setting-warehouse/listing', [SettingWarehouseController::class, 'listing'])->name('setting_warehouse_listing');
        Route::match(['get', 'post'], 'setting-warehouse/add', [SettingWarehouseController::class, 'add'])->name('setting_warehouse_add');
        Route::match(['get', 'post'], 'setting-warehouse/edit/{id}', [SettingWarehouseController::class, 'edit'])->name('setting_warehouse_edit');
        Route::match(['get', 'post'], 'setting-warehouse/delete', [SettingWarehouseController::class, 'delete'])->name('setting_warehouse_delete');
        Route::get('get_warehouse_listing_by_id/{id}', [SettingWarehouseController::class, 'view_setting_warehouse_by_id'])->name('get_warehouse_listing_by_id');
    });
    //Setting Product size
    Route::group(['middleware' => ['permission:setting_product_size']], function () {
        Route::match(['get', 'post'], 'setting-product-size/listing', [SettingProductSizeController::class, 'listing'])->name('setting_product_size_listing');
        Route::match(['get', 'post'], 'setting-product-size/add', [SettingProductSizeController::class, 'add'])->name('setting_product_size_add');
        Route::match(['get', 'post'], 'setting-product-size/edit/{id}', [SettingProductSizeController::class, 'edit'])->name('setting_product_size_edit');
    });
    //Setting Raw Material
        Route::match(['get', 'post'], 'setting_raw_material/listing', [SettingRawMaterialController::class, 'listing'])->name('setting_raw_material_listing');
        Route::match(['get', 'post'], 'setting_raw_material/add', [SettingRawMaterialController::class, 'add'])->name('setting_raw_material_add');
        Route::match(['get', 'post'], 'setting_raw_material/edit/{id}', [SettingRawMaterialController::class, 'edit'])->name('setting_raw_material_edit');
    //Setting Raw Material Category
        Route::match(['get', 'post'], 'setting_raw_material_category/listing_category', [SettingRawMaterialCategoryController::class, 'listing_category'])->name('setting_raw_material_category_listing');
        Route::match(['get', 'post'], 'setting_raw_material_category/add_category', [SettingRawMaterialCategoryController::class, 'add_category'])->name('setting_raw_material_category_add');
        Route::match(['get', 'post'], 'setting_raw_material_category/edit_category/{id}', [SettingRawMaterialCategoryController::class, 'edit_category'])->name('setting_raw_material_category_edit');

    //Setting Tree Age
    Route::group(['middleware' => ['permission:setting_tree_age']], function () {
        Route::match(['get', 'post'], 'setting_tree_age/add', [SettingTreeAgeController::class, 'add'])->name('setting_tree_age_add');
        Route::match(['get', 'post'], 'setting_tree_age/edit/{id}', [SettingTreeAgeController::class, 'edit'])->name('setting_tree_age_edit');
    // Route::get('setting-tree-age/add_view', [SettingTreeAgeController::class, 'add_view'])->name('setting_tree_age_add_form');
    // Route::match(['get', 'post'],'setting-tree-age/add', [SettingTreeAgeController::class, 'add'])->name('setting_tree_age_add');
        Route::match(['get', 'post'], 'setting_tree_age/listing', [SettingTreeAgeController::class, 'listing'])->name('setting_tree_age_listing');
        Route::match(['get', 'post'], 'setting_tree_age/pointer', [SettingTreeAgeController::class, 'pointer'])->name('setting_tree_age_pointer');
    });

    /**** Admin Section ****/
    Route::group(['middleware' => ['permission:admin_listing']], function () {
        // Admin Listing
        Route::match(['get', 'post'], 'admin/listing', [AdminController::class, 'listing'])->name('admin_listing');
    });
    Route::group(['middleware' => ['permission:admin_manage']], function () {
        // Admin Manage
        Route::match(['get', 'post'], 'admin/add', [AdminController::class, 'add'])->name('admin_add');
        Route::match(['get', 'post'], 'admin/edit/{id}', [AdminController::class, 'edit'])->name('admin_edit');
        Route::match(['get', 'post'], 'admin/assign_permission/{id}', [AdminController::class, 'assign_permission'])->name('assign_permission');
        Route::match(['get', 'post'], 'admin/update_user_to_admin', [AdminController::class, 'update_user_to_admin'])->name('update_user_to_admin');
        Route::match(['get', 'post'], 'ajax_check_user_mobile', [AdminController::class, 'ajax_check_user_mobile'])->name('ajax_check_user_mobile');
        Route::match(['get', 'post'], 'ajax-check-user-slug', [AdminController::class, 'ajax_check_user_slug'])->name('ajax_check_user_slug');
        Route::match(['get', 'post'], 'ajax-check-user-unique-code', [AdminController::class, 'ajax_check_user_unique_code'])->name('ajax_check_user_unique_code');
        Route::match(['get', 'post'], 'ajax_get_farm_manager_list', [AdminController::class, 'ajax_get_farm_manager_list'])->name('ajax_get_farm_manager_list');
        Route::post('/ajax_check_email', [AdminController::class, 'ajax_check_email'])->name('ajax_check_email');
        Route::post('status', [AdminController::class, 'status'])->name('admin_status');
        Route::match(['get', 'post'], 'admin/change_password_by_super_admin/{id}', [UserController::class, 'change_password_by_super_admin'])->name('user_change_password_by_super_admin');
        Route::match(['get', 'post'], 'ajax_get_worker_list_sel_by_company', [WorkerController::class, 'ajax_get_worker_list_sel_by_company'])->name('ajax_get_worker_list_sel_by_company');
        Route::match(['get', 'post'], 'ajax_get_worker_list_sel_by_company_without_user_id', [WorkerController::class, 'ajax_get_worker_list_sel_by_company_without_user_id'])->name('ajax_get_worker_list_sel_by_company_without_user_id');

    });
    Route::group(['middleware' => ['permission:admin_role_listing']], function () {
        // Admin Role Listing
        Route::match(['get', 'post'], 'admin_role/listing', [AdminRoleController::class, 'listing'])->name('admin_role_listing');
    });
    Route::group(['middleware' => ['permission:admin_role_manage']], function () {
        // Admin Role Manage
        Route::match(['get', 'post'], 'admin_role/add', [AdminRoleController::class, 'add'])->name('admin_role_add');
        Route::match(['get', 'post'], 'admin_role/edit/{id}', [AdminRoleController::class, 'edit'])->name('admin_role_edit');
    });

    /**** Customer Section ****/
    Route::group(['middleware' => ['permission:customer_listing']], function () {
        //customer Listing
        Route::match(['get', 'post'], 'customer/listing', [CustomerController::class, 'listing'])->name('customer_listing');
        Route::match(['get', 'post'], 'customer/credit_history/{id}', [CustomerController::class, 'customer_credit_detail'])->name('customer_credit_detail');
    });

    Route::group(['middleware' => ['permission:customer_manage']], function () {
        //customer manage
        Route::match(['get', 'post'], 'customer/add', [CustomerController::class, 'add'])->name('customer_add');
        Route::match(['get', 'post'], 'customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer_edit');
        Route::match(['get', 'post'], 'customer/invoice-history', [CustomerController::class, 'assign_permission'])->name('customer_invoice_history');
        Route::match(['get', 'post'], 'customer/delivery-order-history', [CustomerController::class, 'update_user_to_admin'])->name('customer_deliver_order_history');
        Route::match(['get', 'post'], '/ajax_search_customer_mobile', [CustomerController::class, 'ajax_search_customer_by_mobile_no'])->name('ajax_search_customer_by_mobile_no');
        Route::match(['get', 'post'], '/ajax_search_customer_by_customer_name', [CustomerController::class, 'ajax_search_customer_by_customer_name'])->name('ajax_search_customer_by_customer_name');
        Route::match(['get', 'post'], '/ajax_search_customer_pic_by_id', [CustomerPicController::class, 'ajax_search_customer_pic_by_id'])->name('ajax_search_customer_pic_by_id');
        Route::match(['get', 'post'], 'customer/credit_adjustment/{id}', [CustomerController::class, 'credit_adjustment'])->name('credit_adjustment');
        Route::match(['get', 'post'], 'customer/inactivate', [CustomerController::class, 'inactivate'])->name('customer_inactivate');
        Route::match(['get', 'post'], 'customer/activate', [CustomerController::class, 'activate'])->name('customer_activate');
    });

    Route::group(['middleware' => ['permission:customer_term_listing']], function () {
        //customer term listing
        Route::match(['get', 'post'], 'customer-term/listing', [CustomerTermController::class, 'listing'])->name('customer_term_listing');
    });

    Route::group(['middleware' => ['permission:customer_term_manage']], function () {
        //customer term manage
        Route::match(['get', 'post'], 'customer-term/add', [CustomerTermController::class, 'add'])->name('customer_term_add');
        Route::match(['get', 'post'], 'customer-term/edit/{id}', [CustomerTermController::class, 'edit'])->name('customer_term_edit');
        Route::post('customer-term/delete', [CustomerTermController::class, 'delete'])->name('customer_term_delete');
    });

    //  Customer Category
    Route::group(['middleware' => ['permission:customer_category_listing']], function () {
        Route::match(['get', 'post'], 'customer-category/listing', [CustomerCategoryController::class, 'listing'])->name('customer_category_listing');
    });
    Route::group(['middleware' => ['permission:customer_category_manage']], function () {
        Route::match(['get', 'post'], 'customer-category/add', [CustomerCategoryController::class, 'add'])->name('customer_category_add');
        Route::match(['get', 'post'], 'customer-category/edit/{id}', [CustomerCategoryController::class, 'edit'])->name('customer_category_edit');
        Route::match(['get', 'post'], 'customer-category/delete', [CustomerCategoryController::class, 'delete'])->name('customer_category_delete');
    });

    Route::match(['get', 'post'], '/ajax_get_image_by_do_item_id', [DeliveryOrderController::class, 'ajax_get_image_by_do_item_id'])->name('ajax_get_image_by_do_item_id');
    Route::match(['get', 'post'], '/ajax_delete_image_by_media_do_item_id', [DeliveryOrderController::class, 'ajax_delete_image_by_media_do_item_id'])->name('ajax_delete_image_by_media_do_item_id');

    // ** Sync Manangement
    Route::group(['middleware' => ['permission:sync_listing']], function () {
        //  Sync List
        Route::match(['get', 'post'], 'sync-list/delivery-order', [SyncListController::class, 'SyncDeliveryOrder'])->name('sync_delivery_order_listing');
        Route::match(['get', 'post'], 'sync-list/customer', [SyncListController::class, 'SyncCustomer'])->name('synccustomer_listing');
        Route::match(['get', 'post'], 'sync-list/formula_usage', [SyncListController::class, 'SyncFormulaUsage'])->name('sync_formula_usage_listing');
        Route::match(['get', 'post'], 'sync-list/company_expense', [SyncListController::class, 'SyncCompanyExpense'])->name('sync_company_expense_listing');
        Route::match(['get', 'post'], 'sync-list/daily', [SyncListController::class, 'SyncDaily'])->name('daily_listing');
        Route::get('/get_items_from_do/{id}', [SyncListController::class, 'view_do_items'])->name('get_items_from_do');

        Route::match(['get', 'post'], 'sync-list/listing', [SyncListController::class, 'listing'])->name('sync_listing');
        Route::get('/get_sync_do/{id}', [SyncListController::class, 'view_sync_do'])->name('get_sync_do');
        Route::get('/get_sync_cust/{id}', [SyncListController::class, 'view_sync_customer'])->name('get_sync_customer');
        Route::get('/get_sync_daily/{id}', [SyncListController::class, 'view_sync_daily'])->name('get_sync_daily');
        Route::get('/get_sync_formula_usage/{id}', [SyncListController::class, 'view_sync_formula_usage'])->name('get_sync_formula_usage');
        Route::get('/get_sync_company_expense/{id}', [SyncListController::class, 'view_sync_company_expense'])->name('get_sync_company_expense');

        Route::match(['get', 'post'], 'sync-list/delivery-order-items', [SyncListController::class, 'SyncDeliveryOrderItems'])->name('syncDeliveryOrderItems_listing');
        Route::match(['get', 'post'], 'view-sync-do-pdf/{id}', [DeliveryOrderController::class, 'view_pdf'])->name('view_sync_do_pdf');
    });
    Route::group(['middleware' => ['permission:sync_zip_file']], function () {
        Route::match(['get', 'post'], 'sync-zip-file', [SyncListController::class, 'sync_zip_file'])->name('sync_zip_file');
        Route::match(['get', 'post'], 'sync-list/revert-sync', [SyncListController::class, 'revert_sync'])->name('revert_sync');
        Route::match(['get', 'post'], 'sync-list/resync', [SyncListController::class, 'resync'])->name('resync');
    });



    // ** Product
    //  Product
    Route::group(['middleware' => ['permission:product_listing']], function () {
        Route::match(['get', 'post'], 'product/listing', [ProductController::class, 'listing'])->name('product_listing');
    });
    Route::group(['middleware', ['permission:product_manage']], function () {
        Route::match(['get', 'post'], 'product/add', [ProductController::class, 'add'])->name('product_add');
        Route::match(['get', 'post'], 'product/edit/{id}', [ProductController::class, 'edit'])->name('product_edit');
        Route::match(['get', 'post'], 'product/delete', [ProductController::class, 'delete'])->name('product_delete');
        Route::post('/ajax_search_product_by_name', [ProductController::class, 'ajax_search_by_name'])->name('ajax_product_by_name');
        Route::post('/ajax_get_product_details', [ProductController::class, 'ajax_get_product_detail'])->name('ajax_get_product_detail');
        Route::post('/ajax_check_product_sku', [ProductController::class, 'ajax_check_product_sku'])->name('ajax_check_product_sku');
    });

    //Product Category
    Route::group(['middleware' => ['permission:product_category_listing']], function () {
        Route::match(['get', 'post'], 'product-category/listing', [ProductCategoryController::class, 'listing'])->name('product_category_listing');
    });
    Route::group(['middleware' => ['permission:product_category_manage']], function () {
        Route::match(['get', 'post'], 'product-category/add', [ProductCategoryController::class, 'add'])->name('product_category_add');
        Route::match(['get', 'post'], 'product-category/edit/{product_category_id}', [ProductCategoryController::class, 'edit'])->name('product_category_edit');
        Route::match(['get', 'post'], 'product-category/delete', [ProductCategoryController::class, 'delete'])->name('product_category_delete');
    });

    // Product Stock Transfer
    // Product Stock Warehouse
    Route::group(['middleware' => ['permission:product_stock_listing']], function () {
        // Product Stock Transfer Listing
        Route::match(['get', 'post'], 'product-stock-transfer/listing', [ProductStockTransferController::class, 'listing'])->name('product_stock_transfer_listing');
        // Product Stock Warehouse Listing
        Route::match(['get', 'post'], 'product-stock-warehouse/listing', [ProductStockWarehouseController::class, 'listing'])->name('product_stock_warehouse_listing');
    });
    Route::get('/stock/do/{no}', [ProductStockTransferController::class, 'find_do_by_no'])->name('find_do_no');
    // Product Stock Transfer Manage
    Route::group(['middleware' => ['permission:product_stock_manage']], function () {
        Route::match(['get', 'post'], 'product-stock-transfer/add', [ProductStockTransferController::class, 'add'])->name('product_stock_transfer_add');
        // Product Stock Warehouse Manage
        Route::match(['get', 'post'], 'product-stock-warehouse/add', [ProductStockWarehouseController::class, 'add'])->name('product_stock_warehouse_add');
    });
    Route::match(['get', 'post'], 'product-stock-transfer/ajax_get_setting_size_by_product_id', [ProductStockTransferController::class, 'ajax_get_setting_size_by_product_id'])->name('ajax_get_setting_size_by_product_id');
    Route::match(['get', 'post'], 'product-stock-transfer/ajax_get_current_warehouse_qty', [ProductStockTransferController::class, 'ajax_get_current_warehouse_qty'])->name('ajax_get_current_warehouse_qty');
    Route::match(['get', 'post'], 'product-stock-transfer/ajax_get_product_sel_by_company_land_id', [ProductStockTransferController::class, 'ajax_get_product_sel_by_company_land_id'])->name('ajax_get_product_sel_by_company_land_id');
    Route::match(['get', 'post'], 'product-stock-transfer/ajax_get_product_by_product_category_id', [ProductStockTransferController::class, 'ajax_get_product_by_product_category_id'])->name('ajax_get_product_by_product_category_id');
    Route::match(['get', 'post'], 'product-stock-transfer/ajax_get_product_by_product_category_id_land_id', [ProductStockTransferController::class, 'ajax_get_product_by_product_category_id_land_id'])->name('ajax_get_product_by_product_category_id_land_id');


    //  Product Tag
    Route::group(['middleware' => ['permission:product_tag_listing']], function () {
        Route::match(['get', 'post'], 'product-tag/listing', [ProductTagController::class, 'listing'])->name('product_tag_listing');
    });
    Route::group(['middleware' => ['permission:product_tag_manage']], function () {
        Route::match(['get', 'post'], 'product-tag/add', [ProductTagController::class, 'add'])->name('product_tag_add');
        Route::match(['get', 'post'], 'product-tag/edit/{id}', [ProductTagController::class, 'edit'])->name('product_tag_edit');
        Route::match(['get', 'post'], 'product-tag/delete', [ProductTagController::class, 'delete'])->name('product_tag_delete');
    });

    //Supplier
    Route::group(['middleware' => ['permission:supplier_listing']], function () {
        Route::match(['get', 'post'], 'supplier/listing', [SupplierController::class, 'listing'])->name('supplier_listing');
    });


    Route::group(['middleware' => ['permission:supplier_manage']], function () {
        Route::match(['get', 'post'], 'supplier/add', [SupplierController::class, 'add'])->name('supplier_add');
        Route::match(['get', 'post'], 'supplier/edit/{id}', [SupplierController::class, 'edit'])->name('supplier_edit');
    });
    Route::match(['get', 'post'], '/ajax_get_raw_material_by_raw_material_category_id', [SupplierController::class, 'ajax_get_raw_material_by_raw_material_category_id'])->name('ajax_get_raw_material_by_raw_material_category_id');
    Route::match(['get', 'post'], '/ajax_get_supplier_by_company_id', [SupplierController::class, 'ajax_get_supplier_by_company_id'])->name('ajax_get_supplier_by_company_id');
    Route::match(['get', 'post'], '/ajax_get_raw_material_details', [SupplierController::class, 'ajax_get_raw_material_details'])->name('ajax_get_raw_material_details');

    //Supplier Delivery Order
    Route::group(['middleware' => ['permission:supplier_delivery_order_listing']], function () {
        Route::match(['get', 'post'], 'supplier_delivery_order/listing', [SupplierDeliveryOrderController::class, 'listing'])->name('supplier_do_listing');
    });

    Route::group(['middleware' => ['permission:supplier_delivery_order_manage']], function () {
        Route::match(['get', 'post'], 'supplier_delivery_order/add', [SupplierDeliveryOrderController::class, 'add'])->name('supplier_do_add');
        Route::match(['get', 'post'], 'supplier_delivery_order/edit/{id}', [SupplierDeliveryOrderController::class, 'edit'])->name('supplier_do_edit');
        Route::match(['get', 'post'], 'supplier_delivery_order/supplier_do_return/{id}', [SupplierDeliveryOrderController::class, 'return_supplier_do'])->name('supplier_do_return');
    });
    Route::group(['middleware' => ['permission:supplier_delivery_order_delete']], function () {
        Route::match(['get', 'post'], 'supplier_delivery_order/delete', [SupplierDeliveryOrderController::class, 'delete'])->name('supplier_do_delete');
    });
        Route::match(['get', 'post'], '/ajax_search_supplier_by_name', [SupplierController::class, 'ajax_search_supplier_by_name'])->name('ajax_search_supplier_by_name');

        Route::match(['get', 'post'], 'ajax_get_supplier_by_upkeep', [SupplierController::class, 'ajax_get_supplier_by_upkeep'])->name('ajax_get_supplier_by_upkeep');

    // Raw Material Company
    Route::group(['middleware' => ['permission:raw_material_company_listing']], function () {
        Route::match(['get', 'post'], 'raw_material_company/listing', [RawMaterialCompanyController::class, 'listing'])->name('raw_material_company_listing');
    });

    Route::group(['middleware' => ['permission:raw_material_company_manage']], function () {
        Route::match(['get', 'post'], 'raw_material_company/add', [RawMaterialCompanyController::class, 'add'])->name('raw_material_company_add');
        Route::match(['get', 'post'], 'raw_material_company/edit/{id}', [RawMaterialCompanyController::class, 'edit'])->name('raw_material_company_edit');
    });
        Route::post('/ajax_check_existing_raw_material_company', [RawMaterialCompanyController::class, 'ajax_check_existing_raw_material_company'])->name('ajax_check_existing_raw_material_company');
        Route::match(['get', 'post'], 'setting_formula_item/ajax_get_by_company_sel', [RawMaterialCompanyController::class, 'ajax_get_by_company_sel'])->name('ajax_get_by_company_sel');
    // Raw Material Company Usage
    Route::group(['middleware' => ['permission:raw_material_company_usage_listing']], function () {
        Route::match(['get', 'post'], 'raw_material_company_usage/listing', [RawMaterialCompanyUsageController::class, 'listing'])->name('raw_material_company_usage_listing');
        Route::get('/ajax_get_raw_material_company_usage_listing/{id}', [RawMaterialCompanyUsageController::class, 'view_raw_material_company_usage_listing'])->name('get_raw_material_company_usage');
    });
    Route::group(['middleware' => ['permission:raw_material_company_usage_manage']], function () {
        Route::match(['get', 'post'], 'raw_material_company_usage/stock_in', [RawMaterialCompanyUsageController::class, 'stock_in'])->name('raw_material_company_usage_stock_in');
    });
        Route::match(['get', 'post'], '/ajax_get_existing_raw_material_company', [RawMaterialCompanyUsageController::class, 'ajax_get_existing_raw_material_company'])->name('ajax_get_existing_raw_material_company');
        Route::match(['get', 'post'], '/ajax_check_existing_supplier_delivery_order_items', [RawMaterialCompanyUsageController::class, 'ajax_check_existing_supplier_delivery_order_items'])->name('ajax_check_existing_supplier_delivery_order_items');
    // Formula usage
    Route::group(['middleware' => ['permission:formula_usage_listing']], function () {
        Route::match(['get', 'post'], 'formula_usage/listing', [FormulaUsageController::class, 'listing'])->name('formula_usage_listing');
        Route::match(['get', 'post'], 'formula_usage/listing/{fomula_usage_id?}', [FormulaUsageController::class, 'listing_formula_by_id'])->name('formula_usage_listing_by_id');
        Route::match(['get', 'post'], 'formula_usage/listing/rm/{raw_material_id}', [FormulaUsageController::class, 'listing_by_rm_id'])->name('rm_usage_listing_by_id');
    });
    Route::group(['middleware' => ['permission:formula_usage_manage']], function () {
        Route::match(['get', 'post'], 'formula_usage/add', [FormulaUsageController::class, 'add'])->name('formula_usage_add');
        Route::match(['get', 'post'], 'formula_usage/edit/{id}', [FormulaUsageController::class, 'edit'])->name('formula_usage_edit');
        Route::match(['get', 'post'], 'formula_usage/delete', [FormulaUsageController::class, 'delete_formula_usage'])->name('delete_formula_usage');
    });
        Route::match(['get', 'post'], '/ajax_get_formula_by_category', [FormulaUsageController::class, 'ajax_get_formula_by_category'])->name('ajax_get_formula_by_category');
        Route::match(['get', 'post'], '/ajax_get_setting_formula_item', [FormulaUsageController::class, 'ajax_get_setting_formula_item'])->name('ajax_get_setting_formula_item');
        Route::match(['get', 'post'], '/ajax_find_tree_w_product_by_land', [FormulaUsageController::class, 'ajax_find_tree_w_product_by_land'])->name('ajax_find_tree_w_product_by_land');
        Route::match(['get', 'post'], '/ajax_find_formula_usage_item_details', [FormulaUsageController::class, 'ajax_find_formula_usage_item_details'])->name('ajax_find_formula_usage_item_details');
        Route::match(['get', 'post'], '/ajax_get_zone_by_land', [FormulaUsageController::class, 'ajax_get_zone_by_land'])->name('ajax_get_zone_by_land');
        Route::match(['get', 'post'], '/ajax_find_sync_formula_usage_item_details', [SyncListController::class, 'ajax_find_sync_formula_usage_item_details'])->name('ajax_find_sync_formula_usage_item_details');
    //  Delivery Order
    // Route::group(['middleware' => ['subscription:delivery_order_module']], function () {
        Route::group(['middleware' => ['permission:delivery_order_listing']], function () {
            Route::match(['get', 'post'], 'delivery_order/listing', [DeliveryOrderController::class, 'listing'])->name('do_listing');
            Route::match(['get', 'post'], '/view_do/{id}/{encryption}', [DeliveryOrderController::class, 'view_pdf'])->name('do_pdf');
            Route::match(['get', 'post'], 'delivery_order_expense/listing', [DeliveryOrderExpenseController::class, 'listing'])->name('delivery_order_expense');
        });
        Route::group(['middleware' => ['permission:delivery_order_manage']], function () {
            Route::match(['get', 'post'], 'delivery_order/add', [DeliveryOrderController::class, 'add'])->name('do_add');
            Route::match(['get', 'post'], 'delivery_order/edit/{id}', [DeliveryOrderController::class, 'edit'])->name('do_edit');
            Route::match(['get', 'post'], 'delivery_order/delete', [DeliveryOrderController::class, 'delete'])->name('do_delete');
            Route::match(['get', 'post'], 'delivery_order/revert', [DeliveryOrderController::class, 'revert'])->name('do_revert');
            Route::post('/ajax_find_do_quantity_with_id', [DeliveryOrderController::class, 'ajax_find_do_quantity_with_id'])->name('ajax_find_do_quantity_with_id');
            Route::post('/ajax_find_delivery_with_customer_id', [DeliveryOrderController::class, 'ajax_find_delivery_with_customer_id'])->name('ajax_find_delivery_with_customer_id');
            Route::post('/ajax_find_invoice_with_customer_id', [InvoiceController::class, 'ajax_find_invoice_with_customer_id'])->name('ajax_find_invoice_with_customer_id');
            Route::post('/ajax_get_invoice_by_payment_url', [InvoiceController::class, 'ajax_get_invoice_by_payment_url'])->name('ajax_get_invoice_by_payment_url');
            Route::post('/ajax_get_mobile_no_by_id', [InvoiceController::class, 'ajax_get_mobile_no_by_id'])->name('ajax_get_mobile_no_by_id');
            Route::match(['get', 'post'], 'ajax_get_image_by_do_id', [DeliveryOrderController::class, 'ajax_get_image_by_do_id'])->name('ajax_get_image_by_do_id');
            Route::post('/ajax_find_pic_with_customer_id', [CustomerController::class, 'ajax_find_pic_with_customer_id'])->name('ajax_find_pic_with_customer_id');
            Route::match(['get', 'post'], 'delivery_order/issue_invoice', [DeliveryOrderController::class, 'issue_invoice'])->name('do_issue_invoice');
            Route::get('/get_invoice_from_do/{id}', [DeliveryOrderController::class, 'view_invoice_do'])->name('get_invoice_from_do');
            Route::post('/approve/do', [DeliveryOrderController::class, 'approve_do'])->name('do_approve');
            Route::match(['get', 'post'], '/delivery_order/add/expense', [DeliveryOrderController::class, 'add_expense'])->name('add_expense');
            Route::match(['get', 'post'], 'ajax_find_do_with_id', [DeliveryOrderController::class, 'ajax_find_do_with_id'])->name('ajax_find_do_with_id');
            Route::match(['get', 'post'], 'delivery_order/price_verification_approve_reject', [DeliveryOrderController::class, 'price_verification_approve_reject'])->name('price_verification_approve_reject');
            Route::get('/get_rejected_do/{id}', [DeliveryOrderController::class, 'get_rejected_do'])->name('get_rejected_do');
            Route::post('/ajax_get_mobile_no_by_do_id', [DeliveryOrderController::class, 'ajax_get_mobile_no_by_do_id'])->name('ajax_get_mobile_no_by_do_id');
            Route::post('/ajax_get_collect_code_from_do', [DeliveryOrderController::class, 'ajax_get_collect_code_from_do'])->name('ajax_get_collect_code_from_do');
            Route::post('/ajax_get_product_size_edit_do', [DeliveryOrderController::class, 'ajax_get_product_size_edit_do'])->name('ajax_get_product_size_edit_do');
            Route::match(['get', 'post'], 'export', [DeliveryOrderController::class, 'export'])->name('export');
        });
    // });

    //Collect
    // Route::group(['middleware' => ['subscription:collect_module']], function () {
        Route::group(['middleware' => ['permission:collect_listing']], function () {
            Route::match(['get', 'post'], 'collect/listing', [CollectController::class, 'listing'])->name('collect_listing');
            Route::get('get-sync-listing/{id}', [SyncListController::class, 'view_sync_listing'])->name('get_sync_listing');
        });

        Route::group(['middleware' => ['permission:collect_manage']], function () {
            Route::match(['get', 'post'], 'collect/delete', [CollectController::class, 'delete'])->name('collect_delete');
        });

        Route::group(['middleware' => ['permission:delivery_order_company_land_edit']], function () {
            Route::match(['get', 'post'], 'collect/edit', [CollectController::class, 'edit'])->name('collect_edit');
        });
    // });

    // ** Invoice
    Route::group(['middleware' => ['permission:invoice_listing']], function () {
        Route::match(['get', 'post'], 'invoice/listing', [InvoiceController::class, 'listing'])->name('invoice_listing');
    });
    Route::group(['middleware' => ['permission:invoice_manage']], function () {
        Route::match(['get', 'post'], 'invoice/add', [InvoiceController::class, 'add'])->name('invoice_add');
        Route::match(['get', 'post'], 'invoice/edit/{id}', [InvoiceController::class, 'edit'])->name('invoice_edit');
        Route::match(['get', 'post'], 'invoice/delete', [InvoiceController::class, 'delete'])->name('invoice_delete');
        Route::match(['get', 'post'], 'invoice/send', [InvoiceController::class, 'send_invoice'])->name('invoice_send');
        Route::match(['get', 'post'], 'invoice/listing/{id?}', [InvoiceController::class, 'listing_id'])->name('invoice_listing_id');
        Route::match(['get', 'post'], 'invoice/listing/price/{price}/{product}/{company_land_name}/{date}', [InvoiceController::class, 'invoice_by_price'])->name('invoice_by_price');
        // Route::match(['get', 'post'], 'view_invoice/{id}/{encryption}', [InvoiceController::class, 'view_invoice'])->name('view_invoice');
        Route::match(['get', 'post'], 'view_invoice_pdf/{id}/{encryption}', [InvoiceController::class, 'view_invoice_pdf'])->name('view_invoice_pdf');
        Route::match(['get', 'post'], 'invoice/import', [InvoiceController::class, 'import'])->name('invoice_import');
        Route::match(['get', 'post'], 'invoice/export_product_list', [InvoiceController::class, 'export_product_list'])->name('invoice_export_product_list');
    });
    // ** Invoice Partially Paid
    Route::group(['middleware' => ['permission:paid_invoice_edit']], function () {
        Route::match(['get', 'post'], 'paid_invoice/edit/{id}', [InvoiceController::class, 'paid_edit'])->name('paid_invoice_edit');
    });


    Route::group(['middleware' => ['permission:payment_approval']], function () {
        Route::match(['get', 'post'], 'invoice/approve-reject', [InvoiceController::class, 'approve_reject'])->name('invoice_approve_reject');
        Route::match(['get', 'post'], 'ajax_find_invoice_with_id', [InvoiceController::class, 'ajax_find_invoice_with_id'])->name('ajax_find_invoice_with_id');
    });

    //Payroll
    Route::group(['middleware' => ['permission:payroll_listing']], function () {
        Route::match(['get', 'post'], 'payroll/listing', [PayrollController::class, 'listing'])->name('payroll_listing');
        Route::match(['get', 'post'], 'payroll/view/{id}', [PayrollController::class, 'view_details'])->name('payroll_view');
    });

    Route::group(['middleware' => ['permission:payroll_manage']], function () {
        Route::match(['get', 'post'], 'payroll/generate', [PayrollController::class, 'generate'])->name('payroll_generate');
        Route::match(['get', 'post'], 'payroll/add/{id}', [PayrollController::class, 'add'])->name('payroll_add');
        Route::match(['get', 'post'], 'payroll/edit/{id}', [PayrollController::class, 'edit'])->name('payroll_edit');
        Route::match(['get', 'post'], 'payroll/status', [PayrollController::class, 'status'])->name('payroll_status');
        Route::match(['get', 'post'], 'payroll/export/{id}', [PayrollController::class, 'export'])->name('payroll_export');
    });
        Route::match(['get', 'post'], '/ajax_check_payroll_exists', [PayrollController::class, 'ajax_check_payroll_exists'])->name('ajax_check_payroll_exists');
        Route::match(['get', 'post'], '/ajax_get_payroll_item', [PayrollController::class, 'ajax_get_payroll_item'])->name('ajax_get_payroll_item');

    //Payroll Item
    Route::group(['middleware' => ['permission:payroll_item_listing']], function () {
        Route::match(['get', 'post'], 'payroll_item/listing', [PayrollItemController::class, 'listing'])->name('payroll_item_listing');
    });

    Route::group(['middleware' => ['permission:payroll_item_manage']], function () {
        Route::match(['get', 'post'], 'payroll_item/add', [PayrollItemController::class, 'add'])->name('payroll_item_add');
        Route::match(['get', 'post'], 'payroll_item/edit/{id}', [PayrollItemController::class, 'edit'])->name('payroll_item_edit');
        Route::match(['get', 'post'], 'payroll_item/delete', [PayrollItemController::class, 'delete'])->name('payroll_item_delete');
    });



    // Payment Url
    Route::group(['middleware' => ['permission:payment_url_listing']], function () {
        Route::match(['get', 'post'], 'payment-url/listing', [PaymentUrlController::class, 'listing'])->name('payment_url_listing');
    });

    Route::group(['middleware' => ['permission:payment_url_manage']], function () {
        Route::match(['get', 'post'], 'payment-url/add/{customer_id?}/{invoice_id?}', [PaymentUrlController::class, 'add'])->name('payment_url_add');
        Route::match(['get', 'post'], 'payment-url/approve-reject', [PaymentUrlController::class, 'approve_reject'])->name('payment_url_approve_reject');
        Route::match(['get', 'post'], 'payment-url/cancel', [PaymentUrlController::class, 'cancel'])->name('payment_url_cancel');
    });
    Route::match(['get', 'post'], 'ajax_find_payment_url_with_id', [PaymentUrlController::class, 'ajax_find_payment_url_with_id'])->name('ajax_find_payment_url_with_id');
    Route::post('/ajax_get_mobile_no_by_payment_url_id', [PaymentUrlController::class, 'ajax_get_mobile_no_by_payment_url_id'])->name('ajax_get_mobile_no_by_payment_url_id');


    Route::group(['middleware' => ['permission:setting_expense']], function () {
        Route::match(['get', 'post'], 'setting_expense/listing', [SettingExpenseController::class, 'listing'])->name('expense_listing');
        Route::match(['get', 'post'], 'setting_expense/add', [SettingExpenseController::class, 'add'])->name('expense_add');
        Route::match(['get', 'post'], 'setting_expense/edit/{id}', [SettingExpenseController::class, 'edit'])->name('expense_edit');
        Route::match(['get', 'post'], 'setting_expense/activation/{id}/{status}', [SettingExpenseController::class, 'expense_activation'])->name('expense_activation');
        Route::match(['get', 'post'], 'ads/ajax_expense_overwrite_detail_modal', [SettingExpenseController::class, 'ajax_get_expense_overwrite_detail_modal'])->name('expense_overwrite_detail_modal');

        //Setting Expense Overwrite
        Route::match(['get', 'post'], 'setting_expense_overwrite/overwrite/{company_id?}/{setting_expense_id?}', [SettingExpenseOverwriteController::class, 'overwrite'])->name('expense_overwrite');
        Route::post('setting_expense_overwrite/delete', [SettingExpenseOverwriteController::class, 'delete'])->name('expense_overwrite_delete');

        // Setting Expense Category
        Route::match(['get', 'post'], 'setting_expense_category/listing', [SettingExpenseCategoryController::class, 'listing'])->name('expense_category_listing');
        Route::match(['get', 'post'], 'setting_expense_category/add', [SettingExpenseCategoryController::class, 'add'])->name('expense_category_add');
        Route::match(['get', 'post'], 'setting_expense_category/edit/{id}', [SettingExpenseCategoryController::class, 'edit'])->name('expense_category_edit');

        // Setting Race
        Route::match(['get', 'post'], 'setting_race/listing', [SettingRaceController::class, 'listing'])->name('setting_race');
        Route::match(['get', 'post'], 'setting_race/add', [SettingRaceController::class, 'add'])->name('add');
        Route::match(['get', 'post'], 'setting_race/edit/{id}', [SettingRaceController::class, 'edit'])->name('edit');
        Route::match(['get', 'post'], 'setting_race/delete', [SettingRaceController::class, 'delete'])->name('delete');
    });

    // Setting Currency
    Route::match(['get', 'post'], 'setting_currency/listing', [SettingCurrencyController::class, 'listing'])->name('setting_currency');
    Route::match(['get', 'post'], 'setting_currency/add', [SettingCurrencyController::class, 'add'])->name('setting_currency_add');
    Route::match(['get', 'post'], 'setting_currency/edit/{id}', [SettingCurrencyController::class, 'edit'])->name('setting_currency_edit');
    Route::match(['get', 'post'], 'setting_currency/delete', [SettingCurrencyController::class, 'delete'])->name('setting_currency_delete');

    // Setting Reward
    Route::match(['get', 'post'], 'setting_reward/listing', [SettingRewardController::class, 'listing'])->name('setting_reward');
    Route::match(['get', 'post'], 'setting_reward/add', [SettingRewardController::class, 'add'])->name('setting_reward_add');
    Route::match(['get', 'post'], 'setting_reward/edit/{id}', [SettingRewardController::class, 'edit'])->name('setting_reward_edit');
    Route::match(['get', 'post'], 'setting_reward/delete', [SettingRewardController::class, 'delete'])->name('setting_reward_delete');
    Route::match(['get', 'post'], '/ajax_get_reward_sel_by_company', [WorkerController::class, 'ajax_get_reward_sel_by_company'])->name('ajax_get_reward_sel_by_company');

    // Setting Reward Category
    Route::match(['get', 'post'], 'setting_reward_category/listing', [SettingRewardCategoryController::class, 'listing'])->name('setting_reward_category');
    Route::match(['get', 'post'], 'setting_reward_category/add', [SettingRewardCategoryController::class, 'add'])->name('setting_reward_category_add');
    Route::match(['get', 'post'], 'setting_reward_category/edit/{id}', [SettingRewardCategoryController::class, 'edit'])->name('setting_reward_category_edit');
    Route::match(['get', 'post'], 'setting_reward_category/delete', [SettingRewardCategoryController::class, 'delete'])->name('setting_reward_category_delete');

    Route::match(['get', 'post'], 'ajax_get_expense_by_upkeep', [SettingExpenseController::class, 'ajax_get_expense_by_upkeep'])->name('ajax_get_expense_by_upkeep');
    Route::match(['get', 'post'], 'ajax_get_price_expense', [SettingExpenseController::class, 'ajax_get_price_expense'])->name('ajax_get_price_expense');
    Route::match(['get', 'post'], 'ajax_get_expense_by_staff_costing', [SettingExpenseController::class, 'ajax_get_expense_by_staff_costing'])->name('ajax_get_expense_by_staff_costing');

    //! Testing
    Route::get('/runningNo/{type}', [DeliveryOrderController::class, 'running'])->name('test_no');

    Route::group(['middleware' => ['permission:report_listing']], function () {
        Route::match(['get', 'post'], 'reporting/collect_do_variance', [ReportingController::class, 'collect_do_variance_report_yearly'])->name('collect_do_variance');
        Route::match(['get', 'post'], 'reporting/collect_do_variance_daily', [ReportingController::class, 'collect_do_variance_report_daily'])->name('collect_do_variance_daily');
        Route::match(['get', 'post'], 'reporting/collect_daily_report', [ReportingController::class, 'collect_daily_report'])->name('collect_daily_report');
        Route::match(['get', 'post'], 'reporting/do_daily_report', [ReportingController::class, 'do_daily_report'])->name('do_daily_report');
        Route::match(['get', 'post'], 'reporting/invoice_daily_report/{year?}/{month?}/{company_id?}/{land_id?}/{user_id?}', [ReportingController::class, 'invoice_daily_report'])->name('invoice_daily_report');
        Route::match(['get', 'post'], 'reporting/invoice_monthly_report', [ReportingController::class, 'invoice_report_monthly'])->name('invoice_monthly_report');
        Route::match(['get', 'post'], 'reporting/sales_summary_by_product_report', [ReportingController::class, 'sales_summary_by_product_report'])->name('sales_summary_by_product_report');
        Route::match(['get', 'post'], 'reporting/sales_summary_by_product_report_company', [ReportingController::class, 'sales_summary_by_product_report_company'])->name('sales_summary_by_product_report_company');
        Route::match(['get', 'post'], 'reporting/sales_summary_by_product_report_company_no_grade', [ReportingController::class, 'sales_summary_by_product_report_company_no_grade'])->name('sales_summary_by_product_report_company_no_grade');
        Route::match(['get', 'post'], 'reporting/product_detail_report', [ReportingController::class, 'product_detail_report'])->name('product_detail_report');
        Route::match(['get', 'post'], 'reporting/sales_summary_by_farm_report', [ReportingController::class, 'sales_summary_by_farm_report'])->name('sales_summary_by_farm_report');
        Route::match(['get', 'post'], 'reporting/farm_detail_report/{id}', [ReportingController::class, 'detail_farm_sales_summary_report'])->name('farm_detail_report');
        Route::match(['get', 'post'], 'reporting/differentiate', [ReportingController::class, 'differentiate_report_2'])->name('differentiate_report');
        Route::match(['get', 'post'], 'reporting/expense_report', [ReportingController::class, 'expense_report'])->name('expense_report');
        Route::match(['get', 'post'], 'reporting/expense_detail_report/{year}/{month}', [ReportingController::class, 'detail_expense_report'])->name('expense_detail_report');
        Route::match(['get', 'post'], 'reporting/message_template', [ReportingController::class, 'message_template_report'])->name('message_template_report');
        Route::match(['get', 'post'], 'reporting/message_template_by_year', [ReportingController::class, 'message_template_report_by_year'])->name('message_template_report_by_year');
        Route::match(['get', 'post'], 'reporting/message_template_by_month/{year}/{month}', [ReportingController::class, 'message_template_report_by_month'])->name('message_template_report_by_month');
        Route::match(['get', 'post'], 'reporting/message_template_by_day/{year}/{month}/{day}/{id}', [ReportingController::class, 'message_template_report_by_day'])->name('message_template_report_by_day');
        Route::match(['get', 'post'], 'reporting/sync_attendance_report', [ReportingController::class, 'sync_attendance_report'])->name('sync_attendance_report');
        Route::match(['get', 'post'], 'reporting/listing_invoice_daily', [ReportingController::class, 'listing_invoice_daily'])->name('listing_invoice_daily');
        Route::match(['get', 'post'], 'reporting/average_summary_report', [ReportingController::class, 'average_summary_report'])->name('average_summary_report');
        Route::match(['get', 'post'], 'reporting/sales_analysis_do', [ReportingController::class, 'sales_analysis_do'])->name('sales_analysis_do');
        //Claim Reporting
        Route::match(['get', 'post'], 'reporting/claim_report', [ReportingController::class, 'claim_report'])->name('claim_report');
        Route::match(['get', 'post'], 'reporting/claim_detail_report/{year}/{month}/{company_id}/{claim_status_id}', [ReportingController::class, 'claim_detail_report'])->name('claim_detail_report');
        //Claim Pending Reporting
        Route::match(['get', 'post'], 'reporting/claim_pending_report', [ClaimPendingReportController::class, 'claim_pending_report'])->name('claim_pending_report');
        Route::match(['get', 'post'], 'reporting/claim_pending_detail_report/{year}/{month}/{company_id}/{farm_manager}', [ClaimPendingReportController::class, 'claim_pending_detail_report_admin'])->name('claim_pending_detail_report_admin');
        Route::match(['get', 'post'], 'reporting/claim_pending_detail_report/{year}/{month}/{company_id}', [ClaimPendingReportController::class, 'claim_pending_detail_report_superadmin'])->name('claim_pending_detail_report_superadmin');

        //Formula Usage Report
        Route::match(['get', 'post'], 'reporting/formula_usage_report_sa', [ReportingController::class, 'formula_usage_report_sa'])->name('formula_usage_report_sa');
        Route::match(['get', 'post'], 'reporting/formula_usage_report_sa_detail/{year}/{month}/{setting_formula_category_id}', [ReportingController::class, 'formula_usage_report_sa_detail'])->name('formula_usage_report_sa_detail');
        Route::match(['get', 'post'], 'reporting/formula_usage_report_admin', [ReportingController::class, 'formula_usage_report_admin'])->name('formula_usage_report_admin');
        Route::match(['get', 'post'], 'reporting/formula_usage_report_admin_detail/{year}/{month}/{setting_formula_category_id}', [ReportingController::class, 'formula_usage_report_admin_detail'])->name('formula_usage_report_admin_detail');

        //Company Expense Report
        Route::match(['get', 'post'], 'reporting/company_expense', [ReportingController::class, 'company_expense_report'])->name('company_expense');
        Route::match(['get', 'post'], 'reporting/company_expense_detail_report/{month?}/{setting_expense_category_id?}', [ReportingController::class, 'detail_company_expense_report'])->name('company_expense_detail');
        Route::match(['get', 'post'], 'reporting/company_expense_report_land_product/{company_land_id?}/{setting_expense_category_id?}/{year?}/{month_num?}', [ReportingController::class, 'company_expense_report_land_product'])->name('company_expense_report_land_product');
        Route::match(['get', 'post'], 'reporting/company_expense_report_land_product_total/{company_land_id?}/{setting_expense_category_id?}/{year?}', [ReportingController::class, 'company_expense_report_land_product_total'])->name('company_expense_report_land_product_total');

        // //Company Expense Report-Admin
        // Route::match(['get', 'post'], 'reporting/company_expense_reporting', [ReportingController::class, 'company_expense_reporting'])->name('company_expense_reporting');
        // Route::match(['get', 'post'], 'reporting/company_expense_reporting_details/{month?}/{setting_expense_category_id?}', [ReportingController::class, 'company_expense_reporting_details'])->name('company_expense_reporting_details');

        //Company Land Tree Report
        Route::match(['get', 'post'], 'reporting/company_land_tree_report', [ReportingController::class, 'company_land_tree_report'])->name('company_land_tree_report');
        Route::match(['get', 'post'], 'reporting/company_land_tree_report_detail/{company_land_id?}', [CompanyLandTreeController::class, 'company_land_tree_report_detail'])->name('company_land_tree_report_detail');
        Route::match(['get', 'post'], 'reporting/company_land_tree_report_sick_tree_detail/{company_land_id?}/{product_id?}', [CompanyLandTreeController::class, 'company_land_tree_report_sick_tree_detail'])->name('company_land_tree_report_sick_tree_detail');
        Route::match(['get', 'post'], 'reporting/company_land_tree_pointer_age_report_detail/{company_id?}', [CompanyLandTreeController::class, 'company_land_tree_pointer_age_report_detail'])->name('company_land_tree_pointer_age_report_detail');

        //Farm Manager Worker Expense Report
        Route::match(['get', 'post'], 'reporting/farm_manager_worker_expense', [ReportingController::class, 'farm_manager_worker_expense_report'])->name('farm_manager_worker_expense');
        Route::match(['get', 'post'], 'reporting/farm_manager_worker_expense_detail/{year?}/{month?}/{company_id?}/{user_id?}', [ReportingController::class, 'farm_manager_worker_expense_report_detail'])->name('farm_manager_worker_expense_detail');

        //Budget Report
        Route::match(['get', 'post'], 'reporting/budget_report', [ReportingController::class, 'budget_report'])->name('budget_report');
        Route::match(['get', 'post'], 'reporting/budget_report_detail/{company_id?}/{year?}', [ReportingController::class, 'budget_report_detail'])->name('budget_report_detail');

        //Tree Target Report
        Route::match(['get', 'post'], 'reporting/tree_target_report', [ReportingController::class, 'tree_target_report'])->name('tree_target_report');


        //Forecast Report
        Route::match(['get', 'post'], 'reporting/forecast_report', [ReportingController::class, 'forecast_report'])->name('forecast_report');

        //Warehouse Report
        Route::match(['get', 'post'], 'reporting/warehouse_reporting', [WarehouseReportingController::class, 'warehouse_reporting'])->name('warehouse_reporting');

        //Profit & Loss Reporting
        Route::match(['get', 'post'], '/reporting/profit_loss_reporting/listing', [ProfitLossReportingController::class, 'profit_loss_reporting'])->name('profit_loss_reporting');
        Route::match(['get', 'post'], '/reporting/profit_loss_reporting_detail/{company_id?}/{setting_expense_id?}/{year?}', [ProfitLossReportingController::class, 'profit_loss_reporting_detail'])->name('profit_loss_reporting_detail');
        Route::match(['get', 'post'], '/reporting/profit_loss_reporting_detail_by_land/{company_id?}/{company_land_id?}/{setting_expense_id?}/{year?}', [ProfitLossReportingController::class, 'profit_loss_reporting_detail_by_land'])->name('profit_loss_reporting_detail_by_land');
        Route::match(['get', 'post'], '/reporting/profit_loss_y2y_reporting_detail/{company_id?}/{setting_expense_id?}/{year?}', [ProfitLossReportingController::class, 'profit_loss_y2y_reporting_detail'])->name('profit_loss_y2y_reporting_detail');
        Route::match(['get', 'post'], '/reporting/profit_loss_y2y_reporting_detail_by_land/{company_id?}/{company_land_id?}/{setting_expense_id?}/{year?}', [ProfitLossReportingController::class, 'profit_loss_y2y_reporting_detail_by_land'])->name('profit_loss_y2y_reporting_detail_by_land');
        Route::match(['get', 'post'], '/reporting/profit_loss_m2m_reporting_detail/{company_id?}/{setting_expense_id?}/{year?}/{month?}', [ProfitLossReportingController::class, 'profit_loss_m2m_reporting_detail'])->name('profit_loss_m2m_reporting_detail');
        Route::match(['get', 'post'], '/reporting/profit_loss_m2m_reporting_detail_by_land/{company_id?}/{company_land_id?}/{setting_expense_id?}/{year?}/{month?}', [ProfitLossReportingController::class, 'profit_loss_m2m_reporting_detail_by_land'])->name('profit_loss_m2m_reporting_detail_by_land');

        //Budget Estimated Reporting
        Route::match(['get', 'post'], 'reporting/budget_estimate_report/listing_reporting', [BudgetEstimateController::class, 'listing_reporting'])->name('budget_estimate_report_listing_reporting');
        Route::match(['get', 'post'], 'view_monthly_budget_estimate_report/{id}', [BudgetEstimateController::class, 'view_monthly_budget_estimate_report'])->name('view_monthly_budget_estimate_report');

        //Tree Pointer Reporting
        Route::match(['get', 'post'], 'reporting/tree_pointer_reporting', [TreePointerReportingController::class, 'tree_pointer_reporting'])->name('tree_pointer_reporting');
        Route::match(['get', 'post'], 'reporting/tree_pointer_reporting_details/{company_land_id?}/{setting_tree_age_upper?}/{setting_tree_age_lower?}', [TreePointerReportingController::class, 'tree_pointer_reporting_details'])->name('tree_pointer_reporting_details');
        Route::match(['get', 'post'], 'reporting/tree_pointer_reporting_details_total/{company_id?}/{setting_tree_age_upper?}/{setting_tree_age_lower?}', [TreePointerReportingController::class, 'tree_pointer_reporting_details_total'])->name('tree_pointer_reporting_details_total');

         //Worker Attendance Report
         Route::match(['get', 'post'], 'reporting/worker_attendance_report', [WorkerAttendanceReportController::class, 'worker_attendance_report'])->name('worker_attendance_report');

        //Supplier Expenses Report
        Route::match(['get', 'post'], 'reporting/supplier_expenses_report', [SupplierExpensesReportController::class, 'supplier_expenses_report'])->name('supplier_expenses_report');
        Route::match(['get', 'post'], 'reporting/supplier_expenses_report_detail/{year?}/{month?}/{supplier_id?}/{company_id?}', [SupplierExpensesReportController::class, 'supplier_expenses_report_detail'])->name('supplier_expenses_report_detail');
    });

    Route::match(['get', 'post'], 'reporting/ajax_get_land_product_size', [ReportingController::class, 'ajax_get_land_product_size'])->name('ajax_get_land_product_size');
    Route::match(['get', 'post'], 'reporting/ajax_get_users_by_land', [ReportingController::class, 'ajax_get_users_by_land'])->name('ajax_get_users_by_land');
    Route::match(['get', 'post'], 'ajax_get_products_multi_company', [ReportingController::class, 'ajax_get_products_multi_company'])->name('ajax_get_products_multi_company');
    Route::match(['get', 'post'], 'ajax_get_customer_list_by_company_id', [ReportingController::class, 'ajax_get_customer_list_by_company_id'])->name('ajax_get_customer_list_by_company_id');
    Route::match(['get', 'post'], 'ajax_get_product_sel_by_company_land_id', [ReportingController::class, 'ajax_get_product_sel_by_company_land_id'])->name('ajax_get_product_sel_by_company_land_id');
    Route::match(['get', 'post'], 'ajax_get_setting_size_by_product_id', [ReportingController::class, 'ajax_get_setting_size_by_product_id'])->name('ajax_get_setting_size_by_product_id');
    Route::match(['get', 'post'], 'ajax_get_land_by_company_id', [ReportingController::class, 'ajax_get_land_by_company_id'])->name('ajax_get_land_by_company_id');



    Route::match(['get', 'post'], 'ajax_customer_pic', [CustomerPicController::class, 'ajax_check_pic'])->name('ajax_check_pic');
    Route::match(['get', 'post'], 'ajax_check_pic_id', [CustomerPicController::class, 'ajax_check_pic_id'])->name('ajax_check_pic_id');

    //Setting Formula
    Route::group(['middleware' => ['permission:formula_listing']], function () {
        Route::match(['get', 'post'], 'setting_formula/listing/', [SettingFormulaController::class, 'listing'])->name('setting_formula_listing');
    });
    Route::group(['middleware' => ['permission:formula_manage']], function () {
        Route::match(['get', 'post'], 'setting_formula/edit/{id}', [SettingFormulaController::class, 'edit'])->name('setting_formula_edit');
        Route::match(['get', 'post'], 'setting_formula/add', [SettingFormulaController::class, 'add'])->name('setting_formula_add');
        Route::match(['get', 'post'], 'setting_formula/delete', [SettingFormulaController::class, 'delete'])->name('setting_formula_delete');
    });
    Route::match(['get', 'post'], 'ajax_get_rm_name_for_placeholder', [SettingFormulaController::class, 'ajax_get_rm_name_for_placeholder'])->name('ajax_get_rm_name_for_placeholder');

    //Setting Formula Category
    Route::match(['get', 'post'], 'setting_formula_category/listing', [SettingFormulaCategoryController::class, 'listing'])->name('setting_formula_category_listing');
    Route::match(['get', 'post'], 'setting_formula_category/edit/{id}', [SettingFormulaCategoryController::class, 'edit'])->name('setting_formula_category_edit');
    Route::match(['get', 'post'], 'setting_formula_category/add', [SettingFormulaCategoryController::class, 'add'])->name('setting_formula_category_add');
    Route::match(['get', 'post'], 'setting_formula_category/delete', [SettingFormulaCategoryController::class, 'delete'])->name('setting_formula_category_delete');


    //Setting Whatsapp Template
    Route::group(['middleware' => ['permission:setting_message_template']], function () {
    Route::match(['get', 'post'], 'message-template/listing', [MessageTemplateController::class, 'listing'])->name('message_template_listing');
    Route::match(['get', 'post'], 'message-template/add', [MessageTemplateController::class, 'add'])->name('message_template_add');
    Route::match(['get', 'post'], 'message-template/edit/{id}', [MessageTemplateController::class, 'edit'])->name('message_template_edit');
    Route::match(['get', 'post'], 'message-template/delete', [MessageTemplateController::class, 'delete'])->name('message_template_delete');
    Route::match(['get', 'post'], 'message-template/send_whatsapp_template/{id}/{slug}/{message_template_id}/{user_mobile?}', [MessageTemplateController::class, 'send_whatsapp_template'])->name('send_whatsapp_template');
    //Route::match(['get', 'post'], 'message-template/send_whatsapp_template/{invoice_id}/{id}/{slug}/{message_template_id}/{user_mobile?}', [MessageTemplateController::class, 'send_whatsapp_template'])->name('send_whatsapp_template');
    //Route::match(['get', 'post'], 'message-template/send_invoice/{id}/{slug}/{message_template_id}/{user_mobile?}', [MessageTemplateController::class, 'send_invoice'])->name('send_invoice');
    //Route::match(['get', 'post'], 'message-template/send_whatsapp_choosable/{id}/{slug}/{message_template_id}', [MessageTemplateController::class, 'send_whatsapp_choosable'])->name('send_whatsapp_choosable');
    // });


    //Setting Whatsapp Template Involve
    // Route::group(['middleware' => ['permission:setting_message_template']], function () {
    Route::match(['get', 'post'], 'message-template-involve/listing', [MessageTemplateInvolveController::class, 'listing'])->name('message_template_involve_listing');
    Route::match(['get', 'post'], 'message-template-involve/add', [MessageTemplateInvolveController::class, 'add'])->name('message_template_involve_add');
    Route::match(['get', 'post'], 'message-template-involve/edit/{id}', [MessageTemplateInvolveController::class, 'edit'])->name('message_template_involve_edit');
    Route::match(['get', 'post'], 'message-template-involve/delete', [MessageTemplateInvolveController::class, 'delete'])->name('message_template_involve_delete');
    });


    //  Worker
    Route::group(['middleware' => ['permission:worker_listing']], function () {
        Route::match(['get', 'post'], 'worker/listing', [WorkerController::class, 'listing'])->name('worker_listing');
    });
    Route::group(['middleware' => ['permission:worker_manage']], function () {
        Route::match(['get', 'post'], 'worker/add', [WorkerController::class, 'add'])->name('worker_add');
        Route::match(['get', 'post'], 'worker/edit/{id}', [WorkerController::class, 'edit'])->name('worker_edit');
        Route::match(['get', 'post'], 'worker/delete', [WorkerController::class, 'delete'])->name('worker_delete');
        Route::match(['get', 'post'], '/ajax_get_worker_by_farm_manager', [WorkerController::class, 'ajax_get_worker_by_farm_manager'])->name('ajax_get_worker_by_farm_manager');
        Route::match(['get', 'post'], '/ajax_get_worker_list', [WorkerController::class, 'ajax_get_worker_list'])->name('ajax_get_worker_list');
        Route::match(['get', 'post'], '/ajax_get_workder_based_on_user', [WorkerController::class, 'ajax_get_user_id_by_worker_id'])->name('ajax_get_user_id_by_worker');
        Route::match(['get', 'post'], '/ajax_check_worker_ic', [WorkerController::class, 'ajax_check_worker_ic'])->name('ajax_check_worker_ic');
        Route::match(['get', 'post'], 'ajax_get_user_list_sel_by_company', [WorkerController::class, 'ajax_get_user_list_sel_by_company'])->name('ajax_get_user_list_sel_by_company');
        Route::match(['get', 'post'], 'worker/import', [WorkerController::class, 'import'])->name('worker_import');
        Route::match(['get', 'post'], 'worker/adjustment/{id}', [WorkerController::class, 'adjustment'])->name('worker_wallet_adjustment');
        Route::match(['get', 'post'], 'worker/wallet_history/{id}', [WorkerController::class, 'worker_wallet_detail'])->name('worker_wallet_details');
    });

    //  Worker Type
    Route::group(['middleware' => ['permission:worker_type_listing']], function () {
        Route::match(['get', 'post'], 'worker_type/listing', [WorkerTypeController::class, 'listing'])->name('worker_type_listing');
    });
    Route::group(['middleware' => ['permission:worker_type_manage']], function () {
        Route::match(['get', 'post'], 'worker_type/add', [WorkerTypeController::class, 'add'])->name('worker_type_add');
        Route::match(['get', 'post'], 'worker_type/edit/{id}', [WorkerTypeController::class, 'edit'])->name('worker_type_edit');
        Route::match(['get', 'post'], 'worker_type/delete', [WorkerTypeController::class, 'delete'])->name('worker_type_delete');
    });

    Route::match(['get', 'post'], 'ajax_get_farm_manager_by_company_id', [WorkerController::class, 'ajax_get_farm_manager_by_company_id'])->name('ajax_get_farm_manager_by_company_id');

    // Route::group(['middleware' => ['permission:claim_manage']], function () {
        // Route::match(['get', 'post'], 'claim/add', [ClaimController::class, 'add'])->name('claim_add');
        // Route::match(['get', 'post'], 'claim/edit/{id}', [ClaimController::class, 'edit'])->name('claim_edit');
        // Route::match(['get', 'post'], 'claim-item/add/{id}', [ClaimController::class, 'add_claim_item'])->name('claim_item_add');
        // Route::match(['get', 'post'], 'worker_type/delete', [WorkerTypeController::class, 'delete'])->name('worker_type_delete');
    // });

    // Setting Race
        // Route::match(['get', 'post'], 'setting_race/listing', [SettingRaceController::class, 'listing'])->name('setting_race');
        // Route::match(['get', 'post'], 'setting_race/add', [SettingRaceController::class, 'add'])->name('add');
        // Route::match(['get', 'post'], 'setting_race/edit/{id}', [SettingRaceController::class, 'edit'])->name('edit');
        // Route::match(['get', 'post'], 'setting_race/delete', [SettingRaceController::class, 'delete'])->name('delete');

    // Claim
    Route::group(['middleware' => ['permission:claim_listing']], function () {
        Route::match(['get', 'post'], 'claim/listing', [ClaimController::class, 'listing'])->name('claim_listing');
    });
    Route::group(['middleware' => ['permission:claim_manage']], function () {
        Route::match(['get', 'post'], 'claim/add', [ClaimController::class, 'add'])->name('claim_add');
        Route::match(['get', 'post'], 'claim/delete', [ClaimController::class, 'delete_claim'])->name('claim_delete');
        Route::match(['get', 'post'], 'claim/my-claim', [ClaimController::class, 'my_claim'])->name('claim_my_claim');
    });

    Route::match(['get', 'post'], 'ajax_check_company_pic', [ClaimController::class, 'ajax_check_company_pic'])->name('ajax_check_company_pic');

    Route::match(['get', 'post'], 'claim/view/{id}/{encryption}', [ClaimController::class, 'view_claim_pdf'])->name('claim_view');
    Route::match(['get', 'post'], 'claim/export/{id}', [ClaimController::class, 'export_claim'])->name('claim_export');

    Route::match(['get', 'post'], 'claim/item/listing/{id}', [ClaimItemController::class, 'listing'])->name('claim_item_listing');
    Route::match(['get', 'post'], 'claim/item/add/{id}', [ClaimItemController::class, 'add'])->name('claim_item_add');
    Route::match(['get', 'post'], 'claim/item/reject', [ClaimItemController::class, 'reject'])->name('claim_item_reject');
    Route::match(['get', 'post'], 'claim/item/delete/{claim_item_id}', [ClaimItemController::class, 'delete'])->name('claim_item_delete');
    Route::match(['get', 'post'], 'claim/claim_log/{id}', [ClaimItemController::class, 'claim_log_detail'])->name('claim_log');
    Route::match(['get', 'post'], 'ajax_get_price_expense_item', [ClaimItemController::class, 'ajax_get_price_expense_item'])->name('ajax_get_price_expense_item');
    Route::match(['get', 'post'], 'ajax_get_price_raw_material_item', [ClaimItemController::class, 'ajax_get_price_raw_material_item'])->name('ajax_get_price_raw_material_item');
    Route::match(['get', 'post'], 'ajax_get_image_by_company_expense_item_id', [ClaimItemController::class, 'ajax_get_image_by_company_expense_item_id'])->name('ajax_get_image_by_company_expense_item_id');
    Route::match(['get', 'post'], 'ajax_get_image_by_claim_item_id', [ClaimItemController::class, 'ajax_get_image_by_claim_item_id'])->name('ajax_get_image_by_claim_item_id');


    //claim action
    Route::match(['get', 'post'], 'claim/submit_claim/{id}', [ClaimController::class, 'submit_claim'])->name('claim_submit_claim');
    Route::match(['get', 'post'], 'claim/approve_checking/{id}', [ClaimController::class, 'approve_checking'])->name('claim_approve_checking');
    Route::match(['get', 'post'], 'claim/approve_verify/{id}', [ClaimController::class, 'approve_verify'])->name('claim_approve_verify');

    Route::match(['get', 'post'], 'claim/approve_approval/{id}', [ClaimController::class, 'approve_approval'])->name('claim_approve_approval');
    Route::match(['get', 'post'], 'claim/account_check/{id}', [ClaimController::class, 'account_check'])->name('claim_account_check');
    Route::match(['get', 'post'], 'claim/payment/{id}', [ClaimController::class, 'payment'])->name('claim_payment');

    Route::match(['get', 'post'], 'claim/cancel_submission', [ClaimController::class, 'cancel_submission'])->name('claim_cancel_submission');
    Route::match(['get', 'post'], 'claim/resubmit_reject', [ClaimController::class, 'resubmit_reject'])->name('claim_resubmit_reject');
    Route::match(['get', 'post'], 'claim/permanent_reject/{id}', [ClaimController::class, 'permanent_reject'])->name('claim_permanent_reject');

    Route::match(['get', 'post'], '/reset_security_pin', function () {
        $result = new SettingSecurityPin;
        $result->handle();
    });

    Route::match(['get', 'post'], '/cancel_url', function () {
        $result = new PaymentUrlCancellation;
        $result->handle();
    });


    // Route::match(['get', 'post'], '/testing_pl_report', [ProfitLossReportingController::class, 'profit_loss_testing'])->name('profit_loss_testing');
    Route::match(['get', 'post'], '/reporting/profit_loss_reporting/listing', [ProfitLossReportingController::class, 'profit_loss_reporting'])->name('profit_loss_reporting');

    //Budget Estimate
    Route::group(['middleware' => ['permission:budget_estimate_listing']], function () {
        Route::match(['get', 'post'], 'reporting/budget_estimate_report/listing', [BudgetEstimateController::class, 'listing'])->name('budget_estimate_report_listing');
    });

    Route::group(['middleware' => ['permission:budget_estimate_manage']], function () {
        Route::match(['get', 'post'], 'reporting/budget_estimate_report/add', [BudgetEstimateController::class, 'add'])->name('budget_estimate_report_add');
        Route::match(['get', 'post'], 'reporting/budget_estimate_report/edit/{id}', [BudgetEstimateController::class, 'edit'])->name('budget_estimate_report_edit');
        Route::match(['get', 'post'], 'reporting/budget_estimate_report/delete', [BudgetEstimateController::class, 'delete'])->name('budget_estimate_report_delete');
    });

    Route::get('tenant-profile', [TenantController::class, 'profile'])->name('tenant.profile');
    Route::post('save-tenant-profile', [TenantController::class, 'saveProfile'])->name('save.tenant.profile');
});
