<?php

namespace Eyamin\Mediawp\Http\Controllers;

use Illuminate\Http\Request;
use Eyamin\Mediawp\Models\Upload;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as InterventionImage;

class MediaAjaxRequest extends Controller
{

    /**
     * query_menager
     *
     * @param  mixed $r
     *
     * @return void
     */
    public function query_menager(Request $r)
    {
        
        $upload = Upload::find($r->id);

        // return $upload;
        if ($r->action == 'query-attachments') {
            $type = $r->query_u['post_mime_type'];


            $orderby = empty($r->query_u['orderby']) ? 'desc' : $r->query_u['orderby'];
            $posts_per_page = empty($r->query_u['posts_per_page']) ? 20 : $r->query_u['posts_per_page'];


            $uploads = Upload::where('type','like',"%$type%")->orderby('updated_at',$orderby)->limit($posts_per_page)->get();

            return view('mediawp::media.components.uploads-list.index',compact('uploads'));



        }elseif($r->action == 'download-attachments' && $upload->exists()) {

            $path = $upload->getPathMain();


            return Storage::disk('public')->download($path,$upload->name);


        }elseif ($r->action == 'image-editor' && $upload->exists()) {



            if ($r->do == 'rotateleft') {
                return InterventionImage::make(Storage::disk('public')->get($upload->getPathMain()))->rotate($r->dig??0)->response();
            }


            if ($upload->type == 'image/jpeg' || 'image/png') {
                $size = getimagesize($upload->path);
                $upload->sizeW = $size[0];
                $upload->sizeH = $size[1];


                return view('mediawp::media.components.image-editor.image-editor',compact('upload'));
            }else {
                return response(['error'=>'Request Not Found'],Response::HTTP_NOT_FOUND);
            }


        }elseif($r->action == 'image-editor-save' && $upload->exists()) {

            return $this->imagEditorSave($upload,$r);
        }else{
            return response(['error'=>'Request Not Found'],Response::HTTP_NOT_FOUND);
        }

    }



    /**
     * query_attachments
     *
     * @param  mixed $r
     *
     * @return void
     */
    private function query_attachments($query)
    {
        return $query;

    }

    /**
     * imagEditorSave
     *
     * @param  mixed $upload
     * @param  mixed $request
     *
     * @return void
     */
    private function imagEditorSave($upload,$request)
    {
        if ($request->target == 'all') {
            return $this->imagEditorSaveAll($upload,$request);
        }elseif ($request->target == 'thumbnail') {
            return $this->imagEditorSaveThumbnail($upload,$request);
        }else{
            return $this->imagEditorSaveNothumb($upload,$request);
        }
    }

    /**
     * imagEditorSaveAll
     *
     * @param  mixed $upload
     * @param  mixed $request
     *
     * @return void
     */
    private function imagEditorSaveAll($upload,$request)
    {
        $image = InterventionImage::make($request->cropImageSrc == null ? $upload->path : $request->cropImageSrc);


        $image->crop($request->input('imagePosition.width'),$request->input('imagePosition.height'),$request->input('imagePosition.x'),$request->input('imagePosition.y'));

        $cropedImage = $image->encode();

        Storage::disk('public')->put($upload->mediaPath('thumbnail',true), $cropedImage);
        Storage::disk('public')->put($upload->mediaPath('medium',true), $cropedImage);
        Storage::disk('public')->put($upload->mediaPath('large',true), $cropedImage);
        Storage::disk('public')->put($upload->mediaPath('source',true), $cropedImage);

        return response(
            [
                'url'=>$upload->mediaPath('source',true),
                'status'=>'success',
                'message'=>'Your File Updated Successfully'
            ],
            200
        );
                
    }
    /**
     * imagEditorSaveThumbnail
     *
     * @param  mixed $upload
     * @param  mixed $request
     *
     * @return void
     */
    private function imagEditorSaveThumbnail($upload,$request)
    {
        $image = InterventionImage::make($request->cropImageSrc == null ? $upload->path : $request->cropImageSrc);


        $image->crop($request->input('imagePosition.width'),$request->input('imagePosition.height'),$request->input('imagePosition.x'),$request->input('imagePosition.y'));

        $cropedImage = $image->encode();

        Storage::disk('public')->put($upload->mediaPath('thumbnail',true), $cropedImage);

        return response(
            [
                'url'=>$upload->mediaPath('source',true),
                'status'=>'success',
                'message'=>'Your File Updated Successfully'
            ],
            200
        );
    }
    /**
     * imagEditorSaveNothumb
     *
     * @param  mixed $upload
     * @param  mixed $request
     *
     * @return void
     */
    private function imagEditorSaveNothumb($upload,$request)
    {
        $image = InterventionImage::make($request->cropImageSrc == null ? $upload->path : $request->cropImageSrc);


        $image->crop($request->input('imagePosition.width'),$request->input('imagePosition.height'),$request->input('imagePosition.x'),$request->input('imagePosition.y'));

        $cropedImage = $image->encode();

        Storage::disk('public')->put($upload->mediaPath('medium',true), $cropedImage);
        Storage::disk('public')->put($upload->mediaPath('large',true), $cropedImage);
        Storage::disk('public')->put($upload->mediaPath('source',true), $cropedImage);

        return response(
            [
                'url'=>$upload->mediaPath('source',true),
                'status'=>'success',
                'message'=>'Your File Updated Successfully'
            ],
            200
        );
    }

    /**
     * query_get_singel_media
     *
     * @param  mixed $r
     *
     * @return void
     */
    public function query_get_singel_media(Request $r)
    {
        $uploads = Upload::latest()->get();
        return $uploads;
    }
}
