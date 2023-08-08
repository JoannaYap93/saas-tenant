<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Model\SettingRawMaterialCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SettingRawMaterialCategoryController extends Controller
{
    public function construct()
    {
        $this->middleware(['auth']);
    }

    public function listing_category(Request $request)
    {
        if ($request->isMethod('post')) {
        $submit = $request->input('submit');
        switch ($submit) {
            case 'search':
                Session::forget('listing');
                $search['freetext'] = $request->input('freetext');

                Session::put('listing', $search);
                break;
            case 'reset':
                Session::forget('listing');
                break;
            }
         }

        $search = session('listing') ?? array();
        $get_records = SettingRawMaterialCategory::get_records($search);

        return view('setting_raw_material_category.listing', [
            'page_title' => 'Setting Raw Material',
            'submit' => route('setting_raw_material_category_listing'),
            'search' => $search,
            'records' => SettingRawMaterialCategory::get_records($search),
            ]);
        }


    public function add_category(Request $request)
    {
        $validator = null;
        $post = null;
        $category_name_multi_lang = array();

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'raw_material_category_id' => '',
                'raw_material_category_name_en' => 'required',
                'raw_material_category_name_cn' => 'required',
            ])->setAttributeNames([
                'raw_material_category_id' => '',
                'raw_material_category_name_en' => 'Name (EN)',
                'raw_material_category_name_cn' => 'Name (CN)',
            ]);

            if (!$validator->fails()) {
                $category_name_multi_lang['en'] = $request->input('raw_material_category_name_en');
                $category_name_multi_lang['cn'] = $request->input('raw_material_category_name_cn');

                SettingRawMaterialCategory::create([
                    'raw_material_category_name' => json_encode($category_name_multi_lang) ?? '',
                ]);

                Session::flash('success_msg', 'Successfully Updated');
                return redirect()->route('setting_raw_material_category_listing');
            }
        }
        return view('setting_raw_material_category.form', [
            'submit' => route('setting_raw_material_category_add'),
            'title' => 'Add',
            'post' => $post,
        ])->withErrors($validator);

    }

    public function edit_category(Request $request, $raw_material_category_id)
    {
        $post = SettingRawMaterialCategory::find($raw_material_category_id);
        $validation = null;
        $category_name_multi_lang = array();

        if ($request->isMethod('post')) {
            $validation = Validator::make($request->all(), [
                'raw_material_category_name_en' => 'required',
                'raw_material_category_name_cn' => 'required',
            ])->setAttributeNames([
                'raw_material_category_name_en' => 'Name (EN)',
                'raw_material_category_name_cn' => 'Name (CN)',
            ]);
            if (!$validation->fails()) {
                $category_name_multi_lang['en'] = $request->input('raw_material_category_name_en');
                $category_name_multi_lang['cn'] = $request->input('raw_material_category_name_cn');

                $post->update([
                  'raw_material_category_name' => json_encode($category_name_multi_lang),
                ]);
                Session::flash('success_msg', 'Successfully edited ');
                return redirect()->route('setting_raw_material_category_listing');
            }
            $post = (object) $request->all();

        }
        return view('setting_raw_material_category.form', [
            'submit' => route('setting_raw_material_category_edit', $raw_material_category_id),
            'edit' => true,
            'title' => 'Edit',
            'post'=> $post,
        ])->withErrors($validation);
      }
}
