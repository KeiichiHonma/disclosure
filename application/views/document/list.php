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
        <div id="document" class="pt10">
            <?php if(!empty($documents)): ?>
            <?php $this->load->view('layout/common/select_year'); ?>
            <?php $this->load->view('layout/common/pager'); ?>
            <table>
                <tr>
                    <th class="cell01 date">提出日</th>
                    <th>書類</th>
                    <th class="dl undisp">ダウンロード</th>
                </tr>
                <?php $count = count($documents);$i = 1; ?>
                <?php foreach ($documents as $document) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="cell02"><span class="undisp"><?php echo strftime("%Y年", strtotime($document->date)); ?></span><?php echo strftime("%m月%d日", strtotime($document->date)); ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor('document/'.( $document->is_html == 1 ? 'data' : 'show' ).'/'.$document->id, $document->document_name.' - '.$document->presenter_name); ?></td>
                    
                    <td class="undisp">
                    <?php echo anchor('document/download/'.$document->id.'/csv','<img src="/images/icon/csv_30.png" alt="csv" />'); ?>
                    <?php echo anchor('document/download/'.$document->id.'/xlsx','<img src="/images/icon/xlsx_30.png" alt="xlsx" />'); ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <div class="blank">指定の情報はありません。</div>
            <?php endif; ?>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div id="side_cat">
                <?php if(isset($market_id)): ?>
                    <?php $this->load->view('layout/common/document_markets'); ?>
                    <?php $this->load->view('layout/common/document_categories'); ?>
                <?php else: ?>
                    <?php $this->load->view('layout/common/document_categories'); ?>
                    <?php $this->load->view('layout/common/document_markets'); ?>
                <?php endif; ?>
            </div><!-- /side_cat -->
            <?php $this->load->view('layout/common/ads/adsense_side_2'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
