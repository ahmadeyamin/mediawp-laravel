<ui class="attachments">
    @foreach ($uploads as $item)
    <li 
    rel="modal:open" 
    class="attachment" 
    id="attachment-{{$item->id}}" 
    aria-type="{{$item->type}}"
    aria-name="{{$item->name}}" 
    aria-user="{{$item->user->name}}" 
    aria-uploaded="{{$item->updated_at}}"
    aria-size="{{$item->MediaSize}}" 
    aria-path="{{url($item->path)}}" 
    aria-pathmain="{{$item->getPathMain()}}" 
    aria-checked="false"
    data-id="{{$item->id}}">
        <div title="{{$item->name}}" class="attachment-preview" style="">

            <div class="thumbnail">
                <div class="centered">
                    <img src="{{$item->mediaIcon()}}" class="avatar" style="" title="{{$item->name}}" alt="">
                </div>
            </div>
        </div>

        <button type="button" class="check" tabindex="0">
            <span class="media-modal-icon"></span>
            <span class="screen-reader-text">Deselect</span>
        </button>

        <div class="attachments-info">
            <span class="attachments-info-name">{{$item->name}}</span>
            <br>
            <span class="attachments-info-size">
                ({{$item->MediaSize}})
            </span>
        </div>

    </li>
    @endforeach
</ui>

<div class="dynamic-ele">


</div>
