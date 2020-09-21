<?php

namespace Eyamin\Mediawp\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Upload extends Model
{
    /**
     * mediaPath
     *
     * @param  mixed $size
     *
     * @return void
     */

    protected $attributes = array(
        // 'pathM' => '',
    );


    public function mediaPath($size = '',$main = false)
    {
        preg_match(config('mediawp.extType'),$main ? $this->getPathMain() : $this->path , $matches);


        if ($this->type == 'image/jpeg' || 'image/png') {
            switch ($size) {
                case 'thumbnail':
                    return "$matches[1]-thumbnail.$matches[2]";
                    break;
                case 'medium':
                    return "$matches[1]-medium.$matches[2]";
                    break;
                case 'large':
                    return "$matches[1]-large.$matches[2]";
                    break;
                case 'source':
                    return $main ? $this->getPathMain() : $this->path;
                    break;
                default:
                    return "$matches[1]-medium.$matches[2]";
                    break;
            }
        }else{
            return $main ? $this->getPathMain() : $this->path;
        }

        return $main ? $this->getPathMain() : $this->path;
    }

    /**
     * getMediaSizeAttribute
     *
     * @return void
     */
    public function getMediaSizeAttribute()
    {
        $path = str_replace('storage/','',$this->path);

        if(Storage::disk('public')->exists($path)){
            return $this->bytesToHuman(Storage::disk('public')->size($path));
        }

       return 'Unknown Bit';

    }

    /**
     * bytesToHuman
     *
     * @param  mixed $bytes
     *
     * @return void
     */
    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * mediaIcon
     *
     * @return void
     */
    public function mediaIcon()
    {

        preg_match(config('mediawp.extType'), $this->path, $matches);


        switch ($this->type) {
            case 'image/jpeg':
                return asset("$matches[1]-thumbnail.$matches[2]");
                break;
            case 'image/png':
                return  asset("$matches[1]-thumbnail.$matches[2]");
                break;
            case 'image/gif':
                return asset('img/svg/gif.svg');
                break;
            case 'application/x-zip-compressed':
                return asset('img/svg/archive.svg');
                break;
            case 'application/pdf':
                return asset('img/svg/pdf.svg');
                break;
            case 'application/msword':
                return asset('img/svg/document.svg');
                break;
            case 'video/mp4':
                return asset('img/svg/video-file.svg');
                break;
            case 'audio/mpeg':
                return asset('img/svg/audio.svg');
                break;
            case 'text/plain':
                return asset('img/svg/file.svg');
                break;
            case 'audio/wav':
                return asset('img/svg/audio.svg');
                break;
            default:
                return asset('img/svg/file.svg');
                break;
        }
    }

    /**
     * getPathAttribute
     *
     * @param  mixed $path
     *
     * @return void
     */
    public function getPathAttribute($path)
    {
        return "storage/".$path;
    }

    public function getPathMain()
    {
        return $this->attributes['path'];
    }

    /**
     * user
     *
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
