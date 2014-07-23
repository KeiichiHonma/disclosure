<?php $this->load->view('layout/header/header'); ?>
<?php
$title_language = 'title_'.$this->config->item('language_min');
$description_language = 'description_'.$this->config->item('language_min');
?>
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
                            <h1 class="l1"><?php echo $this->lang->line('common_title_news'); ?></h1>
                            <?php foreach ($newsResult as $news) : ?>
                            <h2 class="l2"><span><?php echo $news->$title_language; ?></span></h2>
                            <p><?php echo nl2br(htmlspecialchars_decode($news->$description_language)); ?></p>
                            <?php endforeach; ?>
                        </div>
                        <!--/c_wrapper-->
                        <div class="c_wrapper">
                            <div class="pager">
                                <?php $this->load->view('common/pager'); ?>
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
