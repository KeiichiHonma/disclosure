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
                            <h1 class="l1"><?php echo $this->lang->line('common_title_company'); ?></h1>

                            <h3 class="l3"><?php echo $this->lang->line('company_title_1'); ?></h3>
                            <p><?php echo nl2br($this->lang->line('company_text_1')); ?></p>
                            <h3 class="l3"><?php echo $this->lang->line('company_title_2'); ?></h3>
                            <p><?php echo nl2br($this->lang->line('company_text_2')); ?></p>
                            <h3 class="l3"><?php echo $this->lang->line('company_title_3'); ?></h3>
                            <p><?php echo nl2br($this->lang->line('company_text_3')); ?></p>
                            <h3 class="l3"><?php echo $this->lang->line('company_title_4'); ?></h3>
                            <p><?php echo nl2br($this->lang->line('company_text_4')); ?></p>
                            <h3 class="l3"><?php echo $this->lang->line('company_title_5'); ?></h3>
                            <p><?php echo nl2br($this->lang->line('company_text_5')); ?></p>
                            <h3 class="l3"><?php echo $this->lang->line('company_title_6'); ?></h3>
                            <p><?php echo nl2br($this->lang->line('company_text_6')); ?></p>
                            <h3 class="l3"><?php echo $this->lang->line('company_title_7'); ?></h3>
                            <p><?php echo nl2br($this->lang->line('company_text_7')); ?></p>
                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
