<?php

namespace Eyamin\Mediawp\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as InterventionImage;
use Storage;
use App\Http\Controllers\Controller;
use Eyamin\Mediawp\Models\Upload;
use Eyamin\Mediawp\Http\Requests\MediaUploadRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class MediaUploadController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * index
     *
     * @return void
     */
    public function index()
    {
        $uploads = Upload::latest()->get();
        // return Upload::find(64)->mediaPath('large');


        return view('mediawp::media.index', compact('uploads'));
    }
    /**
     * upload
     *
     * @return void
     */
    public function upload()
    {
        return view('mediawp::media.create');
    }
    /**
     * query_attachments
     *
     * @return void
     */
    public function query_attachments()
    {
        return view('mediawp::media.components.file-list.list');
    }

    /**
     * update
     *
     * @param  mixed $r
     *
     * @return void
     */
    public function update(Request $r)
    {

        // return $r;
        $media = Upload::findOrFail($r->id);

        if ($media) {
            $media->name = $r->name;
            $media->save();


            return response(['success'=>'File Deleted Successfully'],Response::HTTP_ACCEPTED);
        }else{
            return response(['error'=>'Request Not Found'],Response::HTTP_NOT_FOUND);
        }
    }


    /**
     * store
     *
     * @param  mixed $request
     *
     * @return void
     */
    public function store(MediaUploadRequest $request)
    {
        $file = $request->file('upload');

        $upload = new Upload;

        $folder = date('Y') . DIRECTORY_SEPARATOR . date('F') . DIRECTORY_SEPARATOR;

        $filename = str_random(10) . '-' . time() . '.' . $file->getClientOriginalExtension();

            // return $filename;


        if (!preg_match(config('mediawp.extType'), $filename, $matches)) {
            return response()->json(['error' => 'Format Not Suport'], 422);
        };

        $matches[4] = $file->getClientMimeType();

        switch ($file->getClientMimeType()) {
                //Copy and crop resize image brfore
            case 'image/jpeg':

                $image = InterventionImage::make($file);

                $image->backup();


                $image_original = $image->encode($matches[2]);
                $this->storeInStorage($image_original, $filename,$folder,$matches[2]);
                $image->reset();



                $image_thumbnail = $image->fit(300)->encode($matches[2]);
                $this->storeInStorage($image_thumbnail, "$matches[1]-thumbnail.$matches[2]",$folder,$matches[2]);
                $image->reset();



                $image_medium = $image->resize(
                    config('mediawp.image_medium_width'),
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )->encode($matches[2]);
                $this->storeInStorage($image_medium, "$matches[1]-medium.$matches[2]",$folder,$matches[2]);
                $image->reset();



                $image_large = $image->resize(
                    config('mediawp.image_large_width'),
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )->encode($matches[2]);
                $this->storeInStorage($image_large, "$matches[1]-large.$matches[2]",$folder,$matches[2]);
                $image->reset();


                break;

            case 'image/png':

                $image = InterventionImage::make($file);

                $image->backup();


                $image_original = $image->encode($matches[2]);
                $this->storeInStorage($image_original,$filename,$folder,$matches[2]);
                $image->reset();



                $image_thumbnail = $image->fit(300)->encode($matches[2]);
                $this->storeInStorage($image_thumbnail, "$matches[1]-thumbnail.$matches[2]",$folder,$matches[2]);
                $image->reset();



                $image_medium = $image->resize(
                    config('mediawp.image_medium_width') > $image->width() ? $image->width() : config('mediawp.image_medium_width'),
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )->encode($matches[2]);
                $this->storeInStorage($image_medium, "$matches[1]-medium.$matches[2]",$folder,$matches[2]);
                $image->reset();



                $image_large = $image->resize(
                    config('mediawp.image_large_width') > $image->width() ? $image->width() : config('mediawp.image_large_width'),
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                )->encode($matches[2]);
                $this->storeInStorage($image_large, "$matches[1]-large.$matches[2]",$folder,$matches[2]);
                $image->reset();

                break;

                //Copy And Paste In Disk no edit need
            case 'image/gif':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;

            case 'application/x-zip-compressed':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;


            case 'application/pdf':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;

            case 'application/msword':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;

            case 'video/mp4':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;

            case 'audio/mpeg':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;

            case 'text/plain':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;

            case 'audio/wav':
                $this->storeInStorage($file, $filename,$folder,$matches[2]);
                break;

            default:
                return response()->json(['error' => 'Format Not Suport'], 422);
                break;
        }


        $upload->path = $folder.$filename;
        $upload->type = $file->getClientMimeType();
        $upload->name = $file->getClientOriginalName();
        $upload->user_id = Auth::id();

        $resize_width = null;
        $resize_height = null;


        $upload->save();

        $data = $upload;

        return response($data,200);

        // return redirect(route('admin.media'));


    }

    /**
     * editImage
     *
     * @param  mixed $r
     *
     * @return void
     */
    public function editImage(Request $r)
    {
        return $r;
    }

    /**
     * delete
     *
     * @param  mixed $r
     *
     * @return void
     */
    public function delete(Request $r)
    {
        $media = Upload::findOrFail($r->id);

        if ($media) {
            if ($media->type == 'image/jpeg' || $media->type == 'image/png') {
                 //Delete Image File
                preg_match(config('mediawp.extType'), $media->getPathMain(), $matches);

                if(Storage::disk('public')->exists("$matches[1]-thumbnail.$matches[2]")){
                    Storage::disk('public')->delete("$matches[1]-thumbnail.$matches[2]");
                }
                if(Storage::disk('public')->exists("$matches[1]-medium.$matches[2]")){
                    Storage::disk('public')->delete("$matches[1]-medium.$matches[2]");
                }
                if(Storage::disk('public')->exists("$matches[1]-large.$matches[2]")){
                    Storage::disk('public')->delete("$matches[1]-large.$matches[2]");
                }
                if(Storage::disk('public')->exists($media->getPathMain())){
                    Storage::disk('public')->delete($media->getPathMain());
                }
                $media->delete();
                return response(['success'=>'File Deleted Successfully'],Response::HTTP_ACCEPTED);

            }else{

                //Delete Normal File
                if(Storage::disk('public')->exists($media->getPathMain())){
                    Storage::disk('public')->delete([$media->getPathMain()]);
                }
            }
            $media->delete();
            return response(['success'=>'File Deleted Successfully'],Response::HTTP_ACCEPTED);
        }else{
            return response(['error'=>'Request Not Found'],Response::HTTP_NOT_FOUND);
        }
    }





    /**
     * storeInStorage
     *
     * @param  mixed $media
     * @param  mixed $path
     * @param  mixed $folder
     * @param  mixed $matches
     *
     * @return void
     */
    public function storeInStorage($media,$path,$folder,$matches)
    {

        if ($matches == 'jpg' || $matches =='jpeg' ||$matches == 'png') {
            Storage::disk('public')->put(
                "$folder$path",
                $media,
                'public'
            );
        }else{
            $media->storeAs("public/$folder",$path);

        }
    }
    // public function d()
    // {




    // if (isset($this->options->resize) && (
    //                 isset($this->options->resize->width) || isset($this->options->resize->height)
    //             )) {
    //             if (isset($this->options->resize->width)) {
    //                 $resize_width = $this->options->resize->width;
    //             }
    //             if (isset($this->options->resize->height)) {
    //                 $resize_height = $this->options->resize->height;
    //             }
    //         } else {
    //             $resize_width = $image->width();
    //             $resize_height = $image->height();
    //         }

    //         $resize_quality = isset($this->options->quality) ? intval($this->options->quality) : 75;

    //         $image = $image->resize(
    //             $resize_width,
    //             $resize_height,
    //             function (Constraint $constraint) {
    //                 $constraint->aspectRatio();
    //                 if (isset($this->options->upsize) && !$this->options->upsize) {
    //                     $constraint->upsize();
    //                 }
    //             }
    //         )->encode($file->getClientOriginalExtension(), $resize_quality);

    //         if ($this->is_animated_gif($file)) {
    //             Storage::disk(config('voyager.storage.disk'))->put($fullPath, file_get_contents($file), 'public');
    //             $fullPathStatic = $path.$filename.'-static.'.$file->getClientOriginalExtension();
    //             Storage::disk(config('voyager.storage.disk'))->put($fullPathStatic, (string) $image, 'public');
    //         } else {
    //             Storage::disk(config('voyager.storage.disk'))->put($fullPath, (string) $image, 'public');
    //         }

            // if (isset($this->options->thumbnails)) {
            //     foreach ($this->options->thumbnails as $thumbnails) {
            //         if (isset($thumbnails->name) && isset($thumbnails->scale)) {
            //             $scale = intval($thumbnails->scale) / 100;
            //             $thumb_resize_width = $resize_width;
            //             $thumb_resize_height = $resize_height;

            //             if ($thumb_resize_width != null && $thumb_resize_width != 'null') {
            //                 $thumb_resize_width = intval($thumb_resize_width * $scale);
            //             }

            //             if ($thumb_resize_height != null && $thumb_resize_height != 'null') {
            //                 $thumb_resize_height = intval($thumb_resize_height * $scale);
            //             }

            //             $image = InterventionImage::make($file)->resize(
            //                 $thumb_resize_width,
            //                 $thumb_resize_height,
            //                 function (Constraint $constraint) {
            //                     $constraint->aspectRatio();
            //                     if (isset($this->options->upsize) && !$this->options->upsize) {
            //                         $constraint->upsize();
            //                     }
            //                 }
            //             )->encode($file->getClientOriginalExtension(), $resize_quality);
            //         } elseif (isset($thumbnails->crop->width) && isset($thumbnails->crop->height)) {
            //             $crop_width = $thumbnails->crop->width;
            //             $crop_height = $thumbnails->crop->height;
            //             $image = InterventionImage::make($file)
            //                 ->fit($crop_width, $crop_height)
            //                 ->encode($file->getClientOriginalExtension(), $resize_quality);
            //         }

            //         Storage::disk(config('voyager.storage.disk'))->put(
            //             $path.$filename.'-'.$thumbnails->name.'.'.$file->getClientOriginalExtension(),
            //             (string) $image,
            //             'public'
            //         );
            //     }
            // }

            // return $fullPath;
    // }

}






















