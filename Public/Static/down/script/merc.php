<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name="format-detection" content="telephone=no">
	<title>商家详情</title>
	<!-- 基础样式及JS -->
	<link rel="stylesheet" href="css/init.css" type="text/css">
</head>
<body class="bg-gra3 loading-bg">
<section class="ui-container pad-a-0 mar-a-0" id="main-content">
	<div class="ui-container pad-a-0 mar-a-0">
		<ul class="ui-list ui-border-tb bg-wh ov-h">
			<li class="pad-r-10 ui-border-t">
				<ul class="ui-list" style="background: none;" tapmode="bg-e5e6e7">
					<li style="margin: 0;">
						<div class="ui-list-img bg-cover-img">
							<img src="" id="group_merc_shop_hearder" style="width: 100px;height: 100px;" class="wd-pre-100 echo"/>
						</div>
						<div class="ui-list-info" style="padding-right: 0;">
							<h5 class="ui-nowrap umh25 ftz16 ftb" id="group_merc_shop_name"></h5>
							<p class="ui-nowrap  umh25">
								<font class="ftz14 umh25" style="color: black;">销量：<font id="group_merc_shop_sales">0</font></font>
							</p>
							<p class="ui-nowrap   umh25">
								<span class="ftz14 umh25" style="color: black;">营业时间：<font id="business_hours"></font></span>
							</p>
							<p class="ui-nowrap ">
								<span class="ftz14 umh25 fll ui-txt-center" tapmode="opa-5" onclick="call_service_tel()" style="color: #ffffff; background: green; width:80px; height: 25px; border-radius: 5px;">联系商家</span>
							</p>
						</div>
						<div class="ov-h pad-t-30" onclick="changeImg()">
							<div class="ov-h ui-txt-center" id="img-0" style="display: block">
								<img src="img/iconfont/iconfont-xihuan01.png" class=" w-20 h-20"/>
							</div>
							<div class="ov-h ui-txt-center" id="img-1" style="display: none;">
								<img src="img/iconfont/ic_like01.png" class=" w-20 h-20"/>
							</div>
							<i class="ftz14">收藏</i>
						</div>
					</li>
					<p class="ui-nowrap   umh40 ui-border-t">
						<font class="ftz14 umh40" style="color: black;">商家地址：<font id="merc_address"></font></font>
					</p>
					<!-- <div class="h-05"></div>-->
				</ul>
			</li>
		</ul>
	</div>
	<div class="pad-t-05 pad-l-025 pad-r-025 pad-b-05 bg-gra3 ov-h" id="merc_category_html">
		<ul class="ui-row bg-gra3" id="all_group"></ul>
	</div>
</section>
<input id="page" value="1"  type="hidden"/>
<input id="is_last_page" value="0"  type="hidden"/>
<input id="service_tel" value=""  type="hidden"/>
<input id="longitude" value=""  type="hidden"/>
<input id="latitude" value=""  type="hidden"/>
</body>
<script src="script/api.js"></script>
<script src="script/zepto.min.js"></script>
<script src="script/zv.js"></script>
<script src="script/apiExt.js"></script>
<script src="script/template.js"></script>
<script>
	//show_top();
	get_merc_category('first');
//	scrollBottom(function() {
//		get_merc_category('next');
//	});
	scroll_bottom(function(){
		get_merc_category('next');
	});

	function call_service_tel() {
		var service_tel = $("#service_tel").val();
		callTel(service_tel);
	}




	function get_merc_category(type) {
		if (type == 'first') {
			var show_page = '1';
			$("#page").val('1');
		}
		if (type == 'next') {
			var show_page = $("#page").val();
			show_page = parseInt(show_page) + 1;
			$("#page").val(show_page);
		}
		var num_per_page = 4;
		var id = "<?=intval($_GET['id'])?>";
		getAll(serverURL,{
				service : 'merc_category',
				act : 'list',
				id : id,
				show_page : show_page,
				num_per_page : num_per_page
			}, function(ret) {
			//标题

			$("#group_merc_shop_name").text(ret.data.merc.merc_name);
			//店铺名称
			$("#group_merc_shop_sales").text(ret.data.merc.sales_volume);
			//销量
			$("#business_hours").text(ret.data.merc.business_hours);
			//营业时间
			$("#merc_address").text(ret.data.merc.merc_address);
			//地址
			$("#service_tel").val(ret.data.merc.service_tel);
			//地址
			$("#longitude").val(ret.data.merc.longitude);
			//地址
			$("#latitude").val(ret.data.merc.latitude);
			//地址


			var src = serverIMG + ret.data.merc.merc_pic + imgStyle250x250;
			$('#group_merc_shop_hearder').attr('src', src);
			var merc_category_str = '';

			//(ret.data.total_count == 0) ? nodata(-1) : nodata(0);
			var imgH = ($(window).width() - 35) / 2;
			for (var i = 0; i < ret.data.list.length; i++) {
				merc_category_str += '<li class="ui-col-50 bg-gra3 ftz12 fll ov-h pad-b-05" tapmode="opa-5" onclick=open_url("goods.php?id='+ret.data.list[i].id+'")>';
				merc_category_str += '<div class="ui-border t-gra2 ov-h bg-wh mar-l-025 mar-r-025">';
				merc_category_str += '<div class="pad-l-05 pad-r-05 pad-t-05 ov-h"  tapmode="bg-e5e6e7">';
				merc_category_str += '<div class="ov-h bg-cover-img">';
				merc_category_str += '<img onerror=this.src="icon/icon150x150.png"  src="' + serverIMG + ret.data.list[i].cover_pic + imgStyle250x250 + '" style="height:' + imgH + 'px" class="wd-pre-100"/>';
				merc_category_str += '</div>';
				merc_category_str += '<div class="umh20 ui-nowrap">';
				merc_category_str += ret.data.list[i].title;
				merc_category_str += '</div>';
				merc_category_str += '<div class="umh20">';
				merc_category_str += '<span class="fll img-mid t-eb5b41 pad-b-05">￥' + ret.data.list[i].price + '</span>';
				merc_category_str += '<span class="flr img-mid t-eb5b41 pad-b-05">销量:' + ret.data.list[i].sales_num + '</span>';
				merc_category_str += '</div>';
				merc_category_str += '</div>';
				merc_category_str += '</div>';
				merc_category_str += '</li>';
			}
			load_page(0);
			load_page_append(ret, "merc_category_html", merc_category_str);
			main_show();


		});
	}

</script>
</html>