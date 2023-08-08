<?php

namespace App\View\Components;

use App\Model\Company;
use App\Model\CompanyLand;
use App\Model\Product;
use Carbon\CarbonPeriod;
use Illuminate\View\Component;

class AverageSummaryReport extends Component
{
    public $records;
    public $search;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($records, $search)
    {
        $this->records = $records;
        $this->search = $search;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.average_summary_report', [
            'search' => $this->search,
            'records' => $this->records,
            'product_list' => Product::get_w_size_by_company($this->search),
            'date_range' => CarbonPeriod::create($this->search['date_from'], $this->search['date_to']),
            'company_name' => Company::get_company_name_by_id(@$this->search['company_id']),
            'company_land_name' => CompanyLand::get_company_land_name_by_id(@$this->search['company_land_id']),
        ]);
    }
}
