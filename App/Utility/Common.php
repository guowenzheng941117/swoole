<?php

namespace App\Utility;

use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Config;

class Common
{
    use Singleton;
    /**
     * @author injection(injection.mail@gmail.com)
     * @var date1日期1
     * @var date2 日期2
     * @var tags 年月日之间的分隔符标记,默认为'-'
     * @return 相差的月份数量
     * @example:
     *$date1 = "2003-08-11";
     *$date2 = "2008-11-06";
     *$monthNum = getMonthNum( $date1 , $date2 );
     *echo $monthNum;
     */
    public function getMonthNum($date1, $date2, $tags = '-')
    {
        $date1 = explode($tags, $date1);
        $date2 = explode($tags, $date2);
        return abs($date1[0] - $date2[0]) * 12 + abs($date1[1] - $date2[1]);
    }

    /**
     * @description 读取本地json
     * @param string path 文件路径
     * @return array 数组
     */
    public function readJson($path)
    {
        $result = file_get_contents($path);
        $result = mb_convert_encoding($result, "UTF-8");
        $result = json_decode($result, true);
        // return json_last_error();
        return $result;
    }

    public function savePoint($array)
    {
        $ban = [
            0 => 'http://yushenghuo.jiruipay.com:80/',
            1 => 'http://yushenghuo.jiruipay.com:80/favicon.ico',
            2 => 'http://47.94.101.137:80/',
        ];
        $ban = Config::getInstance()->getConf('BAN_LIST');
        if (!in_array($array[0]['startArg']['uri'], $ban)) {
            $data['pointId'] = $array[0]['pointId'];
            $data['pointName'] = $array[0]['pointName'];
            $data['startTime'] = $array[0]['startTime'];
            $data['endTime'] = $array[0]['endTime'];
            $data['uri'] = $array[0]['startArg']['uri'];
            if ($array[0]['startArg']['method'] === 'POST') {
                if (!empty($array[0]['startArg']['post'])) {
                    $data['params'] = json_encode($array[0]['startArg']['post'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
                if (!empty($array[0]['startArg']['row'])) {
                    $data['params'] = json_encode($array[0]['startArg']['row'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                }
            }
            if ($array[0]['startArg']['method'] === 'GET') {
                $data['params'] = json_encode($array[0]['startArg']['get'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            }
            $data['ip'] = $array[0]['startArg']['ip'];
            $data['status'] = $array[0]['status'];
            $data['spendTime'] = bcsub($array[0]['endTime'], $array[0]['startTime'], 4);
            MyLog::getInstance()->log(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), MyLog::LOG_LEVEL_INFO, 'requestPoint');
        }
    }
}
