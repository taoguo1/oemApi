<form id="pagerForm" method="post" action="#rel#">
	<input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
	<input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
	<input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
	<input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
	<form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('ReportsUser','depositAmount')?>" method="post">
		<div class="searchBar">
			<ul class="searchContent">
                <li>
                    <select name="year">
                        <option value="">选择年份</option>
                        <?php for($i=2012;$i<=date("Y");$i++){?>
                            <option value="<?=$i?>"<?php if(\Core\Lib::request('year')==$i){?> selected<?php }?>><?=$i?>年</option>
                        <?php }?>
                    </select>
                </li>
                <li>
                    <select name="month">
                        <option value="">选择月份</option>
                        <option value="01"<?php if(\Core\Lib::request('month')=="01"){?> selected<?php }?>>1月</option>
                        <option value="02"<?php if(\Core\Lib::request('month')=="02"){?> selected<?php }?>>2月</option>
                        <option value="03"<?php if(\Core\Lib::request('month')=="03"){?> selected<?php }?>>3月</option>
                        <option value="04"<?php if(\Core\Lib::request('month')=="04"){?> selected<?php }?>>4月</option>
                        <option value="05"<?php if(\Core\Lib::request('month')=="05"){?> selected<?php }?>>5月</option>
                        <option value="06"<?php if(\Core\Lib::request('month')=="06"){?> selected<?php }?>>6月</option>
                        <option value="07"<?php if(\Core\Lib::request('month')=="07"){?> selected<?php }?>>7月</option>
                        <option value="08"<?php if(\Core\Lib::request('month')=="08"){?> selected<?php }?>>8月</option>
                        <option value="09"<?php if(\Core\Lib::request('month')=="09"){?> selected<?php }?>>9月</option>
                        <option value="10"<?php if(\Core\Lib::request('month')=="10"){?> selected<?php }?>>10月</option>
                        <option value="11"<?php if(\Core\Lib::request('month')=="11"){?> selected<?php }?>>11月</option>
                        <option value="12"<?php if(\Core\Lib::request('month')=="12"){?> selected<?php }?>>12月</option>
                    </select>
                </li>
                <?php if(empty($s_month)){?>
                <li>
                    <input type="radio" value="week" name="type"<?php if(\Core\Lib::request('month')=='week'){?> checked<?php }?>>按周查看
                    <input type="radio" value="day" name="type"<?php if(\Core\Lib::request('month')=='day'){?> checked<?php }?>>按天查看
                </li>
                <?php }?>
                <li>
                    <input size=25 select_name='keyword' type='text' alt='输入会员手机号或者姓名'>
                    <select name="userid">
                        <option value="">选择会员</option>
                    </select>
                </li>
				<li>
					<div class="buttonActive">
						<div class="buttonContent">
							<button type="submit">查询</button>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<li><a class="add" rel="reload" title="刷新" onclick="navTabPageBreak()"><span>刷新</span></a></li>
		</ul>
	</div>
    <?php
        $leap = \Core\Lib::isLeapYear();
    ?>
	<table class="list" width="100%" layoutH="90">
		<thead>
			<tr>
                <th width="30" align="center">
                    <?=date('Y',time());?>年
                </th>
				<th width="30" align="center">
                    合计
                </th>
                <?php for($i = 1;$i <= 31;$i++){ ?>
                <th width="30" align="center">
                    <?=$i?>日
                </th>
                <?php } ?>
			</tr>
		</thead>
		<tbody>
		    <?php for($j = 1;$j <= 12;$j++){?>
			<tr target="id" rel="<?=$j?>">
                <td align="center"><a href=""><strong style="color:#ff0000;"><?=$j?>月</strong></a></td>
                <td align="center"><?=$j?></td>
                <?php
                for($i = 1;$i <= 31;$i++){
                    if($j == 2) {
                ?>
                    <td align="center">
                        <?php
                        if($leap){
                            if($i <= 29){
                                echo $i*$j;
                            }else{echo '';
                                echo "<div style='width:100%;height:25px;background:#cccccc;'></div>";
                            }
                        }else {
                            if ($i <= 28) {
                                echo $i * $j;
                            }else{
                                echo "<div style='width:100%;height:25px;background:#cccccc;'></div>";
                            }
                        }
                        ?>
                    </td>
                    <?php
                    }else{
                    ?>
                    <td align="center">
                        <?php
                        if(in_array($j,[4,6,9,11])){
                            if($i <= 30){
                                echo $i*$j;
                            }else{echo '';
                                echo "<div style='width:100%;height:25px;background:#cccccc;'></div>";
                            }
                        }else {
                            echo $i * $j;
                        }
                        ?>

                    </td>
                    <?php
                    }
                    ?>
                <?php
                }
                ?>
			</tr>
			<?php }?>

            <tr target="id" rel="<?=$j?>">
                <td align="center"><strong>合计</strong></td>
                <td align="center"><?=$j?></td>
                <?php for($i = 1;$i <= 31;$i++){ ?>
                    <td align="center"><?=$i*$j?></td>
                <?php }?>
            </tr>

		</tbody>
	</table>
	<div class="panelBar">
		<div class="pages">
			<span>合计：0</span>
        </div>
	</div>
</div>
