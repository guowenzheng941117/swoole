<?php

namespace App\Utility;

use EasySwoole\Component\Singleton;
use EasySwoole\Log\LoggerInterface;

class MyLog implements LoggerInterface
{

    use Singleton;

    public function log(?string $msg, int $logLevel = self::LOG_LEVEL_INFO, string $category = 'DEBUG'): string
    {
        $date = date('Y-m-d H:i:s');
        $levelStr = $this->levelMap($logLevel);
        $filePath = EASYSWOOLE_ROOT . "/Log/" . date('Y-m-d') . "/log.log";
        $dir = EASYSWOOLE_ROOT . "/Log/" . date('Y-m-d');
        $str = "[{$date}][{$category}][{$levelStr}] : [{$msg}]\n";
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        file_put_contents($filePath, "{$str}", FILE_APPEND | LOCK_EX);
        return $str;
    }

    public function console(?string $msg, int $logLevel = self::LOG_LEVEL_INFO, string $category = 'DEBUG')
    {
        $date = date('Y-m-d H:i:s');
        $levelStr = $this->levelMap($logLevel);
        $temp = $this->colorString("[{$date}][{$category}][{$levelStr}] : [{$msg}]", $logLevel) . "\n";
        fwrite(STDOUT, $temp);
    }

    private function colorString(string $str, int $logLevel)
    {
        switch ($logLevel) {
            case self::LOG_LEVEL_INFO:
                $out = "[42m";
                break;
            case self::LOG_LEVEL_NOTICE:
                $out = "[43m";
                break;
            case self::LOG_LEVEL_WARNING:
                $out = "[45m";
                break;
            case self::LOG_LEVEL_ERROR:
                $out = "[41m";
                break;
            default:
                $out = "[42m";
                break;
        }
        return chr(27) . "$out" . "{$str}" . chr(27) . "[0m";
    }

    private function levelMap(int $level)
    {
        switch ($level) {
            case self::LOG_LEVEL_INFO:
                return 'INFO';
            case self::LOG_LEVEL_NOTICE:
                return 'NOTICE';
            case self::LOG_LEVEL_WARNING:
                return 'WARNING';
            case self::LOG_LEVEL_ERROR:
                return 'ERROR';
            default:
                return 'UNKNOWN';
        }
    }
}
