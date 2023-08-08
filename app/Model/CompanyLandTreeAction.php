<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyLandTreeAction extends Model
{
    protected $table = 'tbl_company_land_tree_action';
    protected $primaryKey = 'company_land_tree_action_id';
    protected $dateFormat = 'Y-m-d H:i:s';
    const CREATED_AT = 'company_land_tree_action_created';
    const UPDATED_AT = 'company_land_tree_action_updated';

    protected $fillable = [
        'company_land_tree_action_name',
        'company_id',
        'company_land_tree_action_created',
        'company_land_tree_action_updated',
        'user_id',
        'is_value_required',
    ];

    public static function get_records($search)
    {
        $query = CompanyLandTreeAction::query();

        if (@$search['freetext']) {
            $freetext = $search['freetext'];
            $query->where(function ($q) use ($freetext) {
                $q->orWhere('company_land_tree_action_name', 'like', '%' . $freetext . '%');
            });
        }

        if (@$search['action_from']) {
            $query->whereDate('company_land_tree_action_created', '>=', DATE($search['action_from']) . ' 00:00:00');
        }

        if (@$search['action_to']) {
            $query->whereDate('company_land_tree_action_updated', '<=', DATE($search['action_to']) . ' 23:59:59');
        }

        if (@$search['company_land_tree_action_id']) {
            $query->where('company_land_tree_action_id', @$search['company_land_tree_action_id']);
        }

        if (@$search['company_land_tree_action_name']) {
            $query->where('company_land_tree_action_name', $search['company_land_tree_action_name']);
        }

        if (auth()->user()->company_id != 0) {
            $company_ids = array(
                0,
                auth()->user()->company_id
            );
            $query->whereIn('company_id', $company_ids);
        } else if (@$search['company_id']) {
            $query->where('company_id', $search['company_id']);
        }

        if (@$search['company_name']) {
            $query->where('company_name', $search['company_name']);
        }

        if (@$search['user_id']) {
            $query->where('user_id', $search['user_id']);
        }

        if (@$search['is_value_required']) {
            $query->where('is_value_required', $search['is_value_required']);
        }

        $query->orderBy('company_land_tree_action_id', 'asc');
        $result = $query->paginate(15);

        return $result;
    }

    public static function get_action_sel()
    {
        $result = [];
        $result = ['' => 'Please Select Action...'];
        $query = CompanyLandTreeAction::get();
        foreach ($query as $value) {
            $result[$value->company_land_tree_action_id] = $value->company_land_tree_action_name;
        }

        return $result;
    }

    public static function get_by_company_id()
    {
        $query = CompanyLandTreeAction::query();
        if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
            $ids = array();

            foreach(auth()->user()->user_company as $key => $user_company){
                $ids[$key] = $user_company->company_id;
            }

            array_push($ids, 0);
            $query->whereIn('company_id', $ids);

        }else if(auth()->user()->company_id != 0){
            $query->whereIn('company_id', [0, auth()->user()->company_id]);
        }

        $query->orderBy('company_id', 'ASC');
        $result = $query->get();
        return $result;
    }

    public function company()
    {
        return $this->belongsTo('App\Model\Company', 'company_id');
    }
}
