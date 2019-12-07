<?php

namespace App\HttpController\Admin;

use EasySwoole\Http\AbstractInterface\Controller;
use App\Model\OwnerModel;
use App\Utility\Common;
use App\Validate\OwnerValidate;
use EasySwoole\RedisPool\Redis;
use EasySwoole\Utility\Random;
use EasySwoole\Jwt\Jwt;
use Exception;

class Index extends Controller
{

    public function index()
    {
        $this->actionNotFound('index');
    }

    protected function actionNotFound(?string $action)
    {
        $this->response()->withStatus(404);
        $file = EASYSWOOLE_ROOT . '/vendor/easyswoole/easyswoole/src/Resource/Http/404.html';
        if (!is_file($file)) {
            $file = EASYSWOOLE_ROOT . '/src/Resource/Http/404.html';
        }
        $this->response()->write(file_get_contents($file));
    }

    public function checkLogin()
    {
        try {
            //获取请求参数
            $data = json_decode($this->request()->getBody()->__toString(), true);
            $ov = new OwnerValidate();
            $bool = $ov->editOwner($data);
            if (!$bool['status'])
                throw new Exception($bool['msg']);
            $om = new OwnerModel();
            $info = $om->checkLogin($data['phone']);
            if (empty($info)) {
                throw new Exception('账号不存在');
            }
            $result = $info[0];
            $uid = $result['id'];
            //验证密码
            if ($data['password'] == $result['password']) {
                //生产token
                $res = $this->makeToken($uid);
                $result['oid'] = $res['id'];
                $result['ot'] = $res['token'];
                unset($result['password']);
                //修改登录时间
                $om->editOwner(['id' => $uid, 'login_time' => date('Y-m-d H:i:s')]);
                $this->writeJson($result, '成功', 1);
            } else {
                throw new Exception('密码错误');
            }
        } catch (Exception $e) {
            $this->writeJson(null, $e->getMessage(), $e->getCode());
        }
    }

    protected function makeToken($uid)
    {
        //生产token
        $jwtObject = Jwt::getInstance()->publish();
        $jwtObject->setIss('bjdl'); // 该JWT的签发者
        $jwtObject->setExp(time() + 2592000); // 什么时候过期，这里是一个Unix时间戳（7200 = 2hours）
        $jwtObject->setAud($uid); //设置接收方
        $token = $jwtObject->__toString();

        $method = 'DES-ECB'; //加密方法
        $passwd = 'ownerid'; //加密密钥
        $options = 0; //数据格式选项
        $oid = openssl_encrypt($uid, $method, $passwd, $options);
        return ['token' => $token, 'id' => $oid];
    }

    protected function writeJson($data = null, $info = null, $status = null)
    {
        if (!$this->response()->isEndResponse()) {
            $data = array(
                "data" => $data,
                "info" => $info,
                "status" => $status
            );
            $this->response()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type', 'application/json;charset=utf-8');
            $this->response()->withStatus(200);
            return true;
        } else {
            return false;
        }
    }

    function onException(\Throwable $throwable): void
    {
        MyLog::getInstance()->log($throwable->getMessage(), MyLog::LOG_LEVEL_INFO, 'Exception');
        $this->writeJson(null, $throwable->getMessage(), $throwable->getCode());
    }
}
