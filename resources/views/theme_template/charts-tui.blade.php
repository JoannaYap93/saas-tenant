@extends('layouts.master')

@section('title') Toast UI Chart @endsection

@section('css') 
        <link rel="stylesheet" type="text/css" href="{{ global_asset('assets/libs/tui-chart/tui-chart.min.css')}}">
@endsection

@section('content')

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18">Toast UI Chart</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Charts</a></li>
                                            <li class="breadcrumb-item active">Toast UI Chart</li>
                                        </ol>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Bar charts</h4>
        
                                        <div id="bar-charts" dir="ltr"></div>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Column charts</h4>
        
                                        <div id="column-charts" dir="ltr"></div>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->

                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Line charts</h4>
        
                                        <div id="line-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Area charts</h4>
        
                                        <div id="area-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                        </div>
                        <!-- end row -->
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Bubble charts</h4>
        
                                        <div id="bubble-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Scatter charts</h4>
        
                                        <div id="scatter-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Pie charts</h4>
        
                                        <div id="pie-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Donut pie charts</h4>
        
                                        <div id="donut-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->

                        <div class="row">
                            
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Heatmap charts</h4>
        
                                        <div id="heatmap-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Treemap charts</h4>
        
                                        <div id="treemap-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                        </div>
                        <!-- end row -->

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Map charts</h4>
        
                                        <div id="map-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Boxplot charts</h4>
        
                                        <div id="boxplot-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->

                        </div>
                        <!-- end row -->
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Bullet charts</h4>
        
                                        <div id="bullet-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Radial charts</h4>
        
                                        <div id="radial-charts" dir="ltr"></div>
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>

@endsection

@section('script')

        <!-- flot plugins -->
        <script src="{{ global_asset('assets/libs/tui-chart/tui-chart.min.js')}}"></script>

        <script src="{{ global_asset('assets/js/pages/tui-charts.init.js')}}"></script> 

@endsection