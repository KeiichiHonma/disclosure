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
                            <h1 class="l1"><?php echo $this->lang->line('common_title_faq'); ?></h1>
                            <p><?php echo nl2br($this->lang->line('faq_text')); ?></p>

                            <div class="faq_list">
                                <ul>
                                    <li><a href="#faq01"><?php echo $this->lang->line('faq_title_1'); ?></a></li>
                                    <li><a href="#faq02"><?php echo $this->lang->line('faq_title_2'); ?></a></li>
                                    <li><a href="#faq03"><?php echo $this->lang->line('faq_title_3'); ?></a></li>
                                    <li><a href="#faq04"><?php echo $this->lang->line('faq_title_4'); ?></a></li>
                                    <li><a href="#faq05"><?php echo $this->lang->line('faq_title_5'); ?></a></li>
                                    <li><a href="#faq06"><?php echo $this->lang->line('faq_title_6'); ?></a></li>
                                    <li><a href="#faq07"><?php echo $this->lang->line('faq_title_7'); ?></a></li>
                                    <li><a href="#faq08"><?php echo $this->lang->line('faq_title_8'); ?></a></li>
                                    <li><a href="#faq09"><?php echo $this->lang->line('faq_title_9'); ?></a></li>
                                </ul>
                            </div>

                            <div class="faq" id="faq01">
                            <h4><?php echo $this->lang->line('faq_title_1'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_1')); ?></p>
                            </div>

                            <div class="faq" id="faq02">
                            <h4><?php echo $this->lang->line('faq_title_2'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_2')); ?></p>
                            </div>

                            <div class="faq" id="faq03">
                            <h4><?php echo $this->lang->line('faq_title_3'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_3')); ?></p>
                            </div>

                            <div class="faq" id="faq04">
                            <h4><?php echo $this->lang->line('faq_title_4'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_4')); ?></p>
                            </div>

                            <div class="faq" id="faq05">
                            <h4><?php echo $this->lang->line('faq_title_5'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_5')); ?></p>
                            </div>

                            <div class="faq" id="faq06">
                            <h4><?php echo $this->lang->line('faq_title_6'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_6')); ?></p>
                            </div>

                            <div class="faq" id="faq07">
                            <h4><?php echo $this->lang->line('faq_title_7'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_7')); ?></p>
                            </div>

                            <div class="faq" id="faq08">
                            <h4><?php echo $this->lang->line('faq_title_8'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_8')); ?></p>
                            </div>

                            <div class="faq" id="faq09">
                            <h4><?php echo $this->lang->line('faq_title_9'); ?></h4>
                            <p><?php echo nl2br($this->lang->line('faq_text_9')); ?></p>
                            </div>

                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
