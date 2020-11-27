// X-CSRF-TOKEN send to server each request
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


//update list of selected item each time by database id
function updateMediaListAdd(newKey,element) {
    if (mediaWp.multiSelect) {
        mediaList.push(newKey);
    }else{
        $('.attachment').each((index,ele)=>{
            if (ele.getAttribute('data-id') != element.delegateTarget.getAttribute('data-id')) {
                ele.setAttribute('aria-checked',false)
                ele.children[1].setAttribute('tabindex',0)
                ele.children[1].style = 'display:none';
            }
        })
        mediaList = [newKey]

    }
    console.log(mediaList);
}


// show image in modal after clicking a attacment
function attachmentSelected(){
    console.log(mediaWp.result);
    $('.editor-post-featured-image-show').removeClass('d-none');
    $('.editor-post-featured-image-show img').attr('src',mediaWp.result.src);
    $('#post-featured-image').val(mediaWp.result.srcM);
    $.modal.close();
}

//update list of selected item each time by database id in multibul select mode
function updateMediaListDel(Key) {
    if (mediaWp.multiSelect) {
        mediaList = mediaList.filter(item=>{
            if (item !== Key) {
                return item
            }

        });
    }else{
        mediaList = [];
    }
    console.log(mediaList);

}



// manage select and deselect function
function attachmentsBrowseLoded() {
    $('.attachment').each((index,ele)=>{
        if (!mediaWp.selectMod) {
            return false;
        }
        $(ele).click(e=>{
            if (eval(e.delegateTarget.getAttribute('aria-checked'))) {
                e.delegateTarget.setAttribute('aria-checked',false);


                if (e.delegateTarget.getAttribute('aria-checked')) {
                    e.currentTarget.children[1].setAttribute('tabindex',0);
                    e.currentTarget.children[1].style = 'display:none';
                    updateMediaListDel(e.delegateTarget.getAttribute('data-id'));

                    if (mediaList.length == 0) {
                        $('#attachmentSelecteBTN').attr('disabled','disabled');
                    }
                    // document.querySelector(".sourcemedia").src  = '';
                    mediaWp.result = {} ;
                };
            }else{
                e.delegateTarget.setAttribute('aria-checked',true)
                e.currentTarget.children[1].setAttribute('tabindex',1);
                e.currentTarget.children[1].style = 'display:block';
                updateMediaListAdd(e.delegateTarget.getAttribute('data-id'),e);


                let mediaPathM = e.delegateTarget.getAttribute('aria-pathmain');
                let mediaPath = e.delegateTarget.getAttribute('aria-path');
                // $('#valuemedia').val(mediaPath)
                console.log(mediaPath);
                
                mediaWp.result.src = mediaPath;
                mediaWp.result.srcM = mediaPathM;
                mediaWp.result.media_id = e.delegateTarget.getAttribute('data-id');

                if (mediaList.length != 0) {
                        $('#attachmentSelecteBTN').removeAttr('disabled');
                }
                // document.querySelector(".sourcemedia").src = $('#valuemedia').val();
            };

        });
    });


    $('.dynamic-ele').html(`
        <button disabled onClick="attachmentSelected()" style="position:absolute;bottom:10px;right:10px;" id="attachmentSelecteBTN" class="btn btn-md ml-3 btn-success float-right">Select</button>
    `);
};



//load modal of each attacments and load details of attachment

$('li[rel="modal:open"]').click(function(event) {
    if (!mediaWp.viewMode) {
        return false;
    }

    event.preventDefault();
    this.blur();

    activeModel = this.id;

    $('#attachments-view-ui').modal().html(`
        <div class="container" >
            <div>
                <div>
                    <div class="attachments-view-nav " style="display:flow-root">
                        <div class="float-right">
                            <button onclick="attachmentsViewPrevious(event)" class="attachments-view-previous btn btn-primary btn-sm">prev</button>
                            <button onclick="attachmentsViewNext(event)" class="attachments-view-next btn btn-primary btn-sm">Next</button>
                        </div>

                        <h1 class="text-left h3 pt-1 float-left">Attachment Details</h1>
                    </div>
                    <div class="container pt-3">
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <div>

                                    ${
                                        event.delegateTarget.getAttribute('aria-type') == 'image/jpeg' ||event.delegateTarget.getAttribute('aria-type') == 'image/png' ?
                                        "<img src="+event.delegateTarget.getAttribute('aria-path').replace(/\\/g, '/')+" class='attachment-info-show-image' onload="+attachmentInfoImageLoad(event)+" />" :
                                        event.delegateTarget.getAttribute('aria-type') == 'video/mp4' ?
                                        "<video style='width:100%' controls><source src="+event.delegateTarget.getAttribute('aria-path')+" type="+event.delegateTarget.getAttribute('aria-type')+"></video>" :
                                        event.delegateTarget.getAttribute('aria-type') == 'audio/mpeg' ?
                                        "<audio style='width:100%' controls><source src="+event.delegateTarget.getAttribute('aria-path')+" type="+event.delegateTarget.getAttribute('aria-type')+"></audio>" : "<a class='text-center m-auto d-block attachment-info-undisplay' target='_blank' href="+event.delegateTarget.getAttribute('aria-path')+">"+event.delegateTarget.getAttribute('aria-name')+"</a>"
                                    }
                                    <div>

                                    ${
                                        event.delegateTarget.getAttribute('aria-type') == 'image/jpeg' ||event.delegateTarget.getAttribute('aria-type') == 'image/png' ?
                                        "<button class='btn btn-info btn-sm' onclick='attachmentsImageEdit("+event.delegateTarget.getAttribute('data-id')+")' type=\"button\">Edit Image</button>" : ""
                                    }


                                    <button id="attachments-download" class='btn btn-success float-right btn-sm' onclick="attachmentsDownload(${event.delegateTarget.getAttribute('data-id')})" type="button">Download</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4  col-sm-12">
                                <div>
                                    <div>

                                        <label>
                                            <span><strong>Name: </strong></span>
                                            <input type="text" class='form-control attachment-name-u' value="${event.delegateTarget.getAttribute('aria-name')}">
                                        </label>


                                        <div>
                                        <strong>File type:</strong>
                                            ${event.delegateTarget.getAttribute('aria-type')}
                                        </div>




                                        <div><strong>Uploaded on:</strong> ${new Date(event.delegateTarget.getAttribute('aria-uploaded')).toDateString()} | ${new Date(event.delegateTarget.getAttribute('aria-uploaded')).toLocaleTimeString()}</div>



                                        <div><strong>File size:</strong> ${event.delegateTarget.getAttribute('aria-size')}</div>
                                        <div class="attachment-image-dy">


                                        </div>
                                    </div>
                                    <div>

                                        <div>
                                            <span>
                                                <strong>Uploaded By:</strong>
                                            </span>
                                            <span>${event.delegateTarget.getAttribute('aria-user')}</span>
                                        </div>

                                        <label data-setting="url">
                                            <span><strong>Copy Link: </strong></span>
                                            <input type="text" onclick="this.select();document.execCommand('copy')" class='form-control' value="${event.delegateTarget.getAttribute('aria-path').replace(/\\/g, '/')}" readonly="">
                                        </label>
                                        <div>
                                            <form></form>
                                        </div>
                                    </div>
                                    <div>
                                        <button class='btn btn-danger btn-sm btn-rounded' onclick="v = confirm('Are you sure you want to delete this item?'); v ? attachmentDelete(${event.delegateTarget.getAttribute('data-id')}) : '';return v" type="button">Delete</button>

                                        <button class='btn btn-info btn-sm btn-rounded' onclick="attachmentUpdate(${event.delegateTarget.getAttribute('data-id')})" type="button">Update</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `);
})




//fix windows overflowY bug after modal CLOSE
$(`#attachments-view-ui`).on($.modal.AFTER_CLOSE , function() {
    $('body').css('overflowY','auto');
})


// attachments previous and button disabled

function attachmentsViewPrevious() {


    if($(`#${activeModel}`).prev().length > 0) {
        $(`#${activeModel}`).prev().click();
        $('.undefined.blocker.behind').detach();
    }
    else {
        $('.attachments-view-previous').attr('disabled','disabled');
    }
}

// attachments next and button disabled
function attachmentsViewNext(event) {
    if($(`#${activeModel}`).next().length > 0) {
        $(`#${activeModel}`).next().click();
        $('.undefined.blocker.behind').detach();
    }
    else {
        $('.attachments-view-next').attr('disabled','disabled');
    }
}






// calculate each image Dimensions in modal
function attachmentInfoImageLoad(event){
    var img = new Image();
    img.src = event.delegateTarget.getAttribute('aria-path');

    img.onload = function(){
        $('.attachment-image-dy').html(`
            <strong>Dimensions:</strong>
            ${img.naturalWidth} by ${img.naturalHeight} pixels
        `);
    }

}







// manage select and deselect function in normal mod
$('.attachment').each((index,ele)=>{

    if (!mediaWp.selectMod) {
        return false;
    }

    $(ele).click(e=>{

        if (eval(e.delegateTarget.getAttribute('aria-checked'))) {
            e.delegateTarget.setAttribute('aria-checked',false);


            if (e.delegateTarget.getAttribute('aria-checked')) {
                e.currentTarget.children[1].setAttribute('tabindex',0);
                e.currentTarget.children[1].style = 'display:none';


            };
            updateMediaListDel(e.delegateTarget.getAttribute('data-id'));
        }else{
            e.delegateTarget.setAttribute('aria-checked',true)
            e.currentTarget.children[1].setAttribute('tabindex',1);
            e.currentTarget.children[1].style = 'display:block';

            updateMediaListAdd(e.delegateTarget.getAttribute('data-id'),e);


            let mediaPath = e.delegateTarget.getAttribute('aria-path').replace(/\\/g, '/');
            $('#valuemedia').val(mediaPath)
            document.querySelector(".sourcemedia").src = $('#valuemedia').val();
        };

    })
})
