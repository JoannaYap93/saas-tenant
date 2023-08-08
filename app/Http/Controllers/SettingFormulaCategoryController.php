<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Setting;
use App\Model\SettingFormula;
use App\Model\SettingFormulaCategory;
use Session;

class SettingFormulaCategoryController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'super_admin'], ['except' => ['listing']]);
    // }

    public function listing(Request $request)
    {
        $search = array();

        if ($request->isMethod('post')) {
            $submit = $request->input('submit');
            switch ($submit) {
                case 'search':
                    Session::forget('formula_category_listing');
                    $search['freetext'] = $request->input('freetext');

                    Session::put('formula_category_listing', $search);
                    break;
                case 'reset':
                    Session::forget('formula_category_listing');
                    break;
                }
             }

            $search = session('formula_category_listing') ?? array();

        return view('setting_formula_category.listing', [
            'submit' => route('setting_formula_category_listing'),
            'search' => $search,
            'records' => SettingFormulaCategory::get_records($search),
        ]);
    }

    public function edit(Request $request, $setting_formula_category_id)
    {

        $validation = null;
        $formula_category_name_multi_lang = array();

        $post = $setting_formula_category = SettingFormulaCategory::find($setting_formula_category_id);
        $post->setting_formula_category_name_en = json_decode(@$setting_formula_category->setting_formula_category_name)->en;
        $post->setting_formula_category_name_cn = json_decode(@$setting_formula_category->setting_formula_category_name)->cn;

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
              'setting_formula_category_name_en' => 'required',
              'setting_formula_category_name_cn' => 'required',
            ])->setAttributeNames([
                'setting_formula_category_name_en' => 'setting_formula_category_name_en',
                'setting_formula_category_name_cn' => 'setting_formula_category_name_cn',
            ]);
            if (!$validation->fails()) {
                $formula_category_name_multi_lang['en'] = $request->input('setting_formula_category_name_en');
                $formula_category_name_multi_lang['cn'] = $request->input('setting_formula_category_name_cn');

                $setting_formula_category->update([
                  'setting_formula_category_name' => json_encode($formula_category_name_multi_lang),
                  'is_budget_limited' => $request->input('is_budget_limited') ?? 0,
                  'setting_formula_category_budget' => $request->input('setting_formula_category_budget')
                ]);
                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_formula_category_listing');
            }
            $post = (object) $request->all();
        }

        return view('setting_formula_category.form', [
            'submit' => route('setting_formula_category_edit', $setting_formula_category_id),
            'title' => 'Edit',
            'post' => $post,
        ])->withErrors($validation);
    }

    public function add(Request $request)
    {
        $validator = null;
        $post = null;

        $formula_category_name_multi_lang = array();

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'setting_formula_category_name_en' => 'required',
                'setting_formula_category_name_cn' => 'required',
            ])->setAttributeNames([
                'setting_formula_category_name_en' => 'setting_formula_category_name_en',
                'setting_formula_category_name_cn' => 'setting_formula_category_name_cn',
            ]);

            if (!$validator->fails()) {
                $formula_category_name_multi_lang['en'] = $request->input('setting_formula_category_name_en');
                $formula_category_name_multi_lang['cn'] = $request->input('setting_formula_category_name_cn');

                SettingFormulaCategory::create([
                    'setting_formula_category_name' => json_encode($formula_category_name_multi_lang) ?? '',
                    'is_budget_limited' => $request->input('is_budget_limited') ?? 0,
                    'setting_formula_category_budget' => $request->input('setting_formula_category_budget')
                ]);

                Session::flash('success_msg', 'Successfully Added');
                return redirect()->route('setting_formula_category_listing');
            }
            $post = (object) $request->all();
        }
        return view('setting_formula_category.form', [
            'submit' => route('setting_formula_category_add'),
            'title' => 'Add',
            'post' => $post,
        ])->withErrors($validator);

    }

    public function delete(Request $request)
    {
        $setting_formula_category_id = $request->input('setting_formula_category_id');
        $setting_formula_category = SettingFormulaCategory::find($setting_formula_category_id);
        $setting_formula = SettingFormula::find($setting_formula_category_id);

        $setting_formula = SettingFormula::query()
                            ->where('setting_formula_category_id', $setting_formula_category_id)
                            ->get();

        if(!is_null($setting_formula))
        {
            foreach($setting_formula as $formula)
            {
                $setting_formula_id = $formula->setting_formula_id;
                $setting_formula_ids = SettingFormula::find($setting_formula_id);

                if($setting_formula_ids)
                {
                        Session::flash('fail_msg', 'Invalid Setting Formula Category, Please Try Again.');
                        return redirect()->route('setting_formula_category_listing');
                }
            }

        }
                $setting_formula_category->delete();

        Session::flash('success_msg', 'Successfully Deleted Setting Formula Category');
        return redirect()->route('setting_formula_category_listing');
    }
}
