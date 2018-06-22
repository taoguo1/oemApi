<form id="pagerForm" method="post" action="#rel#">
    <input type="hidden" name="pageNum" value="<?=$data['pageNum']?>" /> 
    <input type="hidden" name="numPerPage" value="<?=$data['numPerPage']?>" /> 
    <input type="hidden" name="orderField" value="<?=$data['orderField']?>" />
    <input type="hidden" name="orderDirection" value="<?=$data['orderDirection']?>" />
</form>
<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="<?=\Core\Lib::getUrl('ReportsUserVQ','index')?>" method="post">
        <div class="searchBar">
            <table class="searchContent">
                <tr>
                    <td>
                        <select class="combox" name="year">
                            <option value="">选择年份</option>
                            <?php for($i=2017;$i<=date("Y");$i++){?>
                                <option value="<?=$i?>"<?php if(\Core\Lib::request('year')==$i){?> selected<?php }?>><?=$i?>年</option>
                            <?php }?>
                        </select>
                    </td>
                    <td>
                        
                        <select class="combox" name="month">
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
                    </td>
                    <?php if(empty(\Core\Lib::request('month'))){?>
                    <td>
                        <input type="radio" value="week" name="type" <?php if(\Core\Lib::request('type')=='week'){?> checked<?php }?>>按周查看
                        <input type="radio" value="day" name="type" <?php if(\Core\Lib::request('type')=='day' || \Core\Lib::request('type')==''){?> checked<?php }?>>按天查看
                    </td>
                    <?php }?>
                    <td>
                        <input type="text" disabled  class="" name="users.real_name" value="<?php echo $real_name; ?>" lookupGroup="users" />
                        <input type="hidden"  class="" name="users.id" value="" lookupGroup="users" />
                        <a class="btnLook" href="<?php echo \Core\Lib::getUrl('Bill','users');?>" lookupGroup="users">选择用户</a>

                    </td>
                    <td>
                        <div class="buttonActive">
                            <div class="buttonContent">
                                <button type="submit">查询</button>
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
            <li><a class="add" rel="reload" title="刷新" onclick="navTabPageBreak()"><span>刷新</span></a></li>
        </ul>
    </div>

    <?php
        if(\Core\Lib::request('month')){
    ?>
    
    <table class="list" width="100%" layoutH="85">
        <thead>
            <tr>
                <th width="30" align="center">
                    <?php if(\Core\Lib::request('year')==''){ echo date('Y').'年';?>
                
                     <?php  }else{ ?>
                        <?php echo \Core\Lib::request('year').'年';?>
                </th>
                <?php } ?>
                </th>
                <th width="30" align="center">
                    合计
                </th>
                <?php for($i = 0;$i <= 23;$i++){ ?>
                <th width="30" align="center">
                    <?=$i?>时
                </th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php for($j = 1;$j <= \Core\Lib::getMonthLastDay(\Core\Lib::request('year'),\Core\Lib::request('month'));$j++){?>
            <tr target="id" rel="<?=$j?>">
                <td align="center"><a href=""><strong style="color:#ff0000;"><?=$j?>日</strong></a></td>
                <td align="center"><?php
                         echo $day_data1[$j]['sum'];
                     ?></td>
                <?php

                for($i = 0;$i <= 23;$i++){
                ?>
                <td align="center">
                    <?php
                         echo $day_data1[$j][$i];
                     ?>
                </td>
                <?php
                }
                ?>
            </tr>
            <?php }?>

            <tr target="id" rel="<?=$j?>">
                <td align="center"><strong>合计</strong></td>
                <td align="center"><?=$total1?></td>
                <?php for($i = 0;$i <= 23;$i++){ ?>
                    <td align="center"><?=$sum1[$i]?></td>
                <?php }?>
            </tr>

        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>合计：<?=$total1?></span>
        </div>
    </div>
    <?php
        }else{
    ?>
    <table class="list" width="100%" layoutH="85">
        <thead>
        <tr>
            <th width="30" align="center">
                <?=date('Y',time());?>年
            </th>
            <th width="30" align="center">
                合计
            </th>
            <?php if(\Core\Lib::request('type') == 'week'){for($i = 0;$i <= 6;$i++){ ?>
                <th width="30" align="center">
                    <?=\Core\Lib::getWeek($i)?>
                </th>
            <?php } }else{for($i = 1;$i <= 31;$i++){ ?>
                <th width="30" align="center">
                    <?=$i?>日
                </th>
            <?php } } ?>
        </tr>
        </thead>
        <tbody>
       <?php for($j = 1;$j <= 12;$j++){?>
            <tr target="id" rel="<?=$j?>">
                <td align="center"><a href=""><strong style="color:#ff0000;"><?=$j?>月</strong></a></td>
                <td align="center"><?php echo $day_data[$j]['sum']; ?></td>
                <?php
                if(\Core\Lib::request('type') == 'week') {
                    for ($i = 0; $i <= 6; $i++) {
                        ?>
                        <td align="center">
                            <?php
                            echo $day_data[$j][$i];
                            ?>
                        </td>
                        <?php
                    }
                }else{for ($i = 1; $i <=count($day_data[$j])-1 ; $i++) {
                ?>
                    <td align="center">
                        <?php
                        echo $day_data[$j][$i];
                        ?>
                    </td>
                <?php } } ?>
            </tr>
        <?php }?>

        <tr target="id" rel="<?=$j?>">
            <td align="center"><strong>合计</strong></td>
            <td align="center"><?=$total?></td>
            <?php if(\Core\Lib::request('type') == 'week'){for($i =0;$i <=6;$i++){ ?>
                <td align="center"><?=$weekMoney[$i] ?></td>
            <?php }}else{for ($i = 1; $i <= 31; $i++) {?>
                <td align="center"><?=$sum[$i] ?></td>
            <?php } } ?>
        </tr>

        </tbody>
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>合计：<?=$total?></span>
        </div>
    </div>
    <?php
    }
    ?>
</div>
