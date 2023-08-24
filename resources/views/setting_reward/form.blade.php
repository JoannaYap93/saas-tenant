@extends('layouts.master')

@section('title') {{ $title }} Setting Reward @endsection

@section('css')
@endsection

@section('content')
<div class="row">
	<div class="col-12">
		<div class="page-title-box d-flex align-items-center justify-content-between">
			<h4 class="mb-0 font-size-18">{{ $title }} Setting Reward</h4>
			<div class="page-title-right">
				<ol class="breadcrumb m-0">
					<li class="breadcrumb-item">
						<a href="javascript: void(0);">Setting Reward</a>
					</li>
					<li class="breadcrumb-item active">Form</li>
				</ol>
			</div>
		</div>
	</div>
</div>
@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
    @endforeach
@enderror
<div class="row">
    <div class="col-12">
        <form method="POST" action="{{ $submit }}" enctype="multipart/form-data">
            @csrf
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">{{ $title}} Setting Reward</h4>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Setting Reward Name<span class="text-danger">*</span></label>
                                    <input type="text" name="setting_reward_name" class="form-control" value="{{ @$post->setting_reward_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Setting Reward Description<span class="text-danger">*</span></label>
                                    <textarea name="setting_reward_description"
                                        class="form-control">{{ @$post->setting_reward_description }}</textarea>
                                </div>
                                <div class="form-group">
                                    <label>Setting Reward Category<span class="text-danger">*</span></label>
                                    {!! Form::select('setting_reward_category_id', $setting_reward_category_sel, @$post->setting_reward_category_id, ['class' => 'form-control', 'required']) !!}
                                </div>
                                <div class="form-group">
                                    <label>Setting Reward Status<span class="text-danger">*</span></label>
                                    {!! Form::select('setting_reward_status', $setting_reward_status_sel, @$post->setting_reward_status, ['class' => 'form-control', 'required']) !!}
                                </div>
                                {{-- @if(auth()->user()->user_type_id == 1)
                                <div class="form-group">
                                    <label>Company</label>
                                        {!! Form::select('company_id', $company_sel, @$post->company_id, ['class' => 'form-control','id'=> 'company_id']) !!}
                                </div>
                                @endif --}}
                            </div>
                            @if (auth()->user()->user_type_id == 1)
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="is_default" class="">Default</label>
                                    <div>
                                        <input name="is_default" type="checkbox" id="is_default" switch="none" value="1" {{ @$post->is_default ? 'checked' : '' }}/>
                                        <label for="is_default" data-on-label="On" data-off-label="Off"></label>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>


                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-4">Reward Details</h4>
                        <div class="repeater">
                            <div data-repeater-list="add_setting_reward_data_to_json">
                                @if (@$post->add_setting_reward_data_to_json)
                                    @foreach($post->add_setting_reward_data_to_json as $json)

                                    {{-- @dd($json->tier) --}}
                                        <div data-repeater-item class="row">
                                            <div  class="form-group col-lg-2">
                                                <label>Tier No.<span class="text-danger">*</span></label>
                                                <input type="number" min="0" step="0" name="tier" class="form-control" value="{{ @$json['tier'] }}" required>
                                            </div>
                                            <div  class="form-group col-lg-2">
                                                <label>Amount<span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" name="amount" class="form-control" value="{{ @$json['day'] }}" required>
                                            </div>
                                            <div  class="form-group col-lg-2">
                                                <label>Day<span class="text-danger">*</span></label>
                                                <input type="number" min="0" step="0"  name="day" class="form-control" value="{{ @$json['day'] }}" required>
                                            </div>
                                            <div  class="form-group col-lg-2">
                                                <label>Full Attendance</label>
                                                <div>
                                                    <input type="checkbox" name="full_attendance"  id="full_attendance" value="1" {{ @$json['full_attendance'] ? 'checked' : '' }}/>
                                                </div>
                                            </div>
                                            <div class="form-group col-lg-2 align-self-center">
                                                <i data-repeater-delete class="mdi mdi-trash-can font-size-20 text-danger"
                                                    id="delete"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div data-repeater-item class="row">
                                        <div  class="form-group col-lg-2">
                                            <label>Tier No.<span class="text-danger">*</span></label>
                                            <input type="number" min="0" step="0" name="tier" class="form-control" value="" required>
                                        </div>
                                        <div  class="form-group col-lg-2">
                                            <label>Amount<span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" name="amount" class="form-control" value="" required>
                                        </div>
                                        <div  class="form-group col-lg-2">
                                            <label>Day<span class="text-danger">*</span></label>
                                            <input type="number" min="0" step="0"  name="day" class="form-control" value="" required>
                                        </div>
                                        <div  class="form-group col-lg-2">
                                            <label>Full Attendance</label>
                                            <div>
                                                <input type="checkbox" name="full_attendance"  id="full_attendance" value="1"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-lg-2 align-self-center">
                                            <i data-repeater-delete class="mdi mdi-trash-can font-size-20 text-danger"
                                                id="delete"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <input data-repeater-create type="button" class="btn btn-success mt-3 mt-lg-0" value="Add"/>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary waves-effect waves-light mr-1">Submit</button>
                                <a href="{{ route('setting_reward', ['tenant' => tenant('id')]) }}" class="btn btn-secondary">Cancel</a>
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
<script src="{{ global_asset('assets/libs/jquery-repeater/jquery-repeater.min.js')}}"></script>
<script src="{{ global_asset('assets/js/pages/form-repeater.int.js')}}"></script>
@endsection
