<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="maximum-scale=1.0,minimum-scale=1.0,user-scalable=0,width=device-width,initial-scale=1.0"/>
    <link rel="stylesheet" type="text/css" href="/Static/down/css/init.css"/>
    <style>
        html,body{  height:100%; margin:0; padding:0}
        body{overflow:hidden;}
        .installBtn{width:90%;border-radius:10px;  margin:0 auto; background:#ff1854;text-align:center; color:#fff; font-size:18px; font-weight:bold; line-height:60px; display:block; text-decoration:none}

        .box{ text-align:center; margin:0 auto; width:100%;overflow:hidden;}
            header{
                background: #00b21f;
            }
            .installBtn{
                background: #00b21f;
            }
    </style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></head>
<body class="bg-wh">
<div style="width: 100%; margin: 0 auto; position: relative;" id="box_all">

<header class="ui-header-auto">
    <div class="h-45 ">

        <div class=" ui-txt-center umh45">
            <h1><?php echo $ret['app_name'];?>-管家APP下载 </h1>
        </div>
    </div>
</header>
<!-- kawangka -->
<section class=" pad-a-0 mar-a-0 bg-wh" id="main-content">
    <div class="ui-txt-center" id="ewm" style="width: 100%"><img src="/Static/down/tmp/down_<?php echo $appid;?>.png"></div>
    <div class="box">
        <div style="height: 40px"></div>
        <div  class="installBtn" ontouchstart="$(this).addClass('opa-5')" ontouchend="$(this).removeClass('opa-5')" onClick="download_url('ios')">
            <img src="/Static/down/image/ios.png" width="35" class="img-mid" style=" margin-top: -10px;">
            苹果版下载
        </div>
        <div  class="installBtn" ontouchstart="$(this).addClass('opa-5')" ontouchend="$(this).removeClass('opa-5')" style="margin-top:20px" onClick="download_url('andriod')">
            <img src="/Static/down/image/android.png" width="35" class="img-mid" style=" margin-top: -10px;">
            安卓版下载</div>
    </div>
</section>
</div>
</body>
<script src="/Static/down/script/api.js"></script>
<script src="/Static/down/script/zepto.min.js"></script>
<script src="/Static/down/script/zv.js"></script>
<script src="/Static/down/script/apiExt.js"></script>

<script type="text/javascript">
    $(function(){

        var h = $("body").height();
        var h1 = h - 45 - 60 - 120 - 40;
        var h2 = h1/1.6;
        $("#ewm img").css("width",h2+"px");
        $("#ewm img").css("margin-top",(h1-h2)/2+"px");
        main_show();
    });
    function System_judge()
    {
        var u = navigator.userAgent;
        if (u.indexOf('Android') > -1 || u.indexOf('Linux') > -1) {//安卓手机
            return "/Static/down/image/live_weixin_a.png";
        } else if (u.indexOf('iPhone') > -1) {//ios
            return "/Static/down/image/live_weixin_i.png";
        } else if (u.indexOf('Windows Phone') > -1) {//winphone手机
            return "/Static/down/image/live_weixin_a.png";
        }
        else
        {
            return "/Static/down/img/iconfont/live_weixin_a.png";
        }
    }
    function download_url(type){//下载
        var isWeixin = is_weixin();
        if(isWeixin){
            $("#weixin-tip").show();
        }
        else
        {
            if(type=='andriod')
            {
                window.location.href="<?php echo $ret['android_down_url'];?>";
                 //alert("即将上线敬请期待");
            }
            else
            {
                window.location.href="<?php echo $ret['ios_down_url'];?>";
                //alert("即将上线敬请期待");
            }
        }
    }
    function is_weixin() {
        var ua = navigator.userAgent.toLowerCase();
        if (ua.match(/MicroMessenger/i) == "micromessenger"){
            return true;
        } else {
            return false;
        }
    }
    
    var isWeixin = is_weixin();
    var winHeight = typeof window.innerHeight != 'undefined' ? window.innerHeight : document.documentElement.clientHeight;
    console.log(winHeight);
    function loadHtml(){
        var div = document.createElement('div');
        div.id = 'weixin-tip';
        var img_width=document.body.offsetWidth;
        div.innerHTML = '<div style="height: 100%" onclick=$("#weixin-tip").hide()><p><img src="'+System_judge()+'" style="width:'+img_width+'px"/></p></div>';
        document.body.appendChild(div);
    }
    function loadStyleText(cssText) {
        var style = document.createElement('style');
        style.rel = 'stylesheet';
        style.type = 'text/css';
        try {
            style.appendChild(document.createTextNode(cssText));
        } catch (e) {
            style.styleSheet.cssText = cssText; //ie9以下
        }
        var head=document.getElementsByTagName("head")[0]; //head标签之间加上style样式
        head.appendChild(style);
    }
    var cssText = "#weixin-tip{position: fixed; left:0; top:0; background: rgba(0,0,0,0.6); filter:alpha(opacity=60); width: 100%; height:100%; z-index: 99999999999;} #weixin-tip p{text-align: center; margin-top: 10%; padding:0 5%;}";
    if(isWeixin){
        loadHtml();
        loadStyleText(cssText);
    }
</script>
</html>