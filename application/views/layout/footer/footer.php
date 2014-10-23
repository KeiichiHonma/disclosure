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
                    <li><?php echo force_anchor('site/company',  $this->lang->line('common_title_company')); ?></li>
                    <li class="last"><?php echo force_anchor('site/contact',  $this->lang->line('common_title_contact'),TRUE); ?></li>
                </ul>
            </div>

            <div class="sec">
                  <ul class="clearfix mb05">
                    <?php foreach ($categories as $category) : ?>
                    <?php if($category->id > 1): ?><li><a href="<?php echo '/income/category/'.$category->id; ?>"><span><?php echo $category->name; ?></span></a></li><?php endif; ?>
                    <?php endforeach; ?>
                  </ul>
            </div>

            <div class="boxRight"><span class="copy">copyrights &#169 Gramer</span></div>
        </div>
    </div>
</div>

</body>
</html>