<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/2/21
 * Time: 12:01
 */
namespace App\API\V100\Controller;
use Core\Base\Controller;
use Core\Lib;
use Core\DB\DBQ;
class DebitCard extends Controller
{
    /**
     * @获取个人储蓄卡列表
     */
    public function getCard()
    {
        $getAllHeaders = Lib::getAllHeaders();
        $condition['AND']['user_id'] = $getAllHeaders['UID'];
        $condition['AND']['status'] = 1;
        $condition['AND']['user_type'] = 1;
        $condition['ORDER'] = [
            'is_default' => 'DESC'
        ];
        $data=$this->M()->getList($condition);
       if($data){
           Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '','data'=>$data));
       }else{
           Lib::outputJson(array('status'=>'success','code'=>1000,'msg'=>'','data'=>null));
       }

    }
    /**
     * @绑定储蓄卡
     */
    public function postCard()
    {
        $data['sdkappid'] = '';
        $data['appid'] = '';
        $getAllHeaders = !empty(Lib::getAllHeaders()) ? Lib::getAllHeaders() : '';
        $data['user_id'] = !empty($getAllHeaders['UID']) ? $getAllHeaders['UID'] : '';
        $data['user_type'] = !empty($getAllHeaders['UTYPE']) ? $getAllHeaders['UTYPE'] : '';
        $data['bank_id'] = !empty(Lib::post('bank_id')) ? Lib::post('bank_id') : '';
        $bank=lib::getOneBankConfig($data['bank_id']);
        $data['bank_name'] = !empty($bank['name']) ? $bank['name'] : '';
        $data['real_name'] = !empty(Lib::post('real_name')) ? Lib::post('real_name') : '';
        $data['mobile'] = !empty(Lib::post('mobile')) ? Lib::post('mobile') : '';
        $data['card_no']=!empty(Lib::post('card_no')) ? Lib::post('card_no') : '';
        $data['id_card'] = !empty(Lib::post('id_card')) ? Lib::post('id_card') : '';
        $data['code'] = !empty(Lib::post('code')) ? Lib::post('code') : '';
        if (!empty(Lib::post('appid'))) {
            $data['sdkappid'] = Lib::post('appid');
            $data['appid'] = Lib::post('appid');
        }
        $this->M()->add($data);
    }
    /**
     * @设置储蓄卡默认结算账户
     */
    public function putCard()
    {
        $getAllHeaders = Lib::getAllHeaders();
        $where['user_id'] = $getAllHeaders['UID'];

        $where['id'] = Lib::post('id');
        if(empty($where['id']) || !ctype_digit($where['id'])){
            Lib::outputJson(array('status' => 'fail', 'code' =>1000, 'msg' => 'id参数错误'));
        }
        $defaultData = [
            'is_default'=>  '1'
        ];
        $result = $this->M()->edit($defaultData, $where);
        if ($result){
            Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '设置成功'));
        } else {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '设置失败'));
        }
    }
    /**
     * @解绑储蓄卡
     */
    public function deleteCard()
    {
        $getAllHeaders = Lib::getAllHeaders();
        $where['user_id'] = $getAllHeaders['UID'];

        $where['id'] = Lib::post('id');
        $where['user_type'] = 1;
        if(empty($where['id']) || !ctype_digit($where['id'])){
            Lib::outputJson(array('status' => 'fail', 'code' =>1000, 'msg' => '删除参数错误'));
        }
        $this->M()->deletions($where);
    }
    public function deleteCardBAK()
    {
        $getAllHeaders = Lib::getAllHeaders();
        $where['user_id'] = $getAllHeaders['UID'];
        
        $where['id'] = Lib::post('id');
        $where['user_type'] = 1;
        if(empty($where['id']) || !ctype_digit($where['id'])){
            Lib::outputJson(array('status' => 'fail', 'code' =>1000, 'msg' => '删除参数错误'));
        }
        $result = $this->M()->deletions($where);
        if ($result){
            Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '删除成功'));
        } else {
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '删除失败'));
        }
    }
    /**
     * 储蓄卡识别接口
     */
    public function imageRecognition()
    {
        $cardnumber = Lib::post('card_no');
        if(empty($cardnumber)){
            $data=$this->M()->cardUpload($_FILES);
        }else{
            $data=$this->M()->cardNumber($cardnumber);
        }
       if($data['result']['iscreditcard']!=1){
           Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '请选择正确的储蓄卡'));
       }
       Lib::outputJson($data);
    }




}