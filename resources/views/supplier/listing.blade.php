@extends('layouts.master')

@section('title')
    Supplier Listing
@endsection

@section('css')
    <style></style>
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                  <h4 class="mb-0 font-size-18 mr-2">Supplier Listing</h4>
                    @can('supplier_manage')
                        <a href="{{ route('supplier_add') }}" class="btn btn-sm btn-outline-success waves-effect waves-light mr-2 mb-1"><i class="fas fa-plus"></i> ADD NEW</a>
                    @endcan
                </div>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Supplier Listing</a>
                        </li>
                        <li class="breadcrumb-item active">Listing</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <form method="POST" action="{{ $submit }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @csrf
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="freetext">Freetext</label>
                                            <input type="text" class="form-control" name="freetext"
                                                placeholder="Search for..." value="{{ @$search['freetext'] }}">
                                        </div>
                                    </div>
                                    @if(auth()->user()->company_id == 0)
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="company_id">Company</label>
                                                {!! Form::select('company_id', $company_sel, @$search['company_id'], ['class' => 'form-control', 'id' => 'company_id']) !!}
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="raw_material_category_id">Raw Material Category</label>
                                            {!! Form::select('raw_material_category_id', $raw_material_category_sel, @$search['raw_material_category_id'], ['class' => 'form-control', 'id' => 'raw_material_category_id']) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="raw_material_id">Raw Material</label>
                                            <select name="raw_material_id" class="form-control" id="raw_material_id">
                                                <option value="">Please Select Raw Material</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light mr-2"
                                            name="submit" value="search" id="search">
                                            <i class="fas fa-search mr-1"></i> Search
                                        </button>
                                        <button type="submit" class="btn btn-danger waves-effect waves-light mr-2"
                                            name="submit" value="reset">
                                            <i class="fas fa-times mr-1"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-light">
                                <th>#</th>
                                <th>Supplier Details</th>
                                <th>Supplier Bank</th>
                                <th style="width: 25%">Raw Materials</th>
                                <th>Companies</th>
                                <th>Status</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                                @if ($records->isNotEmpty())
                                    @php $no = $records->firstItem() @endphp
                                    @foreach ($records as $supplier)
                                        <?php
                                            $status = '';
                                            switch (@$supplier->supplier_status) {
                                                case 'Active':
                                                    $status = "<span class='badge badge-success font-size-11'>{$supplier->supplier_status}</span>";
                                                break;
                                                case 'Inactive':
                                                    $status = "<span class='badge badge-warning'>{$supplier->supplier_status}</span>";
                                                break;
                                            }

                                            $raw_material_list = '';
                                            if($supplier->raw_material){
                                                $sorted_raw_material = @$supplier->raw_material->sortBy('raw_material_name')->sortBy('raw_material_category_id');
                                                $prev_category_id = null;
                                                $category_count = 0;
                                                foreach($sorted_raw_material as $supplier_raw_material){
                                                    if($prev_category_id != $supplier_raw_material->raw_material_category_id){
                                                        if($category_count != 0)
                                                            $raw_material_list .= "<br>";

                                                        $raw_material_list .= "<span><b>" . json_decode($supplier_raw_material->setting_raw_category->raw_material_category_name)->en . "</b></span><br>";
                                                        $category_count++;
                                                    }
                                                    $raw_material_list .= "<span class='badge badge-secondary font-size-11 mr-1'>" . json_decode($supplier_raw_material->raw_material_name)->en . "</span>";
                                                    $prev_category_id = $supplier_raw_material->raw_material_category_id;
                                                }
                                            }
                                        ?>
                                        <tr>
                                            <td>{{ $no++ }}</td>
                                            <td>
                                                <b>{{ @$supplier->supplier_name }}</b><br/>
                                                @if (!is_null($supplier->supplier_mobile_no))
                                                {{ @$supplier->supplier_mobile_no }}<br/>
                                                @else
                                                @endif
                                                @if (!is_null($supplier->supplier_email))
                                                {{ @$supplier->supplier_email }}<br/>
                                                @else
                                                @endif
                                                @if (!is_null($supplier->supplier_address))
                                                {{ (substr(@$supplier->supplier_address, -1) == "," ? @$supplier->supplier_address : @$supplier->supplier_address . ",") }}<br/>
                                                {{ (substr(@$supplier->supplier_address2, -1) == "," ? @$supplier->supplier_address2 : @$supplier->supplier_address2 . ",") }}<br/>
                                                {{ @$supplier->supplier_postcode . ", " . @$supplier->supplier_city . ", " . @$supplier->supplier_state . ", " . @$supplier->supplier_country }}<br/>
                                                @else
                                                @endif
                                                <b>PIC: </b>
                                                @if (!is_null($supplier->supplier_pic))
                                                {{ @$supplier->supplier_pic }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                            @if($supplier->supplier_bank->isNotEmpty())
                                            @foreach (@$supplier->supplier_bank as $supplier_bank)
                                                @if($supplier_bank->is_deleted != "1")
                                                    <b>{{ $supplier_bank->setting_bank->setting_bank_name }}</b><br/>
                                                    {{ $supplier_bank->supplier_bank_acc_name }}<br/>
                                                    {{ $supplier_bank->supplier_bank_acc_no }}<br/>
                                                @endif
                                            @endforeach
                                            @else
                                            -
                                            @endif
                                            </td>
                                            <td>
                                                @if (!empty($raw_material_list))
                                                {!! $raw_material_list !!}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>
                                                @foreach(@$supplier->supplier_company as $supplier_company)
                                                    {{ $supplier_company->company_name }}<br/>
                                                @endforeach
                                            </td>
                                            <td>
                                                {!! $status !!}
                                            </td>
                                            <td>
                                                <a href="{{route('supplier_edit', $supplier->supplier_id)}}" class='btn btn-sm btn-outline-warning waves-effect waves-light'>Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                <tr>
                                    <td colspan="7" class="text-center">No Records!</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {!! $records->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            let company_id = $('#company_id').val();

            if('{{ auth()->user()->company_id }}' == '0')
                company_id = $('#company_id').val();
            else
                company_id = '{{ auth()->user()->company_id }}'

            get_raw_material_by_raw_material_category($('#raw_material_id').val(), company_id);
        });

        $('#raw_material_category_id').on('change', function() {
            $('#raw_material_id').html('<option value="">Loading...</option>');
            let raw_material_category_id = $(this).val();
            let company_id = $('#company_id').val();

            if('{{ auth()->user()->company_id }}' == '0')
                company_id = $('#company_id').val();
            else
                company_id = '{{ auth()->user()->company_id }}'

            get_raw_material_by_raw_material_category(raw_material_category_id, company_id);
        });

        function get_raw_material_by_raw_material_category(raw_material_category_id, company_id) {
            let raw_material_id = '{{ @$search['raw_material_id'] }}' ?? null;
            let raw_material_sel = '<option value="">Please Select Raw Material</option>';

            $.ajax({
                url: "{{ route('ajax_get_raw_material_by_raw_material_category_id') }}",
                method: "GET",
                data: {
                    _token: "{{ csrf_token() }}",
                    company_id: company_id,
                    raw_material_category_id: raw_material_category_id
                },
                success: function(e) {
                    if (e.data.length > 0) {
                        e.data.forEach(element => {
                            if (raw_material_category_id != null && raw_material_id == element.id) {
                                raw_material_sel += '<option selected value="' + element.id + '">' +
                                    element.value + '</option>';
                            } else {
                                raw_material_sel += '<option value="' + element.id + '">' +
                                    element.value + '</option>';
                            }
                        });
                        $('#raw_material_id').html(raw_material_sel);
                    } else {
                        $('#raw_material_id').html('<option value="">No Raw Material</option>');
                    }
                }
            });
        }
    </script>
@endsection
