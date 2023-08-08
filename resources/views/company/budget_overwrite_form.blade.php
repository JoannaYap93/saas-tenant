@extends('layouts.master')

@section('title') Company Land Budget Overwrite @endsection

@section('css')

@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">Company Land Budget Overwrite</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Company Land Budget Overwrite</a>
                        </li>
                        <li class="breadcrumb-item active">Form</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger" role="alert">
                {{ $error }}
            </div>
        @endforeach
        @enderror
        <div class="row">
            <div class="col-6">
                <form method="POST" action="{{ $submit }}">
                    @csrf
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-4">Budget Overwrite Details ({{$company_land->company_land_name}})</h4>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="setting_expense_category_name">Default Budget:</label>
                                        <input type="number" class="form-control" readonly
                                               value="{{ @$default_budget }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="customSwitches">Enable Overwrite: </label>
                                    <div class="custom-switch custom-control ml-2">
                                        <input type="checkbox" class="custom-control-input" id="customSwitches"
                                            name="is_overwrite_budget" value="1"
                                            @if (@$company_land->is_overwrite_budget == 1) checked @endif>
                                        <label for="customSwitches" class="custom-control-label"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="setting_expense_category_name">Overwrite Budget:</label>
                                        <input type="number" step=".01" name="overwrite_budget_per_tree" class="form-control"
                                               value="{{ @$company_land->overwrite_budget_per_tree }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                  <div class="table-responsive border">
                                      <table class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Budget Item</th>
                                                <th>Default</th>
                                                <th>Overwrite</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                          @php
                                            $i = 1;
                                          @endphp
                                          @if(count($setting_formula_category) > 0)
                                            @foreach($setting_formula_category as $sfckey => $sfc)
                                              <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                  {{json_decode(@$sfc->setting_formula_category_name)->en}}
                                                  <input hidden type="number" name="company_land_budget_overwrite_type_id[formula][]" value="{{$sfc->setting_formula_category_id}}"/>
                                                </td>
                                                <td><input class="form-control" type="number" step=".01" readonly value="{{$sfc->setting_formula_category_budget}}"/></td>
                                                <td><input class="form-control overwrite-value" type="number" step=".01" name="company_land_budget_overwrite_value[formula][]"
                                                    id="formula_{{$sfc->setting_formula_category_id}}"
                                                  /></td>
                                              </tr>
                                              @php $i++; @endphp
                                            @endforeach
                                          @endif
                                          @if(count($setting_expense_category) > 0)
                                            @foreach($setting_expense_category as $seckey => $sec)
                                              <tr>
                                                <td>{{$i}}</td>
                                                <td>
                                                  {{json_decode(@$sec->setting_expense_category_name)->en}}
                                                  <input hidden type="number" name="company_land_budget_overwrite_type_id[expense][]" value="{{$sec->setting_expense_category_id}}"/>
                                                </td>
                                                <td><input class="form-control" type="number" step=".01" readonly value="{{$sec->setting_expense_category_budget}}"/></td>
                                                <td><input class="form-control overwrite-value" type="number" step=".01" name="company_land_budget_overwrite_value[expense][]"
                                                    id="expense_{{$sec->setting_expense_category_id}}"
                                                  /></td>
                                              </tr>
                                              @php $i++; @endphp
                                            @endforeach
                                            <tr>
                                                <td colspan="2"></td>
                                                <td align="right"><b>Total</b></td>
                                                <td><input name="total_overwrite" class="form-control" type="number" step=".01" readonly id="total_overwrite"/>
                                                </td>
                                            </tr>
                                          @endif
                                        </tbody>
                                      </table>
                                  </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12  mt-2">
                                  <div class="col-12 d-flex justify-content-end">
                                    <button type="submit"
                                            class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                    <a href="{{ route('company_listing') }}" class="btn btn-secondary">Cancel</a>
                                  </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
@endsection
@section('script')
<script>
    $('input[name="company_land_budget_overwrite_value[formula][]"]').on('keyup clickup change', function () {
        let f_id = $(this).attr("id").substr(8);
        let overwrite_value_f = $('#formula_' + f_id).val();

        if(overwrite_value_f >= 0){
            cal_total();
        }
    })
    $('input[name="company_land_budget_overwrite_value[expense][]').on('keyup clickup change', function () {
        let e_id = $(this).attr("id").substr(8);
        let overwrite_value_e = $('#expense_' + e_id).val();

        if(overwrite_value_e >= 0){
            cal_total();
        }
    })
    function cal_total(){
        var total_overwrite = 0;
        $(".overwrite-value").each(function(){
            var overwrite_value = 0;
            if(!isNaN(parseFloat($(this).val())) && parseFloat($(this).val()) != null && parseFloat($(this).val()) != undefined && parseFloat($(this).val()) != ''){
                overwrite_value =  parseFloat($(this).val());
            }
            total_overwrite += parseFloat(overwrite_value);
        });
        $("#total_overwrite").val(total_overwrite.toFixed(2));
    }

  @if(@$existing_overwrite)
    $(document).ready(function(e) {
      let existing_overwrite_total = 0;
      let existing_overwrite = <?php echo json_encode($existing_overwrite); ?>;
      existing_overwrite.forEach((eo, i) => {
        $('#' + eo.company_land_budget_overwrite_type + '_' + eo.company_land_budget_overwrite_type_id).val(eo.company_land_budget_overwrite_value);

        if(eo.company_land_budget_overwrite_value > 0){
            existing_overwrite_total += parseFloat(eo.company_land_budget_overwrite_value);
        }
      });

      $("#total_overwrite").val(existing_overwrite_total.toFixed(2));
      console.log(existing_overwrite_total);
    });
  @endif
</script>
@endsection
