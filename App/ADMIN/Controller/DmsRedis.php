<?php

namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\Extend\Redis as RedisM;

class DmsRedis extends Controller {
    /**
     * 查看Redis
     */
    public function index()
    {
        $redis_token = RedisM::instance('token');
        $tokenList = $redis_token->keys('*');

        $msg_token = RedisM::instance('msg');
        $msgList = $msg_token->keys('*');

        $plan_token = RedisM::instance('plan');
        $planList = $plan_token->keys('*');
        $this->assign('tokenList',$tokenList);
        $this->assign('msgList',$msgList);
        $this->assign('planList',$planList);
        $this->view();
    }

    /**
     * 库 redis
     */

    public function  redisList($key=null,$type=null) {
        $redis = RedisM::instance($type);
        $pageArr = Lib::setPagePars();
        $row = $this->pages($pageArr,$key,$redis);
        $this->assign('keys',$key);
        $this->assign('type',$type);
        $this->assign('type',$type);
        $this->assign('data',$row);

        $this->view();

    }



    private function pages($pageArr,$key,$redis) {

        $pageNum = $pageArr['pageNum'];
        $numPerPage = $pageArr['numPerPage'];
        $totalCount =  $redis->zCard($key);

        $pageCount = ceil($totalCount / $numPerPage);
        $pageNum = ($pageNum <= 1) ? 1 : $pageNum;
        $pageNum = ($pageNum >= $pageCount) ? $pageCount : $pageNum;
        $isFirstPage = ($pageNum <= 1) ? 1 : 0;
        $isLastPage = ($pageNum >= $pageCount) ? 1 : 0;
        $start = ($pageNum - 1) * $numPerPage;
        $isOnePage = 0;
        if ($isFirstPage == 1 && $isLastPage == 1) {
            $isOnePage = 1;
        }
        if($start<=0){$start=0;}

        $row = $redis->zRangeByScore($key,'-inf','inf',['withscores'=>true,'limit'=>[$start,$numPerPage]]);
        if (empty($row)) {
            $row = [];
        }
        $array = [
            'pageNum' => $pageNum,
            'numPerPage' => $numPerPage,
            'totalCount' => $totalCount,
            'pageCount' => $pageCount,
            'isFirstPage' => $isFirstPage,
            'isLastPage' => $isLastPage,
            'isOnePage' => $isOnePage,
            'list' => $row
        ];

        return $array;

    }
}