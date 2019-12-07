<?php

namespace App\HttpController\Admin;

use App\HttpController\Admin\Base;
use App\Validate\UserValidate;
use App\Model\UserInfo;
use Exception;

class User extends Base
{
    public function userList()
    {
        try {
            $data = json_decode($this->request()->getBody()->__toString(), true);
            $ov = new UserValidate();
            $bool = $ov->userList($data);
            if (!$bool['status']) {
                throw new Exception($bool['msg']);
            }
            $om = new UserInfo();
            $this->writeJson($om->getUserInfo($data), 'ok', 1);
        } catch (Exception $e) {
            $this->writeJson(null, $e->getMessage(), $e->getCode());
        }
    }
}
