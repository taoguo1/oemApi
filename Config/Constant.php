<?php
\define('APP_DEBUG', true);
\define('DEFAULT_CONTROLLER', 'index'); // www访问入口
\define('DEFAULT_ACTION', 'index'); // www访问入口
\define('APP_WWW', 'www'); // www访问入口
\define('APP_ADMIN', 'admin'); // 后台访问入口
\define('APP_API', 'api'); // api访问入口1
\define('APP_WX', 'wx'); // wx访问入口
\define('APP_EXCHANGE', 'exchange'); // 回调访问入口
\define('APP_CALL', 'call'); // 回调访问入口


\define('APP_SITE_PATH', '/'); // 站点根目录
//\define('OSS_ENDDOMAIN', 'https://dzz-ydjx.oss-cn-beijing.aliyuncs.com'); // oss路径
//\define('EX_SERVICE', 'https://exchange.dizaozhe.cn/'); // EXChange  https://exchange-dev.dizaozhe.cn/
//\define('SIGN_CODE', '159AF20FE2BF8B8EB72A02F016A12C1010ED5A65'); //签名code
\define('APP_ADMIN_STATIC', APP_SITE_PATH . 'Static/Admin/'); // 站点根目录
\define('APP_ADMIN_PUBLIC_CONTROLLER', 'Upload|Login|Index|ChangePwd');
\define('APP_ADMIN_EXPIRATION', 30);//后台20分钟非活动时间则过期
\define('ZF_DIFF_PATH', 'https://oem.dizaozhe.cn/call/');//支付回调路径
\define('OEM_CTRL_URL', 'https://manage-dev.dizaozhe.cn/');//OEM主控制台url
\define('MAINURL', 'https://manage-dev.dizaozhe.cn/api/SynBill/');//OEM主控制台url
\define('OEM_CTRL_URL_VERSION', 'V100');//OEM主控制台url 版本

//\define('ZF_DIFF_PATH', 'https://oem.dizaozhe.cn/call/');//支付回调路径


//\define('OEM_CTRL_URL_VERSION', 'V100');//OEM主控制台url 版本https://oem.dizaozhe.cn/call/PaySuccess/frame?appid=1feb30526e31e188
\define('FRAME_OPEN_URL', 'https://oem.dizaozhe.cn/call/PaySuccess/frame?appid=');//OEM主控制台url 版本1feb30526e31e188
\define('LOGS_PATH', 'Logs/');//存储日志的路径


\define('TX_DISABLED',['2018-05-01','2018-04-30']);//禁用套现日期




