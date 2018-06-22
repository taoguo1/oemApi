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
class AgentCard extends Controller
{
    public $m;
    public $headers;
    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
        $this->headers = Lib::getAllHeaders();

    }
    /**
     * @获取代理银行卡列表
     */
    public function getCard()
    {
        $condition['AND']['C.user_id'] = $this->headers['UID'];
        $condition['AND']['C.status']= 1;
        $condition['AND']['C.user_type'] = 2;
        $condition['ORDER'] = [
            'C.is_default' => 'DESC'
        ];
        $data = $this->M()->getList($condition);
       if($data){
           Lib::outputJson(array('status' => 'success', 'code' => 1000, 'msg' => '','data'=>$data));
       }else{
           Lib::outputJson(array('status'=>'success','code'=>1000,'msg'=>'','data'=>null));
       }


    }

    /**
     * @绑定代理银行卡
     */
    public function postCard()
    {

        $data=[];
        $getAllHeaders = $this->headers['UID'];
        $uid = null;
        if (!empty($getAllHeaders)) {
            $data['agent_id'] = $getAllHeaders;
        }
        $utype = !empty($getAllHeaders['UTYPE']) ? $getAllHeaders['UTYPE'] : 1;
        $data['appid']   = !empty(Lib::request('appid')) ? (Lib::request('appid')) : '';
        $data['bank_id'] = !empty(Lib::post('bank_id')) ? (Lib::post('bank_id')) : '';
        $data['user_type'] = 2;

        $bank=lib::getOneBankConfig($data['bank_id']);
        $data['bank_name'] = Lib::post('bank_name');

        $data['mobile'] = !empty(Lib::post('mobile')) ? (Lib::post('mobile')) : '';
        $data['real_name'] = !empty(Lib::post('real_name')) ? (Lib::post('real_name')) : '';
        $data['cvn']=!empty(Lib::post('cvn')) ? (Lib::post('cvn')) : '';
        $data['card_no']=!empty(Lib::post('card_no')) ? (Lib::post('card_no')) : '';
        $data['id_card'] = !empty(Lib::post('id_card')) ? (Lib::post('id_card')) : '';
        $data['expiry_date']=!empty(Lib::post('expiry_date')) ? (Lib::post('expiry_date')) : '';

        $data['smsCode']=!empty(Lib::post('smsCode')) ? (Lib::post('smsCode')) : '';
        $data['bankCode']=!empty(Lib::post('bankCode')) ? (Lib::post('bankCode')) : '';
        $data['orderId']=!empty(Lib::post('orderId')) ? (Lib::post('orderId')) : '';
        $data['smSign'] = !empty(Lib::post('smSign')) ? (Lib::post('smSign')) : '';
        $data['sysId'] = !empty(Lib::post('sysId')) ? (Lib::post('sysId')) : '';

        //Lib::outputJson($data);die;
        //王田军添加判断月份类型本月|下月
        /*        if($data['bill_day'] < $data['repayment_day']){
                    //本月
                    $data['repayment_month'] = 1;
                }elseif($data['bill_day'] > $data['repayment_day']){
                    //下月
                    $data['repayment_month'] = 2;
                }else{
                    Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '账单日和还款日不能同一天'));
                }*/
        $this->M()->add($data);
    }

    /**
     * @设置代理信用卡默认结算账户
     */
    public function putCard()
    {
        $where['user_id'] = $this->headers['UID'];
        $where['id'] = Lib::post('id');
        $where['user_type'] = 2;
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
     * @代理解绑信用卡
     */
    public function deleteCard()
    {
        $where['user_id'] = $this->headers['UID'];
        $where['id'] = Lib::post('id');
        $where['user_type'] = 2;
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
     * 代理信用卡识别接口
     */
    public function imageRecognition()
    {
        $cardnumber = Lib::post('card_no');
        if(empty($cardnumber)){
            $data=$this->M()->cardUpload($_FILES);
        }else{
            $data=$this->M()->cardNumber($cardnumber);
        }
        if($data['result']['iscreditcard']!=2){
            Lib::outputJson(array('status' => 'fail', 'code' => 1000, 'msg' => '请选择正确的信用卡'));
        }
        Lib::outputJson($data);
    }
    /**
     * 代理信用卡鉴权消费
     */
    public function SdkPayAuthPay()
    {

        $data['UID']   = $this->headers['UID'];
        $data['card_no']       = Lib::post('card_no');
        $data['pay_password']  = Lib::post('pay_password');
        $data['appid']    = Lib::request('appid');

        $data=$this->M()->SdkPayAuthPay($data);

        Lib::outputJson($data);
    }

}