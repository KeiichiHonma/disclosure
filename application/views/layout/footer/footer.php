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
                        <?php foreach ($categories as $category) : ?>
                        <li><?php echo force_anchor('search/category/'.$category->id, $category->name); ?></li>
                        <?php endforeach; ?>
                  </ul>
            </div>

            <div class="boxRight"><span class="copy">copyrights &#169 Gramer</span></div>
        </div>
    </div>
</div>

</body>
</html>