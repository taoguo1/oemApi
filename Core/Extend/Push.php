<?php
namespace Core\Extend;

use Core\Lib;

class Push
{

    public static function request($url="",$param="",$header="")
    {
        if (empty($url)||empty($param))
        {
            return false;
        }
        $postUrl = $url;
        $curlPost = $param;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
        // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }
    public static function send($title,$message,$audience,$platform="all",$extras="")
    {
        $sendno=1;
        $url='https://api.jpush.cn/v3/push';
        $conf = Lib::loadFile('Config/JpushConfig.php');
        $appKey=$conf['appKey'];
        $masterSecret = $conf['masterSecret'];
        $base64=base64_encode("$appKey:$masterSecret");
        $header=array("Authorization:Basic $base64","Content-Type:application/json");
        if($platform=="ios")
        {//ios的消息是可以透传的，所以只要传输一次就可以了，不需要二次message
            $param='{"platform":["ios"],"audience":'.$audience.',"notification":{"ios":{"alert":"'.$message.'","sound":"happy","badge":"+1","extras":'.$extras.'}},"options":{"time_to_live":1,"sendno":'.$sendno.',"apns_production":'.APNS_PRODUCTION.'}}';
        }
        else if($platform=="android")
        {
            //android的消息没有办法透传，所以必须传送两次
            //$param='{"platform":["android"],"audience":'.$audience.',"notification":{"android":{"alert":"'.$message.'","title":"", "builder_id":3,"badge":"+1","extras":'.$extras.'}},"message":{"msg_content":"'.$message.'","title":"'.$title.'","extras":'.$extras.'},"options":{"time_to_live":1,"sendno":'.$sendno.',"apns_production":'.APNS_PRODUCTION.'}}';
            $param='{"platform":["android"],
                    "audience":'.$audience.',
                    "notification":{
                        "android":{
                            "alert":"'.$message.'",
                            "title":"",
                            "builder_id":3,
                            "extras":'.$extras.'
                        }
                    },
                    "message":{
                        "msg_content":"'.$message.'",
                        "title":"'.$title.'",
                        "extras":'.$extras.'
                    },
                    "options":{
                        "time_to_live":1,
                        "sendno":'.$sendno.',
                        "apns_production":'.APNS_PRODUCTION.'
                    }
                }';
        }
        else if($platform=="all")
        {//如果是全平台推送的话,进行全部推送，这个时候如果是安卓手机的话就不能进行message透传了，也就是说，应用打开的时候是收不到message参数的
            $param='{"platform":"all","audience":'.$audience.',"notification":{"ios":{"alert":"'.$message.'","sound":"happy","badge":"+1","extras":'.$extras.'},"android":{"alert":"'.$message.'","title":"", "builder_id":3,"extras":'.$extras.'}},"options":{"time_to_live":1,"sendno":'.$sendno.',"apns_production":'.$conf['apnsProduction'].'}}';
        }
        $res=self::request($url,$param,$header);
        $res_arr=json_decode($res, true);
        return $res_arr;
    }
    
    
    public static function jpush($deviceId,$platform,$content,$pars)
    {
        if(!empty($content))
        {
            $audience = "\"all\"";
            if(!empty($deviceId))
            {
                $alias_arr = explode(",",$deviceId);
                $json_alias=json_encode($alias_arr);
                $audience='{"alias":'.$json_alias.'}';
            }
            //$pars = [];
            
            $extrasArr= $pars;//这个是我们额外推送的内容，也是做成数组的形式，那么你在jpush.js中接收了extra就可以直接解析出来了
            $extras=json_encode($extrasArr);
            $platform=$platform;//推送平台可以是ios,android,all，一般我们会分开推送两次。
            $back_arr=self::send('',$content,$audience,$platform,$extras);//推送
            return $back_arr;
        }
        else
        {
            return "-1";
        }
    }
}