<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/1/17
 * Time: 10:04
 */

namespace Core\Extend;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\WebProcessor;
use Monolog\Formatter\JsonFormatter;

class Logs
{
    public static function logs($extArr)
    {
        // 创建Logger实例
        $logger = new \Monolog\Logger('dzz_logger');
        // 添加handler
        $file = APP_PATH.'Public/Logs/'.date("Ymdh").".log";
        $stream_handler = new StreamHandler($file, Logger::INFO);
        $stream_handler->setFormatter(new JsonFormatter());
        $logger->pushHandler($stream_handler);
        $logger->pushProcessor(new WebProcessor());

        //添加额外数据
        $logger->pushProcessor(function ($record) use ($extArr) {
            $record['extra'] = $extArr;
            return $record;
        });

        // 开始使用
        $logger->addInfo('My logger is now ready');
    }
}