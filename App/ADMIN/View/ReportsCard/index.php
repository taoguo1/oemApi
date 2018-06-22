<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" />
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" />
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<style>
.cardtypeclick{
	padding-left: 20px;
}
.tdcombox{

}
</style>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('ReportsCard','index')?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tr>

                        <td class="cardtypeclick">
                            <input type="radio" value="year" name="type"<?php if(\Core\Lib::request('type')=='year' || \Core\Lib::request('type')==''){?> checked<?php }?>>按年统计
                            <input type="radio" value="month" name="type"<?php if(\Core\Lib::request('type')=='month'){?> checked<?php }?>>按月统计
                        </td>

                    <td class="tdcombox">
                        <select class="combox" name="year">
                            <option value="2018">2018年</option>
                            <?php for($i=2018;$i<date("Y");$i++){?>
                                <option value="<?=$i?>"<?php if(\Core\Lib::request('year')==$i){?> selected <?php }?>><?=$i?>年</option>
                            <?php }?>
                        </select>
                    </td>
					
                    <td class="tdcombox cardnummonth" <?php if(\Core\Lib::request('type')!='month'){?> style="display:none;"<?php }?> >
                        <select class="combox" name="month">
                            <option value="1"<?php if(\Core\Lib::request('month')=="1"){?> selected<?php }?>>1月</option>
                            <option value="2"<?php if(\Core\Lib::request('month')=="2"){?> selected<?php }?>>2月</option>
                            <option value="3"<?php if(\Core\Lib::request('month')=="3"){?> selected<?php }?>>3月</option>
                            <option value="4"<?php if(\Core\Lib::request('month')=="4"){?> selected<?php }?>>4月</option>
                            <option value="5"<?php if(\Core\Lib::request('month')=="5"){?> selected<?php }?>>5月</option>
                            <option value="6"<?php if(\Core\Lib::request('month')=="6"){?> selected<?php }?>>6月</option>
                            <option value="7"<?php if(\Core\Lib::request('month')=="7"){?> selected<?php }?>>7月</option>
                            <option value="8"<?php if(\Core\Lib::request('month')=="8"){?> selected<?php }?>>8月</option>
                            <option value="9"<?php if(\Core\Lib::request('month')=="9"){?> selected<?php }?>>9月</option>
                            <option value="10"<?php if(\Core\Lib::request('month')=="10"){?> selected<?php }?>>10月</option>
                            <option value="11"<?php if(\Core\Lib::request('month')=="11"){?> selected<?php }?>>11月</option>
                            <option value="12"<?php if(\Core\Lib::request('month')=="12"){?> selected<?php }?>>12月</option>
                        </select>
                    </td>
					<!-- <td class="tdcombox">
                        <select class="combox" name="card_type">
                            <option value="">请选择卡片类型</option>
                            <option value="1" <?php if(\Core\Lib::request('card_type')=="1"){?> selected<?php }?>>信用卡</option>
							<option value="2" <?php if(\Core\Lib::request('card_type')=="2"){?> selected<?php }?>>储蓄卡</option>
                        </select>
                    </td> -->
                    <input type="hidden"   name="card_type" value="1" />
                  
                    <td>
                        <label>用户：</label>
                        <input type="text"  class="user" style="" name="user.real_name" value="<?=\Core\Lib::request('user_real_name')?>" placeholder="全部" lookupGroup="user" readonly/>
                        <input type="hidden"  class="user" name="user.id" value="<?=\Core\Lib::request('user_id')?>" lookupGroup="user" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('UserAccount','getUserList');?>" lookupGroup="user">选择用户</a>
                        &nbsp;
                        <a title="删除"  href="javascript:void(0)" onclick="$('.user').val('')" class="btnDel" style="float: right">删除</a>
                    </td>
                    <td>
                        <div class="buttonActive">
                            <div class="buttonContent">
                                <button type="submit">开始统计</button>
                            </div>
                        </div>
                    </td>

                </tr>
            </table>

        </div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">
            <li><a class="icon" rel="reload" title="刷新" onclick="navTabPageBreak()"><span>刷新</span></a></li>
            
        </ul>
    </div>

 <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="cardmain" style="width: 90%;height:500px; margin-top:80px;"></div>
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('cardmain'));

        // 指定图表的配置项和数据
        var option = {
            title: {
                text: '合计：<?php echo $datasum;?>张',
				left:'110px;',
				textStyle: {
					fontSize: 16,
					fontWeight: 'bolder',
					color: '#f00'          // 主标题文字颜色
				}
            },
			tooltip : {
				trigger: 'axis',
				axisPointer: {
					type: 'shadow',
					label: {
						show: true
					}
				}
			},
			toolbox: {
				show : true,
				feature : {
					mark : {show: true},
					dataView : {show: true, readOnly: false},
					magicType: {show: true, type: ['line', 'bar']},
					restore : {show: true},
					saveAsImage : {show: true}
				}
			},
            legend: {
                data:['绑定卡片']
            },
            xAxis: {
                data: <?php echo $xAxis;?>
            },
            yAxis: {},
            series: [{
                name: '绑定卡片',
                type: 'bar',
                data: <?php echo $data;?>,
				color:'rgb(124, 181, 236)'
            }]
        };

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
    </script>
        

</div>
   <script type="text/javascript">
   $(".cardtypeclick").click(function(){
	   
	    var val=$(this).find('input:radio[name="type"]:checked').val();
		if(val=="month"){
			$(".cardnummonth").show();
		}else{
			$(".cardnummonth").hide();
		}
   })
   </script>


