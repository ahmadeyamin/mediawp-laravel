@extends('mediawp::layouts.app')



@section('css')


<link rel="stylesheet" href="{{asset('css/jquery.uploadfile.css')}}">
<link rel="stylesheet" href="{{asset('css/mediawp.css')}}">

@endsection



@section('content')
<div class="container">


    <!-- Card -->
    <div class="card">
        <div class="card-body">
            <div id="fileuploader"> @csrf</div>
        </div>
    </div>

    <!-- Card -->
</div>
@endsection









@section('js')
<script src="{{asset('js/jquery.uploadfile.min.js')}}"></script>
<script src="{{asset('js/jquery.modal.min.js')}}"></script>


<script>

$(document).ready(function()
{
	$("#fileuploader").uploadFile({
        url:"{{route('admin.media.upload')}}",
        multiple:true,
        dragDrop:true,
        fileName:'upload',
        formData: {"_token":$('input[name=_token]').val()},
        allowedTypes:"jpeg,jpg,png,gif,zip,doc,pdf,mp3,wev,mpga,mp4",
        showProgress:true,
        showFileSize:true,
        onError:function(a,b,c,d){

        },
        onSuccess:function(files,data,xhr,pd){
            VanillaToasts.create({
                title: 'Success',
                text: 'File Upload Successful',
                type: 'success',
                icon: '{{asset('img/svg/success.svg')}}',
                timeout: 4000
            });

        }
	});
});

$('#fileuploader').ajaxError(
    function (event, jqXHR, ajaxSettings, thrownError) {
        VanillaToasts.create({
            title: 'Error',
            text: jqXHR.responseJSON.message,
            type: 'error',
            icon: '{{asset('img/svg/error.svg')}}',
            timeout: 4000
        });

});

</script>

@endsection
