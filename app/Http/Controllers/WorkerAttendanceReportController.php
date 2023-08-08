<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\Worker;
use App\Model\Company;
use App\Model\Reporting;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\WorkerRole;
use App\Model\CompanyLand;
use Illuminate\Http\Request;
use App\Model\WorkerAttendanceReport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Exports\WorkerAttendanceReportingExport;

class WorkerAttendanceReportController extends Controller
{
    public function worker_attendance_report(Request $request)
    {
        $validation = null;
        $day_array = [];
        $search['year'] = date('Y');
        if(empty($request->input('month'))){
            $search['month'] = ltrim(date('m'), '0');
        }else{
            $search['month'] = ltrim($request->input('month'), '0');
        }
        if($search['month'] == ltrim(date('m'), '0')){
            $current_day = ltrim(date('d'), '0');
        }else{
            $current_day = Carbon::createFromFormat('m', $search['month'])
                            ->endOfMonth()
                            ->format('d');
        }

        $days_range = Carbon::now()->month($search['month'])->daysInMonth;
        for($i = 1; $i < $days_range+1; $i++)
        {
            $day_array[$i] = $i;
        }

        if (isset($_GET['reset'])) {
            Session::forget('worker_attendance_report');
        }
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'year' => 'required',
                'month' => 'required'
            ]);

            if (!$validation->fails()) {
                $submit = $request->input('submit');
                switch ($submit) {
                    case 'search':
                        Session::forget('worker_attendance_report');
                        $search['year'] = $request->input('year');
                        $search['month'] = ltrim($request->input('month'), '0');
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['worker_role_id'] = $request->input('worker_role_id');
                        $search['company_cb_id'] = $request->input('company_cb_id');

                        Session::put('worker_attendance_report', $search);
                        break;
                    case 'reset':
                        Session::forget('worker_attendance_report');
                        break;
                    case 'export':
                        $search['year'] = $request->input('year');
                        $search['month'] = ltrim($request->input('month'), '0');;
                        $search['company_id'] = $request->input('company_id');
                        $search['company_land_id'] = $request->input('company_land_id');
                        $search['worker_role_id'] = $request->input('worker_role_id');

                        $records = WorkerAttendanceReport::get_worker_attendance_report($search);
                        $month_sel = Reporting::get_month_w_filter($search);
                        $worker_list = Worker::get_worker_farm_manager_by_company($search);
                        Session::put('worker_attendance_report', $search);
                        return Excel::download(new WorkerAttendanceReportingExport('components/worker_attendance_report_component', $records, $day_array, $month_sel, $search, $worker_list,$current_day), 'worker_attendance_report.xlsx');
                        break;
                    }
                }
            }

        $records = WorkerAttendanceReport::get_worker_attendance_report($search);

        return view('report.worker_attendance_report', [
            'submit' => route('worker_attendance_report'),
            'search' => $search,
            'day_array' => $day_array,
            'records' => $records,
            'current_day' => $current_day,
            'worker_list' => Worker::get_worker_farm_manager_by_company($search),
            'month_sel' => Reporting::get_month_w_filter($search),
            'worker_role_sel' => WorkerRole::get_worker_role_sel(),
            'company_land' => CompanyLand::get_company_land_name($search),
            'company_sel' => Company::get_company_sel(),
            'company_cb' => Company::get_company_check_box(),
        ]);
    }
}
