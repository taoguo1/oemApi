<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;

class NotifyMessage extends Controller {

   	public $headers;
   	private $m;
   	public function __construct($controller, $action){
   		parent::__construct($controller, $action);
   		$this->headers = Lib::getAllHeaders();

        $V = "\\App\\API\\" . $this->headers['VERSION'] . "\\Model\\NotifyMessage";



        $this->m = new $V;
    }
	   public function push(){


           $pageArr['pageNum'] = (Lib::post('pageNum')) ? Lib::post('pageNum') : 1;

           $pageArr['numPerPage'] = (Lib::post('numPerPage')) ? Lib::post('numPerPage') : 5;

   	        $uid = $this->headers['UID'];

   	        if(!is_numeric($uid) || empty($uid))
   	            Lib::outputJson([
                    'status' =>'fail',
                    'code'   =>1000,
                    'msg'    =>'参数错误',

                ]);
        $condition = null;
        $condition['AND']['A.uid'] = $uid;
        $condition['ORDER'] = ["A.id" => "DESC"];
		$datalist = $this->m->getList($pageArr,$condition);
        $restAllRead = $this->m->isAllRead($uid);
        if(empty($restAllRead)){
            $restAllRead=0;//全部已读
        }else{
            $restAllRead=1;
        }
		if($datalist){

                foreach ($datalist['list'] as $k => $v){

                    $datalist['list'][$k]['create_time'] = Lib::uDate('Y-m-d H:i:s', $v['create_time']);
                }
                $datalist['ifAllRead']=$restAllRead;
                $data =[
                    'status' =>'success',
                    'code'   =>10000,
                    'msg'    =>'获取成功',
                    'data'   =>$datalist
                        ];

                 Lib::outputJson($data);
        }else{
            $datalist=[];
            $datalist['ifAllRead']=$restAllRead;
                $data =[
                        'status' =>'success',
                        'code'   =>1000,
                        'msg'    =>'获取失败',
                        'data'   => $datalist
                        ];
		}

		      Lib::outputJson($data);
	}
        public function getshow(){

   	     $id = Lib::post('id');

            $datalist = $this->m->show($id);

            if($datalist){

                $data =[
                        'status' =>'success',
                        'code'   =>10000,
                        'msg'    =>'获取成功',
                        'data'   =>$datalist
                        ];
                Lib::outputJson($data);
            }else{

                $data =[
                        'status' =>'success',
                        'code'   =>10011,
                        'msg'    =>'获取成功',
                        'data'   => []
                        ];

                Lib::outputJson($data);
            }
        }




    //获取系统消息详情
    public function getInfo(){      
        $id = Lib::post('id');
        $xq = $this->m->show($id);
        if(empty($xq)){
            $data =[
                'status' => 'fail',
                'code' => 10000,
                'msg' => '获取失败',
                'data' => ''
            ];
            Lib::outputJson($data);
        }
        //$xq['last_update_time'] = Lib::uDate('Y-m-d',$xq['last_update_time']);
        //$xq['create_time'] = Lib::uDate('Y-m-d',$xq['create_time']);
        //$xq['pic'] = OSS_ENDDOMAIN.'/'.$xq['pic'];
            //$v['total']=$rss;
        $data =[
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'data' => $xq
        ];
        Lib::outputJson($data);
    }
    public function getread(){

        $uid = $this->headers['UID'];
        $bool = $this->m->read($uid);

        if($bool){

            $data =[
                'status' =>'success',
                'code'   =>10000,
                'msg'    =>'获取成功'
            ];
            Lib::outputJson($data);
        }else{

            $data =[
                'status' =>'error',
                'code'   =>1000,
                'msg'    =>'参数错误',
            ];

            Lib::outputJson($data);
        }
    }



}



