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
                            <h1 class="l1"><?php echo $this->lang->line('common_title_contact'); ?></h1>
                            <p><?php echo $this->lang->line('contact_message'); ?></p>
                            <?php echo form_open($this->uri->uri_string()); ?>
                            <table class="table_g" style="width:100%">
                            <tr>
                                <td class="line"><?php echo $this->lang->line('contact_name'); ?></td>
                                <td class="line"><input type="text" name="username" class="text" value="<?php echo set_value('username',ENVIRONMENT == 'development' ? 'keiichi honma' : ''); ?>" size="50" /><?php echo form_error('username'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $this->lang->line('contact_email'); ?></td>
                                <td><input type="text" name="email" class="text" value="<?php echo set_value('email',ENVIRONMENT == 'development' ? 'honma@zeus.corp.813.co.jp' : ''); ?>" size="50" /><?php echo form_error('email'); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $this->lang->line('contact_description'); ?></td>
                                <td><textarea name="description"><?php echo set_value('description',ENVIRONMENT == 'development' ? '詳細-1' : ''); ?></textarea><?php echo form_error('description'); ?></td>
                            </tr>
                            </table>
                            <table style="width:100%">
                            <tr>
                                <td align="center"><input type="submit" style="display:block;" class="submit" value="<?php echo $this->lang->line('contact_send'); ?>"></td>
                            </tr>
                            </table>
                            <?php echo form_close(); ?>
                        </div>
                        <!--/c_wrapper-->
                    </div>
                    <!--/page-->
                    <?php $this->load->view('layout/sidebar/sidebar'); ?>
                </div>
            </div>
            <!--/contents-->

<?php $this->load->view('layout/footer/footer'); ?>
