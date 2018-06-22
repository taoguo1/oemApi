function wxPay(ret, callback) {
	var wxPay = api.require('wxPay');
	var apiKey = ret.appid;
	var noncestr = ret.noncestr;
	var _package = ret.package;
	var partnerid = ret.partnerid;
	var prepayid = ret.prepayid;
	var timestamp = ret.timestamp;
	var sign = ret.sign;
	wxPay.payOrder({
		apiKey : apiKey,
		orderId : prepayid,
		mchId : partnerid,
		nonceStr : noncestr,
		timeStamp : timestamp,
		package : _package,
		sign : sign
	}, function(ret, err) {
		if (callback) {
			callback(ret);
		}
	});
}

function aliPay(ret,callback) {
	var obj = api.require('aliPay');
	obj.pay({
		subject : ret.subject,
		body : ret.body,
		amount : ret.money,
		tradeNO : ret.order_sn
	}, function(ret, err) {
		if (callback) {
			callback(ret);
		}
	});
}