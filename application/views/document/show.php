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

            <div id="side_cat">
                <h1 class="side_title"><?php echo $document->presenter_name; ?></h1>
                <ul>
                <li<?php if($document_side_current == 'document_show'): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/show/'.$document->id; ?>"><span><?php echo strftime($this->lang->line('setting_date_format'), strtotime($document->date)); ?>&nbsp;<?php echo $document->document_name; ?></span><span class="ico"><img src="/images/icon/document_20.png" alt="document" /></span></a></li>
                <li<?php if($document_side_current == 'document_dl_csv'): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/download/'.$document->id.'/csv'; ?>"><span>CSVファイルダウンロード</span><span class="ico"><img src="/images/icon/csv_20.png" alt="csv" /></a></li>
                <li<?php if($document_side_current == 'document_dl_xlsx'): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/download/'.$document->id.'/xlsx'; ?>"><span>EXCELファイルダウンロード</span><span class="ico"><img src="/images/icon/xlsx_20.png" alt="xlsx" /></span></a></li>
                <?php if(!empty($etc_documents)): ?><li<?php if($document_side_current == 'document_company'): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/company/'.$edinet->presenter_name_key; ?>"><span>開示情報一覧</span><span class="ico"><img src="/images/icon/presenter_documents_20.png" alt="presenter_documents" /></span></a></li><?php endif; ?>
                <?php if(!empty($company)): ?>
                <li<?php if($document_side_current == 'company_show'): ?> class="current"<?php endif; ?>><a href="<?php echo '/companyh/show/'.$edinet->presenter_name_key; ?>"><span>企業情報</span><span class="ico"><img src="/images/icon/comnpany_info_20.png" alt="omnpany_info" /></span></a></li>
                <li<?php if($document_side_current == 'company_show'): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/show/'.$edinet->presenter_name_key; ?>"><span>年収情報</span><span class="ico"><img src="/images/icon/comnpany_info_20.png" alt="omnpany_info" /></span></a></li>
                <?php endif; ?>
                </ul>
            </div><!-- /side_cat -->
            <?php if(!empty($html_index)): ?>
            <div id="sticker">
                <div id="side_cat">
                    
                    <h1 class="side_title">目次</h1>
                    <ul>
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
                                        <li><a href="#<?php echo $tag.'_'.$html_number.'_'.$h_three_tag_number; ?>"><span><?php echo $value; ?></span></a></li>
                                        <?php else: ?>
                                        <li><a href="<?php echo '/document/show/'.$document->id.'/'.$html_number.'#'.$tag.'_'.$html_number.'_'.$h_three_tag_number; ?>"><span><?php echo $value; ?></span></a></li>
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

        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
