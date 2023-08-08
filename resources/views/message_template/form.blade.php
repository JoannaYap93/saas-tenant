@extends('layouts.master')

@section('title') {{ $page_title }} @endsection

@section('css')

@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">{{ $page_title }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="javascript: void(0);">Message Template</a>
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
        <div class="col-8">
            <form method="POST" action="{{ $submit }}">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Details</h4>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="message_template_name">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="message_template_name" class="form-control"
                                        value="{{ @$post['message_template_name'] }}">
                                </div>

                                <div class="form-group">
                                    <label for="message_template_mobile_no">Mobile No<span class="text-danger">*</span></label>
                                    <input type="text" name="message_template_mobile_no" class="form-control number_only"
                                        value="{{ @$post['message_template_mobile_no'] }}">
                                </div>

                                <div class="form-group">
                                    <label for="message_template_content">Content<span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="message_template_content" name="message_template_content" rows="10">{{@$post['message_template_content']}}</textarea>
                                </div>

                                <div class="form-group d-flex">
                                    <label for="customSwitches">Status: </label>
                                    <div class="custom-switch custom-control ml-2">
                                        <input type="checkbox" class="custom-control-input" id="customSwitches"
                                            name="message_template_status" value="1"
                                            @if (@$post['message_template_status'] == 1) checked @endif>
                                        <label for="customSwitches" class="custom-control-label"></label>
                                    </div>
                                </div>
                                <div class="form-group d-flex">
                                    <label for="code">Reporting: </label>
                                    <div class="custom-switch custom-control ml-2">
                                        <input type="checkbox" class="custom-control-input" id="code"
                                            name="is_reporting" value="1"
                                            @if (@$post['is_reporting'] == 1) checked @endif>
                                        <label for="code" class="custom-control-label"></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Message Type :</label>
                                    @if($involve_sel)
                                    <div class="checkbox-inline">
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                                <label class="checkbox">
                                                    <input type='checkbox' id='check_all_involve' name='check_all_involve' value=''>
                                                    <span></span>
                                                    Check All
                                                </label>
                                            </div>
                                            @foreach($involve_sel as $involve)
                                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
                                                <label class="checkbox">
                                                    <input type="checkbox" class="check_involve" name="message_template_involve_id[]" id="message_template_involve_id[]" value="{{$involve->message_template_involve_id}}" @if (in_array($involve->message_template_involve_id, @$post['message_template_involve_id']))
                                                    checked
                                                @endif />
                                                    <span></span>
                                                    {{$involve->message_template_involve_type}}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('message_template_listing') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">References</h4>
                    <div class="row">
                        <div class="col-12">
                            <span>[CUSTOMER_NAME]</span>
                            <br/>
                            <span>[SHORT_CUSTOMER_NAME]</span>
                            <br>
                            <span>[ADMIN_NAME]</span>
                            <br/>
                            <span>[ADMIN_MOBILE_NO]</span>

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="card-title mb-4">DO Listing</h4>
                    <div class="row">
                        <div class="col-12">
                            <span>[VERIFY_PRICE_URL]</span>
                            <br/>
                            <span>[DO_DETAILS]</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="card-title mb-4">Invoice Listing</h4>
                    <div class="row">
                        <div class="col-12">
                            <span>[INVOICE_URL]</span>
                            <br/>
                            <span>[PAYMENT_URL]</span>
                            <br/>
                            <span>[RECEIPT_URL]</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="card-title mb-4">Payment Url Listing</h4>
                    <div class="row">
                        <div class="col-12">
                            <span>[RECEIPT_URL]</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {

        $('.number_only').bind('input paste', function(){
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        var check_all_involve = document.getElementById("check_all_involve"); //select all checkbox
        var check_involve = document.getElementsByName("message_template_involve_id[]"); //checkbox items

        //select all checkboxes_book
        check_all_involve.addEventListener("change", function(e) {

            for (i = 0; i < check_involve.length; i++) {
                check_involve[i].checked = check_all_involve.checked;
            }
        });


        for (var i = 0; i < check_involve.length; i++) {
            check_involve[i].addEventListener('change', function(e) { //".checkbox" change
                //uncheck "select all", if one of the listed checkbox item is unchecked
                if (this.checked == false) {
                    check_all_involve.checked = false;
                }

                if (document.querySelectorAll('.check_involve:checked').length == check_involve.length) {
                    check_all_involve.checked = true;
                }
            });

        }

        var initial_involve = <?php echo json_encode(@$post['message_template_involve_id']); ?>;

        if (initial_involve.length == check_involve.length) {
            check_all_involve.checked = true;
        }
    });
</script>
@endsection
