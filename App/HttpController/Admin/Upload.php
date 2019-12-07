<?php

namespace App\HttpController\Admin;

use App\HttpController\Admin\Base;
use App\Utility\UPToken;
use App\Validate\UploadValidate;

class Upload extends Base
{
    public function getToken()
    {
        $data = json_decode($this->request()->getBody()->__toString(), true);
        $uv = new UploadValidate();
        $bool = $uv->getToken($data);
        if (!$bool['status']) {
            $this->writeJson(200, null, $bool['msg']);
        } else {
            $token = [];
            switch ($data['type']) {
                case 'image':
                    $token = UPToken::getInstance()->imageToken($data['fileclassify']);
                    break;
                case 'video':
                    $token = UPToken::getInstance()->videoToken($data['fileclassify'], $data['frame']);
                    break;
            }
            $this->writeJson(200, $token, 'ok');
        }
    }
}
