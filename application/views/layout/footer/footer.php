<!-- 
//////////////////////////////////////////////////////////////////////////////
footer
//////////////////////////////////////////////////////////////////////////////
-->
<div id="footer">
    <div id="footerInner">
        <div id="footerInnerIn" class="cf">
            <img src="/images/logo.png" alt=""/>

            <div class="boxLeft">
                  <ul class="clearfix mb05">
                    <?php foreach ($categories as $category) : ?>
                    <li><a href="<?php echo '/income/category/'.$category->id; ?>"><span><?php echo $category->name; ?></span></a></li>
                    <?php endforeach; ?>
                  </ul>
            </div>

            <div class="boxRight"><span class="copy">copyrights &#169 Gramer</span></div>
        </div>
    </div>
</div>

</body>
</html>