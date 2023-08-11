<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\SettingRawMaterial;
use App\Model\SettingRawMaterialCategory;
use App\Model\SettingFormula;
use App\Model\SettingFormulaItem;
use App\Model\RawMaterialCompany;
use App\Model\Company;
use App\Model\CompanyLand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SettingRawMaterialController extends Controller
{
    public function construct()
    {
        $this->middleware(['auth']);
    }

    public function listing(Request $request)
    {
        if ($request->isMethod('post')) {
        $submit = $request->input('submit');
        switch ($submit) {
            case 'search':
                Session::forget('listing');
                $search['freetext'] = $request->input('freetext');
                $search['raw_material_category_id'] = $request->input('raw_material_category_id');
                $search['raw_material_quantity_unit'] = $request->input('raw_material_quantity_unit');
                $search['raw_material_value_unit'] = $request->input('raw_material_value_unit');


                Session::put('listing', $search);
                break;
            case 'reset':
                Session::forget('listing');
                break;
            }
         }

        $search = session('listing') ?? array();
        $get_records = SettingRawMaterial::get_records($search);

        return view('setting_raw_material.listing', [
            'page_title' => 'Setting Raw Material',
            'submit' => route('setting_raw_material_listing', ['tenant' => tenant('id')]),
            'search' => $search,
            'records' => SettingRawMaterial::get_records($search),
            'raw_material_status_sel'=>[''=>'Please select status','active' =>'Active', 'pending' => 'Pending', 'deleted' =>'Deleted'],
            'quantity_sel' => SettingRawMaterial::get_quantity_sel(),
            'value_sel' => SettingRawMaterial::get_value_sel(),
            'category_sel' => SettingRawMaterialCategory::get_category_sel(),
            ]);
        }


    public function add(Request $request)
    {
        $validator = null;
        $post = null;
        $material_name_multi_lang = array();
        $json_to_string_multi_lang_name = null;
        $array_name_to_json = null;

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'raw_material_id' => '',
                'raw_material_name_en' => 'required',
                'raw_material_name_cn' => 'required',
                'raw_material_status' => 'required',
                'raw_material_category_id' => 'required',
                'raw_material_quantity_unit' => 'required',
                'raw_material_value_unit' => 'required',
            ])->setAttributeNames([
                'raw_material_name_en' => 'Name',
                'raw_material_name_cn' => 'Name',
                'raw_material_status' => 'Status',
                'raw_material_category_id' => 'Category',
                'raw_material_quantity_unit' => 'Quantity',
                'raw_material_value_unit' => 'Value',
            ]);

            if (!$validator->fails()) {

                $material_name_multi_lang['en'] = $request->input('raw_material_name_en');
                $material_name_multi_lang['cn'] = $request->input('raw_material_name_cn');
                $array_name_to_json = json_encode($material_name_multi_lang);
                $json_to_string_multi_lang_name = strval($array_name_to_json);

                $setting_raw_material = SettingRawMaterial::create([
                    'raw_material_name' => $json_to_string_multi_lang_name ?? '',
                    'raw_material_status' => $request->input('raw_material_status') ?? '',
                    'raw_material_category_id' => $request->input('raw_material_category_id') ?? '',
                    'raw_material_quantity_unit' => $request->input('raw_material_quantity_unit') ?? '',
                    'raw_material_value_unit' => $request->input('raw_material_value_unit') ?? '',
                ]);
                $company_cb = Company::get_company_check_box();
                $selected_company = $request->input('company_cb_id');
                if(!empty($company_cb)){
                    foreach($selected_company as $key => $selected_company_id){
                        $company_lands = CompanyLand::get_by_company_id($selected_company_id);
                        // if(!empty($company_lands)){
                            // foreach($company_lands as $company_land){
                                $raw_material_company = RawMaterialCompany::create([
                                    'raw_material_id' => $setting_raw_material->raw_material_id,
                                    'company_id' => $selected_company_id,
                                    // 'company_land_id' => $company_land->company_land_id,
                                    'raw_material_quantity' =>'',
                                    'raw_material_value' =>'',
                                    'raw_material_company_status' => 'active',
                                ]);
                            // }
                        // }
                        $cat_id = $setting_raw_material->raw_material_category_id;
                        if($cat_id == 1 || $cat_id == 4){
                            $setting_formula = SettingFormula::create([
                                'setting_formula_name' =>  $setting_raw_material->raw_material_name,
                                'setting_formula_category_id' => $setting_raw_material->raw_material_category_id,
                                'setting_formula_status' => $request->input('setting_formula_status') ?? 'active',
                                'setting_formula_measurement' => $this->get_formula_measurement($setting_raw_material->raw_material_category_id),
                                'company_id' => $selected_company_id,
                            ]);
                            SettingFormulaItem::create([
                                'setting_formula_id' => $setting_formula->setting_formula_id ?? '',
                                'setting_formula_item_name' => $setting_raw_material->raw_material_name,
                                'raw_material_id' => $setting_raw_material->raw_material_id,
                                'setting_formula_item_value' => $request->input('setting_formula_item_value') ?? '',
                            ]);

                        }
                    }
                }
                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('setting_raw_material_listing', ['tenant' => tenant('id')]);
            }
        }

        return view('setting_raw_material.form', [
            'submit' => route('setting_raw_material_add', ['tenant' => tenant('id')]),
            'title' => 'Add',
            'post' => $post,
            'raw_material_status_sel'=>[''=>'Please select status','active' =>'Active', 'pending' => 'Pending', 'deleted' =>'Deleted'],
            'raw_material_category_sel' => SettingRawMaterialCategory::get_category_sel(),
            'raw_material_quantity_unit_sel'=> SettingRawMaterial::get_quantity_sel(),
            'raw_material_value_unit_sel'=> SettingRawMaterial::get_value_sel(),
            'company_cb' => Company::get_company_check_box(),
        ])->withErrors($validator);
    }

    public function get_formula_measurement($raw_material_category_id)
    {
        $getcatId =  $raw_material_category_id;

        if($getcatId==1){
            $formula_measurement = "pack";
        }elseif($getcatId==2){
            $formula_measurement = "litre";
        }else{
            $formula_measurement = "acres";
        }

        return $formula_measurement;
    }

    public function edit(Request $request, $raw_material_id)
    {
        $post = SettingRawMaterial::find($raw_material_id);
        $validation = null;
        $material_name_multi_lang = array();
        $json_to_string_multi_lang_name = null;
        $array_name_to_json = null;

        // dd($request->all());
        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'raw_material_id' => '',
                'raw_material_name_en' => 'required',
                'raw_material_name_cn' => 'required',
                'raw_material_status' => 'required',
                'raw_material_category_id' => 'required',
                'raw_material_quantity_unit' => 'required',
                'raw_material_value_unit' => 'required',
            ])->setAttributeNames([
                'raw_material_name_en' => 'Name',
                'raw_material_name_cn' => 'Name',
                'raw_material_status' => 'Status',
                'raw_material_category_id' => 'Category',
                'raw_material_quantity_unit' => 'Quantity',
                'raw_material_value_unit' => 'Value',
            ]);
            if (!$validation->fails()) {
                $material_name_multi_lang['en'] = $request->input('raw_material_name_en');
                $material_name_multi_lang['cn'] = $request->input('raw_material_name_cn');
                $array_name_to_json = json_encode($material_name_multi_lang);
                $json_to_string_multi_lang_name = strval($array_name_to_json);

                $post->update([
                    'raw_material_name' => $json_to_string_multi_lang_name ?? '',
                    'raw_material_status' => $request->input('raw_material_status') ?? '',
                    'raw_material_category_id' => $request->input('raw_material_category_id') ?? '',
                    'raw_material_quantity_unit' => $request->input('raw_material_quantity_unit') ?? '',
                    'raw_material_value_unit' => $request->input('raw_material_value_unit') ?? '',
                ]);

                $existing_cb_id = RawMaterialCompany::where('raw_material_id', $raw_material_id)->pluck('company_id')->toArray();
                $selected_cb_id = $request->input('company_cb_id') ?? array();
                $remove_cb = array_diff($existing_cb_id, $selected_cb_id);
                $add_cb = array_diff($selected_cb_id, $existing_cb_id);

                if($add_cb != null && $remove_cb == null){
                    foreach ($add_cb as $index => $id) {
                        // $company_lands = CompanyLand::get_by_company_id($id);
                        // if(!empty($company_lands)){
                            // foreach($company_lands as $company_land){
                                $raw_material_company = RawMaterialCompany::create([
                                    'raw_material_id' => $post->raw_material_id,
                                    'company_id' => $id,
                                    // 'company_land_id' => $company_land->company_land_id,
                                    'raw_material_quantity' =>'',
                                    'raw_material_value' =>'',
                                    'raw_material_company_status' => 'active',
                                ]);
                                $cat_id = $post->raw_material_category_id;
                                if($cat_id == 1 || $cat_id == 4){
                                    $setting_formula = SettingFormula::create([
                                        'setting_formula_name' =>  $post->raw_material_name,
                                        'setting_formula_category_id' => $post->raw_material_category_id,
                                        'setting_formula_status' => $request->input('setting_formula_status') ?? 'active',
                                        'setting_formula_measurement' => $this->get_formula_measurement($post->raw_material_category_id),
                                        'company_id' => $id,
                                    ]);
                                    SettingFormulaItem::create([
                                        'setting_formula_id' => $setting_formula->setting_formula_id ?? '',
                                        'setting_formula_item_name' => $post->raw_material_name,
                                        'raw_material_id' => $post->raw_material_id,
                                        'setting_formula_item_value' => $request->input('setting_formula_item_value') ?? '',
                                    ]);
                                }
                            // }
                        // }
                    }
                }elseif($add_cb == null && $remove_cb != null){
                foreach ($remove_cb as $index => $id){
                    RawMaterialCompany::where('company_id', $id)->where('raw_material_id', $raw_material_id)->delete();
                }
                }elseif($add_cb != null && $remove_cb != null){
                    foreach($remove_cb as $index => $id){
                    RawMaterialCompany::where('company_id', $id)->where('raw_material_id', $raw_material_id)->delete();
                    }
                    foreach($add_cb as $index => $id){
                    // $company_lands = CompanyLand::get_by_company_id($id);
                        // if(!empty($company_lands)){
                            // foreach($company_lands as $company_land){
                                $raw_material_company = RawMaterialCompany::create([
                                    'raw_material_id' => $post->raw_material_id,
                                    'company_id' => $id,
                                    // 'company_land_id' => $company_land->company_land_id,
                                    'raw_material_quantity' =>'',
                                    'raw_material_value' =>'',
                                    'raw_material_company_status' => 'active',
                                ]);
                                $cat_id = $post->raw_material_category_id;
                                if($cat_id == 1 || $cat_id == 4){
                                    $setting_formula = SettingFormula::create([
                                        'setting_formula_name' =>  $post->raw_material_name,
                                        'setting_formula_category_id' => $post->raw_material_category_id,
                                        'setting_formula_status' => $request->input('setting_formula_status') ?? 'active',
                                        'setting_formula_measurement' => $this->get_formula_measurement($post->raw_material_category_id),
                                        'company_id' => $id,
                                    ]);
                                    SettingFormulaItem::create([
                                        'setting_formula_id' => $setting_formula->setting_formula_id ?? '',
                                        'setting_formula_item_name' => $post->raw_material_name,
                                        'raw_material_id' => $post->raw_material_id,
                                        'setting_formula_item_value' => $request->input('setting_formula_item_value') ?? '',
                                    ]);
                                }
                            // }
                        // }
                    }
                }elseif($add_cb == null && $remove_cb == null){
                    $selected_company = $request->input('company_cb_id');
                    $cat_id = $post->raw_material_category_id;
                    $existing_setting_formula_item = SettingFormulaItem::get_setting_formula($raw_material_id);
                    $setting_formula_item = SettingFormulaItem::where('raw_material_id', $raw_material_id)->get();
                        if($cat_id == 1 || $cat_id == 4){
                            if ($existing_setting_formula_item->isEmpty()){
                                foreach($selected_company as $key => $selected_company_id){
                                    $setting_formula = SettingFormula::create([
                                        'setting_formula_name' =>  $post->raw_material_name,
                                        'setting_formula_category_id' => $cat_id,
                                        'setting_formula_status' => $request->input('setting_formula_status') ?? 'active',
                                        'setting_formula_measurement' => $this->get_formula_measurement($cat_id),
                                        'company_id' => $selected_company_id,
                                    ]);
                                    SettingFormulaItem::create([
                                        'setting_formula_id' => $setting_formula->setting_formula_id,
                                        'setting_formula_item_name' => $post->raw_material_name,
                                        'raw_material_id' => $post->raw_material_id,
                                        'setting_formula_item_value' => $request->input('setting_formula_item_value') ?? '',
                                    ]);
                                }
                            }
                            elseif ($existing_setting_formula_item->isNotEmpty()){
                                $formula = SettingFormula::with('setting_formula_item')->whereHas('setting_formula_item', function($q) use($raw_material_id){
                                    $q->where('raw_material_id',$raw_material_id);
                                })->whereIn('company_id', $request->input('company_cb_id'))->get();
                                foreach($formula as $formula){
                                    foreach($selected_company as $key => $selected_company_id){
                                        if($selected_company_id == $formula->company_id){
                                            $formula->update([
                                                'setting_formula_name' =>  $post->raw_material_name,
                                                'setting_formula_category_id' => $cat_id,
                                                'setting_formula_status' => $request->input('setting_formula_status') ?? 'active',
                                                'setting_formula_measurement' => $this->get_formula_measurement($cat_id),
                                                'company_id' => $selected_company_id,
                                            ]);
                                            $setting_formula_item = SettingFormulaItem::where([
                                                    'raw_material_id' => $raw_material_id,
                                                    'setting_formula_id' => $formula->setting_formula_id,
                                                ])->first();
                                            $setting_formula_item->update([
                                                'setting_formula_id' => $formula->setting_formula_id,
                                                'setting_formula_item_name' => $post->raw_material_name,
                                                'raw_material_id' => $post->raw_material_id,
                                                'setting_formula_item_value' => $request->input('setting_formula_item_value') ?? '',
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                        elseif ($cat_id == 2 || $cat_id == 3){
                            SettingFormula::query()->whereIn('setting_formula_id', @$setting_formula_item->pluck('setting_formula_id'))->delete();
                            SettingFormulaItem::where('raw_material_id', $raw_material_id)->delete();
                        }
                }
                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_raw_material_listing', ['tenant' => tenant('id')]);
            }
            $post = (object) $request->all();
        }
        return view('setting_raw_material.form', [
            'submit' => route('setting_raw_material_edit', ['tenant' => tenant('id'), 'id' => $raw_material_id]),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
            'raw_material_status_sel'=>[''=>'Please select status','active' =>'Active', 'pending' => 'Pending', 'deleted' =>'Deleted'],
            'raw_material_category_sel' => SettingRawMaterialCategory::get_category_sel(),
            'raw_material_quantity_unit_sel'=> SettingRawMaterial::get_quantity_sel(),
            'raw_material_value_unit_sel'=> SettingRawMaterial::get_value_sel(),
            'company_cb' => Company::get_company_check_box(),

        ])->withErrors($validation);
    }
}
