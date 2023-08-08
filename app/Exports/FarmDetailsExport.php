<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FarmDetailsExport implements FromView, ShouldAutoSize {

     //use Exportable;

    /**
     * @return \Illuminate\Support\Collection
     * @return \Illuminate\Database\Eloquent\Model|null
     */

     public $view, $data;

     public function __construct($view, $data, $land, $company)
     {
          $this->view = $view;
          $this->data = $data;
          $this->land = $land;
          $this->company = $company;
     }

     public function view(): View {
          return view($this->view, ['records' => $this->data, 'land' => $this->land, 'company' => $this->company]);
     }

}
