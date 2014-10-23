<?php $this->load->view('layout/header/header'); ?>
<script>
    $(window).load(function(){
      $("#sticker").sticky({ topSpacing: 45, center:true, className:"hey" });
    });
</script>
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
        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
