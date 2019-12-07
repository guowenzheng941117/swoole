<?php
return [
    'SERVER_NAME' => "EasySwoole",
    'MAIN_SERVER' => [
        'LISTEN_ADDRESS' => '0.0.0.0',
        'PORT' => 9501,
        'SERVER_TYPE' => EASYSWOOLE_WEB_SERVER, //可选为 EASYSWOOLE_SERVER  EASYSWOOLE_WEB_SERVER EASYSWOOLE_WEB_SOCKET_SERVER,EASYSWOOLE_REDIS_SERVER
        'SOCK_TYPE' => SWOOLE_TCP,
        'RUN_MODEL' => SWOOLE_PROCESS,
        'SETTING' => [
            'worker_num' => 8,
            'reload_async' => true,
            'task_enable_coroutine' => true, //开启后自动在onTask回调中创建协程
            'max_wait_time' => 5,
            'package_max_length' => 209715200, //修改上传文件大小1024*1024为1M
        ],
        'TASK' => [
            'workerNum' => 4,
            'maxRunningNum' => 128,
            'timeout' => 15,
        ],
    ],
    'TEMP_DIR' => null,
    'LOG_DIR' => null,
    /*################ MYSQL CONFIG ##################*/
    'MYSQL' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'user' => 'root',
        'connect_timeout' => '5', //连接超时时间
        'charset' => 'utf8mb4',
        'password' => 'root',
        'database' => 'beijingdl',
        'intervalCheckTime' => 30 * 1000, //定时验证对象是否可用以及保持最小连接的间隔时间
        'maxIdleTime' => 15, //最大存活时间,超出则会每$intervalCheckTime/1000秒被释放
        'maxObjectNum' => 2000, //最大创建数量
        'minObjectNum' => 5, //最小创建数量 最小创建数量不能大于等于最大创建
        'getObjectTimeout' => 3.0, //设置获取连接池对象超时时间
    ],
    /*################ REDIS CONFIG ##################*/
    'REDIS' => [
        'host' => '127.0.0.1',
        'port' => '6379',
        'auth' => '',
        'db' => 0, //选择数据库,默认为0
        'intervalCheckTime' => 30 * 1000, //定时验证对象是否可用以及保持最小连接的间隔时间
        'maxIdleTime' => 15, //最大存活时间,超出则会每$intervalCheckTime/1000秒被释放
        'maxObjectNum' => 8000, //最大创建数量
        'minObjectNum' => 5, //最小创建数量 最小创建数量不能大于等于最大创建
    ],
    /*################ QINIUYUN 七牛云配置 ##################*/
    'QINIU' => [
        'accessKey' => '*************',
        'secretKey' => '**************'
    ],
    /*################ BAN_LIST  不需要进行请求日志的网站   ##################*/
    'BAN_LIST' => [
        0 => 'http://yushenghuo.jiruipay.com:80/',
        1 => 'http://yushenghuo.jiruipay.com:80/favicon.ico',
        2 => 'http://47.94.101.137:80/',
    ]
];
