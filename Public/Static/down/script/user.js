function setPointTypeVal(uid, token, type, callback) {

	api.ajax({
		method : 'POST',
		headers : {
			"application" : "json"
		},
		url : serverURL,
		dataType : 'json',
		returnAll : false,
		data : {
			values : {
				uid : uid,
				service : 'point',
				act : 'type',
				token : token
			}
		}
	}, function(ret, err) {
		if (ret.status == '0') {
			for (var i = 0; i < ret.rows.length; i++) {
				if (ret.rows[i].type_name == type) {
					if (isDefine(ret.rows[i].point)) {
						pointAdd(uid, token, ret.rows[i].point, callback);
					}
				}
			}
		} else {
			$toast("获取积分信息失败！", 2000);
		}
	});

}

function pointAdd(uid, token, point, callback) {
	var user_id = localStorage.getItem("user_id");
	api.ajax({
		method : 'POST',
		headers : {
			"application" : "json"
		},
		url : serverURL,
		dataType : 'json',
		returnAll : false,
		data : {
			values : {
				uid : uid,
				service : 'point',
				act : 'add',
				token : token,
				user_id : user_id,
				point : point
			}
		}
	}, function(ret, err) {
		if (ret.status == '0') {
			if (callback) {
				callback(ret, point);
			}
		} else {
		}
	});
}

function getPoint(callback) {
	var user_id = getLocal("user_id");
	var uid = getLocal("uid");
	var token = getLocal("token");
	var data = "{values:{service:'point',act:'query',user_id:'" + user_id + "',uid:'" + uid + "',token:'" + token + "'}}";
	getAll(serverURL, strToJson(data), function(ret) {
		callback(ret.point);
	});
}

function login(tel, password, callback) {
	var deviceId = api.deviceId;
	deviceId = deviceId.replace(/\-/g, "");
	var data = "{values:{service:'user.public',act:'login',uid:'" + tel + "',pwd :'" + password + "',deviceId:'" + deviceId + "'}}";
	getAll(serverURL, strToJson(data), function(ret) {
		if (ret.status == 1) {
			$("#loginBtn").css("display", "block");
			$("#loginBtnIng").css("display", "none");

			$toast("账号或密码不正确", 2000);
			document.getElementById("pwd").value = "";
			return false;
		} else {
			//alert(json2str(ret))
			//登录成功
			setLocal("user_id", ret.row.ID);
			setLocal("uid", tel);
			setLocal("user_tel", tel);
			setLocal("token", ret.token);
			setLocal("realName",ret.row.realName);
			setLocal("postCard",ret.row.postCard);
			setLocal("last_login_time", ret.row.last_login_time);
			callback(ret);
		}
	});
}

function isLogin() {
	var uid = getLocal("uid");
	var token = getLocal("token");
	//判断当是否登录
	var data = "{values:{service:'user.public',act:'checkToken',uid:'" + uid + "',token :'" + token + "'}}";
	getAll(serverURL, strToJson(data), function(ret) {
		api.refreshHeaderLoadDone();
		if (parseInt(ret.isHave) == 0) {
			//没有登录
			setLocal("user_id", "");
			setLocal("uid", "");
			setLocal("token", "");
		} else {
			getMyInfo();
			var face = getLocal("user_pic");
			var uid = getLocal("uid");
			$("#tel").text(uid);
			if (isDefine(face) && face != "0") {
				$("#face").attr("src", serverFace + "" + face);
			}
			getPoint(function(point) {
				$("#point").text(point + "积分");
				$("#noLogin").hide();
				$("#loginSucess").show();

				if (isDefine(point)) {
					$("#point").text(point + "积分");
				}
				if (point >= 800) {
					$api.attr($api.byId("pointImg"), "src", "../img/z.png");
				} else if (point >= 500 && point < 800) {
					$api.attr($api.byId("pointImg"), "src", "../img/y.png");
				} else {
					$api.attr($api.byId("pointImg"), "src", "../img/y.png");
				}

				//alert($api.attr($api.byId("pointImg"), "src"));

			});
		}
	});
}

function isToken(callback) {
	var uid = getLocal("uid");
	var token = getLocal("token");
	var tel = getLocal("tel");
	//判断当是否登录
	//Loading(0);
	var data = "{values:{service:'user.public',act:'checkToken',tel:'" + tel + "',uid:'" + uid + "',token :'" + token + "'}}";
	getAll(serverURL, strToJson(data), function(ret) {
		//alert(json2str(ret))
	//	alert(json2str(ret))
		if (ret.isHave == 1) {
			//getMyInfo();
		}
		//Loading(-1);
		callback(parseInt(ret.isHave));
	});
}
function Loading1(time) {
	var y = api.pageParam.headerH
	api.openFrame({
		name : 'loading',
		url : 'common/loading.html',
		rect : {
			x : 0,
			y : y,
			w : 'auto',
			h : api.frameHeight
		},
		bounces : false,
		bgColor : 'rgba(0,0,0,0)',
		vScrollBarEnabled : true,
		hScrollBarEnabled : true,
		reload : false
	});

	if (parseInt(time) < 0) {
		//$api.css($api.byId('cusloading'),"display:block");
		$api.css($api.byId('main-content'), "visibility:visible");
		api.closeFrame({
			name : 'loading'
		});
	}
	if (parseInt(time) == 0) {

		//$api.css($api.byId('cusloading'),"visibility:visible");
	}
	if (parseInt(time) > 0) {

		//		$api.css($api.byId('cusloading'),"visibility:visible");
		setTimeout(function() {
			$api.css($api.byId('main-content'), "visibility:visible");

			api.closeFrame({
				name : 'loading'
			});
		}, time);
	}
}
function logout(callback) {

	var uid = getLocal("uid");
	var token = getLocal("token");
	var data = "{values:{service:'user.public',act:'logout',uid:'" + uid + "',token :'" + token + "'}}";
	getAll(serverURL, strToJson(data), function(ret) {
		setLocal("user_id","");
		setLocal("uid","");
		setLocal("token","");
		setLocal("last_login_time","");
		callback(ret);
	});
}

//分享
function zv_share() {
	api.actionSheet({
		title : '分享',
		cancelTitle : '关闭',
		//destructiveTitle : '红色警告按钮',
		buttons : ['微信朋友圈', '微信好友', 'QQ空间', 'QQ好友']
	}, function(ret, err) {
		if (ret.buttonIndex == 1) {
			share_wxh();
		}
		if (ret.buttonIndex == 2) {
			share_wxp();
		}
		if (ret.buttonIndex == 3) {
			share_qqk();
		}
		if (ret.buttonIndex == 4) {
			share_qqh();
		}
		//		if (ret.buttonIndex == 5) {
		//			share_sina();
		//		}
	});
}

//分享开始
function share_sina()//分享到新浪
{
	//openWin('share', '../share/share.html');
	api.openWin({
		name : 'share',
		url : '../share/share.html',
	});
}

function share_qqk() {//qq空间
	qq = api.require('qq');
	qq.installed(function(ret, err) {
		if (ret.status) {
			Loading(0);
			qq.shareNews({
				url : "http://app.dldtc.com/",
				title : '店老大同城',
				description : '店老大同城app',
				imgUrl : serverIMG + 'APPLOGO.png',
				type : 'QZone'
			}, function(ret, err) {
				if (ret.status) {
					$toast('分享成功', 5000);
					share_commons("3");
				} else {

				}
			});
			Loading(-1);
		} else {
			$toast('当前设备未安装qq', 5000);
		}
	});
}

function share_commons(type) {
	isToken(function(isHave) {
		if (parseInt(isHave) == 1) {
			//积分增加
			var uid = localStorage.getItem("uid");
			var token = localStorage.getItem("token");
			var user_id = localStorage.getItem("user_id");
			api.ajax({
				method : 'POST',
				headers : {
					"application" : "json"
				},
				url : serverURL,
				dataType : 'json',
				returnAll : false,
				data : {
					values : {
						uid : uid,
						service : 'point',
						act : 'share',
						token : token,
						user_id : user_id,
						type : type
					}
				}
			}, function(ret, err) {

				//$toast(ret.msg, 2000);
			});
		} else {
			//$toast("分享成功！", 2000);
		}
	});
}

function share_qqh() {//qq好友
	qq = api.require('qq');
	qq.installed(function(ret, err) {
		if (ret.status) {
			Loading(0);
			qq.shareNews({
				url : "http://app.dldtc.com/",
				title : '店老大同城',
				description : '店老大同城app',
				imgUrl : 'widget://icon/a.jpg',
				type : 'QFriend'
			}, function(ret, err) {
				if (ret.status) {
					$toast('分享成功', 2000);
					share_commons("4");
				} else {
					$toast('分享失败', 5000);
				}
			});
			Loading(-1);
		} else {
			$toast('当前设备未安装qq', 5000);
		}
	});
}

function share_wxp() {//朋友圈
	wx = api.require('wx');
	wx.isInstalled(function(ret, err) {
		if (ret.installed) {
			Loading(0);
			wx.shareWebpage({
				apiKey : 'wx63f5557d441d5f8d',
				scene : 'session',
				title : '店老大同城',
				description : '店老大同城app',
				thumb : 'widget://icon/a.jpg',
				contentUrl : "http://app.dldtc.com/"
			}, function(ret, err) {
				if (ret.status) {
					$toast('分享成功', 2000);

					share_commons("1");

				} else {
					//$toast('分享失败', 5000);
				}
			});
			Loading(-1);
		} else {
			$toast('当前设备未安装微信', 5000);
		}
	});
}

function share_wxh() {//微信好友
	wx = api.require('wx');
	wx.isInstalled(function(ret, err) {
		if (ret.installed) {
			Loading(0);
			wx.shareWebpage({
				apiKey : 'wx63f5557d441d5f8d',
				scene : 'timeline',
				title : '店老大同城',
				description : '店老大同城app',
				thumb : 'widget://icon/a.jpg',
				contentUrl : "http://app.dldtc.com/"
			}, function(ret, err) {
				if (ret.status) {
					$toast('分享成功', 2000);

					share_commons("2");
				} else {
					//$toast('分享失败', 5000);
				}
			});
			Loading(-1);
		} else {
			$toast('当前设备未安装微信', 5000);
		}
	});
}

//获取用户信息
function getMyInfo(callback) {
	var data = "{values : {uid :'" + getLocal("uid") + "',service:'user.public',act:'userInfo',token:'" + getLocal("token") + "'}}";
	getAll(serverURL, strToJson(data), function(ret) {
		var user_id = ret.rows.ID;
		if (isDefine(user_id)) {
			setLocal("user_id", ret.rows.ID);
		}
		if (callback) {
			callback(ret);
		}
	});
}

//每日签到
function sign() {

	isToken(function(isHave) {
		if (isHave == 1) {
			var data = "{values : {uid :'" + getLocal("uid") + "',service:'user',act:'sign',user_id:'" + getLocal("user_id") + "',token:'" + getLocal("token") + "'}}";
			getAll(serverURL, strToJson(data), function(ret) {
				$toast(ret.msg, 3000);
				exScriptFrame("root", "my", "isLogin()");
			});
		} else {
			openWin("widget://my/login");
		}
	});

}

//判断登陆
//function user_login(url) {
//	var uid = getLocal("uid");
//	if (!isDefine(uid)) {
//		setTimeout(function() {
//			openWin('../register/login');
//		}, 300);
//		return false;
//	} else {
//		openWin(url)
//	}
//}