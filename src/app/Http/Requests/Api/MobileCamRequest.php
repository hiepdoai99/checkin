<?php

namespace App\Http\Requests\Api;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class MobileCamRequest extends BaseRequest
{

    public function rules()
    {
        return [
//            'keycode' => 'nullable',
//            'date' => 'required',
//            'personTitle' => 'nullable',
//            'temp' => 'nullable',
//            'action_type' => 'required',
            'image' => 'required|image',
//            'placeID' => 'required',
//            'deviceID' => 'required',
//            'deviceName' =>'required',
//            'personName' => 'required',
//            'aliasID' => 'required',
//            'data_type' => 'required',
//            'personID' => 'required',
//            'id' => 'required',
//            'time' => 'required',
//            'personType' => 'required',
//            'placeName' => 'required',
//            'hash' => 'required',
//            'mask' => 'required',
//            'ip' => 'required',
        ];
    }
}
