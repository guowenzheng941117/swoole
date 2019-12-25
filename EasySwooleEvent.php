<?php

namespace EasySwoole\EasySwoole;

use App\Utility\Common;
use App\Utility\MyLog;
use App\Utility\ReverseProxyTools;
use EasySwoole\Component\Di;
use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\EasySwoole\SysConst;

use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Jwt\Jwt;
use EasySwoole\ORM\DbManager;
use EasySwoole\ORM\Db\Config;
use EasySwoole\ORM\Db\Connection;
use EasySwoole\Redis\Config\RedisConfig;
use EasySwoole\Tracker\Point;
use EasySwoole\Tracker\PointContext;

class EasySwooleEvent implements Event
{

    public static function initialize()
    {
        // TODO: Implement initialize() method.
        Di::getInstance()->set(SysConst::HTTP_CONTROLLER_MAX_DEPTH, 5); //配置http控制器最大解析层级
        date_default_timezone_set('Asia/Shanghai');
    }

    public static function mainServerCreate(EventRegister $register)
    {
        /*################ JWT验证 ##################*/
        Jwt::getInstance()->publish();

        /*################ REDIS CONFIG ##################*/
        \EasySwoole\RedisPool\Redis::getInstance()->register('redis', new RedisConfig(\EasySwoole\EasySwoole\Config::getInstance()->getConf('REDIS')));

        /*################ MYSQL CONFIG ##################*/
        $mysql = \EasySwoole\EasySwoole\Config::getInstance()->getConf('MYSQL');
        $config = new Config($mysql);
        DbManager::getInstance()->addConnection(new Connection($config));
        DbManager::getInstance()->onQuery(function ($res, $builder, $start) {
            MyLog::getInstance()->log(bcsub(time(), $start, 6), MyLog::LOG_LEVEL_INFO, 'SQL');
            MyLog::getInstance()->log(json_encode($builder->getLastBindParams(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), MyLog::LOG_LEVEL_INFO, 'SQL');
            MyLog::getInstance()->log($builder->getLastQuery(), MyLog::LOG_LEVEL_INFO, 'SQL');
            MyLog::getInstance()->log($builder->getLastPrepareQuery(), MyLog::LOG_LEVEL_INFO, 'SQL');
            //res不一定为数组 也可能是boolean
            MyLog::getInstance()->log(json_encode($res->toArray(), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), MyLog::LOG_LEVEL_INFO, 'SQL');
        });
    }

    public static function onRequest(Request $request, Response $response): bool
    {
        //全局跨域   不能用*
        $response->withHeader('Access-Control-Allow-Origin', '*');
        $response->withHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        $response->withHeader('Access-Control-Allow-Credentials', 'true');
        $response->withHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With,ot,bt,tt,oid,bid,tid,admin');
        // $response->withHeader('Access-Control-Expose-Headers', 'ot,bt,tt,oid,bid,tid,admin');  //允许用户从请求头拿数据
        if ($request->getMethod() === 'OPTIONS') {
            $response->withStatus(200);
            return false;
        } else {
            $point = PointContext::getInstance()->createStart('onRequest');

            $content = $request->getBody()->__toString();
            $raw_array = json_decode($content, true);

            $point->setStartArg([
                'uri' => $request->getUri()->__toString(),
                'get' => $request->getQueryParams(),
                'post' => $request->getParsedBody(),
                'row' => $raw_array,
                'method' => $request->getMethod(),
                'ip' => ReverseProxyTools::getInstance()->checkCurrentClientIP($request),
            ]);
        }
        // TODO: Implement onRequest() method.
        return true;
    }

    public static function afterRequest(Request $request, Response $response): void
    {
        if ($request->getMethod() !== 'OPTIONS') {
            // TODO: Implement afterAction() method.
            $point = PointContext::getInstance()->startPoint();
            $point->end();
            $array = Point::toArray($point);
            Common::getInstance()->savePoint($array);
        }
    }
}
