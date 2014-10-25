<?php $this->load->view('layout/header/header'); ?>
<script>
    $(window).load(function(){
      $("#sticker").sticky({ topSpacing: 45, center:true, className:"hey" });
    });
</script>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $document->presenter_name.' - '.strftime($this->lang->line('setting_date_format'), strtotime($document->date)).' '.$document->document_name; ?></h1>
        <div class="box_adx mb20 spdisp">
            <img src="/images/ad_example_sp1.jpg" alt="" />
        </div>
        <div id="document_html" style="padding:5px !important;">
            <?php $this->load->view('layout/common/document_sns'); ?>
            <?php echo $document_htmls->html_data; ?>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/switch_side'); ?>
            <div id="sticker">
                <div id="side_cat">
                    
                    <h1 class="side_title">目次</h1>
                    <ul class="mokuji">
                    <?php foreach ($html_index as $html_number => $tags_index) : ?>
                        <?php if(!empty($tags_index)): ?>
                            <?php $h_two_tag_number = 0; ?>
                            <?php $h_three_tag_number = 0; ?>
                            <?php foreach ($tags_index as $tag => $values) : ?>
                                <?php if($tag == 'h2'): ?>
                                    <?php foreach ($values as $value) : ?>
                                        <?php if($target_html_number == $html_number): ?>
                                        <li><a href="#<?php echo $tag.'_'.$html_number.'_'.$h_two_tag_number; ?>"><span><?php echo $value; ?></span></a></li>
                                        <?php else: ?>
                                        <li><a href="<?php echo '/document/show/'.$document->id.'/'.$html_number.'#'.$tag.'_'.$html_number.'_'.$h_two_tag_number; ?>"><span><?php echo $value; ?></span></a></li>
                                        <?php endif; ?>
                                        <?php $h_two_tag_number++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if($tag == 'h3'): ?>
                                    <?php foreach ($values as $value) : ?>
                                        <?php if($target_html_number == $html_number): ?>
                                        <li><a class="child" href="#<?php echo $tag.'_'.$html_number.'_'.$h_three_tag_number; ?>"><span>-<?php echo $value; ?></span></a></li>
                                        <?php else: ?>
                                        <li><a class="child"  href="<?php echo '/document/show/'.$document->id.'/'.$html_number.'#'.$tag.'_'.$html_number.'_'.$h_three_tag_number; ?>"><span>-<?php echo $value; ?></span></a></li>
                                        <?php endif; ?>
                                        <?php $h_three_tag_number++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </ul>
                </div><!-- /side_cat -->
            </div>
        </div>
        <span class="cf" />
        <?php $this->load->view('common/ads/adsense_bottom'); ?>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
