<?php
namespace Core;

use Core\DB\DBQ;
use Core\Extend\Redis;
use Core\Lib;
// use Core\Extend\RedisAliMulti;

class Filter
{

    public $controller;

    public $token;

    public $uid;

    public $timestamp;

    public function __construct()
    {
        $headers = Lib::getAllHeaders();
        $this->timestamp = $headers['TIME'];
    }

    

    public function isSign($controllerName, $actionName)
    {
        $APISignController = Lib::loadFile('Config/APISignController.php');
        if (isset($APISignController[$controllerName][$actionName])) {
            if ($APISignController[$controllerName][$actionName] === true) {
                \Core\Sign::checkSign();
            }
        }
    }

    public function run($controllerName, $actionName)
    {
        $APITokenController = Lib::loadFile('Config/APITokenController.php');
        $apiVersions = Lib::loadFile('Config/APIVersion.php');
        $APPSign = Lib::loadFile('Config/APPSign.php');
        $this->controller = 'App\\' . \strtoupper(RUN_PATH) . '\\Controller\\' . ucfirst($controllerName);
        if (RUN_PATH === APP_API) {

            $headers = Lib::getAllHeaders();
            $this->isSign($controllerName, $actionName);

            if (! isset($headers['APPID'])) {
                $data = [
                    'status' => 'fail',
                    'code' => 10008,
                    'msg' => '缺少APPID参数'
                ];
                Lib::outputJson($data);
            } else {
                $isAppId = false;
                foreach ($APPSign as $v) {
                    if (\in_array($headers['APPID'], $v)) {
                        $isAppId = true;
                    }
                }
                if (! $isAppId) {
                    $data = [
                        'status' => 'fail',
                        'code' => 10009,
                        'msg' => '无效的APPID参数'
                    ];
                    Lib::outputJson($data);
                }
            }
            if (! isset($headers['APPSECRET'])) {
                $data = [
                    'status' => 'fail',
                    'code' => 10010,
                    'msg' => '缺少APPSECRET参数'
                ];
                Lib::outputJson($data);
            } else {
                $isAppId = false;
                foreach ($APPSign as $v) {
                    if (\in_array($headers['APPSECRET'], $v)) {
                        $isAppId = true;
                    }
                }
                if (! $isAppId) {
                    $data = [
                        'status' => 'fail',
                        'code' => 10011,
                        'msg' => '无效的APPSECRET参数'
                    ];
                    Lib::outputJson($data);
                }
            }
            if (isset($headers['VERSION'])) {
                $apiVersion = $headers['VERSION'];
                $apiVersionsKeys = \array_keys($apiVersions);
                
                if (! \in_array($apiVersion, $apiVersionsKeys)) {
                    $data = [
                        'status' => 'fail',
                        'code' => 10006,
                        'msg' => '无效的版本信息'
                    ];
                    Lib::outputJson($data);
                } else {
                    $apiVersionArr = $apiVersions[$apiVersion];
                    if (! $apiVersionArr['status']) {
                        $data = [
                            'status' => 'fail',
                            'code' => 10007,
                            'msg' => $apiVersionArr['msg']
                        ];
                        Lib::outputJson($data);
                    }
                }
                if (in_array($controllerName, $APITokenController)) {
                    if (isset($headers['TOKEN'])) {
                        $this->token = $headers['TOKEN'];
                    } else {
                        $data = [
                            'status' => 'fail',
                            'code' => 10003,
                            'msg' => '缺少TOKEN参数'
                        ];
                        Lib::outputJson($data);
                    }
                    if (isset($headers['UID'])) {
                        $this->uid = $headers['UID'];
                    } else {
                        $data = [
                            'status' => 'fail',
                            'code' => 10002,
                            'msg' => '缺少UID参数'
                        ];
                        Lib::outputJson($data);
                    }
                    
/*                    $row = DBQ::getRow('token', [
                        'uid',
                        'token',
                        'utype',
                        'create_time'
                    ], [
                        'uid' => $this->uid,
                        'token' => $this->token,
                        'utype' => $headers['UTYPE']
                    ]);*/

//                     $redisObj =  RedisAliMulti::getRedisInstance(REDIS['token'], 0);
//                     $redis = $redisObj->getConnect();
                   
//                     $redis = new \Redis();
//                     $conf = REDIS['token'];
//                     $redis->connect($conf['host'], $conf['port']);
//                     $redis->auth($conf['password']);
                    $redis = Redis::instance('token');
                    //$redis->zRemRangeByScore('1feb30526e31e188_code','-inf','+inf');
                    
                    //$redis ->set( "test" , "Hello World");
                  //  echo $redis ->get( "test");  
                    
                    $appid = Lib::request("appid");
               
                    //$redis = Redis::instance('token');
                    if ($headers['UTYPE'] == 1) {
                        $redis_key = $appid.'_user_token';
                    } else {
                        $redis_key = $appid.'_agent_token';
                    }
                    $row = $redis->zRangeByScore($redis_key,$this->uid,$this->uid,['withscores'=>false,'limit'=>[0,100000]]);
                    if (count($row) >= 1){
                        $row = json_decode($row[0],true);
                        if ($row['token'] != $this->token) $row = null;
                    }
                    if (empty($row)) {
                        $data = [
                            'status' => 'fail',
                            'code' => 10012,
                            'msg' => '无效TOKEN参数'
                        ];
                        Lib::outputJson($data);
                    } else {

                        // 判断token是否到期，如果到期需要重新发布token
                        $create_time = $row['create_time'];
                        // token 有效期为2小时 3600*2
                        $now = Lib::getMs();
                        $diff = ($now - $create_time) / 1000;

                        if ($diff >= (3600 * 2)) {
                            // 重新发布token
                            $token = md5(Lib::getMs() . rand(0, 999999));
/*                            DBQ::upd('token', [
                                'token' => $token,
                                'create_time' => Lib::getMs()
                            ], [
                                'uid' => $this->uid,
                                'token' => $this->token,
                                'utype' => $headers['UTYPE']
                            ]);*/
                            //$token_data = json_decode($row,true);
                            $row['token'] = $token;
                            $row['create_time'] = Lib::getMs();
                            $redis->zRemRangeByScore($redis_key,$this->uid,$this->uid);
                            $redis->zAdd($redis_key,$this->uid,json_encode($row));

                            // 插入日志

                            if ($headers['UTYPE'] == 1) {
                                DBQ::add('user_login_log', [
                                    'user_id' => $this->uid,
                                    'device_id' => $headers['DEVICEID'],
                                    'device_os' => $headers['SYSTEMTYPE'],
                                    'token' => $token,
                                    'remarks' => '重新颁发TOKEN',
                                    'create_time' => Lib::getMs()
                                ]);
                            }
                            if ($headers['UTYPE'] == 2) {
                                DBQ::add('agent_login_log', [
                                    'agent_id' => $this->uid,
                                    'device_id' => $headers['DEVICEID'],
                                    'device_os' => $headers['SYSTEMTYPE'],
                                    'token' => $token,
                                    'remarks' => '重新颁发TOKEN',
                                    'create_time' => Lib::getMs()
                                ]);
                            }
                            $data = [
                                'status' => 'success',
                                'code' => 10013,
                                'token' => $token,
                                'msg' => 'TOKEN已经失效'
                            ];
                            Lib::outputJson($data);
                        } else {}
                    }
                }
                
                $this->controller = 'App\\' . strtoupper(RUN_PATH) . '\\' . $apiVersion . '\\Controller\\' . $controllerName;
            } else {
                $data = [
                    'status' => 'fail',
                    'code' => 10001,
                    'msg' => '缺少VERSION参数'
                ];
                Lib::outputJson($data);
            }
        }
    }
}