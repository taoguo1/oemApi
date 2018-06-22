<div class="pageContent">
    <form method="post"
          action="<?php echo \Core\Lib::getUrl('AppConfig','index');?>"
          class="pageForm required-validate"
          onsubmit="return validateCallback(this, dialogAjaxDone);">
        <input type="hidden" name="act" value="edit">

        <div class="pageFormContent" layoutH="56">
            <div class="tabs" currentIndex="0" eventType="click">
                <div class="tabsHeader">
                    <div class="tabsHeaderContent">
                        <ul>
                            <li><a href="javascript:;"><span>基本配置</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tabsContent">
                    <div>
                        <div class="unit">
                            <dl>
                                <dt>客服QQ：</dt>
                                <dd>
                                    <input type="text" size="50" name="custom_qq" class="required" placeholder="多个qq用'|'分开，如(123|456|789)" value="<?php echo $list['custom_qq']; ?>" />

                                    <select class="required" name="custom_qq_status" style="margin-left: 10px;">

                                        <option <?php if($list['custom_qq_status']==1){echo 'selected';}?> value='1' >开启</option>
                                        <option <?php if($list['custom_qq_status']==0){echo 'selected';}?> value='0'>关闭</option>
                                    </select>
                                </dd>
                            </dl>
                        </div>
                        <div class="divider"></div>
                        <div class="unit">
                            <dl>
                                <dt>客服电话：</dt>
                                <dd>
                                    <input type="text" size="50" name="custom_mobile" class="required" placeholder="多个电话用'|'分开，如(123|456|789)" value="<?php echo $list['custom_mobile']; ?>" />

                                    <select class="required" name="custom_mobile_status" style="margin-left: 10px;">

                                        <option <?php if($list['custom_mobile_status']==1){echo 'selected';}?> value='1' >开启</option>
                                        <option <?php if($list['custom_mobile_status']==0){echo 'selected';}?> value='0'>关闭</option>
                                    </select>
                                </dd>
                            </dl>
                        </div>
                        <div class="divider"></div>

                        <div class="unit" style="display:none">
                            <dl>
                                <dt>签到红包：</dt>
                                <dd>

                                    <select class="required" name="redbag_status">

                                        <option <?php if($list['redbag_status']==1){echo 'selected';}?> value='1' >启用签到送红包</option>
                                        <option <?php if($list['redbag_status']==0){echo 'selected';}?> value='0'>关闭签到送红包</option>
                                    </select>
                                </dd>
                            </dl>
                        </div>
                       <!--  <div class="divider"></div> -->
                        <!--<div class="unit">
                            <table cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <label>关注我们：</label>
                                        <input type="text" id="contact_us" size="70" name="contact_us" onclick="upload_file('<?php echo \Core\Lib::getUrl('upload','index','path=contact_us')?>')" value="<?php echo $list['contact_us']; ?>" />
                                        <span style="line-height: 24px; padding-left: 10px;" onclick="$('#contact_us').val('');$('#icon_img').attr('src','')">清除</span>
                                    </td>
                                    <td width="60" align="center">
                                        <img src="<?php echo OSS_ENDDOMAIN.'/'.$list['contact_us'];?>" id="icon_img" onerror="javascript:this.src='<?=APP_ADMIN_STATIC?>image/no_pic.png';" width="30" />
                                    </td>
                                </tr>
                            </table>
                        </div>-->


                        <div class="divider"></div>
                        <div class="unit">
                            <dl>
                                <dt>办理信用卡连接：</dt>
                                <dd>
                                    <input type="text" size="90" name="debit_card_url" class="required" value="<?php echo $list['debit_card_url']; ?>" />


                                </dd>
                            </dl>
                        </div>
                        <!-- <div class="divider"></div> -->

                        <div class="unit" style="display:none">
                            <dl>
                                <dt>保险链接：</dt>
                                <dd>
                                    <input type="text" size="90" name="credit_card_url" class="required" value="<?php echo $list['credit_card_url']; ?>" />


                                </dd>
                            </dl>
                        </div>
                        <!-- <div class="divider"></div> -->

                        <div class="unit" style="display:none">
                            <dl>
                                <dt>小额贷款链接：</dt>
                                <dd>
                                    <input type="text" size="90" name="loan_url" class="required" value="<?php echo $list['loan_url']; ?>" />


                                </dd>
                            </dl>
                        </div>



                    </div>

                    <!-- 					<div>tab2</div> -->
                </div>
                <div class="tabsFooter">
                    <div class="tabsFooterContent"></div>
                </div>
            </div>
            <div style="height: 10px;"></div>

            <div class="tabs" currentIndex="0" eventType="click">
                <div class="tabsHeader">
                    <div class="tabsHeaderContent">
                        <ul>
                            <li><a href="javascript:;"><span>用户指南</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tabsContent">
                    <div>
                        <div class="unit nowrap">
                            <dl>
                                <dd>
                                    <textarea class="editor" name="contact_us" rows="20" cols="120"
                                              upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx"
                                              upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorNew','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp"
                                              upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
                                              upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"><?php echo $list['contact_us']?></textarea>

                                </dd>
                            </dl>
                        </div>
                    </div>

                </div>
                <div class="tabsFooter">
                    <div class="tabsFooterContent"></div>
                </div>
            </div>
            <div style="height: 10px;"></div>

            <div class="tabs" currentIndex="0" eventType="click">
                <div class="tabsHeader">
                    <div class="tabsHeaderContent">
                        <ul>
                            <li><a href="javascript:;"><span>用户须知</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tabsContent">
                    <div>
                        <div class="unit nowrap">
                            <dl>
                                <dd>
                                    <textarea class="editor" name="user_notice" rows="20" cols="120"
                                              upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx"
                                              upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorNew','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp"
                                              upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
                                              upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"><?php echo $list['user_notice']?></textarea>

                                </dd>
                            </dl>
                        </div>
                    </div>

                </div>
                <div class="tabsFooter">
                    <div class="tabsFooterContent"></div>
                </div>
            </div>

            <div style="height: 10px;"></div>

            <div class="tabs" currentIndex="0" eventType="click">
                <div class="tabsHeader">
                    <div class="tabsHeaderContent">
                        <ul>
                            <li><a href="javascript:;"><span>常见问题</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tabsContent">
                    <div>
                        <div class="unit nowrap">
                            <dl>
                                <dd>
                                    <textarea class="editor" name="common_problem" rows="20" cols="120"
                                              upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx"
                                              upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorNew','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp"
                                              upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
                                              upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"><?php echo $list['common_problem']?></textarea>

                                </dd>
                            </dl>
                        </div>
                    </div>

                </div>
                <div class="tabsFooter">
                    <div class="tabsFooterContent"></div>
                </div>
            </div>

            <div style="height: 10px;"></div>

            <div class="tabs" currentIndex="0" eventType="click">
                <div class="tabsHeader">
                    <div class="tabsHeaderContent">
                        <ul>
                            <li><a href="javascript:;"><span>注册协议</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="tabsContent">
                    <div>
                        <div class="unit nowrap">
                            <dl>
                                <dd>
                                    <textarea class="editor" name="registration_agreement" rows="20" cols="120"
                                              upLinkUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorFile','path=ArticleEditorFile')?>" upLinkExt="zip,rar,txt,pdf,ppt,doc,docx,xls,xlsx,pptx"
                                              upImgUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorNew','path=ArticleEditor')?>" upImgExt="jpg,jpeg,gif,png,bmp"
                                              upFlashUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorSwf','path=ArticleEditorSwf')?>" upFlashExt="swf"
                                              upMediaUrl="<?php echo  \Core\Lib::getUrl('Upload','uploadEditorMedia','path=ArticleEditorMedia')?>" upMediaExt:"avi,mp4"><?php echo $list['registration_agreement']?></textarea>

                                </dd>
                            </dl>
                        </div>
                    </div>

                </div>
                <div class="tabsFooter">
                    <div class="tabsFooterContent"></div>
                </div>
            </div>



        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">保存</button>
                        </div>
                    </div></li>

            </ul>
        </div>

    </form>
</div>
