<?php
namespace Core\Extend;


class RedisAliMulti
{
    /**
     * 构造函数
     * @param array $options 缓存参数
     * @param null  $select
     * @access public
     */
    public $redis;
    public function __construct()
    {
        $this->redis = new \Redis();
        
    }
    
    public function connection($conf){
        
        //$conf = REDIS['token'];
       
        $this->redis->connect($conf['host'], $conf['port']);
        $this->redis->auth($conf['password']);
        return $this->redis;
        
    }
    public function run(){
        
    }
}