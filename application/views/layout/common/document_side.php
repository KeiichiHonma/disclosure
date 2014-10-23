            <?php $this->load->view('layout/common/switch_side'); ?>
            <?php if(!empty($html_index)): ?>
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
            <?php endif; ?>
            <?php if( !isset($document_side_current) || $document_side_current != 'document_show' ): ?>
            <div class="side_list">
                <?php $this->load->view('layout/common/categories'); ?>
            </div>
            
            <div class="box_wrap">
                <div class="box_adx pcdisp">
                    <img src="/images/ad_example1.gif" alt="" />
                </div>
                <div class="box_adx spdisp">
                    <img src="/images/ad_example_sp1.jpg" alt="" />
                </div>
            </div>
            <?php endif; ?>