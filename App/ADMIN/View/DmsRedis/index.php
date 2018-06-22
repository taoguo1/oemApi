<?php
    $appid = \Core\Lib::request('appid');
?>
<style type="text/css">
    ul.rightTools {float:right; display:block;}
    ul.rightTools li{float:left; display:block; margin-left:5px}
</style>
<div class="pageContent" style="padding:5px;">
    <div class="divider"></div>
    <div class="tabs">
        <div class="tabsHeader">
            <div class="tabsHeaderContent">
                <ul>
                    <li><a href="javascript:;"><span>Token</span></a></li>
                    <li><a href="javascript:;"><span>Msg</span></a></li>
                    <li><a href="javascript:;"><span>计划-plan</span></a></li>

                </ul>
            </div>
        </div>
        <div class="tabsContent">
            <div>
                <div layoutH="52" style="float:left; display:block; overflow:auto; width:480px; border:solid 1px #CCC; line-height:21px; background:#fff">
                    <ul class="tree treeFolder">
                        <li><a href="javascript">Token Key</a>
                            <ul>
                                <?php
                                    foreach ($tokenList as $k=>$v ) {
                                 ?>
                                <li><a href="<?=\Core\Lib::getUrl('DmsRedis', 'redisList','keys='.$v."&type=token");?>" target="ajax" rel="token"><?=$v?></a></li>
                                <?php
                                    }
                                ?>

                            </ul>
                        </li>

                    </ul>
                </div>
                <div id="token" class="unitBox" style="margin-left:246px;">
                    <!--#include virtual="list1.html" -->
                </div>
            </div>

            <div>

                <div layoutH="52" style="float:left; display:block; overflow:auto; width:480px; border:solid 1px #CCC; line-height:21px; background:#fff ">
                    <ul class="tree treeFolder">
                        <li><a href="javascript">Msg Key</a>
                            <ul>
                                <?php
                                foreach ($msgList as $k=>$v ) {
                                    ?>
                                    <li><a href="<?=\Core\Lib::getUrl('DmsRedis', 'redisList','keys='.$v."&type=msg");?>" target="ajax" rel="msg"><?=$v?></a></li>
                                    <?php
                                }
                                ?>

                            </ul>
                        </li>

                    </ul>
                </div>

                <div id="msg" class="unitBox" style="margin-left:246px;">
                    <!--#include virtual="list1.html" -->
                </div>

            </div>
            <div>

                <div layoutH="52" style="float:left; display:block; overflow:auto; width:480px; border:solid 1px #CCC; line-height:21px; background:#fff">
                    <ul class="tree treeFolder">
                        <li><a href="javascript">Plan Key</a>
                            <ul>
                                <?php
                                foreach ($planList as $k=>$v ) {
                                    ?>
                                    <li>
                                        <a href="<?=\Core\Lib::getUrl('DmsRedis', 'redisList','keys='.$v."&type=plan");?>" target="ajax" rel="plan"><?php
                                            if(strpos($v,":")) {
                                                echo $v;
                                            } else {
                                                echo $v;
                                            }
                                            ?>
                                        </a>
                                    </li>
                                    <?php
                                }
                                ?>

                            </ul>
                        </li>

                    </ul>
                </div>

                <div id="plan" class="unitBox" style="margin-left:246px;">
                    <!--#include virtual="list1.html" -->
                </div>

            </div>
        </div>
        <div class="tabsFooter">
            <div class="tabsFooterContent"></div>
        </div>
    </div>

</div>




