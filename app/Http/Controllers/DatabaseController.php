<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\CompanyExpense;
use App\Model\CompanyExpenseLand;
use App\Model\Worker;
use App\Model\WorkerType;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class DatabaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function compare(Request $request){

        if(Auth::id() != '1'){
            return redirect()->route('dashboard', ['tenant' => tenant('id')]);
        }

        $db_live = DB::connection();
        $db_staging = DB::connection('mysql_second');

        $tables_live = $db_live->select('SHOW TABLES');
        $tables_live = array_map('current',$tables_live);
        sort($tables_live);

        $tables_staging = $db_staging->select('SHOW TABLES');
        $tables_staging = array_map('current',$tables_staging);
        sort($tables_staging);

        $result_live=array_diff($tables_live,$tables_staging);
        $result_staging=array_diff($tables_staging,$tables_live);

        if ($request->isMethod('post')) {
            foreach($result_staging as $rows){
                $table_properties = $db_staging->select('SHOW CREATE TABLE ' . $rows);
                $create_table = (array)$table_properties[0];
                $db_live->statement($create_table['Create Table']);
            }
            Session::flash('success_msg', 'Successfully sync DB');
            return redirect()->route('compare_db', ['tenant' => tenant('id')]);
        }

        return view('database.compare', [
            'submit' => route('compare_db', ['tenant' => tenant('id')]),
            'title' => 'Compare DB',
            'result_live' => $result_live,
            'result_staging' =>  $result_staging,
        ]);
    }

    public function change_company_expense_land(Request $request, $pw){

      if($pw != ' ' && $pw == '20221028'){
        $company_expense = CompanyExpense::all();
        foreach ($company_expense as $ckey => $ce) {
          if($ce->company_land_id > 0 && count($ce->company_expense_land) <= 0){
            // dd($ce);
            CompanyExpenseLand::create([
              'company_expense_id' => $ce->company_expense_id,
              'company_land_id' => $ce->company_land_id,
              'company_expense_land_total_tree' => 0,
              'company_expense_land_total_price' => 0
            ]);

            // $ce->update([
            //   'company_land_id' => 0
            // ]);
          }elseif($ce->company_land_id > 0 && count($ce->company_expense_land) > 0){
            // $ce->update([
            //   'company_land_id' => 0
            // ]);
          }

        }
        return redirect()->route('company_expense_listing', ['tenant' => tenant('id')]);
      }else{
        return redirect()->route('dashboard', ['tenant' => tenant('id')]);
      }
    }
}
