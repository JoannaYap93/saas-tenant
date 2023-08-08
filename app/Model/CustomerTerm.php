<?php

namespace App\Model;

use DB;
use Illuminate\Database\Eloquent\Model;

class CustomerTerm extends Model
{
  protected $table = 'tbl_customer_term';

  protected $primaryKey = 'customer_term_id';

  const CREATED_AT = 'customer_term_created';
  const UPDATED_AT = 'customer_term_updated';
  protected $dateFormat = 'Y-m-d H:i:s';

  protected $fillable = [
    'customer_term_name', 'is_deleted', 'customer_term_created', 'customer_term_updated', 'company_id'
  ];

  public static function get_record($search, $perpage)
  {
    $customer_term = CustomerTerm::query();

    // if (auth()->user()->company_id != 0) {
    //   $customer_term = $customer_term->where('company_id', auth()->user()->company_id);
    // }
    // dd($search);

    if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
      $ids = array();
      foreach(auth()->user()->user_company as $key => $user_company){
        // $company->where('company_id', $user_company->company_id);
        $ids[$key] = $user_company->company_id;
        // dd($ids[$key]);
      }
      $customer_term->whereIn('company_id', $ids);
    }else if(auth()->user()->company_id != 0){
       $customer_term->where('company_id', auth()->user()->company_id);
    }

    if (@$search['freetext']) {
      $freetext = $search['freetext'];
      $customer_term->where(function ($q) use ($freetext) {
        $q->where('tbl_customer_term.customer_term_name', 'like', '%' . $freetext . '%');
      });
    }
    if (@$search['is_deleted']) {
      $customer_term = $customer_term->where('tbl_customer_term.is_deleted', $search['is_deleted']);
    } else {
      $customer_term->where('is_deleted', '!=', '1')->get();
    }
    $customer_term->orderBy('customer_term_created', 'DESC');
    // dd($customer_term);
    return $customer_term->paginate(15);
  }

  public static function get_customer_term_is_deleted()
  {
    $customer_term = CustomerTerm::query();
    $customer_term->where('is_deleted', '=', 1);

    // if (auth()->user()->company_id != 0) {
    //   $customer_term = $customer_term->where('company_id', auth()->user()->company_id);
    // }
    if(auth()->user()->company_id == 0 && count(auth()->user()->user_company) > 0){
      $ids = array();
      foreach(auth()->user()->user_company as $key => $user_company){
        // $company->where('company_id', $user_company->company_id);
        $ids[$key] = $user_company->company_id;
        // dd($ids[$key]);
      }
      $customer_term->whereIn('company_id', $ids);
    }else if(auth()->user()->company_id != 0){
       $customer_term->where('company_id', auth()->user()->company_id);
    }
    
    $customer_term = $customer_term->get();

    return $customer_term;
  }
}
