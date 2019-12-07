<?php

namespace App\Utility;

use EasySwoole\Component\Singleton;
use EasySwoole\Utility\Random;
use Qiniu\Auth;
use EasySwoole\EasySwoole\Config;

class UPToken
{
    use Singleton;

    private $auth;

    public function __construct()
    {
        $qiniu = Config::getInstance()->getConf('QINIU');
        $this->auth = new Auth($qiniu['accessKey'], $qiniu['secretKey']);
    }

    public function imageToken($fileclassify)
    {
        if (is_null($fileclassify)) {
            return '文件归类不能为空';
        }
        $bucket = 'yjmhsh';
        $policy = array(
            'scope' => 'yjmhsh',
            'deadline' => 3600,
            'returnBody' => '{"key":"$(key)", "hash":"$(etag)", "fname":"$(fname)", "ext":"$(ext)"}',
            'insertOnly' => 1,
            'mimeLimit' => 'image/jpeg;image/png',
        );
        $key = $fileclassify . '_' . date('Ymd') . '_' . date("His") . '_' . Random::character(10);
        $upToken = $this->auth->uploadToken($bucket, null, 3600, $policy);
        return ['token' => $upToken, 'key' => $key];
    }

    public function videoToken($fileclassify, $frame = 5)
    {
        if (is_null($fileclassify)) {
            return '文件归类不能为空';
        }
        $bucket = 'yjmhsh';
        $policy = array(
            'scope' => 'yjmhsh',
            'deadline' => 3600,
            'returnBody' => '{"key":"$(key)", "hash":"$(etag)", "fname":"$(fname)", "ext":"$(ext)","persistentId":"$(persistentId)"}',
            'insertOnly' => 1,
            'mimeLimit' => 'video/*',
            "persistentOps" => "vframe/jpg/offset/" . $frame,
            "persistentNotifyUrl" => "https://yushenghuo.jiruipay.com/Common/Upload/logVideo",
        );
        $key = $fileclassify . '_' . date('Ymd') . '_' . date("His") . '_' . Random::character(10);
        $upToken = $this->auth->uploadToken($bucket, null, 3600, $policy);
        return ['token' => $upToken, 'key' => $key];
    }
}
