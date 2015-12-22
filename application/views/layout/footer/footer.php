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
                                <li><a href="https://twitter.com/opendatacompany" target="_blank"><img src="/images/ico_tt_off.png" width="35" height="34" alt="twitter"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="about">
                <ul>
                    <li><a href="http://gramer.co.jp/" target="_blank"><?php echo $this->lang->line('common_title_company'); ?></a></li>
                    <li><?php echo force_anchor('site/ad',  $this->lang->line('common_title_ad'),FALSE); ?></li>
                    <li class="last"><?php echo force_anchor('site/api',  '年収データAPI',FALSE); ?></li>
                </ul>
            </div>

            <div class="sec">
                  <ul class="clearfix mb05">
                    <li><a href="/"><?php echo $this->lang->line('common_title_home'); ?></a></li>
                    <li><a href="/income/"><?php echo $this->lang->line('common_title_income'); ?></a></li>
                    <li><a href="/finance/"><?php echo $this->lang->line('common_title_finance'); ?></a></li>
                    <li><a href="/finance/category/1/pl"><?php echo $this->lang->line('common_title_finance_pl'); ?></a></li>
                    <li><a href="/finance/category/1/bs"><?php echo $this->lang->line('common_title_finance_bs'); ?></a></li>
                    <li><a href="/finance/category/1/cf"><?php echo $this->lang->line('common_title_finance_cf'); ?></a></li>
                    <li><a href="/site/issues"><?php echo $this->lang->line('common_title_issues'); ?></a></li>
                  </ul>
            </div>
            <div class="boxRight"><span class="copy">copyrights &#169 Gramer</span></div>
        </div>
    </div>
</div>

</body>
</html>