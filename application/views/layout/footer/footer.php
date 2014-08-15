<!-- 
//////////////////////////////////////////////////////////////////////////////
footer
//////////////////////////////////////////////////////////////////////////////
-->
<div id="footer">
    <div id="footerInner">
        <div id="footerInnerIn" class="cf">
            <img src="/images/h1_logo.png" alt=""/>

            <div class="boxLeft">
                  <ul class="clearfix mb05">
                    <?php foreach ($income_categories as $income_category) : ?>
                    <li><a href="<?php echo '/income/category/'.$income_category->_id; ?>"><span><?php echo $income_category->col_name; ?></span><span class="new">平均    <?php echo $income_category->col_income_average; ?>万円</span></a></li>
                    <?php endforeach; ?>
                  </ul>
            </div>

            <div class="boxRight"><span class="copy">copyrights &#169 Gramer</span></div>
        </div>
    </div>
</div>

</body>
</html>