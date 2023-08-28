<?php
namespace Database\Seeders;

use Database\Seeders\BudgetEstimated\BudgetEstimated;
use Database\Seeders\BudgetEstimated\BudgetEstimatedItem;
use Database\Seeders\BudgetEstimated\BudgetEstimatedLog;
use Database\Seeders\Claim\Claim;
use Database\Seeders\Claim\ClaimApproval;
use Database\Seeders\Claim\ClaimApprovalStep;
use Database\Seeders\Claim\ClaimItem;
use Database\Seeders\Claim\ClaimItemLog;
use Database\Seeders\Claim\ClaimLog;
use Database\Seeders\Claim\ClaimStatus;
use Database\Seeders\Collect\Collect;
use Database\Seeders\Company\Company;
use Database\Seeders\Company\CompanyBank;
use Database\Seeders\Company\CompanyClaimApproval;
use Database\Seeders\Company\CompanyExpense;
use Database\Seeders\Company\CompanyExpenseItem;
use Database\Seeders\Company\CompanyExpenseLand;
use Database\Seeders\Company\CompanyExpenseLandProduct;
use Database\Seeders\Company\CompanyExpenseLog;
use Database\Seeders\Company\CompanyExpenseWorker;
use Database\Seeders\Company\CompanyFarm;
use Database\Seeders\Company\CompanyLand;
use Database\Seeders\Company\CompanyLandBudgetOverwrite;
use Database\Seeders\Company\CompanyLandCategory;
use Database\Seeders\Company\CompanyLandTree;
use Database\Seeders\Company\CompanyLandTreeAction;
use Database\Seeders\Company\CompanyLandTreeLog;
use Database\Seeders\Company\CompanyLandZone;
use Database\Seeders\Company\CompanyPnlItem;
use Database\Seeders\Company\CompanyPnlSubItem;
use Database\Seeders\Customer\Customer;
use Database\Seeders\Customer\CustomerCategory;
use Database\Seeders\Customer\CustomerCreditHistory;
use Database\Seeders\Customer\CustomerLog;
use Database\Seeders\Customer\CustomerPic;
use Database\Seeders\Customer\CustomerPicLog;
use Database\Seeders\Customer\CustomerTerm;
use Database\Seeders\DeliveryOrder\DeliveryOrder;
use Database\Seeders\DeliveryOrder\DeliveryOrderExpense;
use Database\Seeders\DeliveryOrder\DeliveryOrderItem;
use Database\Seeders\DeliveryOrder\DeliveryOrderLog;
use Database\Seeders\DeliveryOrder\DeliveryOrderStatus;
use Database\Seeders\DeliveryOrder\DeliveryOrderType;
use Database\Seeders\FormulaUsage\FormulaUsage;
use Database\Seeders\FormulaUsage\FormulaUsageItem;
use Database\Seeders\FormulaUsage\FormulaUsageProduct;
use Database\Seeders\Invoice\Invoice;
use Database\Seeders\Invoice\InvoiceItem;
use Database\Seeders\Invoice\InvoiceLog;
use Database\Seeders\Invoice\InvoicePayment;
use Database\Seeders\Invoice\InvoiceStatus;
use Database\Seeders\Invoice\InvoiceType;
use Database\Seeders\Media\Media;
use Database\Seeders\Media\MediaTemp;
use Database\Seeders\Message\MessageLog;
use Database\Seeders\Message\MessageTemplate;
use Database\Seeders\Message\MessageTemplateInvolve;
use Database\Seeders\Message\MessageTemplateInvolveLink;
use Database\Seeders\PaymentUrl\PaymentUrl;
use Database\Seeders\PaymentUrl\PaymentUrlItem;
use Database\Seeders\PaymentUrl\PaymentUrlLog;
use Database\Seeders\Payroll\Payroll;
use Database\Seeders\Payroll\PayrollItem;
use Database\Seeders\Payroll\PayrollItemWorkerRole;
use Database\Seeders\Payroll\PayrollLog;
use Database\Seeders\Payroll\PayrollUser;
use Database\Seeders\Payroll\PayrollUserItem;
use Database\Seeders\Payroll\PayrollUserReward;
use Database\Seeders\Product\Product;
use Database\Seeders\Product\ProductCategory;
use Database\Seeders\Product\ProductCompanyLand;
use Database\Seeders\Product\ProductInfo;
use Database\Seeders\Product\ProductSizeLink;
use Database\Seeders\Product\ProductStatus;
use Database\Seeders\Product\ProductStockTransfer;
use Database\Seeders\Product\ProductStockWarehouse;
use Database\Seeders\Product\ProductTag;
use Database\Seeders\Product\ProductTagLink;
use Database\Seeders\RawMaterial\RawMaterial;
use Database\Seeders\RawMaterial\RawMaterialCategory;
use Database\Seeders\RawMaterial\RawMaterialCompany;
use Database\Seeders\RawMaterial\RawMaterialCompanyUsage;
use Database\Seeders\RawMaterial\RawMaterialCompanyUsageLog;
use Database\Seeders\SendSmsLog;
use Database\Seeders\Setting\Setting;
use Database\Seeders\Setting\SettingBank;
use Database\Seeders\Setting\SettingBanner;
use Database\Seeders\Setting\SettingCountry;
use Database\Seeders\Setting\SettingCurrency;
use Database\Seeders\Setting\SettingExpense;
use Database\Seeders\Setting\SettingExpenseCategory;
use Database\Seeders\Setting\SettingExpenseOverwrite;
use Database\Seeders\Setting\SettingExpenseType;
use Database\Seeders\Setting\SettingFormula;
use Database\Seeders\Setting\SettingFormulaCategory;
use Database\Seeders\Setting\SettingFormulaItem;
use Database\Seeders\Setting\SettingNo;
use Database\Seeders\Setting\SettingPayment;
use Database\Seeders\Setting\SettingProductSize;
use Database\Seeders\Setting\SettingRace;
use Database\Seeders\Setting\SettingReportingTemplate;
use Database\Seeders\Setting\SettingReward;
use Database\Seeders\Setting\SettingRewardCategory;
use Database\Seeders\Setting\SettingSecurityPin;
use Database\Seeders\Setting\SettingState;
use Database\Seeders\Setting\SettingTreeAge;
use Database\Seeders\Setting\SettingTreeAgePointer;
use Database\Seeders\Setting\SettingWarehouse;
use Database\Seeders\Supplier\Supplier;
use Database\Seeders\Supplier\SupplierBank;
use Database\Seeders\Supplier\SupplierCompany;
use Database\Seeders\Supplier\SupplierDeliveryOrder;
use Database\Seeders\Supplier\SupplierDeliveryOrderItem;
use Database\Seeders\Supplier\SupplierDeliveryOrderLog;
use Database\Seeders\Supplier\SupplierDeliveryOrderReturn;
use Database\Seeders\Supplier\SupplierRawMaterial;
use Database\Seeders\Sync\Sync;
use Database\Seeders\Sync\SyncCollect;
use Database\Seeders\Sync\SyncCompanyExpense;
use Database\Seeders\Sync\SyncCompanyExpenseItem;
use Database\Seeders\Sync\SyncCompanyExpenseWorker;
use Database\Seeders\Sync\SyncCustomer;
use Database\Seeders\Sync\SyncDeliveryOrder;
use Database\Seeders\Sync\SyncDeliveryOrderItem;
use Database\Seeders\Sync\SyncDeliveryOrderLog;
use Database\Seeders\Sync\SyncFormulaUsage;
use Database\Seeders\Sync\SyncFormulaUsageItem;
use Database\Seeders\Sync\SyncFormulaUsageProduct;
use Database\Seeders\Sync\SyncProduct;
use Database\Seeders\Url;
use Database\Seeders\User\User;
use Database\Seeders\User\UserAccessToken;
use Database\Seeders\User\UserCompany;
use Database\Seeders\User\UserGroup;
use Database\Seeders\User\UserLand;
use Database\Seeders\User\UserLog;
use Database\Seeders\User\UserModelHasPermission;
use Database\Seeders\User\UserModelHasRole;
use Database\Seeders\User\UserPasswordReset;
use Database\Seeders\User\UserPermission;
use Database\Seeders\User\UserRole;
use Database\Seeders\User\UserRoleHasPermission;
use Database\Seeders\User\UserToken;
use Database\Seeders\User\UserType;
use Database\Seeders\User\UserWalletHistory;
use Database\Seeders\Worker\Worker;
use Database\Seeders\Worker\WorkerRole;
use Database\Seeders\Worker\WorkerStatus;
use Database\Seeders\Worker\WorkerType;
use Database\Seeders\Worker\WorkerWalletHistory;
use App\Model\Landlord\CentralTenantCompany;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([
        //     BudgetEstimated::class,
        //     BudgetEstimatedItem::class,
        //     BudgetEstimatedLog::class,
        //     Claim::class,
        //     ClaimApproval::class,
        //     ClaimApprovalStep::class,
        //     ClaimItem::class,
        //     ClaimItemLog::class,
        //     ClaimLog::class,
        //     ClaimStatus::class,
        //     Collect::class,
        //     Company::class,
        //     CompanyBank::class,
        //     CompanyClaimApproval::class,
        //     CompanyExpense::class,
        //     CompanyExpenseItem::class,
        //     CompanyExpenseLand::class,
        //     CompanyExpenseLandProduct::class,
        //     CompanyExpenseLog::class,
        //     CompanyExpenseWorker::class,
        //     CompanyFarm::class,
        //     CompanyLand::class,
        //     CompanyLandBudgetOverwrite::class,
        //     CompanyLandCategory::class,
        //     CompanyLandTree::class,
        //     CompanyLandTreeAction::class,
        //     CompanyLandTreeLog::class,
        //     CompanyLandZone::class,
        //     CompanyPnlItem::class,
        //     CompanyPnlSubItem::class,
        //     Customer::class,
        //     CustomerCategory::class,
        //     CustomerCreditHistory::class,
        //     CustomerLog::class,
        //     CustomerPic::class,
        //     CustomerPicLog::class,
        //     CustomerTerm::class,
        //     DeliveryOrder::class,
        //     DeliveryOrderExpense::class,
        //     DeliveryOrderItem::class,
        //     DeliveryOrderLog::class,
        //     DeliveryOrderStatus::class,
        //     DeliveryOrderType::class,
        //     FormulaUsage::class,
        //     FormulaUsageItem::class,
        //     FormulaUsageProduct::class,
        //     Invoice::class,
        //     InvoiceItem::class,
        //     InvoiceLog::class,
        //     InvoicePayment::class,
        //     InvoiceStatus::class,
        //     InvoiceType::class,
        //     Media::class,
        //     MediaTemp::class,
        //     MessageLog::class,
        //     MessageTemplate::class,
        //     MessageTemplateInvolve::class,
        //     MessageTemplateInvolveLink::class,
        //     PaymentUrl::class,
        //     PaymentUrlItem::class,
        //     PaymentUrlLog::class,
        //     Payroll::class,
        //     PayrollItem::class,
        //     PayrollItemWorkerRole::class,
        //     PayrollLog::class,
        //     PayrollUser::class,
        //     PayrollUserItem::class,
        //     PayrollUserReward::class,
        //     Product::class,
        //     ProductCategory::class,
        //     ProductCompanyLand::class,
        //     ProductInfo::class,
        //     ProductSizeLink::class,
        //     ProductStatus::class,
        //     ProductStockTransfer::class,
        //     ProductStockWarehouse::class,
        //     ProductTag::class,
        //     ProductTagLink::class,
        //     RawMaterial::class,
        //     RawMaterialCategory::class,
        //     RawMaterialCompany::class,
        //     RawMaterialCompanyUsage::class,
        //     RawMaterialCompanyUsageLog::class,
        //     SendSmsLog::class,
        //     Setting::class,
        //     SettingBank::class,
        //     SettingBanner::class,
        //     SettingCountry::class,
        //     SettingCurrency::class,
        //     SettingExpense::class,
        //     SettingExpenseCategory::class,
        //     SettingExpenseOverwrite::class,
        //     SettingExpenseType::class,
        //     SettingFormula::class,
        //     SettingFormulaCategory::class,
        //     SettingFormulaItem::class,
        //     SettingNo::class,
        //     SettingPayment::class,
        //     SettingProductSize::class,
        //     SettingRace::class,
        //     SettingReportingTemplate::class,
        //     SettingReward::class,
        //     SettingRewardCategory::class,
        //     SettingSecurityPin::class,
        //     SettingState::class,
        //     SettingTreeAge::class,
        //     SettingTreeAgePointer::class,
        //     SettingWarehouse::class,
        //     Supplier::class,
        //     SupplierBank::class,
        //     SupplierCompany::class,
        //     SupplierDeliveryOrder::class,
        //     SupplierDeliveryOrderItem::class,
        //     SupplierDeliveryOrderLog::class,
        //     SupplierDeliveryOrderReturn::class,
        //     SupplierRawMaterial::class,
        //     Sync::class,
        //     SyncCollect::class,
        //     SyncCompanyExpense::class,
        //     SyncCompanyExpenseItem::class,
        //     SyncCompanyExpenseWorker::class,
        //     SyncCustomer::class,
        //     SyncDeliveryOrder::class,
        //     SyncDeliveryOrderItem::class,
        //     SyncDeliveryOrderLog::class,
        //     SyncFormulaUsage::class,
        //     SyncFormulaUsageItem::class,
        //     SyncFormulaUsageProduct::class,
        //     SyncProduct::class,
        //     Url::class,
        //     User::class,
        //     UserAccessToken::class,
        //     UserCompany::class,
        //     UserGroup::class,
        //     UserLand::class,
        //     UserLog::class,
        //     UserModelHasPermission::class,
        //     UserModelHasRole::class,
        //     UserPasswordReset::class,
        //     UserPermission::class,
        //     UserRole::class,
        //     UserRoleHasPermission::class,
        //     UserToken::class,
        //     UserType::class,
        //     UserWalletHistory::class,
        //     Worker::class,
        //     WorkerRole::class,
        //     WorkerStatus::class,
        //     WorkerType::class,
        //     WorkerWalletHistory::class
        // ]);
        $this->call(TblBudgetEstimatedTableSeeder::class);
        $this->call(TblBudgetEstimatedItemTableSeeder::class);
        $this->call(TblBudgetEstimatedLogTableSeeder::class);
        $this->call(TblClaimTableSeeder::class);
        $this->call(TblClaimApprovalTableSeeder::class);
        $this->call(TblClaimApprovalStepTableSeeder::class);
        $this->call(TblClaimItemTableSeeder::class);
        $this->call(TblClaimItemLogTableSeeder::class);
        $this->call(TblClaimLogTableSeeder::class);
        $this->call(TblClaimStatusTableSeeder::class);
        $this->call(TblCollectTableSeeder::class);
        $this->call(TblCompanyTableSeeder::class);
        $this->call(TblCompanyBankTableSeeder::class);
        $this->call(TblCompanyClaimApprovalTableSeeder::class);
        $this->call(TblCompanyExpenseTableSeeder::class);
        $this->call(TblCompanyExpenseItemTableSeeder::class);
        $this->call(TblCompanyExpenseLandTableSeeder::class);
        $this->call(TblCompanyExpenseLandProductTableSeeder::class);
        $this->call(TblCompanyExpenseLogTableSeeder::class);
        $this->call(TblCompanyExpenseWorkerTableSeeder::class);
        $this->call(TblCompanyFarmTableSeeder::class);
        $this->call(TblCompanyLandTableSeeder::class);
        $this->call(TblCompanyLandBudgetOverwriteTableSeeder::class);
        $this->call(TblCompanyLandCategoryTableSeeder::class);
        $this->call(TblCompanyLandTreeTableSeeder::class);
        $this->call(TblCompanyLandTreeActionTableSeeder::class);
        $this->call(TblCompanyLandTreeLogTableSeeder::class);
        $this->call(TblCompanyLandZoneTableSeeder::class);
        $this->call(TblCompanyPnlItemTableSeeder::class);
        $this->call(TblCompanyPnlSubItemTableSeeder::class);
        $this->call(TblCustomerTableSeeder::class);
        $this->call(TblCustomerCategoryTableSeeder::class);
        $this->call(TblCustomerCreditHistoryTableSeeder::class);
        $this->call(TblCustomerLogTableSeeder::class);
        $this->call(TblCustomerPicTableSeeder::class);
        $this->call(TblCustomerPicLogTableSeeder::class);
        $this->call(TblCustomerTermTableSeeder::class);
        $this->call(TblDeliveryOrderTableSeeder::class);
        $this->call(TblDeliveryOrderExpenseTableSeeder::class);
        $this->call(TblDeliveryOrderItemTableSeeder::class);
        $this->call(TblDeliveryOrderLogTableSeeder::class);
        $this->call(TblDeliveryOrderStatusTableSeeder::class);
        $this->call(TblDeliveryOrderTypeTableSeeder::class);
        $this->call(TblFormulaUsageTableSeeder::class);
        $this->call(TblFormulaUsageItemTableSeeder::class);
        $this->call(TblFormulaUsageProductTableSeeder::class);
        $this->call(TblInvoiceTableSeeder::class);
        $this->call(TblInvoiceItemTableSeeder::class);
        $this->call(TblInvoiceLogTableSeeder::class);
        $this->call(TblInvoicePaymentTableSeeder::class);
        $this->call(TblInvoiceStatusTableSeeder::class);
        $this->call(TblInvoiceTypeTableSeeder::class);
        $this->call(TblMediaTableSeeder::class);
        $this->call(TblMediaTempTableSeeder::class);
        $this->call(TblMessageLogTableSeeder::class);
        $this->call(TblMessageTemplateTableSeeder::class);
        $this->call(TblMessageTemplateInvolveTableSeeder::class);
        $this->call(TblMessageTemplateInvolveLinkTableSeeder::class);
        $this->call(TblPaymentUrlTableSeeder::class);
        $this->call(TblPaymentUrlItemTableSeeder::class);
        $this->call(TblPaymentUrlLogTableSeeder::class);
        $this->call(TblPayrollTableSeeder::class);
        $this->call(TblPayrollItemTableSeeder::class);
        $this->call(TblPayrollItemWorkerRoleTableSeeder::class);
        $this->call(TblPayrollLogTableSeeder::class);
        $this->call(TblPayrollUserTableSeeder::class);
        $this->call(TblPayrollUserItemTableSeeder::class);
        $this->call(TblPayrollUserRewardTableSeeder::class);
        $this->call(TblProductTableSeeder::class);
        $this->call(TblProductCategoryTableSeeder::class);
        $this->call(TblProductCompanyLandTableSeeder::class);
        $this->call(TblProductInfoTableSeeder::class);
        $this->call(TblProductSizeLinkTableSeeder::class);
        $this->call(TblProductStatusTableSeeder::class);
        $this->call(TblProductStockTransferTableSeeder::class);
        $this->call(TblProductStockWarehouseTableSeeder::class);
        $this->call(TblProductTagTableSeeder::class);
        $this->call(TblProductTagLinkTableSeeder::class);
        $this->call(TblRawMaterialTableSeeder::class);
        $this->call(TblRawMaterialCategoryTableSeeder::class);
        $this->call(TblRawMaterialCompanyTableSeeder::class);
        $this->call(TblRawMaterialCompanyUsageTableSeeder::class);
        $this->call(TblRawMaterialCompanyUsageLogTableSeeder::class);
        $this->call(TblSendSmsLogTableSeeder::class);
        $this->call(TblSettingTableSeeder::class);
        $this->call(TblSettingBankTableSeeder::class);
        $this->call(TblSettingBannerTableSeeder::class);
        $this->call(TblSettingCountryTableSeeder::class);
        $this->call(TblSettingCurrencyTableSeeder::class);
        $this->call(TblSettingExpenseTableSeeder::class);
        $this->call(TblSettingExpenseCategoryTableSeeder::class);
        $this->call(TblSettingExpenseOverwriteTableSeeder::class);
        $this->call(TblSettingExpenseTypeTableSeeder::class);
        $this->call(TblSettingFormulaTableSeeder::class);
        $this->call(TblSettingFormulaCategoryTableSeeder::class);
        $this->call(TblSettingFormulaItemTableSeeder::class);
        $this->call(TblSettingNoTableSeeder::class);
        $this->call(TblSettingPaymentTableSeeder::class);
        $this->call(TblSettingProductSizeTableSeeder::class);
        $this->call(TblSettingRaceTableSeeder::class);
        $this->call(TblSettingReportingTemplateTableSeeder::class);
        $this->call(TblSettingRewardTableSeeder::class);
        $this->call(TblSettingRewardCategoryTableSeeder::class);
        $this->call(TblSettingSecurityPinTableSeeder::class);
        $this->call(TblSettingStateTableSeeder::class);
        $this->call(TblSettingTreeAgeTableSeeder::class);
        $this->call(TblSettingTreeAgePointerTableSeeder::class);
        $this->call(TblSettingWarehouseTableSeeder::class);
        $this->call(TblSupplierTableSeeder::class);
        $this->call(TblSupplierBankTableSeeder::class);
        $this->call(TblSupplierCompanyTableSeeder::class);
        $this->call(TblSupplierDeliveryOrderTableSeeder::class);
        $this->call(TblSupplierDeliveryOrderItemTableSeeder::class);
        $this->call(TblSupplierDeliveryOrderLogTableSeeder::class);
        $this->call(TblSupplierDeliveryOrderReturnTableSeeder::class);
        $this->call(TblSupplierRawMaterialTableSeeder::class);
        $this->call(TblSyncTableSeeder::class);
        $this->call(TblSyncCollectTableSeeder::class);
        $this->call(TblSyncCompanyExpenseTableSeeder::class);
        $this->call(TblSyncCompanyExpenseItemTableSeeder::class);
        $this->call(TblSyncCompanyExpenseWorkerTableSeeder::class);
        $this->call(TblSyncCustomerTableSeeder::class);
        $this->call(TblSyncDeliveryOrderTableSeeder::class);
        $this->call(TblSyncDeliveryOrderItemTableSeeder::class);
        $this->call(TblSyncDeliveryOrderLogTableSeeder::class);
        $this->call(TblSyncFormulaUsageTableSeeder::class);
        $this->call(TblSyncFormulaUsageItemTableSeeder::class);
        $this->call(TblSyncFormulaUsageProductTableSeeder::class);
        $this->call(TblSyncProductTableSeeder::class);
        $this->call(TblUrlTableSeeder::class);
        $this->call(TblUserTableSeeder::class);
        $this->call(TblUserAccessTokenTableSeeder::class);
        $this->call(TblUserCompanyTableSeeder::class);
        $this->call(TblUserGroupTableSeeder::class);
        $this->call(TblUserLandTableSeeder::class);
        $this->call(TblUserLogTableSeeder::class);
        $this->call(TblUserModelHasPermissionTableSeeder::class);
        $this->call(TblUserModelHasRoleTableSeeder::class);
        $this->call(TblUserPasswordResetTableSeeder::class);
        $this->call(TblUserPermissionTableSeeder::class);
        $this->call(TblUserRoleTableSeeder::class);
        $this->call(TblUserRoleHasPermissionTableSeeder::class);
        $this->call(TblUserTokenTableSeeder::class);
        $this->call(TblUserTypeTableSeeder::class);
        $this->call(TblUserWalletHistoryTableSeeder::class);
        $this->call(TblWorkerTableSeeder::class);
        $this->call(TblWorkerRoleTableSeeder::class);
        $this->call(TblWorkerStatusTableSeeder::class);
        $this->call(TblWorkerTypeTableSeeder::class);
        $this->call(TblWorkerWalletHistoryTableSeeder::class);
    }
}
