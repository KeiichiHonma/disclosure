<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->

<div id="contents">
    <div id ="contentsInner"><?php $this->load->view('layout/common/topicpath'); ?>
        <h3 class="l1"><?php echo $page_title; ?></h3>
        <?php $this->load->view('layout/common/document_tab'); ?>
        <div id="document_html">
            <?php //$this->load->view('layout/common/document_sns'); ?>
<iframe style="border: none;" width="660" id="parent-iframe"  scrolling="no" src="/document/iframe/<?php echo $document->id; ?>/<?php echo $target_html_number; ?>"></iframe>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/switch_side'); ?>
                <div id="side_cat">
                    <h1 class="side_title">目次</h1>
                    <ul class="mokuji clearfix">
                    <?php foreach ($html_index as $html_number => $tags_index) : ?>
                        <?php if(!empty($tags_index)): ?>
                            <?php $h_two_tag_number = 0; ?>
                            <?php $h_three_tag_number = 0; ?>
                            <?php foreach ($tags_index as $tag => $values) : ?>
                                <?php if($tag == 'h2'): ?>
                                    <?php foreach ($values as $value) : ?>
                                        <?php if($target_html_number == $html_number): ?>
                                        <li><a href="?<?php echo $tag.'_'.$html_number.'_'.$h_two_tag_number; ?>"><span><?php echo $value; ?></span></a></li>
                                        <?php else: ?>
                                        <li><a href="<?php echo '/document/show/'.$document->id.'/'.$html_number.'?'.$tag.'_'.$html_number.'_'.$h_two_tag_number; ?>"><span><?php echo $value; ?></span></a></li>
                                        <?php endif; ?>
                                        <?php $h_two_tag_number++; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                
                                <?php if($tag == 'h3'): ?>
                                    <?php foreach ($values as $value) : ?>
                                        <?php if($target_html_number == $html_number): ?>
                                        <li><a class="child" href="?<?php echo $tag.'_'.$html_number.'_'.$h_three_tag_number; ?>"><span>-<?php echo $value; ?></span></a></li>
                                        <?php else: ?>
                                        <li><a class="child"  href="<?php echo '/document/show/'.$document->id.'/'.$html_number.'?'.$tag.'_'.$html_number.'_'.$h_three_tag_number; ?>"><span>-<?php echo $value; ?></span></a></li>
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
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
