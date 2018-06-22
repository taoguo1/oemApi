var IP = "http://app.gangushop.com/"
var serverURL = IP + "api/api.php";
//服务器地址
//用户地址
var serverIMG = "http://gangushop.oss-cn-shanghai.aliyuncs.com/";
var ossServiceUrl = "http://gangushop.oss-cn-shanghai.aliyuncs.com/";
var serverFace = IP;
var serverIP = IP;
//图片管理开始
var imgStyle90x90 = "?x-oss-process=image/resize,m_fill,w_90,h_90,limit_0/auto-orient,0/quality,q_100";
var imgStyle100x100 = "?x-oss-process=image/resize,m_fill,w_100,h_100,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle110x110 = "?x-oss-process=image/resize,m_fill,w_110,h_110,limit_0/auto-orient,0/quality,q_100";
var imgStyle150x150 = "?x-oss-process=image/resize,m_fill,w_150,h_150,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle180x180_90 = "?x-oss-process=image/resize,m_fill,w_180,h_180,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle200x200 = "?x-oss-process=image/resize,m_fill,w_200,h_200,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle250x250 = "?x-oss-process=image/resize,m_fill,w_250,h_250,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle300x300 = "?x-oss-process=image/resize,m_fill,w_300,h_300,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle350x350 = "?x-oss-process=image/resize,m_fill,w_350,h_350,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle400x400 = "?x-oss-process=image/resize,m_fill,w_400,h_400,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle500x500 = "?x-oss-process=image/resize,m_fill,w_500,h_500,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle600x600 = "?x-oss-process=image/resize,m_fill,w_600,h_600,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle100xauto = "?x-oss-process=image/resize,m_lfit,w_100,limit_0/auto-orient,0/quality,q_90/format,jpg";
var imgStyle200xauto = "?x-oss-process=image/resize,m_lfit,w_200,limit_0/auto-orient,0/quality,q_90/format,jpg";
var imgStyle60xauto = "?x-oss-process=image/resize,m_lfit,w_60,limit_0/auto-orient,0/quality,q_90/format,jpg";
var imgStyle400xauto = "?x-oss-process=image/resize,m_lfit,w_400,limit_0/auto-orient,0/quality,q_90/format,jpg";
var imgStyle500xauto = "?x-oss-process=image/resize,m_lfit,w_500,limit_0/auto-orient,0/quality,q_90/format,jpg";
var imgStyle600xauto = "?x-oss-process=image/resize,m_lfit,w_600,limit_0/auto-orient,0/quality,q_90/format,jpg";
var imgStyle500x200 = "?x-oss-process=image/resize,m_fill,w_500,h_200,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle400x160 = "?x-oss-process=image/resize,m_fill,w_400,h_160,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var imgStyle180x180 = "?x-oss-process=image/resize,m_fill,w_180,h_180,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_40x40 = "?x-oss-process=image/resize,m_fill,w_40,h_40,limit_0/auto-orient,0/quality,q_100";
var img_style_80x80 = "?x-oss-process=image/resize,m_fill,w_80,h_80,limit_0/auto-orient,0/quality,q_100";
var img_style_90x90 = "?x-oss-process=image/resize,m_fill,w_90,h_90,limit_0/auto-orient,0/quality,q_100";
var img_style_100x100 = "?x-oss-process=image/resize,m_fill,w_100,h_100,limit_0/auto-orient,0/quality,q_100";
var img_style_150x150 = "?x-oss-process=image/resize,m_fill,w_150,h_150,limit_0/auto-orient,0/quality,Q_80/format,jpg";
var img_style_180x180_90 = "?x-oss-process=image/resize,m_fill,w_180,h_180,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_200x200_90 = "?x-oss-process=image/resize,m_fill,w_200,h_200,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_200x200 = "?x-oss-process=image/resize,m_fill,w_200,h_200,limit_0/auto-orient,0/quality,q_100";
var img_style_200x200_90jpg = "?x-oss-process=image/resize,m_fill,w_200,h_200,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_250x250jpg = "?x-oss-process=image/resize,m_fill,w_250,h_250,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_250x250 = "?x-oss-process=image/resize,m_fill,w_250,h_250,limit_0/auto-orient,0/quality,q_100";
var img_style_300x300 = "?x-oss-process=image/resize,m_fill,w_300,3_200,limit_0/auto-orient,0/quality,q_100";
var img_style_350x350 = "?x-oss-process=image/resize,m_fill,w_350,h_350,limit_0/auto-orient,0/quality,q_100";
var img_style_400x400 = "?x-oss-process=image/resize,m_fill,w_400,h_400,limit_0/auto-orient,0/quality,q_100";
var img_style_400x300_80 = "?x-oss-process=image/resize,m_fill,w_400,h_300,limit_0/auto-orient,0/quality,q_80";
var img_style_400x400_80 = "?x-oss-process=image/resize,m_fill,w_400,h_400,limit_0/auto-orient,0/quality,Q_80/format,jpg";
var img_style_500x500 = "?x-oss-process=image/resize,m_fill,w_500,h_500,limit_0/auto-orient,0/quality,Q_100/format,jpg";
var img_style_500x500jpg = "?x-oss-process=image/resize,m_fill,w_500,h_500,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_500x250 = "?x-oss-process=image/resize,m_fill,w_500,h_250,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_400x200_70 = "?x-oss-process=image/resize,m_fill,w_400,h_200,limit_0/auto-orient,0/quality,Q_70/format,jpg";
var img_style_400x200 = "?x-oss-process=image/resize,m_fill,w_400,h_200,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_600x300 = "?x-oss-process=image/resize,m_fill,w_600,h_300,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_400x300_70 = "?x-oss-process=image/resize,m_fill,w_400,h_300,limit_0/auto-orient,0/quality,Q_70/format,jpg";
var img_style_400x250_70 = "?x-oss-process=image/resize,m_fill,w_400,h_250,limit_0/auto-orient,0/quality,Q_70/format,jpg";
var img_style_600x600 = "?x-oss-process=image/resize,m_fill,w_600,h_600,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_600x240 = "?x-oss-process=image/resize,m_fill,w_600,h_240,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_500x250_auto = "?x-oss-process=image/resize,m_fill,w_500,h_250,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_100xauto = "?x-oss-process=image/resize,m_lfit,w_100,limit_0/auto-orient,0/quality,q_100/format,jpg";
var img_style_200xauto = "?x-oss-process=image/resize,m_lfit,w_200,limit_0/auto-orient,0/quality,q_100/format,jpg";
var img_style_60xauto = "?x-oss-process=image/resize,m_lfit,w_60,limit_0/auto-orient,0/quality,q_100/format,jpg";
var img_style_400xauto = "?x-oss-process=image/resize,m_lfit,w_400,limit_0/auto-orient,0/quality,q_100/format,jpg";
var img_style_500xauto = "?x-oss-process=image/resize,m_lfit,w_500,limit_0/auto-orient,0/quality,q_100/format,jpg";
var img_style_500xauto_90 = "?x-oss-process=image/resize,m_lfit,w_500,limit_0/auto-orient,0/quality,q_90/format,jpg";
var img_style_450xauto_80 = "?x-oss-process=image/resize,m_lfit,w_450,limit_0/auto-orient,0/quality,q_100/format,jpg";
var img_style_600xauto = "?x-oss-process=image/resize,m_lfit,w_600,limit_0/auto-orient,0/quality,q_90/format,jpg";
var img_style_800xauto = "?x-oss-process=image/resize,m_lfit,w_800,limit_0/auto-orient,0/quality,q_90/format,jpg";
var img_style_500x200 = "?x-oss-process=image/resize,m_fill,w_500,h_200,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_400x160 = "?x-oss-process=image/resize,m_fill,w_400,h_160,limit_0/auto-orient,0/quality,Q_90/format,jpg";
var img_style_180x180 = "?x-oss-process=image/resize,m_fill,w_180,h_180,limit_0/auto-orient,0/quality,Q_90/format,jpg";


//图片管理开始结束
var AppId = 'A6928945983146';
var defaultColor = "#000000";
//底部文字的默认颜色
var activeColor = "#F84E37";
//底部活动状态的颜色
var AppVersion = '1.0.0';
//function getAll(serverURL, data, callback) {
//	if (!isJson(data)) {
//		data = str2json(data);
//	}
//	api.ajax({
//		url : serverURL,
//		method : 'post',
//		timeout : 30,
//		dataType : 'json',
//		returnAll : false,
//		data : data
//	}, function(ret, err) {
//		noLine(-1);
//		if (ret) {
//			if (ret.status == '-1')//已被其他用户登录
//			{
//				setLocal("uid", '');
//				setLocal("token", '');
//				setLocal("user_id", '');
//				setLocal("user_tel", '');
//				setLocal("user_type", '');
//				setLocal("zv_server_type", 1);
//				exScriptFrame('root', 'my', 'judege_login_exit()');
//			}
//
//			if (callback) {
//				callback(ret);
//			}
//		} else {
//			$alert(json2str(err));
//			noLine(0);
//		}
//	});
//}
function get_os_id() {
	var deviceId = api.deviceId;
	deviceId = deviceId.replace(/\-/g, "");
	return deviceId;
}

function getAll(serverURL, data, callback) {
	if (!isJson(data)) {
		data = strToJson(data);
	}
	$.ajax({
		type: "post",
		url: serverURL,
		data:data,
		dataType: "json",
		success: function (ret) {
			callback(ret);
		},
		error: function (XMLHttpRequest, textStatus, errorThrown) {
			//alert(json2str(XMLHttpRequest));
		}
	});
}

function load_page_append(ret,id,html)
{

	//if(ret.data.is_first_page==1&&ret.data.is_last_page!=1)
	if(parseInt(ret.data.is_first_page)==1)
	{
		$("#"+id).html(html);
	}
	else
	{
		if($("#is_last_page").val()!=1)
		{
			$("#"+id).append(html);
		}
	}

	if(ret.data.is_last_page==1)
	{
		load_page(-1);
		$("#is_last_page").val(1);
	}
}
function get_goods_attr_key_name(json,key)
{
	var val = "";
	if(isDefine(json)&&json.length>0)
	{
		for(var i=0;i<json.length;i++)
		{
			if(json[i].attr_id==key)
			{
				val = json[i].attr_name;
				//break;
			}
		}
	}
	return val;
}
function get_goods_attr_key_val(json,key)
{
	var val = "";
	if(isDefine(json)&&json.length>0)
	{
		for(var i=0;i<json.length;i++)
		{
			if(json[i].attr_id==key)
			{
				val = json[i].value;
				//break;
			}
		}
	}
	return val;
}
function isJson(obj) {
	var isjson = typeof (obj) == "object" && Object.prototype.toString.call(obj).toLowerCase() == "[object object]" && !obj.length;
	return isjson;
}
function main_show()
{
	$("#main-content").css("visibility","visible");
	$("body").removeClass("loading-bg");
}
function main_hide()
{
	$("#main-content").css("visibility","hidden");
	$("body").addClass("loading-bg");
}
function scroll_init()
{
	var slider = new fz.Scroll('.ui-slider', {
		role: 'slider',
		indicator: true,
		autoplay: true,
		interval: 3000
	});
}
function ios7Bar() {
	var header = $api.dom('header');
	$api.fixStatusBar(header);
}

function imgInit() {
	Echo.init({
		offset : 0,
		throttle : 0
	});
}

function init_page(bg) {
	var load_page = document.getElementById('load_page');
	if (!isDefine(bg)) {
		bg = "bg-gra3";
	}
	var str = '<div class="ui-loading-wrap ' + bg + '" style="display:none;padding-bottom:0px;"  id="load_page">';
	str += '<i  style="display:block;width:20px; height:20px;"><img src="image/icon_loading.gif" class="w-15 h-15"></i>';
	str += '<p class="t-919191"><font class="ftz14"> 玩命加载中...</font></p>';
	str += '</div>';

	$("#main-content").append(str);

}


function ___getPageScroll() {
	var xScroll, yScroll;
	if (self.pageYOffset) {
		yScroll = self.pageYOffset;
		xScroll = self.pageXOffset;
	} else if (document.documentElement && document.documentElement.scrollTop) { // Explorer 6 Strict
		yScroll = document.documentElement.scrollTop;
		xScroll = document.documentElement.scrollLeft;
	} else if (document.body) {// all other Explorers
		yScroll = document.body.scrollTop;
		xScroll = document.body.scrollLeft;
	}
	arrayPageScroll = new Array(xScroll,yScroll);
	return arrayPageScroll;
};
function open_url(url,scroll_page,show_page) {

	if(isDefine(scroll_page)&&isDefine(scroll_page))
	{
		var scroll_array = ___getPageScroll();
		sessionStorage.setItem(scroll_page,scroll_array[1]);
		sessionStorage.setItem(scroll_page+"_current_show_page",show_page);
	}

	window.location.href=url;
}

function openWinFromBottom(url, pars) {
	name = url.substring(url.lastIndexOf('/') + 1);
	url = url + ".html";
	var header = $api.dom('header');
	$api.fixStatusBar(header);
	var systemType = api.systemType;
	var type = "";
	if (systemType == 'ios') {
		type = "cube";
		subType = "from_bottom";
	} else {
		type = "movein";
		subType = "from_right";
	}
	api.openWin({
		name : name,
		url : url,
		animation : {
			type : type,
			subType : subType,
			duration : 300
		}
	});

}

//全屏打开浮动窗口
function openFrameFull(id) {
	document.getElementById(id).style.display = 'block';
}

//关闭页面
function close_win(name) {
	history.go(-1);
}


///获取滚动当前显示数量
function get_sroll_num_per_page(num_per_page,page_name)
{

	var index_current_show_page = isDefine(sessionStorage.getItem(page_name+"_current_show_page"))?sessionStorage.getItem(page_name+"_current_show_page"):1;
	var session_index = isDefine(sessionStorage.getItem(page_name))?sessionStorage.getItem(page_name):"null";

	if(index_current_show_page>1&&isDefine(session_index))
	{
		num_per_page = (index_current_show_page)*num_per_page;
	}

	return num_per_page;
}
//设置滚动的分页
function set_session_scroll_page(ret,page_name){
	var scroll_pos_height = sessionStorage.getItem(page_name);
	if(isDefine(scroll_pos_height))
	{
		window.scrollTo(0,scroll_pos_height);
	}
	sessionStorage.setItem(page_name,"null");

	var index_current_show_page = isDefine(sessionStorage.getItem(page_name+"_current_show_page"))?sessionStorage.getItem(page_name+"_current_show_page"):"null";
	if(isDefine(index_current_show_page))
	{
		$("#show_page").val(index_current_show_page);
	}
	else
	{
		$("#show_page").val(ret.data.show_page);
	}
	sessionStorage.setItem(page_name+"_current_show_page","null");
}
///获取当前滚动的实际分页
function get_current_scroll_show_page(ret,num_per_page)
{
	num_per_page = parseInt(num_per_page);
	var current_total_count = parseInt(ret.data.current_total_count);
	if(current_total_count>num_per_page)
	{
		var current_scroll_show_page = Math.ceil(current_total_count/num_per_page);
	}
	else
	{
		current_scroll_show_page = ret.data.show_page;
	}
	return current_scroll_show_page;
}
//获取滚动分页
function get_sroll_show_page(show_page,page_name)
{

	var index_current_show_page = isDefine(sessionStorage.getItem(page_name+"_current_show_page"))?sessionStorage.getItem(page_name+"_current_show_page"):1;
	var session_index = isDefine(sessionStorage.getItem(page_name))?sessionStorage.getItem(page_name):"null";

	if(index_current_show_page>1&&isDefine(session_index))
	{
		show_page = 1;

	}
	else
	{
		var show_page = show_page;
	}
	return show_page;
}
//加载到最低
function scroll_bottom(callbak) {
	$(window).scroll(function(){
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $(document).height();
		var windowHeight = $(this).height();
		//alert("1:"+scrollTop+windowHeight);
		//alert(scrollHeight);
		//alert("scrollHeight"+scrollHeight);
		//alert("scrollHeight"+scrollHeight);

		if(parseInt(scrollHeight)-parseInt(scrollTop)-parseInt(windowHeight)==0){
			callbak();
		}
	});
}

//加载分页
function load_page(time) {
	if (parseInt(time) == 0) {
		$("#load_page").show();
	}
	if (parseInt(time) == -1) {
		$("#load_page").hide();
	}
	if (parseInt(time) > 0) {
		setTimeout(function() {
			$("#load_page").hide();
		}, time);
	}
}

function Loading(time) {

	var str = "";
	str += '<div class="ui-loading-block show">';
	str += '<div class="ui-loading-cnt">';
	str += '<i class="ui-loading-bright"></i>';
	str += '</div>';
	str += '</div>';
	$("body").append(str);
	if (parseInt(time) == -1) {
		//$api.css($api.byId('main-content'), "visibility:visible");
		$(".ui-loading-block").remove();
	}
	if (parseInt(time) > 0) {

		setTimeout(function() {
			//$api.css($api.byId('main-content'), "visibility:visible");
			$(".ui-loading-block").remove();
		}, time);
	}
}

function nodata(type) {
	if(type==-1)
	{
		$("body").addClass("no_data");
	}
	else
	{
		$("body").removeClass("no_data");
	}
}

//提示框
function $toast(txt, duration, location) {
	//if (!txt) {
	//	txt = 'Loading...';
	//}
	//if (!duration) {
	//	duration = 2000;
	//}
	//if (!location) {
	//	location = 'middle';
	//}
	//api.toast({
	//	msg : txt,
	//	duration : duration,
	//	location : location
	//});
	if(isDefine(txt))
	{
		alert(txt);
	}
}

//警告框


/**
 * 判断是否是空
 * @param value
 */
function isDefine(value) {
	if (value == null || value == "" || value == "undefined" || value == undefined || value == "null" || value == "(null)" || value == 'NULL' || typeof (value) == 'undefined') {
		return false;
	} else {
		value = value + "";
		value = value.replace(/\s/g, "");
		if (value == "") {
			return false;
		}
		return true;
	}
}

//搜索
function showSearch() {
	$(".ui-searchbar-text").hide();
	$(".ui-searchbar-input").show();
	$(".ui-searchbar-cancel").show();

	$(".ui-icon-close").show();
}

//隐藏搜索
function hideSearch() {
	$(".ui-searchbar-text").show();
	$(".ui-searchbar-input").hide();
	$(".ui-searchbar-cancel").hide();
	$(".ui-icon-close").hide();
}

function clearInput(id) {
	$("#" + id).val("");
}

//匹配中文 数字 字母 下划线
function checkInput(str) {
	var pattern = /^[\w\u4e00-\u9fa5]+$/gi;
	if (pattern.test(c)) {
		return false;
	}
	return true;
}

function stripscript(s) {
	var pattern = new RegExp("[`~!@#$^&*()=|{}':;',\\[\\].<>/?~！@#￥……&*（）&mdash;—|{}【】‘；：”“'。，、？]")
	var rs = "";
	for (var i = 0; i < s.length; i++) {
		rs = rs + s.substr(i, 1).replace(pattern, '');
	}
	return rs;
}


/**
 * 根据时间戳获取年、月、日
 * @param String str 时间戳
 * @param Boolean f 是否在原来的基础上*1000，true要*，false或不填就不*
 */
function getMakeTimes(str, f) {
	var t = (f) ? parseInt(str) : parseInt(str) * 1000;
	var d = new Date(t);
	var y = d.getFullYear();
	var m = setNum(d.getMonth() + 1);
	var d = setNum(d.d.getDate());
	return y + "-" + m + "-" + d;
}

function setNum(s) {
	return (parseInt(s) > 9) ? s : '0' + s;
}

/**
 *计算2个日期相差多少天
 *@param String strDateStart和strDateEnd 日期，格式为2014-04-04
 */
function getDays(strDateStart, strDateEnd) {
	var strSeparator = "-";
	//日期分隔符
	var oDate1;
	var oDate2;
	oDate1 = strDateStart.split(strSeparator);
	oDate2 = strDateEnd.split(strSeparator);
	var strDateS = new Date(oDate1[0] + "-" + oDate1[1] + "-" + oDate1[2]);
	var strDateE = new Date(oDate2[0] + "-" + oDate2[1] + "-" + oDate2[2]);
	var iDays = parseInt(Math.abs(strDateS - strDateE) / 1000 / 60 / 60 / 24)//把相差的毫秒数转换为天数
	return iDays;
}

/**
 * json对象转为string
 * @param {Object} j
 */
function json2str(j) {
	return JSON.stringify(j);
}

/**
 * string转为json对象
 * @param String s
 */
function str2json(s) {
	return JSON.parse(s);
}

function strToJson(str) {
	var json = eval('(' + str + ')');
	return json;
}

/*
 * 扫描二维码
 */
function scanner(h, callback) {
	var obj = api.require('scanner');
	obj.open(function(ret, err) {
		callback(ret.msg);
	});
}

//设置子窗口隐藏
function setFrameHide(name, show) {
	api.setFrameAttr({
		name : name,
		hidden : show,
	});
}


Array.prototype.indexOf = function(val) {
	for (var i = 0; i < this.length; i++) {
		if (this[i] == val)
			return i;
	}
	return -1;
};

Array.prototype.remove = function(val) {
	var index = this.indexOf(val);
	if (index > -1) {
		this.splice(index, 1);
	}
};
Array.prototype.baoremove = function(dx) {
	if (isNaN(dx) || dx > this.length) {
		return false;
	}
	this.splice(dx, 1);
}
//设置本地存储
function setLocal(key, val) {
	localStorage.setItem(key, val);
}

//设置本地存储
function removeLocal(key) {
	localStorage.removeItem(key);
}

//获取本地存储
function getLocal(key) {
	return localStorage.getItem(key);
}



/**
 *设置头部状态显示隐藏
 */
function setTopStatus(id) {
	var len = $api.domAll(".topIden").length;

	for (var i = 0; i < len; i++) {
		$api.css($api.byId("header_" + i), "display:none");
	}
	$api.css($api.byId("header_" + id), "display:block");
}

/**
 *设置底部状态显示隐藏，这里我用的是图片，因为svg图不可控，所以就一直没有用svg，腿甲大家去阿里巴巴的图标库下载
 */
function setFootStatus(id) {

	var len = $api.domAll(".footerIden").length;
	for (var i = 0; i < len; i++) {
		$api.css($api.byId("footer_" + i), "color:" + defaultColor);
		var defaultImg = $api.attr($api.byId("img_" + i), "src");
		$api.attr($api.byId("img_" + i), "src", defaultImg.replace("-act", ""));
		// alert(defaultImg);
	}
	var activeImg = $api.attr($api.byId("img_" + id), "src");
	activeImg = activeImg.replace(".png", "-act.png");

	$api.attr($api.byId("img_" + id), "src", activeImg);
	$api.css($api.byId("footer_" + id), "color:" + activeColor);
}

/**
 *同时调用设置顶部和底部方法
 */
function setSwitch(name, id) {
	setTopStatus(id);
	setFootStatus(id);
	setFrameGroupIndex(name, id);
}

/**
 *设置当前显示的窗口
 */
function setFrameGroupIndex(name, id) {
	api.setFrameGroupIndex({
		name : name,
		index : id
	});
}


function scrollToControl(id) {
	var elem = document.getElementById(id);
	var scrollPos = elementPosition(elem).y;
	scrollPos = scrollPos - document.documentElement.scrollTop;
	var remainder = scrollPos % 50;
	var repeatTimes = (scrollPos - remainder) / 50;
	scrollSmoothly(scrollPos, repeatTimes);
	window.scrollBy(0, remainder);
}

var repeatCount = 1;
var cTimeout;
var timeoutIntervals = new Array();
var timeoutIntervalSpeed;
function scrollSmoothly(scrollPos, repeatTimes) {
	if (repeatCount < repeatTimes) {
		window.scrollBy(0, 50);
	} else {
		repeatCount = 1;
		clearTimeout(cTimeout);
		return;
	}
	repeatCount++;
	cTimeout = setTimeout("scrollSmoothly('" + scrollPos + "','" + repeatTimes + "')", 0);
}

function show_top() {
	var str = "<img id='show_top' style='display:none;z-index:9999999999;position:fixed; right:10px; bottom:80px;' src='img/iconfont/gototop.png' width='40' height='40' onclick='goTop()'>";
	$("body").append(str);

	$(window).scroll(function() {
		// 当滚动到最底部以上100像素时， 加载新内容
		var top = $(this).scrollTop();
		if (top > 0) {
			$("#show_top").show();
		} else {
			$("#show_top").hide();
		}
	});

}

function goTop(acceleration, time) {
	acceleration = acceleration || 0.1;
	time = time || 16;
	var x1 = 0;
	var y1 = 0;
	var x2 = 0;
	var y2 = 0;
	var x3 = 0;
	var y3 = 0;
	if (document.documentElement) {
		x1 = document.documentElement.scrollLeft || 0;
		y1 = document.documentElement.scrollTop || 0;
	}
	if (document.body) {
		x2 = document.body.scrollLeft || 0;
		y2 = document.body.scrollTop || 0;
	}
	var x3 = window.scrollX || 0;
	var y3 = window.scrollY || 0;
	// 滚动条到页面顶部的水平距离
	var x = Math.max(x1, Math.max(x2, x3));
	// 滚动条到页面顶部的垂直距离
	var y = Math.max(y1, Math.max(y2, y3));
	// 滚动距离 = 目前距离 / 速度, 因为距离原来越小, 速度是大于 1 的数, 所以滚动距离会越来越小
	var speed = 1 + acceleration;
	window.scrollTo(Math.floor(x / speed), Math.floor(y / speed));
	// 如果距离不为零, 继续调用迭代本函数
	if (x > 0 || y > 0) {
		var invokeFunction = "goTop(" + acceleration + ", " + time + ")";
		window.setTimeout(invokeFunction, time);
	}
}

/**
 *右边滑块-删除按钮start
 */
function leftSider() {
	var startX, startY;
	$('.leftsider').bind("touchstart", function(event) {
		startX = event.changedTouches[0].pageX, startY = event.changedTouches[0].pageY;
	});
	$('.leftsider').bind("touchmove", function(event) {
		var obj = $(this);
		nChangY = event.changedTouches[0].pageY;
		nChangX = event.changedTouches[0].pageX;
		var moveX = startX - nChangX;
		var moveY = startY - nChangY;
		if (Math.abs(moveX) > Math.abs(moveY)) {
			event.preventDefault();
			if (moveX > 0) {
				$(this).removeClass("untransform");
				$(this).addClass("transform");
			} else {
				$(this).addClass("untransform");
				$(this).removeClass("transform");
			}
		} else {
		}
	});
}

/**
 *  *右边滑块-删除按钮start
 */
//网络监听
function lineListener() {
	api.addEventListener({
		name : 'offline'
	}, function(ret, err) {
		if (ret) {
			//alert("ret:"+JSON.stringify(ret));
		} else {
			//无网络
			noLine();
			//alert("err:"+JSON.stringify(err));
		}
	});
	api.addEventListener({
		name : 'online'
	}, function(ret, err) {
		if (ret) {
			//有网络
		} else {
			//无网络
			noLine();
		}
	});
}

function siderScroll(css) {
	var slider = new fz.Scroll(css, {
		role : 'slider',
		indicator : true,
		autoplay : true,
		interval : 5000
	});
}

function siderScroll1(slider, position) {
	var bullets = document.getElementById(position).getElementsByTagName('li');
	var slider = Swipe(document.getElementById(slider), {
		auto : 999999999,
		continuous : true,
		callback : function(pos) {
			var i = bullets.length;
			while (i--) {
				bullets[i].className = ' ';
			}
			bullets[pos].className = 'on';
		}
	});

}

function scrollToHash(id) {
	window.location.hash = "#" + id;
}

String.prototype.Trim = function() {
	return this.replace(/(^\s*)|(\s*$)/g, "");
}

String.prototype.LTrim = function() {
	return this.replace(/(^\s*)/g, "");
}

String.prototype.RTrim = function() {
	return this.replace(/(\s*$)/g, "");
}
//根据经度维护计算距离
var EARTH_RADIUS = 6378137.0;
//单位M
var PI = Math.PI;

function getRad(d) {
	return d * PI / 180.0;
}

function getFlatternDistance(lat1, lng1, lat2, lng2) {
	var f = getRad((lat1 + lat2) / 2);
	var g = getRad((lat1 - lat2) / 2);
	var l = getRad((lng1 - lng2) / 2);

	var sg = Math.sin(g);
	var sl = Math.sin(l);
	var sf = Math.sin(f);

	var s, c, w, r, d, h1, h2;
	var a = EARTH_RADIUS;
	var fl = 1 / 298.257;

	sg = sg * sg;
	sl = sl * sl;
	sf = sf * sf;

	s = sg * (1 - sl) + (1 - sf) * sl;
	c = (1 - sg) * (1 - sl) + sf * sl;

	w = Math.atan(Math.sqrt(s / c));
	r = Math.sqrt(s * c) / w;
	d = 2 * w * a;
	h1 = (3 * r - 1) / 2 / c;
	h2 = (3 * r + 1) / 2 / s;

	return d * (1 + fl * (h1 * sf * (1 - sg) - h2 * (1 - sf) * sg));
}

function setInputVal(id, val) {
	$("#" + id).val(val);
}

function setHtmlVal(id, val) {
	$("#" + id).html(val);
}

function getInputVal(id, callback) {
	var val = $("#" + id).val();
	if (callback) {
		callback(val);
	} else {
		return val;
	}
	//exScriptFrame(api.winName, "category_c", "setInputVal('model','" + model + "')");
}

function setTextVal(id, val) {
	$("#" + id).text(val);
}

function openDialog(title, html, callback) {
	var str = "";
	str += '<div class="ui-dialog show" id="openDialog">';
	str += '<div class="ui-dialog-cnt" >';
	str += '<div class="ui-dialog-bd">';
	str += '<h4 class="pad-b-10">' + title + '</h4>';
	str += '<div>';
	str += html;
	str += '</div>';
	str += '</div>';
	str += '<div class="ui-dialog-ft">';
	str += '<button type="button" data-role="button" tapmode onclick=closeDialog("dialog")>';
	str += '取消';
	str += '</button>';
	str += '<button type="button" data-role="button" tapmode >';
	str += '确定';
	str += '</button>';
	str += '</div>';
	str += '</div>';
	str += '</div>';
	$("body").append(str);
}

function openSucsss(title, html) {
	var str = "";
	str += '<div class="ui-dialog show" id="openSucsss">';
	str += '<div class="ui-dialog-cnt" >';
	str += '<div class="ui-dialog-bd">';
	str += '<h4 class="pad-b-10">' + title + '</h4>';
	str += '<div>';
	str += html;
	str += '</div>';
	str += '</div>';
	str += '<div class="ui-dialog-ft">';

	str += '<button type="button" data-role="button" tapmode  onclick=closeSucsss()>';
	str += '确定';
	str += '</button>';
	str += '</div>';
	str += '</div>';
	str += '</div>';
	$("body").append(str);
}

function closeSucsss() {
	$("#openSucsss").remove();
}

function closeDialog() {
	$("#openDialog").remove();
}
//判断服务器图片
function images_judge(value,style)
{
	if (value == null || value == "" || value == "undefined" || value == undefined || value == "null" || value == "(null)" || value == 'NULL' || typeof (value) == 'undefined') {
		return "icon/ImgLoading.png";
	} else {
		var value_a=value.substr(0,4);
		if(value_a=='http'||value_a=='HTTP')
		{
			return value;
		}
		else
		{
			if (isDefine(style))
			{
				return serverIMG+value+style;
			}
			else
			{
				return serverIMG+value;
			}
		}
	}
}

//判断服务器音乐
function music_judge(value) {
	if (value == null || value == "" || value == "undefined" || value == undefined || value == "null" || value == "(null)" || value == 'NULL' || typeof (value) == 'undefined') {
		return "../data/shanshanlaile.mp3";
	} else {
		var value_a = value.substr(0, 4);
		if (value_a == 'http' || value_a == 'HTTP') {
			return value;
		} else {
			return serverMusic + value;
		}
	}
}

//服务器分享图片
function share_judge(value) {
	if (value == null || value == "" || value == "undefined" || value == undefined || value == "null" || value == "(null)" || value == 'NULL' || typeof (value) == 'undefined') {
		return 'widget://icon/a.png';
	} else {
		var value_a = value.substr(0, 4);
		if (value_a == 'http' || value_a == 'HTTP') {
			return value;
		} else {
			return serverIMG + value + "@1e_1c_0o_0l_400h_400w_100q.src|watermark=1&object=cHViL3dhdGVybWFyazQwMC5wbmdAMTAwcF8wYl8wZA&t=100&p=7&y=0&x=0";
			//return serverIMG + value + "@1e_1c_0o_0l_115h_115w_100q.src|watermark=1&object=cHViL3dhdGVybWFya19ibGFjazExNS5wbmdAMTAwcF8wYl8wZA&t=100&p=7&y=0&x=0";
		}
	}
}
function setPosTop(page)
{
	$(window).scroll(function(){
		totalheight = parseFloat($(window).height()) + parseFloat($(window).scrollTop());
		var tops=document.body.scrollTop;
		//if(window.sessionStorage){
		sessionStorage.setItem(page+"Top",tops);
		sessionStorage.setItem(page+"IsScroll","scroll");
		//}
	});
}
function backTop() {
	show_top();
	//$(window).scroll(function(){
	//	var scrollTop = $(this).scrollTop();
	//	var str="";
	//	str+='<a href="javascript:scroll(0,0)">';
	//	str+='<div id="dingbu" class="posf w-40 h-40" style="bottom: 80px;right: 25px;z-index: 999999999;display: none;" onclick=""><img src="img/iconfont/zhiding.png" width="100%" height="100%" alt=""></div>';
	//	str+='</a>';
	//	$("#main-content").append(str);
	//	if (scrollTop>1500){
	//
	//		$('#dingbu').show();
	//	}else{
	//		$('#dingbu').hide();
	//	}
	//});
}
function setAutoScroll(page,p)
{
	//if(window.sessionStorage){
	var top = parseInt(sessionStorage.getItem(page+"Top"));
	top = top?top:0;
	if(top<=0)
	{
		p = 1;
	}
	sessionStorage.setItem(page+"Page",p);

	sessionStorage.setItem(page+"IsScroll","no");
	document.body.scrollTop = top;
	//}
}
function getIsScroll(page)
{
	return sessionStorage.getItem(page+"IsScroll");
}
function getCurPage(page)
{

	var p = parseInt(sessionStorage.getItem(page+"Page"));
	p = isDefine(p)?p:1;
	p = !isNaN(p)?p:1;
	return p;
}
function $confirm(title,callback)
{
	var str = "";
	str+='<div id="zw_confirm" style="width:100%;height: 100%;top:0;left:0;position: fixed;z-index: 9999999999;" class="fadeIn1">';
	str+='<div style="background-color:black;opacity:0.5;width:100%;height:100%"></div>';
	str+='<div class="box-a ui-border-radius" style="position: absolute;width:70%; left:15%;top:30%; min-height:122px; background:#FFFFFF;">';
	str+='<div class="wd-pre-100 ui-txt-center ftz14 t-bla umh40 pad-t-20 pad-b-20">';
	str+=title;
	str+='</div>';
	str+='<div class="wd-pre-100 umh40" style="border-top:solid #f7f7f7 1px">';
	str+='<div class="wd-pre-100 h-40">';
	str+='<ul class="ui-tiled">';

	if(isDefine(callback))
	{
		str+='<li class="h-40" ontouchend=$(this).removeClass("bg-gra2")  ontouchstart=$(this).addClass("bg-gra2") style="border-right:solid #f7f7f7 1px" tapmode="bg-e5e6e7" onclick=$("#zw_confirm").remove();'+callback+'>';
		str+='<div class="ftz14 ui-txt-center">';
		str+='确定';
		str+='</div>';
		str+='</li>';

	}
	else
	{
		str+='<li class="h-40" ontouchend=$(this).removeClass("bg-gra2")  ontouchstart=$(this).addClass("bg-gra2") style="border-right:solid #f7f7f7 1px" onclick=$("#zw_confirm").remove()>';
		str+='<div class="ftz14 ui-txt-center">';
		str+='确定';
		str+='</div>';
		str+='</li>';
	}

	str+='<li class="h-40" ontouchend=$(this).removeClass("bg-gra2")  ontouchstart=$(this).addClass("bg-gra2") tapmode="bg-e5e6e7"  onclick=$("#zw_confirm").remove()>';
	str+='<div class="ftz14 ui-txt-center">';
	str+='取消';
	str+='</div>';
	str+='</li>';
	str+='</ul>';
	str+='</div>';
	str+='</div>';
	str+='</div>';
	str+='</div>';
	$("body").append(str);
	return false;
}

function $alert(title,callback)
{
	var str = "";
	str+='<div id="zw_alert" style="width:100%;height: 100%;top: 0;left: 0;position: fixed;z-index: 9999999999;" class="fadeIn1">';
	str+='<div style="background-color:black;opacity:0.5;width:100%;height:100%"></div>';
	str+='<div class="box-a ui-border-radius " style="position: absolute;width:70%; left:15%;top:30%; min-height:122px; background:#FFFFFF;">';
	str+='<div class=" wd-pre-100 ui-txt-center ftz14 t-bla umh40 pad-t-20 pad-b-20">';
	str+=title;
	str+='</div>';
	str+='<div class="wd-pre-100 umh40 " ontouchend=$(this).removeClass("bg-gra2")  ontouchstart=$(this).addClass("bg-gra2") style="border-top:solid #f7f7f7 1px">';
	str+='<div class="wd-pre-100 h-40">';
	str+='<ul class="ui-tiled">';

	if(callback)
	{
		str+='<li class="h-40" onclick=$("#zw_alert").remove();'+callback+'>';
		str+='<div class="ftz14 ui-txt-center">';
		str+='确定';
		str+='</div>';
		str+='</li>';
	}
	else
	{
		str+='<li class="h-40"  onclick=$("#zw_alert").remove()>';
		str+='<div class="ftz14 ui-txt-center">';
		str+='确定';
		str+='</div>';
		str+='</li>';
	}
	str+='</ul>';
	str+='</div>';
	str+='</div>';
	str+='</div>';
	str+='</div>';
	$("body").append(str);
	return false;
}


function formatSeconds(value) {
	var theTime = parseInt(value);
	// 秒
	var theTime1 = 00;
	// 分
	var theTime2 = 00;
	// 小时
	if (theTime > 60) {
		theTime1 = parseInt(theTime / 60);
		theTime = parseInt(theTime % 60);
		if (theTime1 > 60) {
			theTime2 = parseInt(theTime1 / 60);
			theTime1 = parseInt(theTime1 % 60);
		}
	}
	if (parseInt(theTime2) > 99) {
		theTime2 = 99;
	}
	var result = '';
	if (theTime2 > 0) {
		var Y_time = parseInt(theTime2);
		if (Y_time < 10) {
			Y_time = "0" + Y_time;
		}
		result += Y_time + ':';
		//时
	} else {
		result += '00:';
		//时
	}
	if (theTime1 > 0) {
		var M_time = parseInt(theTime1);
		if (M_time < 10) {
			M_time = "0" + M_time;
		}
		result += M_time + ':';
		//分
	} else {
		result += '00:';
		//分
	}
	var D_time = parseInt(theTime);
	if (D_time < 10) {
		D_time = "0" + D_time;
	}
	result += D_time;
	//秒
	return result;
}

function timeStamp(second_time) {

	var time = parseInt(second_time) + "秒 ";
	if (parseInt(second_time) > 60) {

		var second = parseInt(second_time) % 60;
		var min = parseInt(second_time / 60);
		time = min + "分 " + second + "秒";

		if (min > 60) {
			min = parseInt(second_time / 60) % 60;
			var hour = parseInt(parseInt(second_time / 60) / 60);
			time = hour + "时 " + min + "分 " + second + "秒";

			if (hour > 24) {
				hour = parseInt(parseInt(second_time / 60) / 60) % 24;
				var day = parseInt(parseInt(parseInt(second_time / 60) / 60) / 24);
				time = day + "天 " + hour + "时 " + min + "分 " + second + "秒";
			}
		}

	}

	return time;
}

function bank_time(end_time,now,callback)//倒计时
{
	if (end_time > now) {
    	var shijian = end_time - now;
        var time_val = formatSeconds(shijian);
        //document.getElementById("time").innerHTML = time_val;
        now = parseInt(now) + 1;
        setTimeout(function(){
			bank_time(end_time,now,callback);
		}, 1000);
   	}
	else
	{
    	var time_val = "00:00:00";
    }
	callback(time_val);
}  
function datetime_to_unix(datetime) {//北京时间转换时间戳
	var tmp_datetime = datetime.replace(/:/g, '-');
	tmp_datetime = tmp_datetime.replace(/ /g, '-');
	var arr = tmp_datetime.split("-");
	var now = new Date(Date.UTC(arr[0], arr[1] - 1, arr[2], arr[3] - 8, arr[4], arr[5]));
	return parseInt(now.getTime() / 1000);
}

function wx_not_share()
{
	wx.ready(function()
	{
		var arraymenu_hide=new Array(
			'menuItem:share:appMessage',
			'menuItem:share:timeline',
			'menuItem:share:qq',
			'menuItem:share:weiboApp',
			'menuItem:favorite',
			'menuItem:copyUrl',
			'menuItem:originPage',
			'menuItem:readMode',
			'menuItem:openWithQQBrowser',
			'menuItem:openWithSafari',
			'menuItem:share:email',
			'menuItem:share:brand',
			'menuItem:share:email',
			'menuItem:share:brand',
			'menuItem:share:QZone',
			'menuItem:originPage',
			'menuItem:share:facebook');
		wx.hideMenuItems(
			{
				menuList:arraymenu_hide,
				success:function(res)
				{

				},
				fail:function(res)
				{

				}
			});
	});
}
//微信分享
function wx_share(title,desc,link,imgUrl,callback)
{
	wx.ready(function()
	{
		var arraymenu_show=new Array('menuItem:share:appMessage','menuItem:share:timeline');
		wx.onMenuShareAppMessage(
			{
				title:title,
				desc:desc,
				link:link,
				imgUrl:imgUrl,
				trigger:function (res)
				{
					//alert('用户点击分享到朋友圈');
				},
				success:function(res)
				{
					//$alert('分享成功');
					if(callback)
					{
						callback();
					}
				},
				cancel: function (res)
				{
					$alert('已取消分享');

				},
				fail:function (res)
				{
					$alert('分享失败');
					return false;
				}
			});
		wx.onMenuShareTimeline({
			title:title,
			desc:desc,
			link:link,
			imgUrl:imgUrl,
			trigger: function (res) {
				//alert('用户点击分享到朋友');
			},
			success: function (res) {
				//$alert('分享成功');
				if(callback)
				{
					callback();
				}
			},
			cancel: function (res) {
				$alert('已取消分享');
			},
			fail: function (res) {
				$alert('分享失败');
				return false;
			}
		});
		wx.onMenuShareWeibo(
			{
				title:title,
				desc:desc,
				link:link,
				imgUrl:imgUrl,
				trigger:function (res)
				{
					//win_open('alert','此页面不允许转发');return false;
				},
				success:function(res)
				{
					//$alert('分享成功');
					if(callback)
					{
						callback();
					}
				},
				cancel: function (res)
				{
					$alert('已取消分享');
				},
				fail:function (res)
				{
					$alert('分享失败');
					return false;
				}
			});
		wx.onMenuShareQQ(
			{
				title:title,
				desc:desc,
				link:link,
				imgUrl:imgUrl,
				trigger:function (res)
				{
					//win_open('alert','此页面不允许转发');return false;
				},
				success:function(res)
				{
					//$alert('分享成功');
					if(callback)
					{
						callback();
					}
				},
				cancel: function (res)
				{
					$alert('已取消分享');
				},
				fail:function (res)
				{
					$alert('分享失败');
					return false;
				}
			});


		if(arraymenu_show.length>0)
		{
			wx.showMenuItems(
				{
					menuList:arraymenu_show,
					success:function(res)
					{
					},
					fail:function(res)
					{
					}
				});
		}


		var arraymenu_hide=new Array(
			'menuItem:share:email',
			'menuItem:share:brand',
			'menuItem:share:QZone',
			'menuItem:originPage',
			'menuItem:share:facebook');
		wx.hideMenuItems(
			{
				menuList:arraymenu_hide,
				success:function(res)
				{

				},
				fail:function(res)
				{

				}
			});
	});
}

function loadImage(id, src, callback) {
	var imgloader = new window.Image();
	//当图片成功加载到浏览器缓存
	imgloader.onload = function(evt) {
		if ( typeof (imgloader.readyState) == 'undefined') {
			imgloader.readyState = 'undefined';
		}
		//在IE8以及以下版本中需要判断readyState而不是complete
		if ((imgloader.readyState == 'complete' || imgloader.readyState == "loaded") || imgloader.complete) {
			//console.log('width='+imgloader.width+',height='+imageloader.height);//读取原始图片大小
			callback({
				'msg' : 'ok',
				'src' : src,
				'id' : id
			});
		} else {
			imgloader.onreadystatechange(evt);
		}
	};

	imgloader.onerror = function(evt) {
		callback({
			'msg' : 'error',
			'id' : id
		});
	};

	imgloader.onreadystatechange = function(e) {
		//此方法只有IE8以及一下版本会调用
	};
	imgloader.src = src;
}

var loadResult = function(data) {
	data = data || {};
	if ( typeof (data.msg) != 'undefined') {
		if (data.msg == 'ok') {
			//这里使用了id获取元素，有点死板，建议读者自行扩展为css 选择符
			document.getElementById('' + data.id).src = data.src;

		} else {
			//这里图片加载失败，我们可以显示其他图片，防止大红叉
			document.getElementById('' + data.id).src = 'unload.png';
		}
	}
}
        