<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 2018/3/16
 * Time: 14:59
 */
namespace App\WWW\Controller;
use Core\Base\Controller;
class Qr extends Controller
{
    /**
     *
     * @name 生成二维码
     */
    public function createQr($sign)
    {
        $QRcode = new \QRcode();
        $sign .=$sign.md5(time());
        $QRcode->png($sign,false,'L',8);
    }
}