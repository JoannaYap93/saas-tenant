<div id="collapse" class="col-md-12 collapse manage-dealer-collapse hide mb-2 mt-2" aria-labelledby="heading">
    @if(!@$companyComponent)
        <div class="form-group col-sm-12">
            <label for="user_land">Companies:</label><br>
            <div class="row col-sm-12" id="company_cb">
                @foreach ($companyCb as $id => $companys)
                    <div class="custom-control custom-checkbox col-sm-3">
                        <input type="checkbox" id="company_cb_{{ $id }}"
                            name="company_cb_id[]" value="{{ $id }}"
                            class= "form-check-input check_company_cb_id"
                            @if(@$search['company_cb_id'])
                            @foreach(@$search['company_cb_id'] as $key => $selected_company)
                                {{ $selected_company == $id ? 'checked' : '' }}
                            @endforeach
                        @endif
                        />
                        <label for="company_cb_{{ $id }}">{{ $companys }}</label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @if(!@$dashboard)
        @if(!@$companyLandComponent)
            <div class="form-group col-sm-12">
                <label for="company_land">Company Land:</label>
                <div class="row col-sm-12" id="land_cb">
                    <div class="land_id_cb">

                    </div>
                </div>
            </div>
        @endif
        @if(!@$productComponent)
            <div class="form-group col-sm-12">
                <label for="user_land">Products:</label><br>
                <div class="row col-sm-12" id="product_cb">
                @foreach ($productCb as $key => $p)
                    <div class="custom-control custom-checkbox col-sm-3">
                        <input type="checkbox" id="product_cb_{{ $p->product_id }}"
                            name="product_cb_id[]" value="{{ $p->product_id }}"
                            class= "form-check-input check_product_cb_id"
                            @if(@$search['product_cb_id'])
                                @foreach(@$search['product_cb_id'] as $key => $selected_product)
                                {{ $selected_product == $p->product_id ? 'checked' : '' }}
                                @endforeach
                            @endif
                        />
                        <label
                            for="product_cb_{{ $p->product_id }}">{{ $p->product_name }}
                        </label>
                    </div>
                @endforeach
                </div>
            </div>
        @endif
    @endif
  </div>
  <div class="col-12 text-left mb-3">
    <a href="#collapse" class="text-center manage-show-hide text-dark collapsed mb-2 collapse-selection" data-toggle="collapse" aria-expanded="true" aria-controls="collapse" style="vertical-align: middle;">
        <span class="font-weight-bold ">
            <span class="text-show-hide">Multiple Company Selection</span>
            <i class="bx bxs-down-arrow rotate-icon"></i>
        </span>
    </a>
</div>
