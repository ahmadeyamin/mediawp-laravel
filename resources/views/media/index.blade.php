@extends('mediawp::layouts.app')


@section('css')

<link rel="stylesheet" href="{{asset('css/jquery.modal.min.css')}}" />
<link rel="stylesheet" href="{{asset('css/mediawp.css')}}"/>

@endsection

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <div>
                <a href="{{route('admin.media.upload')}}" class="btn btn-sm btn-primary">Upload</a>
            </div>
            <div id="attachment-load-list">
                @include('mediawp::media.components.uploads-list.list')
            </div>
        </div>
    </div>

    <div id="attachments-view-ui" class="modal w-100 clearfix h-100">
    </div>
</div>
@endsection


@section('js')

<script src="{{asset('js/jquery.modal.min.js')}}"></script>

<script>
    $.modal.defaults = {
        closeExisting: false,
        escapeClose: true,
        clickClose: true,
        closeClass: '',
        showClose: true,
        fadeDuration: 200,
    }

    let activeModel = '';

    const mediaWp = {
        'multiSelect': false,
        'viewMode': true,
        'selectMod' : false,
        'hostPath' : window.location.hostname,
        'resultPathMode' : true,
        'result' : '',
    }

    let mediaList = new Array();

    function attachmentDelete(id){

        $.ajax({
            data:{
                id
            },
            method:'post',
            dataType: "text",
            url:'{{route("admin.media.upload.delete")}}',
            success: function(result){
                $(`.attachments li[data-id=${id}]`).detach();
                $.modal.close();
                VanillaToasts.create({
                    title: 'Success',
                    text: 'File Deleted Successful',
                    type: 'success',
                    icon: '{{asset('img/svg/success.svg')}}',
                    timeout: 4000,

                });
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

    function attachmentUpdate(id){
        $.ajax({
            data:{
                id,
                name:$('.attachment-name-u').val(),
            },
            method:'post',
            dataType: "text",
            url:'{{route("admin.media.upload.update")}}',
            success: function(result){

                $(`.attachments li[data-id=${id}] .attachments-info-name`).html($('.attachment-name-u').val());

                VanillaToasts.create({
                    title: 'Success',
                    text: 'File Name Updated Successfully',
                    type: 'success',
                    icon: '{{asset('img/svg/success.svg')}}',
                    timeout: 4000,

                });
                $.modal.close();

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


    function attachmentsDownload(id){


        // return console.log(id)

        $.ajax({
            data:{
                action:'download-attachments',
                id,
            },
            method:'get',
            dataType: "html",
            url:'{{route('admin.ajex.query_menager')}}',
            success: function(result){
                window.location.href = `{{route('admin.ajex.query_menager')}}?action=download-attachments&id=${id}`
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



    function attachmentsImageEdit(id){
        $.ajax({
            data:{
                action:'image-editor',
                id,
            },
            method:'get',
            dataType: "html",
            url:'{{route('admin.ajex.query_menager')}}',
            success: function(result){
                $('#attachments-view-ui').html(result).append('<a href="#close-modal" rel="modal:close" class="close-modal "> X </a>');

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





</script>
<script src="{{asset('js/mediawp.js')}}"></script>
@endsection
