<?php

namespace Eyamin\Mediawp\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaUploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     *
     * jpeg,gif,png,pdf,mp4,mp3,doc,docx,txt,wav,zip,pdf
     */
    public function rules()
    {
        return [
            'upload' => config('mediawp.validationsTypes'),
        ];
    }

    /**
     * messages
     * set messages
     * @return void
     */
    public function messages()
    {
        return [
            'upload.required' => 'File is required',
            // 'upload.mimes'  => 'A message is required',
        ];
    }

    /**
     * attributes
     *
     * attributes Name to replace input name of html
     * 
     * @return void
     */
    public function attributes()
    {
        return [
            'upload' => 'File',
        ];
    }
}
