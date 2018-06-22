<?php
namespace App\ADMIN\Controller;
use Core\Base\Controller;
use Core\Lib;
class Index extends Controller {

	private $summaryName = array(
		'billAmount' 	=> '消费金额',
    	'billNum' 		=> '消费笔数',
   	 	'userBalance' 	=> '会员余额',
    	'userNum' 		=> '会员总数',
    	'agentBalance' 	=> '代理余额',
    	'agentNum' 		=> '代理总数',
    	'creditCard' 	=> '信用卡',
    	'debitCard' 	=> '储蓄卡',
    	'plan' 			=> '还款计划',
    	'planList' 		=> '还款任务',
        'takePay'       => '代付金额',
        'resumeAmount'  => '消费任务'

	);
	private $summaryColor = array("","#49c064","#F8A227","#9564E2","#F34642","#14b8d4","","#49c064","#F8A227","#9564E2","#F34642","#14b8d4");
	/**
	 *
	 * @name 系统主页
	 */
	public function index() {
	    $model = new \App\ADMIN\Model\Index();
		//统计
		$listTreeArray = $model->getTreeList ();

		//汇总看板
		$summaryData = $model->summaryPlate();

		//走势看板
		$creditCardData = $model->getCreditCard();
		$this->assign('creditCardData',$creditCardData);

		$debitCardData = $model->getDebitCard();
		$this->assign('debitCardData',$debitCardData);

		$billMountData = $model->getBillMount();
		$this->assign('billMountData',$billMountData);

		$repayDayData = $model->getRepayDay();
		$this->assign('repayDayData',$repayDayData);

		//查询推送文章
		$appid=Lib::request('appid');
        $postData = [
            'version' => OEM_CTRL_URL_VERSION,
            'appid'=>$appid
        ];
        $ret = Lib::httpPostUrlEncode(OEM_CTRL_URL . 'api/getConfig/content', $postData);
        $ret = json_decode($ret, true);
        //根据文章id查询文章
      
        $this->assign('data',$ret);
   
	    //print_r($listTreeArray);
		$listTreeHtml = $listTreeArray ['left'];
		$dataTree= $listTreeArray ['data'];
		$this->assign ( 'listHeaderNav', $model->getHeaderNavList () );
		$this->assign ( 'systemConfig', $model->getLoginInfo () );
		$this->assign ( 'listTreeHtml', $listTreeHtml );
		$this->assign ( 'dataTree', $dataTree);
		$this->assign ( 'summaryData', $summaryData);
        $this->assign ( 'summaryColor', $this->summaryColor);

		$this->assign ( 'summaryName', $this->summaryName);
		$this->view ();
	}




	//获得系统年份数组
	/**
	 *
	 * @return string[]
	 */
	function getSystemYearArr(){
		$year_arr = array('2010'=>'2010','2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014','2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019','2020'=>'2020');
		return $year_arr;
	}

	/**
	 * 获得系统月份数组
	 *
	 * @return array
	 */
	function getSystemMonthArr(){

		$month_arr = array('1'=>'01','2'=>'02','3'=>'03','4'=>'04','5'=>'05','6'=>'06','7'=>'07','8'=>'08','9'=>'09','10'=>'10','11'=>'11','12'=>'12');
		return $month_arr;
	}

	public function getContentDetails($id=null){
  	  //$id=Lib::request('id');
  	  $postData = [
            'version' => OEM_CTRL_URL_VERSION,
            'id'=>$id
        ];
        $ret = Lib::httpPostUrlEncode(OEM_CTRL_URL . 'api/getConfig/contentDetail', $postData);
        $ret = json_decode($ret, true);  
        $this->assign('data',$ret);
        $this->view ();
  }
}