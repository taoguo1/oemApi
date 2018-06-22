<?php
namespace App\WWW\Controller;

use Core\Base\Controller;
use Core\DB\DBQ;
use Core\Lib;

class Down extends Controller
{
    public function index()
    {
        $appid = Lib::get('appid');
        if(!$appid){
            exit("参数错误");
        }
        $postData = [
            'appid' => $appid,
            'version'=>OEM_CTRL_URL_VERSION
        ];
        //查询该应用是否已切通道
        $appsldRest = Lib::httpPostUrlEncode('https://manageld.dizaozhe.cn/Jump', $postData);

        if($appsldRest=="true"){
            header("location:https://appsld.dizaozhe.cn/down/?appid=".$postData['appid']);
        }
        
        $ret = Lib::httpPostUrlEncode(OEM_CTRL_URL.'api/getConfig', $postData);
        $ret = json_decode($ret,true);
//         print_r($ret);
        $this->assign('ret', $ret);
        $this->assign('appid', $appid);
        $this->view();
    }
    
    public function test(){
        $pageArr = Lib::setPagePars ();
        $condition['AND']['A.agent_id'] = 1;
        $data = DBQ::pages($pageArr, 'agent_account (A)', [
            //'[>]agent (B)' => ['A.agent_id' => 'id'],
            '[>]user_account (C)' => ['A.order_sn' => 'order_sn'],
            '[>]user (D)' => ['C.user_id' => 'id']
        ],
            [
                'A.id',
                'A.amount',
                'A.agent_id',
                'A.order_sn',
                'A.description',
                'A.create_time',
                'C.user_id',
                'D.id'
            ],$condition);
        var_dump($data);
    }
}