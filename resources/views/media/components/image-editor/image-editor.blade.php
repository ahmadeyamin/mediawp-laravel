<style>
    .imgedit-panel-header {
        font-weight: bold;

    }

    .imgedit-crop-wrap {
        height: fit-content;

    }

    .imgedit-crop-wrap img {
        height: 100%;

    }

    .imgedit-scale #imgedit-scale-width,
    .imgedit-scale #imgedit-scale-height,
    .imgedit-crop-sel input[type=text],
    .imgedit-crop-ratio input[type=text] {
        width: 50%;
        display: inline;
        width: 80px;
    }

    .imgedit-scale label,
    .imgedit-crop-sel label,
    .imgedit-crop-ratio label {
        display: inline;
    }

    .imgedit-size-preview {
        width: 150px;
        height: 150px;
    }

    .imgedit-wrap {
        height: auto !important;
        position: relative;
    }

    .imgedit-group-control {
        overflow-y: scroll;
        height: 30em;
    }

    .image-preview-main {
        height: auto;
        max-width: 500px;
        margin-bottom: 10px;
    }

    #imgedit-crop {
        max-width: 500px;
    }
</style>


<link rel="stylesheet" href="{{asset('css/croppr.min.css')}}">

<div class="imgedit-wrap container clearfix">
    <div id="imgedit-panel">
        <div class="imgedit-panel-header">
            <h2 class="border-bottom mb-2">
                Attachment Details
            </h2>
        </div>



        <div class="imgedit-settings">
            <div class="row">
                <div class="imgedit-group col-md-8 col-sm-12">

                    <div>
                        <button id="imagedit-image-rleft" class="btn btn-sm btn-info"><i class="fas fa-retweet"></i></button>
                    </div>

                    <div class="imgedit-panel-content clearfix">

                        <div id="imgedit-crop" class="imgedit-crop-wrap px-1">
                            <img class="image-preview-main" id="cropper" src="{{url($upload->mediaPath('source'))}}"
                                alt="" />
                        </div>




                        <div class="imgedit-submit">
                            <input type="button" id="cropper-destroy"
                                class="button imgedit-cancel-btn btn btn-sm btn-danger" value="Cancel" />



                            <input type="button" id="cropper-save" disabled="disabled"
                                class="button button-primary imgedit-submit-btn btn btn-sm btn-primary" value="Save" />
                        </div>
                    </div>
                </div>


                <div class="imgedit-group imgedit-group-control col-md-4 border-left col-sm-12">

                    <div class="imgedit-group-top">
                        <h2 class="h5">Scale Image :</h2>


                        <p>Original dimensions {{$upload->sizeW}} &times; {{$upload->sizeH}}</p>
                        <div class="imgedit-submit">

                            <fieldset class="imgedit-scale">
                                <legend class="h5">New dimensions:</legend>
                                <div class="nowrap">
                                    <label>
                                        <input type="text" class="form-control" id="imgedit-scale-width"
                                            value="{{$upload->sizeW}}" />

                                        <span>&times;</span>

                                        <input type="text" class="form-control" id="imgedit-scale-height"
                                            value="{{$upload->sizeH}}" />
                                    </label>
                                    <input id="imgedit-scale-button" type="button"
                                        class="btn-primary btn btn-sm btn-flat" value="Scale" />
                                </div>
                            </fieldset>

                        </div>
                    </div>


                    <hr>

                    <div class="imgedit-group-top">
                        <h2 class="h5">Image Crop :</h2>
                    </div>

                    <fieldset id="imgedit-crop-sel" class="imgedit-crop-sel">
                        <legend class="h6 lead">Position</legend>
                        <div class="nowrap">
                            <label>
                                <input type="text" disabled="disabled" class="form-control" id="imgedit-pos-x" />
                                <span>:</span>
                                <input type="text" disabled="disabled" class="form-control" id="imgedit-pos-y" />
                            </label>
                        </div>
                    </fieldset>

                    <fieldset id="imgedit-crop-sel" class="imgedit-crop-sel">
                        <legend class="h6 lead">Selection</legend>
                        <div class="nowrap">
                            <label>
                                <input type="text" class="form-control" disabled="disabled" id="imgedit-sel-width" />
                                <span>&times;</span>
                                <input type="text" class="form-control" disabled="disabled" id="imgedit-sel-height" />
                            </label>
                        </div>
                        <input type="submit" class="btn btn-sm btn-flat btn-info" value="Crop">
                    </fieldset>

                    <hr>

                    <div class="imgedit-group imgedit-applyto">
                        <div class="imgedit-group-top">
                            <h2 class="h6 lead">Thumbnail Settings</h2>


                        </div>

                        <figure class="imgedit-thumbnail-preview">
                            <img src="{{url($upload->mediaPath('thumbnail'))}}" width="120" height="120"
                                class="imgedit-size-preview" alt="" draggable="false" />
                            <figcaption class="imgedit-thumbnail-preview-caption">Current thumbnail</figcaption>
                        </figure>

                        <div id="imgedit-save-target" class="imgedit-save-target">
                            <fieldset>
                                <legend><strong>Apply changes to:</strong></legend>

                                <div class="custom-control custom-radio">
                                    <input type="radio" class="custom-control-input" id="imgedit-target-all"
                                        name="imgediTarget" value="all" checked>


                                    <label class="custom-control-label" for="imgedit-target-all"> All image
                                        sizes</label>
                                </div>


                                <div class="custom-control custom-radio">
                                    <input type="radio" value="thumbnail" class="custom-control-input"
                                        id="imgedit-target-thumbnail" name="imgediTarget">

                                    <label class="custom-control-label" for="imgedit-target-thumbnail">Thumbnail</label>
                                </div>


                                <div class="custom-control custom-radio">
                                    <input type="radio" value="nothumb" class="custom-control-input"
                                        id="mgedit-target-nothumb" name="imgediTarget">
                                    <label class="custom-control-label" for="mgedit-target-nothumb">All sizes except
                                        thumbnail</label>
                                </div>
                            </fieldset>
                        </div>
                    </div>

                </div>



            </div>


        </div>

    </div>
    <div class="imgedit-wait" id="imgedit-wait"></div>
</div>

<script src="{{asset('js/croppr.min.js')}}"></script>

<script>
    var cropInstance = ''
    var cropImageSrc = ''
    $(document).ready(function (e) {


        cropInstance = new Croppr('#cropper', {
            minSize: [100, 100, 'px'],
            startSize:[80, 80, '%'],
            onInitialize: (instance) => {

             },
            onCropStart: (data) => {


            },
            onCropEnd: (data) => {
                $('#imgedit-sel-height').val(data.height)
                $('#imgedit-sel-width').val(data.width)
                $('#imgedit-pos-x').val(data.x)
                $('#imgedit-pos-y').val(data.y)
            },
            onCropMove: (data) => {
                console.log(data);

                $('#imgedit-sel-height').val(data.height)
                $('#imgedit-sel-width').val(data.width)
                $('#imgedit-pos-x').val(data.x)
                $('#imgedit-pos-y').val(data.y)
                $('#cropper-save').removeAttr('disabled')


            },

            });

            $('#cropper-destroy').click(e=>{
                $.modal.close()
            })

            $('#cropper-save').click(e=>{
                imagEditorSave({{$upload->id}});
            })
            $('#imagedit-image-rleft').click(e=>{

                imagEditorSaveRleft({{$upload->id}},90);

            })


    });
    
    function imagEditorSave(id){
        $.ajax({
            data:{
                action:'image-editor-save',
                id,
                do:'save',
                target:$('input[name=imgediTarget]:checked').val(),
                imagePosition: cropInstance.getValue(),
                cropImageSrc,

            },
            method:'post',
            dataType: "html",
            url:'{{route('admin.ajex.query_menager')}}',
            success: function(result){
                    VanillaToasts.create({
                    title: 'Success',
                    text: JSON.parse(result).message,
                    type: 'success',
                    icon: '{{asset('img/svg/success.svg')}}',
                    timeout: 4000
                });
            
            // window.location.reload()
                
            },
            error:function(error){
                VanillaToasts.create({
                    title: 'error',
                    text: error.message,
                    type: 'error',
                    icon: '{{asset('img/svg/error.svg')}}',
                    timeout: 4000,

                });
            },

        });

    }

    var imageDig = 0;

    function imagEditorSaveRleft(id,dig) {
        imageDig +=dig;
        imageDig > 360 ? 0 : imageDig;
        cropImageSrc = `{{route('admin.ajex.query_menager')}}?action=image-editor&id=${id}&do=rotateleft&dig=${imageDig}`
        cropInstance.setImage(cropImageSrc);

           

    }

</script>