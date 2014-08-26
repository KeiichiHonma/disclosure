<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $document->presenter_name.' - '.strftime($this->lang->line('setting_date_format'), strtotime($document->date)).' '.$document->document_name; ?></h1>
        <div class="box_adx mb20 spdisp">
            <img src="/images/ad_example_sp1.jpg" alt="" />
        </div>
        <div id="document_html" style="padding:5px !important;">
            <?php $this->load->view('layout/common/document_sns'); ?>
            <?php echo $document_htmls->html_data; ?>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/document_side'); ?>
            <div class="side_list">
                <h3 class="side_title">開示情報 カテゴリー</h3>
                <ul>
                <?php foreach ($categories as $category) : ?>
                <li><a href="http://liginc.co.jp/news"><span><?php echo $category->name; ?></span></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="box_wrap">
                <div class="box_adx pcdisp">
                    <img src="/images/ad_example1.gif" alt="" />
                </div>
                <div class="box_adx spdisp">
                    <img src="/images/ad_example_sp1.jpg" alt="" />
                </div>
            </div>
        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
