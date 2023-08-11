<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li>
                    <a href="{{ route('dashboard', ['tenant' => tenant('id')]) }}" class="waves-effect">
                        <i class="bx bx-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @if (checkSubscriptionFeature('delivery_order_module'))
                    {{-- Delivery Order --}}
                    @canany(['delivery_order_listing', 'delivery_order_manage', 'invoice_listing'])
                        <li class="menu-title">Orders</li>
                        <li>
                            <a href="javascript:void(0)" class="has-arrow waves-effect">
                                <i class="bx bx-store-alt"></i>
                                <span>Delivery Order</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('delivery_order_manage')
                                <li><a href="{{ route('do_add', ['tenant' => tenant('id')]) }}">Add New D.O</a></li>
                                @endcan
                                @can('delivery_order_listing')
                                <li><a href="{{ route('do_listing', ['tenant' => tenant('id')]) }}">D.O Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                    {{-- Invoice --}}
                        @can('invoice_listing')
                            <li>
                                <a href="{{ route('invoice_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Invoice</span>

                                    @if (count(sidebar()) > 0)
                                    <div class="wrapper">
                                        <div class="pulse">
                                            {{ count(sidebar()) }}
                                        </div>
                                    </div>
                                    @endif
                                </a>

                            </li>
                        @endcan
                    {{-- End Invoice --}}
                    @endcanany
                    {{-- End Delivery Order --}}
                @endif

                {{-- Payment Url --}}
                @if (checkSubscriptionFeature('delivery_order_module'))
                    @can('payment_url_listing')
                    <li>
                        <a href="{{ route('payment_url_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                            <i class="bx bx-detail"></i>
                            <span>Payment Url</span>
                        </a>
                    </li>
                    @endcan
                @endif
                {{-- End Payment Url --}}

                @if (checkSubscriptionFeature('delivery_order_module'))
                {{-- Expense List --}}
                    @can('delivery_order_listing')
                    <li>
                        <a href="{{ route('delivery_order_expense', ['tenant' => tenant('id')]) }}" class="waves-effect">
                            <i class="bx bx-detail"></i>
                            <span>Expense Listing</span>
                        </a>
                    </li>
                    @endcan
                {{-- End Expense List --}}
                @endif

                @if (checkSubscriptionFeature('collect_module'))
                    {{-- Collect --}}
                    @canany(['collect_listing'])
                        <li class="menu-title">Collects</li>
                        @can('collect_listing')
                            <li>
                                <a href="{{ route('collect_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bx-store-alt"></i>
                                    <span>Collect</span>
                                </a>
                            </li>
                        @endcan
                    @endcanany
                    {{-- End Collect --}}
                @endif  

                @if (checkSubscriptionFeature('raw_material_module'))
                    {{-- Raw Material --}}
                    @canany(['raw_material_listing','raw_material_category_listing','raw_material_company_listing','raw_material_company_usage_listing'])
                        <li class="menu-title">Raw Materials</li>
                        @can('raw_material_listing')
                            <li>
                                <a href="{{ route('setting_raw_material_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bxs-package"></i>
                                    <span>Raw Material</span>
                                </a>
                            </li>
                        @endcan
                            @can('raw_material_category_listing')
                            <li>
                                <a href="{{ route('setting_raw_material_category_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bxs-package"></i>
                                    <span>Raw Material Category</span>
                                </a>
                            </li>
                        @endcan
                        @can('raw_material_company_listing')
                            <li>
                                <a href="{{ route('raw_material_company_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bxs-package"></i>
                                    <span>Raw Material Company</span>
                                </a>
                            </li>
                        @endcan
                        @can('raw_material_company_usage_listing')
                            <li>
                                <a href="{{ route('raw_material_company_usage_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bxs-package"></i>
                                    <span>Stock History</span>
                                </a>
                            </li>
                        @endcan
                    @endcanany
                    {{-- End Raw Material --}}
                @endif

                @if (checkSubscriptionFeature('formula_usage_module'))
                    {{-- Formula --}}
                    @canany(['formula_usage_manage','formula_usage_listing'])
                        <li class="menu-title">Formula Usage</li>
                        {{-- @can('formula_usage_manage')
                        <li>
                            <a href="{{ route('formula_usage_add', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                <i class="bx bxs-droplet"></i>
                                <span>Add Formula Usage</span>
                            </a>
                        </li>
                        @endcan --}}
                        @can('formula_usage_listing')
                            <li>
                                <a href="{{ route('formula_usage_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bxs-droplet"></i>
                                    <span>Formula Usage Listing</span>
                                </a>
                            </li>
                        @endcan
                        @canany(['formula_listing','formula_category_listing'])
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bxs-cog"></i>
                                    <span>Formula Setting</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('formula_listing')
                                    <li><a href="{{ route('setting_formula_listing', ['tenant' => tenant('id')]) }}">Formula Listing</a></li>
                                    @endcan
                                    @can('formula_category_listing')
                                    <li><a href="{{ route('setting_formula_category_listing', ['tenant' => tenant('id')]) }}">Formula Category</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    @endcanany
                    {{-- End Formula --}}
                @endif

                @if (checkSubscriptionFeature('expense_module'))
                    {{-- Start Expense --}}
                    @canany(['company_expense_listing','company_expense_manage','payroll_listing', 'payroll_manage', 'payroll_item_listing', 'payroll_item_manage'])
                        <li class="menu-title">Expense</li>
                        @can('company_expense_listing')
                            <li>
                                <a href="{{ route('company_expense_listing', ['tenant' => tenant('id')]) }}">
                                    <i class="bx bx-detail"></i>
                                    <span>Company Expense Listing</span>
                                </a>
                            </li>
                        @endcan
                        @can('payroll_listing')
                            <li>
                                <a href="{{ route('payroll_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Monthly Worker Expense</span>
                                </a>
                            </li>
                        @endcan
                        @canany(['payroll_item_listing', 'payroll_item_manage'])
                            <li>
                                <a href="{{ route('payroll_item_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                                    <i class="bx bx-cog"></i>
                                    <span>Monthly Worker Expense Item</span>
                                </a>
                            </li>
                        @endcanany
                    @endcanany
                    {{-- End Expense --}}
                @endif

                @if (checkSubscriptionFeature('supplier_module'))
                    {{-- Supplier --}}
                    @canany(['supplier_listing','supplier_manage', 'supplier_delivery_order_listing','supplier_delivery_order_manage', 'supplier_delivery_order_delete'])
                        <li class="menu-title">Supplier</li>
                        <li>
                            <a href="javascript:void(0)" class="has-arrow waves-effect">
                                <i class="bx bx-store-alt"></i>
                                <span>Supplier</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('supplier_manage')
                                    <li><a href="{{ route('supplier_add', ['tenant' => tenant('id')]) }}">Add Supplier</a></li>
                                @endcan
                                @can('supplier_listing')
                                    <li><a href="{{ route('supplier_listing', ['tenant' => tenant('id')]) }}">Supplier Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                        <li>
                            <a href="javascript:void(0)" class="has-arrow waves-effect">
                                <i class="bx bx-store-alt"></i>
                                <span>Supplier D.O</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('supplier_delivery_order_manage')
                                    <li><a href="{{ route('supplier_do_add', ['tenant' => tenant('id')]) }}">Add Supplier D.O</a></li>
                                @endcan
                                @can('supplier_delivery_order_listing')
                                    <li><a href="{{ route('supplier_do_listing', ['tenant' => tenant('id')]) }}">Supplier D.O Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    {{-- End Supplier --}}
                @endif

                @if (checkSubscriptionFeature('report'))
                    {{-- Reporting --}}
                    @can('report_listing')
                        @if (auth()->user()->user_type_id == 1)
                            <li class="menu-title">Report</li>
                                <li>
                                    <li><a href="{{ route('sales_analysis_do', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span>
                                                Sales Analysis (DO)</span></a></li>
                                    <li>
                                        <a href="javascript:void(0)" class="has-arrow waves-effect">
                                            <i class="bx bx-detail"></i>
                                            <span>Collect + DO</span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <li><a href="{{ route('collect_do_variance_daily', ['tenant' => tenant('id')]) }}">Collect + DO (Daily Report)</a></li>
                                            <li><a href="{{ route('collect_do_variance', ['tenant' => tenant('id')]) }}">Collect + DO (Monthly Report)</a></li>
                                            <li><a href="{{ route('collect_daily_report', ['tenant' => tenant('id')]) }}">Collect (Daily Report)</a></li>
                                            <li><a href="{{ route('do_daily_report', ['tenant' => tenant('id')]) }}">Delivery Order (Daily Report)</a></li>
                                            <li><a href="{{ route('differentiate_report', ['tenant' => tenant('id')]) }}">Differentiate Reporting</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="has-arrow waves-effect">
                                            <i class="bx bx-detail"></i>
                                            <span>Invoice</span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <li><a href="{{ route('invoice_daily_report', ['tenant' => tenant('id')]) }}">Invoice Daily Reporting</a></li>
                                            <li><a href="{{ route('invoice_monthly_report', ['tenant' => tenant('id')]) }}">Invoice Monthly Reporting</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="has-arrow waves-effect">
                                            <i class="bx bx-detail"></i>
                                            <span>Sales & Product Summary</span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <li><a href="{{ route('sales_summary_by_product_report', ['tenant' => tenant('id')]) }}">Product Grade</a></li>
                                            <li><a href="{{ route('sales_summary_by_product_report_company', ['tenant' => tenant('id')]) }}">Product Grade (Company)</a></li>
                                            <li><a href="{{ route('sales_summary_by_product_report_company_no_grade', ['tenant' => tenant('id')]) }}">Product (Company)</a></li>
                                            <li><a href="{{ route('warehouse_reporting', ['tenant' => tenant('id')]) }}">Warehouse Reporting</a></li>
                                        </ul>
                                    </li>
                                    <li><a href="{{ route('average_summary_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                                class="bx bx-detail"></i><span> Average Summary Report</span></a></li>
                                    <li><a href="{{ route('sync_attendance_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                                class="bx bx-detail"></i><span> Sync Attendance Reporting</span></a></li>
                                    <li><a href="{{ route('sales_summary_by_farm_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                                class="bx bx-detail"></i><span> Farm Reporting</span></a></li>
                                    <li><a href="{{ route('message_template_report_by_year', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                                class="bx bx-detail"></i><span> WhatsApp Reporting</span></a></li>
                                    <li><a href="{{ route('company_land_tree_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                                class="bx bx-detail"></i><span> Company Land Tree Reporting</span></a></li>
                                    <li><a href="{{ route('tree_pointer_reporting', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                                class="bx bx-detail"></i><span> Tree Age Pointer Reporting</span></a></li>
                                    <li><a href="{{ route('formula_usage_report_sa', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                                class="bx bx-detail"></i><span> Formula Usage Reporting</span></a></li>
                                    <li><a href="{{ route('budget_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span>
                                                Budget Reporting</span></a></li>
                                    <li><a href="{{ route('claim_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> Staff Claim
                                                Reporting</span></a></li>
                                    <li><a href="{{ route('claim_pending_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> All Claim Reporting</span></a></li>
                                    <li><a href="{{ route('profit_loss_reporting', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> Profit & Loss Reporting</span></a></li>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="has-arrow waves-effect">
                                            <i class="bx bx-detail"></i>
                                            <span>Expense Reporting</span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <li><a href="{{ route('expense_report', ['tenant' => tenant('id')]) }}">D.O. Expense</a></li>
                                            <li><a href="{{ route('company_expense', ['tenant' => tenant('id')]) }}">Company Expense</a></li>
                                            <li><a href="{{ route('farm_manager_worker_expense', ['tenant' => tenant('id')]) }}">Farm Manager Expense</a></li>
                                            <li><a href="{{ route('worker_attendance_report', ['tenant' => tenant('id')]) }}">Worker Attendance Reporting</a></li>
                                            <li><a href="{{ route('supplier_expenses_report', ['tenant' => tenant('id')]) }}">Supplier Expenses Reporting</a>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)" class="has-arrow waves-effect">
                                            <i class="bx bx-detail"></i>
                                            <span>Forecast</span>
                                        </a>
                                        <ul class="sub-menu" aria-expanded="false">
                                            <li><a href="{{ route('tree_target_report', ['tenant' => tenant('id')]) }}"><span> Tree Target Reporting</span></a></li>
                                            <li><a href="{{ route('forecast_report', ['tenant' => tenant('id')]) }}"><span> Profit & Loss Forecast Reporting</span></a></li>
                                        </ul>
                                    </li>
                            </li>
                        @endif
                        @if (auth()->user()->user_type_id == 2)
                        {{-- <li class="menu-title">Report</li>
                        <li>
                            <a href="javascript:void(0)" class="has-arrow waves-effect">
                                <i class="bx bx-cog"></i>
                                <span>Reporting</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                <li><a href="{{ route('collect_do_variance_daily', ['tenant' => tenant('id')]) }}">Collect + DO (Daily Report)</a></li>
                                <li><a href="{{ route('collect_do_variance', ['tenant' => tenant('id')]) }}">Collect + DO (Monthly Report)</a></li>
                                <li><a href="{{ route('collect_daily_report', ['tenant' => tenant('id')]) }}">Collect (Daily Report)</a></li>
                                <li><a href="{{ route('do_daily_report', ['tenant' => tenant('id')]) }}">Delivery Order Daily Reporting</a></li>
                                <li><a href="{{ route('invoice_daily_report', ['tenant' => tenant('id')]) }}">Invoice Daily Reporting</a></li>
                                <li><a href="{{ route('invoice_monthly_report', ['tenant' => tenant('id')]) }}">Invoice Monthly Reporting</a></li>
                                <li><a href="{{ route('differentiate_report', ['tenant' => tenant('id')]) }}">Differentiate Reporting</a></li>
                                <li><a href="{{ route('sync_attendance_report', ['tenant' => tenant('id')]) }}">Sync Attendance Reporting</a></li>
                                <li><a href="{{ route('sales_summary_by_product_report', ['tenant' => tenant('id')]) }}">Sales & Product Summary Report</a>
                                </li>
                                <li><a href="{{ route('sales_summary_by_farm_report', ['tenant' => tenant('id')]) }}">Farm Reporting</a></li>
                                <li><a href="{{ route('message_template_report_by_year', ['tenant' => tenant('id')]) }}">WhatsApp Reporting</a></li>
                                <li><a href="{{ route('sync_attendance_report', ['tenant' => tenant('id')]) }}">Sync Attendance Reporting</a></li>
                                <li><a href="{{ route('differentiate_report', ['tenant' => tenant('id')]) }}">Differentiate Reporting</a></li>
                                <li><a href="{{ route('expense_report', ['tenant' => tenant('id')]) }}">Expense Reporting</a></li>
                            </ul>
                        </li> --}}
                        <li class="menu-title">Report</li>
                        <li>
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Collect + DO</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('collect_do_variance_daily', ['tenant' => tenant('id')]) }}">Collect + DO (Daily Report)</a></li>
                                    <li><a href="{{ route('collect_do_variance', ['tenant' => tenant('id')]) }}">Collect + DO (Monthly Report)</a></li>
                                    <li><a href="{{ route('collect_daily_report', ['tenant' => tenant('id')]) }}">Collect (Daily Report)</a></li>
                                    <li><a href="{{ route('do_daily_report', ['tenant' => tenant('id')]) }}">Delivery Order (Daily Report)</a></li>
                                    <li><a href="{{ route('differentiate_report', ['tenant' => tenant('id')]) }}">Differentiate Reporting</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Invoice</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('invoice_daily_report', ['tenant' => tenant('id')]) }}">Invoice Daily Reporting</a></li>
                                    <li><a href="{{ route('invoice_monthly_report', ['tenant' => tenant('id')]) }}">Invoice Monthly Reporting</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Sales & Product Summary</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('sales_summary_by_product_report', ['tenant' => tenant('id')]) }}">Product Grade</a></li>
                                    <li><a href="{{ route('warehouse_reporting', ['tenant' => tenant('id')]) }}">Warehouse Reporting</a></li>
                                    {{-- <li><a href="{{ route('sales_summary_by_product_report_company') }}">Product Grade
                                            (Company)</a></li> --}}
                                    {{-- <li><a href="{{ route('sales_summary_by_product_report_company_no_grade') }}">Product
                                            (Company)</a></li> --}}
                                </ul>
                            </li>
                            <li><a href="{{ route('average_summary_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                        class="bx bx-detail"></i><span> Average Summary Report</span></a></li>
                            <li><a href="{{ route('sync_attendance_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                        class="bx bx-detail"></i><span> Sync Attendance Reporting</span></a></li>
                            <li><a href="{{ route('sales_summary_by_farm_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                        class="bx bx-detail"></i><span> Farm Reporting</span></a></li>
                            <li><a href="{{ route('message_template_report_by_year', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                        class="bx bx-detail"></i><span> WhatsApp Reporting</span></a></li>
                            <li><a href="{{ route('company_land_tree_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                        class="bx bx-detail"></i><span> Company Land Tree Reporting</span></a></li>
                            <li><a href="{{ route('tree_pointer_reporting', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                        class="bx bx-detail"></i><span> Tree Age Pointer Reporting</span></a></li>
                            <li><a href="{{ route('formula_usage_report_admin', ['tenant' => tenant('id')]) }}" class="waves-effect"><i
                                        class="bx bx-detail"></i><span> Formula Usage Reporting</span></a></li>
                            <li><a href="{{ route('budget_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span>
                                        Budget Reporting</span></a></li>
                            <li><a href="{{ route('claim_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> Staff Claim
                                Reporting</span></a></li>
                            <li><a href="{{ route('claim_pending_report', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> All Claim Reporting</span></a></li>
                            <li><a href="{{ route('profit_loss_reporting', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> Profit & Loss Reporting</span></a></li>
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Expense Reporting</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('expense_report', ['tenant' => tenant('id')]) }}">D.O. Expense</a></li>
                                    <li><a href="{{ route('company_expense', ['tenant' => tenant('id')]) }}">Company Expense</a></li>
                                    <li><a href="{{ route('farm_manager_worker_expense', ['tenant' => tenant('id')]) }}">Farm Manager Expense</a></li>
                                    <li><a href="{{ route('worker_attendance_report', ['tenant' => tenant('id')]) }}">Worker Attendance Reporting</a></li>
                                    <li><a href="{{ route('supplier_expenses_report', ['tenant' => tenant('id')]) }}">Supplier Expenses Reporting</a>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Forecast</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('tree_target_report', ['tenant' => tenant('id')]) }}"><span> Tree Target Reporting</span></a></li>
                                    <li><a href="{{ route('forecast_report', ['tenant' => tenant('id')]) }}"><span> Profit & Loss Forecast Reporting</span></a></li>
                                </ul>
                            </li>
                        </li>
                        @endif
                        <li><a href="{{ route('budget_estimate_report_listing_reporting', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> Budget Estimate Reporting</span></a></li>
                    @endcan
                    {{-- End Of Reporting --}}
                @endif

                {{-- @if (checkSubscriptionFeature('formula_usage_module')) --}}
                    {{-- Budget Estimate --}}
                    @canany(['budget_estimate_manage', 'budget_estimate_listing'])
                        <li class="menu-title">Budget Estimate</li>
                        @can('budget_estimate_listing')
                            <li><a href="{{ route('budget_estimate_report_listing', ['tenant' => tenant('id')]) }}" class="waves-effect"><i class="bx bx-detail"></i><span> Budget Estimate Listing</span></a></li>
                        @endcan
                    @endcanany
                {{-- @endif --}}

                {{-- @if (checkSubscriptionFeature('formula_usage_module')) --}}
                    {{-- Company --}}
                    @canany(['company_manage', 'company_listing'])
                        <li class="menu-title">Company</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-warehouse"></i>
                                <span>Company</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('company_manage')
                                <li><a href="{{ route('company_add', ['tenant' => tenant('id')]) }}">Add New Company</a></li>
                                @endcan
                                @can('company_listing')
                                <li><a href="{{ route('company_listing', ['tenant' => tenant('id')]) }}">Company Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                    {{-- End Company --}}
                {{-- @endif --}}

                @if (checkSubscriptionFeature('zone_module') || checkSubscriptionFeature('land_module'))
                {{-- Tree --}}
                    @can('company_land_tree_listing')
                        <li>
                            <a href="{{ route('company_land_listing', ['tenant' => tenant('id')]) }}">
                                <i class="bx bx-cog"></i>
                                <span>Zone Listing</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search_listing', ['tenant' => tenant('id')]) }}">
                                <i class="bx bx-cog"></i>
                                <span>Tree Listing (Zone)</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('company_landtree_listing', ['tenant' => tenant('id')]) }}">
                                <i class="bx bx-cog"></i>
                                <span>Tree Action</span>
                            </a>
                        </li>
                    @endcan
                @endif

                @if (checkSubscriptionFeature('customer_module'))
                    {{-- Customer --}}
                    @canany(['customer_listing', 'customer_manage', 'customer_category_listing','customer_category_manage',
                    'customer_term_listing', 'customer_term_manage'])
                        <li class="menu-title">Customer</li>
                        @canany(['customer_listing', 'customer_manage'])
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bxs-user"></i>
                                    <span>Customer</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('customer_manage')
                                    <li><a href="{{ route('customer_add', ['tenant' => tenant('id')]) }}">Add New Customer</a></li>
                                    @endcan
                                    @can('customer_listing')
                                    <li><a href="{{ route('customer_listing', ['tenant' => tenant('id')]) }}">Customer Listing</a></li>
                                    @endcan
                                    @can('setting_warehouse')
                                    <li><a href="{{ route('setting_warehouse_listing', ['tenant' => tenant('id')]) }}">Warehouse Listing</a></li>
                                    @endcan
                                    {{-- <li><a href="{{ route('customer_deliver_order_history') }}">Delivery Order History</a></li> --}}
                                </ul>
                            </li>
                        @endcanany
                        @canany(['customer_category_manage','customer_category_listing'])
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bxs-grid-alt"></i>
                                    <span>Category</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('customer_category_manage')
                                    <li><a href="{{ route('customer_category_add', ['tenant' => tenant('id')]) }}">Add New Customer Category</a></li>
                                    @endcan
                                    @can('customer_category_listing')
                                    <li><a href="{{ route('customer_category_listing', ['tenant' => tenant('id')]) }}">Customer Category Listing</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @canany(['customer_term_listing', 'customer_term_manage'])
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bx-detail"></i>
                                    <span>Term</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('customer_term_manage')
                                    <li><a href="{{ route('customer_term_add', ['tenant' => tenant('id')]) }}">Add New Term</a></li>
                                    @endcan
                                    @can('customer_term_listing')
                                    <li><a href="{{ route('customer_term_listing', ['tenant' => tenant('id')]) }}">Term Listing</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    @endcanany
                    {{-- End Customer --}}
                @endif

                @if (checkSubscriptionFeature('product_module'))
                    {{-- Product --}}
                    @canany(['product_listing', 'product_manage', 'product_category_listing', 'product_category_manage',
                    'product_tag_listing', 'product_tag_manage', 'product_stock_listing', 'product_stock_manage'])
                        <li class="menu-title">Product</li>
                        @canany(['product_listing', 'product_manage'])
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bxs-package"></i>
                                    <span>Products</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('product_manage')
                                    <li><a href="{{ route('product_add', ['tenant' => tenant('id')]) }}">Add New Product</a></li>
                                    @endcan
                                    @can('product_listing')
                                    <li><a href="{{ route('product_listing', ['tenant' => tenant('id')]) }}">Product Listing</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @canany(['product_category_listing', 'product_category_manage'])
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bxs-grid-alt"></i>
                                    <span>Category</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('product_category_manage')
                                    <li><a href="{{ route('product_category_add', ['tenant' => tenant('id')]) }}">Add Product Category</a></li>
                                    @endcan
                                    @can('product_category_listing')
                                    <li><a href="{{ route('product_category_listing', ['tenant' => tenant('id')]) }}">Product Category Listing</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @canany(['product_tag_listing', 'product_tag_manage'])
                            <li>
                                <a href="javascript:void(0)" class="has-arrow waves-effect">
                                    <i class="bx bxs-package"></i>
                                    <span>Tag</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('product_tag_manage')
                                    <li><a href="{{ route('product_tag_add', ['tenant' => tenant('id')]) }}">Add New Product Tag</a></li>
                                    @endcan
                                    @can('product_tag_listing')
                                    <li><a href="{{ route('product_tag_listing', ['tenant' => tenant('id')]) }}">Product Tag Listing</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    @endcanany
                    {{-- End Product --}}
                @endif

                @if (checkSubscriptionFeature('claim_module'))
                    @canany(['claim_listing', 'claim_manage'])
                        <li class="menu-title">Claim</li>
                        @canany(['claim_listing', 'claim_manage'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bx-cog"></i>
                                <span>Claim</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('claim_manage')
                                <li><a href="{{ route('claim_add', ['tenant' => tenant('id')]) }}">Add Claim</a></li>
                                @endcan
                                @can('claim_listing')
                                <li><a href="{{ route('claim_listing', ['tenant' => tenant('id')]) }}">Claim Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany
                    @endcanany
                @endif

                {{-- @if (checkSubscriptionFeature('claim_module')) --}}
                    @canany(['worker_manage', 'worker_listing'])
                        <li class="menu-title">Worker</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bxs-user"></i>
                                <span>Worker</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('worker_manage')
                                <li><a href="{{ route('worker_add', ['tenant' => tenant('id')]) }}">Add New Worker</a></li>
                                @endcan
                                @can('worker_listing')
                                <li><a href="{{ route('worker_listing', ['tenant' => tenant('id')]) }}">Worker Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['worker_type_manage','worker_type_listing'])
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bxs-user-detail"></i>
                                <span>Worker Type</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('worker_type_manage')
                                <li><a href="{{ route('worker_type_add', ['tenant' => tenant('id')]) }}">Add New Worker Type</a></li>
                                @endcan
                                @can('worker_type_listing')
                                <li><a href="{{ route('worker_type_listing', ['tenant' => tenant('id')]) }}">Worker Type Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                {{-- @endif --}}

                {{-- @if (checkSubscriptionFeature('claim_module')) --}}
                    @canany(['admin_manage', 'admin_listing', 'admin_role_listing', 'admin_role_manage',
                    'admin_role_listing'])
                        <li class="menu-title">Admin</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="bx bxs-user"></i>
                                <span>Admin</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('admin_manage')
                                <li><a href="{{ route('admin_add', ['tenant' => tenant('id')]) }}">Add New Admin</a></li>
                                @endcan
                                @can('admin_listing')
                                <li><a href="{{ route('admin_listing', ['tenant' => tenant('id')]) }}">Admin Listing</a></li>
                                @endcan
                            </ul>
                        </li>
                        @canany(['admin_role_manage','admin_role_listing'])
                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="bx bxs-user-detail"></i>
                                    <span>Admin Role</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    @can('admin_role_manage')
                                    <li><a href="{{ route('admin_role_add', ['tenant' => tenant('id')]) }}">Add New Admin Role</a></li>
                                    @endcan
                                    @can('admin_role_listing')
                                    <li><a href="{{ route('admin_role_listing', ['tenant' => tenant('id')]) }}">Admin Role Listing</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    @endcanany
                {{-- @endif --}}

                {{-- @if (checkSubscriptionFeature('claim_module')) --}}
                    @canany(['sync_listing', 'sync_zip_file'])
                    {{-- Sync Manangement --}}
                        <li class="menu-title">Sync Manangement</li>
                        <li>
                            <a href="javascript: void(0);" class="has-arrow waves-effect">
                                <i class="mdi mdi-database-sync"></i>
                                <span>Sync</span>
                            </a>
                            <ul class="sub-menu" aria-expanded="false">
                                @can('sync_listing')
                                <li><a href="{{ route('sync_listing', ['tenant' => tenant('id')]) }}">Sync Listing</a></li>
                                @endcan
                                @can('sync_zip_file')
                                <li><a href="{{ route('sync_zip_file', ['tenant' => tenant('id')]) }}">Sync Zip File</a></li>
                                @endcan
                                {{-- <li><a href="{{ route('sync_delivery_order_listing', ['tenant' => tenant('id')]) }}">Sync Delivery Order</a></li>
                                <li><a href="{{ route('synccustomer_listing', ['tenant' => tenant('id')]) }}">Sync Customer</a></li>
                                <li><a href="{{ route('daily_listing', ['tenant' => tenant('id')]) }}">Sync Daily In & Out</a></li> --}}
                            </ul>
                        </li>
                    {{-- END Sync Manangement --}}
                    @endcanany
                {{-- @endif --}}

                {{-- Setting --}}
                @canany(['master_setting', 'setting_payment_method', 'setting_expense', 'company_land_category_listing',
                'company_land_category_manage', 'setting_product_size','setting_message_template'])
                <li class="menu-title">Setting</li>
                @can('master_setting')
                <li>
                    <a href="{{ route('setting_listing', ['tenant' => tenant('id')]) }}" class=" waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Master Setting</span>
                    </a>
                </li>
                @endcan
                @can('setting_product_size')
                <li>
                    <a href="{{ route('setting_product_size_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Product Grade</span>
                    </a>
                </li>
                @endcan
                @canany(['company_land_category_listing', 'company_land_category_manage'])
                @can('company_land_category_listing')
                <li>
                    <a href="{{ route('company_farm_listing', ['tenant' => tenant('id')]) }}" class="waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Farm Listing</span></a>
                </li>
                @endcan
                @endcanany
                @can('setting_tree_age')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Setting Tree</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('setting_tree_age_listing', ['tenant' => tenant('id')]) }}">Setting Tree Age</a></li>
                        <li><a href="{{ route('setting_tree_age_pointer', ['tenant' => tenant('id')]) }}">Setting Tree Age Pointer</a></li>
                    </ul>
                </li>
                @endcan
                @can('setting_payment_method')
                <li>
                    <a href="{{ route('setting_payment_listing', ['tenant' => tenant('id')]) }}" class=" waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Payment Type</span>
                    </a>
                </li>
                @endcan
                @can('setting_currency_listing')
                <li>
                    <a href="{{ route('setting_currency', ['tenant' => tenant('id')]) }}" class=" waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Setting Currency</span>
                    </a>
                </li>
                @endcan

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Setting Reward</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('setting_reward', ['tenant' => tenant('id')]) }}">Setting Reward</a></li>
                        <li><a href="{{ route('setting_reward_category', ['tenant' => tenant('id')]) }}">Setting Reward Category</a></li>
                    </ul>
                </li>
                @can('setting_expense')
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-sack-percent"></i>
                        <span>Setting Expense</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('expense_listing', ['tenant' => tenant('id')]) }}">Setting Expense</a></li>
                        <li><a href="{{ route('expense_category_listing', ['tenant' => tenant('id')]) }}">Setting Expense Category</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('setting_race', ['tenant' => tenant('id')]) }}" class=" waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Setting Race</span>
                    </a>
                </li>
                @endcan
                @can('setting_message_template')
                <li>
                    <a href="{{ route('message_template_listing', ['tenant' => tenant('id')]) }}" class=" waves-effect">
                        <i class="bx bx-cog"></i>
                        <span>Message Templates</span>
                    </a>
                </li>
                @endcan
                @canany(['pnl_item_manage','pnl_item_listing'])
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="mdi mdi-sack-percent"></i>
                        <span>Profit & Loss Item</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @can('pnl_item_manage')
                        <li><a href="{{ route('company_pnl_item_add', ['tenant' => tenant('id')]) }}">Add New Profit & Loss Item</a></li>
                        @endcan

                        @can('pnl_item_listing')
                        <li><a href="{{ route('company_pnl_item_listing', ['tenant' => tenant('id')]) }}">Profit & Loss Item Listing</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany
                @endcanany
                {{-- End Setting --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
