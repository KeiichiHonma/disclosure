<?php
$category_name_language = 'name_'.$this->config->item('language_min');
$title_language = 'title_'.$this->config->item('language_min');
$copy_language = 'copy_'.$this->config->item('language_min');
?>
                    <!-- ■ SIDEBAR ■ -->
                    <div id="sidebar">
                        <h3 class="categorys">CATEGORIES</h3>
                        <div class="cg_nav">
                            <ul>
                                <?php foreach ($categories as $category) : ?>
                                <li><?php echo force_anchor('search/category/'.$category->id, $category->$category_name_language); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php if(isset($related)) : ?>
                        <h3 class="related">RELATED ITEMS</h3>
                        <?php foreach ($related as $coupon) : ?>
                        <div class="sidebox">
                            <h4><?php echo force_anchor('coupon/show/' . $coupon->id, $coupon->$title_language); ?></h4>
                            <div class="sd_wrapper">
                                <div class="sd_box_l">
                                    <div class="n_price"><?php echo $coupon->price.$this->lang->line('unit'); ?></div>
                                    <div class="v_price"><?php echo $coupon->value.$this->lang->line('unit'); ?></div>
                                    <div class="d_price"><?php echo $coupon->save; ?>%OFF</div>
                                    <?php echo force_anchor('coupon/show/' . $coupon->id, '<img src="/images/bt_view_s_off.jpg" width="92" height="26" alt="VIEW">'); ?>
                                </div>
                                <div class="sd_box_r">
                                    <div class="tn_img"><?php echo force_anchor('coupon/show/' . $coupon->id, '<img src="/'.$coupon->thumbnail_filepath.'?'.strtotime($coupon->modified).'" class="image_resize">'); ?></div>
                                    
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <div class="bn_area">
                            <ul>
                                <li><?php echo force_anchor('site/news', '<img src="/images/bt_info2_off.jpg" width="260" height="58" alt="お知らせ">'); ?></li>
                            </ul>
                        </div>
                        <div class="ot_nav">
                            <ul>
                                <li><?php echo force_anchor('site/guide',  $this->lang->line('common_title_guide')); ?></li>
                                <li><?php echo force_anchor('site/faq',  $this->lang->line('common_title_faq')); ?></li>
                            </ul>
                        </div>
                    </div>
                    <!--/sidebar-->