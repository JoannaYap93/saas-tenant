<?php

namespace App\Http\Controllers;

use App\Model\Company;
use App\Model\SettingFormula;
use App\Model\SettingFormulaCategory;
use App\Model\SettingFormulaItem;
use App\Model\SettingRawMaterial;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingFormulaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listing(Request $request){

        $perpage = 10;
        if ($request->isMethod('post')) {
            $submit_type = $request->input('submit');
            switch ($submit_type) {
                case 'search':
                    $search['freetext'] = $request->input('freetext');
                    $search['company_id'] = $request->input('company_id');
                    $search['setting_formula_category_id'] = $request->input('setting_formula_category_id');
                    $search['setting_formula_measurement'] = $request->input('setting_formula_measurement');
                    $search['setting_formula_status'] = $request->input('setting_formula_status');
                    $search['raw_material_id'] = $request->input('raw_material_id');

                    Session::put('setting_formula_search', $search);
                    break;
                case 'reset':
                    Session::forget('setting_formula_search');
                    break;
            }
        }
        $search = Session::has('setting_formula_search') ? Session::get('setting_formula_search') : array();
        $records = SettingFormula::get_records($search, $perpage);

        return view('setting_formula.listing', [
            'submit' => route('setting_formula_listing', ['tenant' => tenant('id')]),
            'title' => 'Formula',
            'records' => $records,
            'search' =>  $search,
            'company_sel' => Company::get_company_sel(),
            'setting_formula_category_sel' => SettingFormulaCategory::get_formula_category_sel(),
            'setting_formula_status_sel' => ['' => 'Please Select Status', 'pending' => 'Pending', 'active' => 'Active'],
            'setting_formula_measurement_sel' => ['' => 'Please Select Measurement', 'litre' =>'Litre', 'acres' => 'Acres' ,'pack' => 'Pack'],
            'raw_material_sel' => ['' => 'Please Select Raw Material']+ SettingRawMaterial::get_selection_2(),
        ]);
    }

    public function add(Request $request)
    {
        $validation = null;
        $post = null;
        $formula_name_multi_lang = array();

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_formula_name_en' => 'required',
                'setting_formula_name_cn' => 'required',
                'setting_formula_category_id' => 'required',
                'setting_formula_status' => 'required',
                'setting_formula_measurement' => 'required',
                'setting_formula_unit' => 'required',
            ])->setAttributeNames([
                'setting_formula_name_en' => 'Name (en)',
                'setting_formula_name_cn' => 'Name (cn)',
                'setting_formula_category_id' => 'Category',
                'setting_formula_status' => 'Status',
                'setting_formula_measurement' => 'Mesurement',
                'setting_formula_unit' => 'Unit',
            ]);
            if (!$validation->fails()) {
                $formula_name_multi_lang['en'] = $request->input('setting_formula_name_en');
                $formula_name_multi_lang['cn'] = $request->input('setting_formula_name_cn');

                $setting_formula = SettingFormula::create([
                    'setting_formula_name' => json_encode($formula_name_multi_lang),
                    'setting_formula_category_id' => $request->input('setting_formula_category_id'),
                    'setting_formula_status' => $request->input('setting_formula_status'),
                    'setting_formula_measurement' => $request->input('setting_formula_measurement'),
                    'setting_formula_unit' => $request->input('setting_formula_unit'),
                    'company_id' => auth()->user()->company_id,
                    'setting_formula_created' => now(),
                    'setting_formula_updated' => now(),
                ]);

                $setting_formula_item = $request->input('setting_formula_item');

                if(!empty($setting_formula_item)){
                    foreach($setting_formula_item as $item){
                        $raw_material_name = SettingRawMaterial::where('raw_material_id', $item['raw_material_id'])->pluck('raw_material_name')->first();
                        SettingFormulaItem::create([
                            'setting_formula_id' => $setting_formula->setting_formula_id,
                            'setting_formula_item_name' => $raw_material_name,
                            'raw_material_id' => $item['raw_material_id'],
                            'setting_formula_item_value' => $item['setting_formula_item_value'],
                            'setting_formula_item_created' => now(),
                            'setting_formula_item_updated' => now(),
                        ]);
                    }
                }

                Session::flash('success_msg', 'Successfully added '.$request->input('setting_formula_name'));
                return redirect()->route('setting_formula_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('setting_formula.form', [
            'submit' => route('setting_formula_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post'=> $post,
            'setting_formula_category_sel' => SettingFormulaCategory::get_formula_category_sel(),
            'setting_raw_material_sel' => SettingRawMaterial::get_selection_2(),
            'setting_formula_status_sel' => ['pending' => 'Pending', 'active' => 'Active'],
            'setting_formula_measurement_sel' => ['litre' =>'Litre', 'acres' => 'Acres' ,'pack' => 'Pack'],
            'setting_formula_unit_sel' => ['kg' =>'KG', 'litre' => 'Litre'],
        ])->withErrors($validation);
    }

    public function edit(Request $request, $setting_formula_id)
    {
        $post = SettingFormula::get_by_id($setting_formula_id);
        $validation = null;
        $formula_name_multi_lang = array();

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'setting_formula_name_en' => 'required',
                'setting_formula_name_cn' => 'required',
                'setting_formula_category_id' => 'required',
                'setting_formula_status' => 'required',
                'setting_formula_measurement' => 'required',
                'setting_formula_unit' => 'required',
            ])->setAttributeNames([
                'setting_formula_name_en' => 'Name',
                'setting_formula_name_cn' => 'Name',
                'setting_formula_category_id' => 'Category',
                'setting_formula_status' => 'Status',
                'setting_formula_measurement' => 'Mesurement',
                'setting_formula_unit' => 'Unit',
            ]);
            if (!$validation->fails()) {
                $formula_name_multi_lang['en'] = $request->input('setting_formula_name_en');
                $formula_name_multi_lang['cn'] = $request->input('setting_formula_name_cn');

                $post->update([
                    'setting_formula_name' => json_encode($formula_name_multi_lang),
                    'setting_formula_category_id' => $request->input('setting_formula_category_id'),
                    'setting_formula_status' => $request->input('setting_formula_status'),
                    'setting_formula_measurement' => $request->input('setting_formula_measurement'),
                    'setting_formula_unit' => $request->input('setting_formula_unit'),
                    'company_id' =>  auth()->user()->company_id,
                    'setting_formula_updated' => now(),
                ]);

                $setting_formula_item = $request->input('setting_formula_item');

                $new_item_array = array();
                $existing_item_array = array();

                if(!empty($setting_formula_item)){
                    foreach($setting_formula_item as $item){
                        if(@$item['setting_formula_item_id']){
                            array_push($new_item_array, $item['setting_formula_item_id']);
                              $raw_material_name = SettingRawMaterial::where('raw_material_id', $item['raw_material_id'])->pluck('raw_material_name')->first();
                            SettingFormulaItem::where([
                                'setting_formula_item_id' => $item['setting_formula_item_id']
                            ])->update([
                                'setting_formula_id' => $post->setting_formula_id,
                                'setting_formula_item_name' => $raw_material_name,
                                'raw_material_id' => $item['raw_material_id'],
                                'setting_formula_item_value' => $item['setting_formula_item_value'],
                                'setting_formula_item_created' => now(),
                                'setting_formula_item_updated' => now(),
                            ]);
                        }else{
                          $raw_material_name = SettingRawMaterial::where('raw_material_id', $item['raw_material_id'])->pluck('raw_material_name')->first();
                            SettingFormulaItem::create([
                                'setting_formula_id' => $post->setting_formula_id,
                                'setting_formula_item_name' => $raw_material_name,
                                'raw_material_id' => $item['raw_material_id'],
                                'setting_formula_item_value' => $item['setting_formula_item_value'],
                                'setting_formula_item_created' => now(),
                                'setting_formula_item_updated' => now(),
                            ]);
                        }
                    }
                }

                if($post->setting_formula_item){
                    foreach($post->setting_formula_item as $existing_item){
                        array_push($existing_item_array, $existing_item['setting_formula_item_id']);
                    }
                }

                $item_delete_array = [];

                $item_delete_array = array_diff($existing_item_array,$new_item_array);

                foreach($item_delete_array as $item_id){
                    SettingFormulaItem::where([
                        'setting_formula_item_id' => $item_id,
                    ])->delete();
                }

                Session::flash('success_msg', 'Successfully edited '.$request->input('setting_formula_name'));
                return redirect()->route('setting_formula_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }

        return view('setting_formula.form', [
            'submit' => route('setting_formula_edit', ['tenant' => tenant('id'), 'id' => $setting_formula_id]),
            'title' => 'Edit',
            'post'=> $post,
            'setting_formula_category_sel' => SettingFormulaCategory::get_formula_category_sel(),
            'setting_raw_material_sel' => SettingRawMaterial::get_selection_2(),
            'setting_formula_status_sel' => ['pending' => 'Pending', 'active' => 'Active'],
            'setting_formula_measurement_sel' => ['litre' =>'Litre', 'acres' => 'Acres' ,'pack' => 'Pack'],
            'setting_formula_unit_sel' => ['kg' =>'KG', 'litre' => 'Litre'],
        ])->withErrors($validation);
    }

    public function delete(Request $request)
    {
        $setting_formula = SettingFormula::find($request->input('setting_formula_id'));

        if(!$setting_formula){
            Session::flash('failed_msg', 'Error, Please try again later..');
            return redirect()->route('setting_formula_listing', ['tenant' => tenant('id')]);
        }

       $setting_formula->update([
           'setting_formula_status' => 'deleted'
       ]);

        Session::flash('success_msg', "Successfully deleted setting formula.");
        return redirect()->route('setting_formula_listing', ['tenant' => tenant('id')]);
    }

    public function ajax_get_rm_name_for_placeholder(Request $request)
    {
        $raw_material_id = $request->input('rm_id');
        // $raw_material_id = 2;
        $raw_material_value_unit = SettingRawMaterial::where('raw_material_id', $raw_material_id)->pluck('raw_material_value_unit')->first();
        // dd($raw_material_value_unit);
        return $raw_material_value_unit;
    }
}
