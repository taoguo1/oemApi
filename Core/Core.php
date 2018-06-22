<?php
namespace Core;

use Core\Base\Model;
use Core\Lib;
// 框架根目录
defined('CORE_PATH') or define('CORE_PATH', __DIR__);

/**
 * 框架核心
 */
class Core
{

    // 配置内容
    protected $config = [];

    protected $appid;

    public function __construct()
    {

    }

    // 运行程序
    public function run()
    {
        spl_autoload_register(array(
            $this,
            'loadClass'
        ));
        $this->setReporting();
        $this->removeMagicQuotes();
        $this->unregisterGlobals();
        $this->setDbConfig();

        // $this->route();
        new \Core\Base\Route();
    }

    // 检测开发环境
    public function setReporting()
    {
        if (APP_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
        }
    }

    // 删除敏感字符
    public function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array(
            $this,
            'stripSlashesDeep'
        ), $value) : stripslashes($value);
        return $value;
    }

    // 检测敏感字符并删除
    public function removeMagicQuotes()
    {
        if (get_magic_quotes_gpc()) {
            $_GET = isset($_GET) ? $this->stripSlashesDeep($_GET) : '';
            $_POST = isset($_POST) ? $this->stripSlashesDeep($_POST) : '';
            $_COOKIE = isset($_COOKIE) ? $this->stripSlashesDeep($_COOKIE) : '';
            $_SESSION = isset($_SESSION) ? $this->stripSlashesDeep($_SESSION) : '';
        }
    }

    // 检测自定义全局变量并移除。因为 register_globals 已经弃用，如果
    // 已经弃用的 register_globals 指令被设置为 on，那么局部变量也将
    // 在脚本的全局作用域中可用。 例如， $_POST['foo'] 也将以 $foo 的
    // 形式存在，这样写是不好的实现，会影响代码中的其他变量。 相关信息，
    public function unregisterGlobals()
    {
        if (ini_get('register_globals')) {
            $array = array(
                '_SESSION',
                '_POST',
                '_GET',
                '_COOKIE',
                '_REQUEST',
                '_SERVER',
                '_ENV',
                '_FILES'
            );
            foreach ($array as $value) {
                foreach ($GLOBALS[$value] as $key => $var) {
                    if ($var === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }

    public function setDbConfig()
    {
        $this->appid = Lib::request('appid');
        if(!$this->appid){
            exit("参数错误");
        }
        $postData = [
            'appid' => $this->appid,
            'version'=>OEM_CTRL_URL_VERSION
        ];
        $ret = Lib::httpPostUrlEncode(OEM_CTRL_URL.'api/getConfig', $postData);
        $ret = json_decode($ret,true);

        if($ret['status']=='fail')

        {
            exit($ret['msg']);
        }
        else
        {
            if($ret['status']==-1){
                exit("该账户异常");
            }
            //print_r($ret);
            \define('OSS_ENDDOMAIN', $ret['oss_enddomain']);                // oss路径
            \define('EX_SERVICE', $ret['ex_service']);                      // EXChange
            \define('SIGN_CODE', $ret['sign_code']);                        //签名code

            //手续费相关
            \define('REPAYMENT_POUNDAGE', $ret['repayment_poundage']);      //还款手续费
            \define('DEPOSIT_POUNDAGE', $ret['deposit_poundage']);          //充值手续费
            \define('WITHDRAW_POUNDAGE', $ret['withdraw_poundage']);        //提现手续费，每笔2元
            \define('VALIDATECARD_POUNDAGE', $ret['validatecard_poundage']);//验卡手续费，每笔2元
            \define('TX_IN',$ret['tx_in']);                                 //套现交易费率
            \define('TX_OUT',$ret['tx_out']);                               //套现出款费率
            \define('TX_AGENT_RATE',$ret['tx_agent_rate']);                 //套现代理分润 0.03%
            \define('MAX_WITHDRAW_DAY',$ret['max_withdraw_day']);           //单日提现限额
            //\define('YB_IN',$ret['sign_code']);                           //成本易宝代付
            //\define('YB_RESUME',$ret['sign_code']);                       //成本易宝消费
            \define('BONUS_DAY_MAX',$ret['bonus_day_max']);                 //红包每日最高
            //还款计划相关
            \define('DEPOSIT_CO',$ret['deposit_co']);                       //保证金倍数，还款金额/天数 * 1.3
            \define('MAX_R_AMOUNT',$ret['max_r_amount']);                   //最大还款金额不能超过10万
            \define('MIN_R_AMOUNT',$ret['min_r_amount']);                   //最小还款金额不能小于1000元
            \define('MAX_R_SIN_AMOUNT',$ret['max_r_sin_amount']);           //单笔最大还款金额不能超过2万
            \define('SetNotifyUrl',$ret['setnotifyurl']);
            \define('REPAYMENT_RATE',$ret['repayment_rate']);               //还款分润
            \define('CONSUME_RATE',$ret['xf_service_charge']);              //消费手续费
            if(isset($ret['sfvalue'])){
                \define('SFVALUE',$ret['sfvalue']);              //身份鉴权手续费
            }else{
                \define('SFVALUE',2);
            }
            if(isset($ret['txlnvalue'])){
                \define('TXLNVALUE',$ret['txlnvalue']);              //套现入款手续费
            }else{
                \define('TXLNVALUE',65);
            }
            if(isset($ret['txoutvalue'])){
                \define('TXOUTVALUE',$ret['txoutvalue']);              //套现出款手续费
            }else{
                \define('TXOUTVALUE',2);
            }



            //\define('MAINURL',$ret['sign_code']);
            //易宝
            //\define('YB_DS',$ret['sign_code']);
            //\define('YB_DF',$ret['sign_code']);
            //\define('YB_DFCX',$ret['sign_code']);
            //支付参数
            \define('ZF_URL',$ret['payment_url']);                            //支付路径
            \define('ZF_VERSION',$ret['version_number']);                     //支付版本号
            \define('ZF_SIGN',$ret['merchant_key']);                          //商户提交秘钥
            \define('ZF_SIGN_IN',$ret['deposit_key']);                                          //入款秘钥
            \define('ZF_SIGN_OUT',$ret['a_deposit_key']);                                         //出款秘钥
            \define('MERCHANT_ID',$ret['merchant_id']);                       //大商户号
            //redis
            \define('REDIS',json_decode($ret['redis_config'],true));
            \define('ABROAD_CONSUME_RATE',$ret['jwpay_earnings']);//境外消费费率
            $is_show = 1;                       
            //4.20 是否显示                      
            if (isset($ret['is_show']))
            {
                $is_show = $ret['is_show'];
            }

            \define('IS_SHOW',$is_show);

            $config = [
                'db' => [
                    'host' => $ret['db_ip'],//$ret['db_ip']
                    'databaseType' => 'mysql',
                    'charSet' => 'utf8',
                    'debugMode' => false,
                    'port' => $ret['db_port'],//$ret['db_port']
                    'prefix' =>$ret['db_prefix'],//$ret['db_prefix']
                    'userName' => $ret['db_user'],//$ret['db_user']
                    'password' => $ret['db_password'],//$ret['db_password']
                    'dbName' => $ret['db_name']//$ret['db_name']
                ]
            ];
        }
        if($config)
        {
            Model::setDbConfig($config['db']);
        }
    }

    // 自动加载类
    public function loadClass($className)
    {
        $classMap = $this->classMap();
        if (isset($classMap[$className])) {
            // 包含内核文件
            $file = $classMap[$className];
        } elseif (strpos($className, '\\') !== false) {
            // 包含应用（App目录）文件
            $file = APP_PATH . str_replace('\\', '/', $className) . '.php';
            if (! is_file($file)) {
                return;
            }
        } else {
            return;
        }
        include $file;
        // 这里可以加入判断，如果名为$className的类、接口或者性状不存在，则在调试模式下抛出错误
    }

    // 内核文件命名空间映射关系
    protected function classMap()
    {
        return [
            'Core\Base\Controller' => CORE_PATH . '/Base/Controller.php',
            'Core\Base\Model' => CORE_PATH . '/Base/Model.php',
            'Core\Base\View' => CORE_PATH . '/Base/View.php',
            'Core\DB\DB' => CORE_PATH . '/DB/DB.php',
            'Core\DB\DBQ' => CORE_PATH . '/DB/DBQ.php'
        ];
    }
}