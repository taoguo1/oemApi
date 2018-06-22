<?php
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
class Carousel extends Controller{

    public function getCarouselConfig(){
        $where['AND']['ad_type']=1;
        $where['ORDER']['sort']='DESC';
        $where['LIMIT']=5;
        $res=DBQ::getAll('carousel','*',$where);
        $where['AND']['ad_type']=2;
        $res1=DBQ::getAll('carousel','*',$where);
        $data = [];
        $data['ad_type1'] = $res;
        $data['ad_type2'] = $res1;
        foreach($data['ad_type1'] as $k=>$v){
            if($v['herf']==''||$v['herf']==null){
                $data['ad_type1'][$k]['dis']=0;
            }else{
                $data['ad_type1'][$k]['dis']=1;
            }
        }
        foreach($data['ad_type2'] as $k1=>$v1){
            if($v1['herf']==''||$v1['herf']==null){
                $data['ad_type2'][$k1]['dis']=0;
            }else{
                $data['ad_type2'][$k1]['dis']=1;
            }
        }
        if($res && $res1){
            $data=[
                'status' => 'success',
                'code' => 10000,
                'msg' => '获取成功',
                'data' => $data
            ];
        }else{
            $data=[
                'status' => 'fail',
                'code' => 1000,
                'msg' => '获取失败'
            ];
        }

        Lib::outputJson($data);
    }
}