<?php


return [

    'validationsTypes' => 'required|mimes:jpeg,png,jpg,doc,docx,pdf',

    'extType'=>'/([^\?]+)\.(jpe?g|jpe|gif|png|pdf|zip|mp4|mp3|docx?|txt|wav)\b/i',

    'image_thumbnail_crop' => 150, //px
    'image_medium_width' => 768, //px
    'image_large_width' => 1568, //px

];
