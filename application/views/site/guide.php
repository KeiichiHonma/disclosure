<?php $this->load->view('layout/header/header'); ?>

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
                            <h1 class="l1"><?php echo $this->lang->line('common_title_guide'); ?></h1>

                            <h2 class="l2"><span><?php echo $this->lang->line('guide_title_1'); ?></span></h2>
                            <p><?php echo nl2br($this->lang->line('guide_text_1')); ?></p>
                            <h2 class="l2"><span><?php echo $this->lang->line('guide_title_2'); ?></span></h2>
                            <p><?php echo nl2br($this->lang->line('guide_text_2')); ?></p>
                            <h2 class="l2"><span><?php echo $this->lang->line('guide_title_3'); ?></span></h2>
                            <p><?php echo nl2br($this->lang->line('guide_text_3')); ?></p>
                            <h2 class="l2"><span><?php echo $this->lang->line('guide_title_4'); ?></span></h2>
                            <p><?php echo nl2br($this->lang->line('guide_text_4')); ?></p>
                            <h2 class="l2"><span><?php echo $this->lang->line('guide_title_5'); ?></span></h2>
                            <p><?php echo nl2br($this->lang->line('guide_text_5')); ?></p>
                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
