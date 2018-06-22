<?php
namespace App\API\V100\Controller;

use Core\Lib;
use Core\Base\Controller;
use Core\Extend\RedisAliMulti;
use Core\Extend\Redis;
class ResisTest extends Controller
{
    public function index()
    {
//         $redis = new \Redis();
//         $conf = REDIS['token'];
//         $redis->connect($conf['host'], $conf['port']);
//         $redis->auth($conf['password']);
        $redis = Redis::instance('token');
        $verifycodeData = [
            'code' => '296311111',
            'mobile' => '18700820590',
            'create_time' => Lib::getMs(),
            'code_type' => 1,
            'ip' => Lib::realIp()
        ];
        
        $redis->zAdd('test_code','18700820591',json_encode($verifycodeData));
        $redis_code = $redis->zRangeByScore('test_code','0','100000000000',['withscores'=>true,'limit'=>[0,100000000000]]);
        echo "<br>";
        echo "======================================验证码redis=======================================";
        echo "<br>";
        print_r($redis_code);
        
        //$redis->close();
        
        
//         $redis = new \Redis();
//         $conf = REDIS['msg'];
//         $redis->connect($conf['host'], $conf['port']);
//         $redis->auth($conf['password']);
        
//         $redis = new \Core\Extend\RedisAliMulti(REDIS['msg']);
        $redis = Redis::instance('msg');
        $verifycodeData = [
            'code' => '296311111',
            'mobile' => '18700820590',
            'create_time' => Lib::getMs(),
            'code_type' => 1,
            'ip' => Lib::realIp()
        ];
        
        $redis->zAdd('test_code','18700820591',json_encode($verifycodeData));
        $redis_code = $redis->zRangeByScore('test_code','0','100000000000',['withscores'=>true,'limit'=>[0,100000000000]]);
        echo "<br>";
        echo "======================================msgredis=======================================";
        echo "<br>";
        print_r($redis_code);
//         $redis->close();
        
       
    }
}

