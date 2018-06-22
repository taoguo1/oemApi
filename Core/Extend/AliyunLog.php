<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/1/18
 * Time: 9:21
 */

namespace Core\Extend;

class AliyunLog
{
    private static $obj;
    private function __construct(){

    }

    public static function instance(){
        if(is_null(self::$obj)){
            self::$obj = new self;
        }
        return self::$obj;
    }



}

