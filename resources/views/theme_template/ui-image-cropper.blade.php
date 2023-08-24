@extends('layouts.master')

@section('title') Grid @endsection

@section('css')
<link rel="stylesheet" href="{{ global_asset('assets/libs/cropperjs/cropperjs.min.css')}}">
@endsection

@section('content')

                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-flex align-items-center justify-content-between">
                                    <h4 class="mb-0 font-size-18">Image Cropper</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Crypto</a></li>
                                            <li class="breadcrumb-item active">Image Cropper</li>
                                        </ol>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>     
                        <!-- end page title -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Example</h4>
                                        <div class="row">
                                            <div class="col-xl-9">
                                              <!-- <h3>Demo:</h3> -->
                                              <div class="img-container mb-2">
                                                <img src="assets/images/small/img-5.jpg" alt="Picture">
                                              </div>
                                            </div>
                                            <div class="col-xl-3">
                                              <!-- <h3>Preview:</h3> -->
                                              <div class="docs-preview image-crop-preview clearfix">
                                                <div class="img-preview preview-lg"></div>
                                                <div class="img-preview preview-md"></div>
                                                <div class="img-preview preview-sm"></div>
                                                <div class="img-preview preview-xs"></div>
                                              </div>
                                      
                                              <!-- <h3>Data:</h3> -->
                                              <!-- <h3>Data:</h3> -->
                                              <div class="docs-data">
                                                <div class="input-group input-group-sm mt-2">
                                                  <span class="input-group-prepend">
                                                    <label class="input-group-text" for="dataX">X</label>
                                                  </span>
                                                  <input type="text" class="form-control" id="dataX" placeholder="x">
                                                  <span class="input-group-append">
                                                    <span class="input-group-text">px</span>
                                                  </span>
                                                </div>
                                                <div class="input-group input-group-sm mt-2">
                                                  <span class="input-group-prepend">
                                                    <label class="input-group-text" for="dataY">Y</label>
                                                  </span>
                                                  <input type="text" class="form-control" id="dataY" placeholder="y">
                                                  <span class="input-group-append">
                                                    <span class="input-group-text">px</span>
                                                  </span>
                                                </div>
                                                <div class="input-group input-group-sm mt-2">
                                                  <span class="input-group-prepend">
                                                    <label class="input-group-text" for="dataWidth">Width</label>
                                                  </span>
                                                  <input type="text" class="form-control" id="dataWidth" placeholder="width">
                                                  <span class="input-group-append">
                                                    <span class="input-group-text">px</span>
                                                  </span>
                                                </div>
                                                <div class="input-group input-group-sm mt-2">
                                                  <span class="input-group-prepend">
                                                    <label class="input-group-text" for="dataHeight">Height</label>
                                                  </span>
                                                  <input type="text" class="form-control" id="dataHeight" placeholder="height">
                                                  <span class="input-group-append">
                                                    <span class="input-group-text">px</span>
                                                  </span>
                                                </div>
                                                <div class="input-group input-group-sm mt-2">
                                                  <span class="input-group-prepend">
                                                    <label class="input-group-text" for="dataRotate">Rotate</label>
                                                  </span>
                                                  <input type="text" class="form-control" id="dataRotate" placeholder="rotate">
                                                  <span class="input-group-append">
                                                    <span class="input-group-text">deg</span>
                                                  </span>
                                                </div>
                                                <div class="input-group input-group-sm mt-2">
                                                  <span class="input-group-prepend">
                                                    <label class="input-group-text" for="dataScaleX">ScaleX</label>
                                                  </span>
                                                  <input type="text" class="form-control" id="dataScaleX" placeholder="scaleX">
                                                </div>
                                                <div class="input-group input-group-sm mt-2 mb-2">
                                                  <span class="input-group-prepend">
                                                    <label class="input-group-text" for="dataScaleY">ScaleY</label>
                                                  </span>
                                                  <input type="text" class="form-control" id="dataScaleY" placeholder="scaleY">
                                                </div>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row" id="actions">
                                            <div class="col-xl-9 img-crop-preview-btns docs-buttons">
                                              <!-- <h3>Toolbar:</h3> -->
                                              <div class="button-items mt-2">
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="move" title="Move">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setDragMode(&quot;move&quot;)">
                                                        <span class="mdi mdi-arrow-all"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="setDragMode" data-option="crop" title="Crop">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setDragMode(&quot;crop&quot;)">
                                                        <span class="mdi mdi-crop"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Zoom In">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(0.1)">
                                                        <span class="mdi mdi-magnify-plus-outline"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Zoom Out">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoom(-0.1)">
                                                        <span class="mdi mdi-magnify-minus-outline"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Move Left">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(-10, 0)">
                                                        <span class="mdi mdi-arrow-left"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Move Right">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(10, 0)">
                                                        <span class="mdi mdi-arrow-right"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Move Up">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(0, -10)">
                                                        <span class="mdi mdi-arrow-up"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Move Down">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(0, 10)">
                                                        <span class="mdi mdi-arrow-down"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Rotate Left">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(-45)">
                                                        <span class="mdi mdi-rotate-left"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Rotate Right">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotate(45)">
                                                        <span class="mdi mdi-rotate-right"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Flip Horizontal">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleX(-1)">
                                                        <span class="mdi mdi-arrow-left-right"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Flip Vertical">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scaleY(-1)">
                                                        <span class="mdi mdi-arrow-up-down"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="crop" title="Crop">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.crop()">
                                                        <span class="mdi mdi-check"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="clear" title="Clear">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.clear()">
                                                        <span class="mdi mdi-close"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="disable" title="Disable">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.disable()">
                                                        <span class="mdi mdi-lock"></span>
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-primary" data-method="enable" title="Enable">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.enable()">
                                                        <span class="mdi mdi-lock-open"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group">
                                                    <button type="button" class="btn btn-primary" data-method="reset" title="Reset">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.reset()">
                                                        <span class="mdi mdi-sync"></span>
                                                      </span>
                                                    </button>
                                                    <label class="btn btn-primary btn-upload" for="inputImage" title="Upload image file">
                                                      <input type="file" class="sr-only" id="inputImage" name="file" accept="image/*">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="Import image with Blob URLs">
                                                        <span class="mdi mdi-upload"></span>
                                                      </span>
                                                    </label>
                                                    <button type="button" class="btn btn-primary" data-method="destroy" title="Destroy">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.destroy()">
                                                        <span class="mdi mdi-power"></span>
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <div class="btn-group btn-group-crop">
                                                    <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ maxWidth: 4096, maxHeight: 4096 })">
                                                        Get Cropped Canvas
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 160, &quot;height&quot;: 90 }">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 160, height: 90 })">
                                                        160&times;90
                                                      </span>
                                                    </button>
                                                    <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;width&quot;: 320, &quot;height&quot;: 180 }">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCroppedCanvas({ width: 320, height: 180 })">
                                                        320&times;180
                                                      </span>
                                                    </button>
                                                  </div>
                                          
                                                  <!-- Show the cropped image in modal -->
                                                  <div class="modal fade docs-cropped" id="getCroppedCanvasModal" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" tabindex="-1">
                                                    <div class="modal-dialog">
                                                      <div class="modal-content">
                                                        <div class="modal-header">
                                                          <h5 class="modal-title mt-0" id="getCroppedCanvasTitle">Cropped</h5>
                                                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                          </button>
                                                        </div>
                                                        <div class="modal-body"></div>
                                                        <div class="modal-footer">
                                                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                          <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg">Download</a>
                                                        </div>
                                                      </div>
                                                    </div>
                                                  </div><!-- /.modal -->
                                          
                                                  <button type="button" class="btn btn-secondary" data-method="getData" data-option data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getData()">
                                                      Get Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="setData" data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setData(data)">
                                                      Set Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="getContainerData" data-option data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getContainerData()">
                                                      Get Container Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="getImageData" data-option data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getImageData()">
                                                      Get Image Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="getCanvasData" data-option data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCanvasData()">
                                                      Get Canvas Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="setCanvasData" data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setCanvasData(data)">
                                                      Set Canvas Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="getCropBoxData" data-option data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.getCropBoxData()">
                                                      Get Crop Box Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="setCropBoxData" data-target="#putData">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.setCropBoxData(data)">
                                                      Set Crop Box Data
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="moveTo" data-option="0">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.moveTo(0)">
                                                      Move to [0,0]
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="zoomTo" data-option="1">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.zoomTo(1)">
                                                      Zoom to 100%
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="rotateTo" data-option="180">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.rotateTo(180)">
                                                      Rotate 180°
                                                    </span>
                                                  </button>
                                                  <button type="button" class="btn btn-secondary" data-method="scale" data-option="-2" data-second-option="-1">
                                                    <span class="docs-tooltip" data-toggle="tooltip" title="cropper.scale(-2, -1)">
                                                      Scale (-2, -1)
                                                    </span>
                                                  </button>
                                              </div>
                                              <div class="mt-3">
                                                <textarea class="form-control" id="putData" rows="2" placeholder="Get data to here or set data with this value"></textarea>
                                              </div>
                                      
                                            </div><!-- /.docs-buttons -->
                                      
                                            <div class="col-xl-3 docs-toggles">
                                                <div class="img-crop-preview-toggles mt-4 mt-xl-2">
                                                  <!-- <h3>Toggles:</h3> -->
                                                  <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
                                                    <label class="btn btn-primary active">
                                                      <input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.7777777777777777">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 16 / 9">
                                                        16:9
                                                      </span>
                                                    </label>
                                                    <label class="btn btn-primary">
                                                      <input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1.3333333333333333">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 4 / 3">
                                                        4:3
                                                      </span>
                                                    </label>
                                                    <label class="btn btn-primary">
                                                      <input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="1">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 1 / 1">
                                                        1:1
                                                      </span>
                                                    </label>
                                                    <label class="btn btn-primary">
                                                      <input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="0.6666666666666666">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: 2 / 3">
                                                        2:3
                                                      </span>
                                                    </label>
                                                    <label class="btn btn-primary">
                                                      <input type="radio" class="sr-only" id="aspectRatio5" name="aspectRatio" value="NaN">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="aspectRatio: NaN">
                                                        Free
                                                      </span>
                                                    </label>
                                                  </div>
                                          
                                                  <div class="btn-group d-flex flex-nowrap" data-toggle="buttons">
                                                    <label class="btn btn-primary active">
                                                      <input type="radio" class="sr-only" id="viewMode0" name="viewMode" value="0" checked>
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 0">
                                                        VM0
                                                      </span>
                                                    </label>
                                                    <label class="btn btn-primary">
                                                      <input type="radio" class="sr-only" id="viewMode1" name="viewMode" value="1">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 1">
                                                        VM1
                                                      </span>
                                                    </label>
                                                    <label class="btn btn-primary">
                                                      <input type="radio" class="sr-only" id="viewMode2" name="viewMode" value="2">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 2">
                                                        VM2
                                                      </span>
                                                    </label>
                                                    <label class="btn btn-primary">
                                                      <input type="radio" class="sr-only" id="viewMode3" name="viewMode" value="3">
                                                      <span class="docs-tooltip" data-toggle="tooltip" title="View Mode 3">
                                                        VM3
                                                      </span>
                                                    </label>
                                                  </div>
                                          
                                                  <div class="dropdown dropup docs-options">
                                                    <button type="button" class="btn btn-primary btn-block dropdown-toggle" id="toggleOptions" data-toggle="dropdown" aria-expanded="true">
                                                        Toggle Options
                                                        <i class="mdi mdi-chevron-down ml-1"></i>
                                                    </button>
                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="toggleOptions">
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="responsive" type="checkbox" name="responsive" checked>
                                                          <label class="form-check-label" for="responsive">responsive</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="restore" type="checkbox" name="restore" checked>
                                                          <label class="form-check-label" for="restore">restore</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="checkCrossOrigin" type="checkbox" name="checkCrossOrigin" checked>
                                                          <label class="form-check-label" for="checkCrossOrigin">checkCrossOrigin</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="checkOrientation" type="checkbox" name="checkOrientation" checked>
                                                          <label class="form-check-label" for="checkOrientation">checkOrientation</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="modal" type="checkbox" name="modal" checked>
                                                          <label class="form-check-label" for="modal">modal</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="guides" type="checkbox" name="guides" checked>
                                                          <label class="form-check-label" for="guides">guides</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="center" type="checkbox" name="center" checked>
                                                          <label class="form-check-label" for="center">center</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="highlight" type="checkbox" name="highlight" checked>
                                                          <label class="form-check-label" for="highlight">highlight</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="background" type="checkbox" name="background" checked>
                                                          <label class="form-check-label" for="background">background</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="autoCrop" type="checkbox" name="autoCrop" checked>
                                                          <label class="form-check-label" for="autoCrop">autoCrop</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="movable" type="checkbox" name="movable" checked>
                                                          <label class="form-check-label" for="movable">movable</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="rotatable" type="checkbox" name="rotatable" checked>
                                                          <label class="form-check-label" for="rotatable">rotatable</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="scalable" type="checkbox" name="scalable" checked>
                                                          <label class="form-check-label" for="scalable">scalable</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="zoomable" type="checkbox" name="zoomable" checked>
                                                          <label class="form-check-label" for="zoomable">zoomable</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="zoomOnTouch" type="checkbox" name="zoomOnTouch" checked>
                                                          <label class="form-check-label" for="zoomOnTouch">zoomOnTouch</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="zoomOnWheel" type="checkbox" name="zoomOnWheel" checked>
                                                          <label class="form-check-label" for="zoomOnWheel">zoomOnWheel</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="cropBoxMovable" type="checkbox" name="cropBoxMovable" checked>
                                                          <label class="form-check-label" for="cropBoxMovable">cropBoxMovable</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="cropBoxResizable" type="checkbox" name="cropBoxResizable" checked>
                                                          <label class="form-check-label" for="cropBoxResizable">cropBoxResizable</label>
                                                        </div>
                                                      </li>
                                                      <li class="dropdown-item">
                                                        <div class="form-check">
                                                          <input class="form-check-input" id="toggleDragModeOnDblclick" type="checkbox" name="toggleDragModeOnDblclick" checked>
                                                          <label class="form-check-label" for="toggleDragModeOnDblclick">toggleDragModeOnDblclick</label>
                                                        </div>
                                                      </li>
                                                    </ul>
                                                  </div><!-- /.dropdown -->
                                                </div>
                                      
                                            </div><!-- /.docs-toggles -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
@endsection

@section('script')
        <script src="{{ global_asset('assets/libs/cropperjs/cropperjs.min.js')}}"></script>

        <!-- cropperjs init -->
        <script src="{{ global_asset('assets/js/pages/cropperjs.init.js')}}"></script>
@endsection