<?php $this->load->view('layout/header/header'); ?>

<script>
    $(document).ready(function(){
        $(".ajax").colorbox({open:true});
    });
</script>
<a class='ajax' href="<?php echo force_lang_base_url('site/pre'); ?>" title="Copyright © 2013 RyDEEN Co., Ltd. All Rights Reserved"></a>
<script type="text/javascript">
$('#contents').corner("round 8px").parent().css('padding', '4px').corner("round 10px");
$('#page h1').corner("6px");
</script>
<?php $this->load->view('layout/common/topicpath'); ?>
            <div id="contents">
                <div id="contents_inner">
                    <!-- ■ MAIN CONTENTS ■ -->
                    <div id="page">
                        <div class="c_wrapper">
                            <h1 class="l1"><?php echo $this->lang->line('common_title_rule'); ?></h1>
                            <p><?php echo nl2br($this->lang->line('rule_text')); ?></p>
                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
