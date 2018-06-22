<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 2018/1/13
 * Time: 10:14
 */

namespace Core\Extend;


class Qrcode
{
    public function __construct()
    {
        include_once 'phpqrcode.php';
    }

    public function createcode($url){
        //二维码内容
        $value = $url;
        //容错级别
        $errorCorrectionLevel = 'H';
        //生成图片大小
        $matrixPointSize = 6;
        //生成二维码图片
        $filename = 'Uploads/qrcode/'.microtime().'.png';
        \QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
        return $filename;
    }

    public function createcodelogo($url,$logo = ''){
        //二维码内容
        $value = $url;
        //容错级别
        $errorCorrectionLevel = 'H';
        //生成图片大小
        $matrixPointSize = 6;
        //生成二维码图片
        $filename = 'Uploads/qrcode/'.microtime().'.png';
        \QRcode::png($value,$filename , $errorCorrectionLevel, $matrixPointSize, 2);
        //准备好的logo图片
        $logo = 'Uploads/qrcode/logo.jpg';
        //已经生成的原始二维码图
        $QR = $filename;
        if (file_exists($logo)) {
            //目标图象连接资源。
            $QR = imagecreatefromstring(file_get_contents($QR));
            //源图象连接资源。
            $logo = imagecreatefromstring(file_get_contents($logo));
            //二维码图片宽度
            $QR_width = imagesx($QR);
            //二维码图片高度
            $QR_height = imagesy($QR);
            //logo图片宽度
            $logo_width = imagesx($logo);
            //logo图片高度
            $logo_height = imagesy($logo);
            //组合之后logo的宽度(占二维码的1/5)
            $logo_qr_width = $QR_width / 4;
            //logo的宽度缩放比(本身宽度/组合后的宽度)
            $scale = $logo_width/$logo_qr_width;
            //组合之后logo的高度
            $logo_qr_height = $logo_height/$scale;
            //组合之后logo左上角所在坐标点
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            /*
             *  imagecopyresampled() 将一幅图像(源图象)中的一块正方形区域拷贝到另一个图像中
             */
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,$logo_qr_height, $logo_width, $logo_height);
        }
        //输出图片
        $outfilename = "Uploads/qrcode/qrcode_".microtime().".png";
        imagepng($QR, $outfilename);
        imagedestroy($QR);
        imagedestroy($logo);
        return $outfilename;
    }

}