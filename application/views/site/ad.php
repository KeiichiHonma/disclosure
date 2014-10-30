<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner"><?php $this->load->view('layout/common/topicpath'); ?>
        <h3 class="l1"><?php echo $this->lang->line('common_title_ad'); ?></h3>
        <div id="site">
            オープンデータ.companyへの広告掲載のご相談は下記お問い合わせ先までお問い合わせください。 <br />
            広告に関するお問い合わせは <img src="/images/ad_mail.gif" alt="" style="vertical-align: middle;" /> までお気軽にお問い合わせください。 
        </div>
        <div id="sidebar">
            <div id="side_cat">
                <?php $this->load->view('layout/common/categories'); ?>
            </div><!-- /side_cat -->
            <div class="box_wrap">
                <div class="box_adx pcdisp">
                    <img src="/images/ad_example1.gif" alt="" />
                </div>
            </div>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
