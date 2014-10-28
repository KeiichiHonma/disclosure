<!-- 
//////////////////////////////////////////////////////////////////////////////
footer
//////////////////////////////////////////////////////////////////////////////
-->
<div id="footer">
    <div id="footerInner">
        <div id="footerInnerIn" class="cf">
            <div class="logo">
                <img src="/images/logo.png" alt="" width="170" />
                <div class="scf clearfix">
                    <div class="right">
                        <h4><?php echo $this->lang->line('footer_info_check'); ?></h4>
                        <div class="sc_nav clearfix">
                            <ul>
                                <li><a href="<?php  echo $this->config->item('language_min') == 'th' ? 'https://www.facebook.com/balloooooonth': 'https://www.facebook.com/ballooooooncom'; ?>" target="_blank"><img src="/images/ico_fb_off.png" width="35" height="34" alt="facebook"></a></li>
                                <li><a href="<?php  echo $this->config->item('language_min') == 'th' ? 'https://twitter.com/balloooooonth': 'https://twitter.com/ballooooooncom'; ?>" target="_blank"><img src="/images/ico_tt_off.png" width="35" height="34" alt="twitter"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about">
                <ul>
                    <li><a href="http://gramer.co.jp/" target="_blank"><?php echo $this->lang->line('common_title_company'); ?></a></li>
                    <li><?php echo force_anchor('site/ad',  $this->lang->line('common_title_ad'),FALSE); ?></li>
                    <li><?php echo force_anchor('site/asp',  $this->lang->line('common_title_asp'),FALSE); ?></li>
                    <li class="last"><?php echo force_anchor('site/contact',  $this->lang->line('common_title_contact'),TRUE); ?></li>
                </ul>
            </div>

            <div class="sec">
                  <ul class="clearfix mb05">
                    <li><a href="/income/"><?php echo $this->lang->line('common_title_income'); ?></a></li>
                    <li><a href="/finance/ranking/pl/2009"><?php echo $this->lang->line('common_title_pl'); ?></a></li>
                    <li><a href="/finance/ranking/bs/2009"><?php echo $this->lang->line('common_title_bs'); ?></a></li>
                    <li><a href="/finance/ranking/cf/2009"><?php echo $this->lang->line('common_title_cf'); ?></a></li>
                    <li><a href="/site/issues"><?php echo $this->lang->line('common_title_issues'); ?></a></li>
                  </ul>
            </div>
            <div class="sec ft_contact">
                <?php echo form_open('site/contact'); ?>
                    <textarea class="textarea" name="contact" id="contact"></textarea>
                    <p><input name="ft_send" id="ft_send" type="submit" value="意見を送る" /></p>
                <?php echo form_close(); ?>
            </div>

            <div class="boxRight"><span class="copy">copyrights &#169 Gramer</span></div>
        </div>
    </div>
</div>

</body>
</html>