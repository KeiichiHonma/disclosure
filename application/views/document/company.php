<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $edinet->presenter_name; ?>の有価証券報告書一覧</h1>
        <div id="document">
            <?php if(!empty($documents)): ?>
            <?php $this->load->view('common/pager'); ?>
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
            <div class="blank"><?php echo $categories[$category_id]->name; ?>カテゴリの開示情報はありません。</div>
            <?php endif; ?>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/switch_side'); ?>
            <div class="side_list">
                <?php $this->load->view('layout/common/document_categories'); ?>
            </div>
            
            <div class="box_wrap">
                <div class="box_adx pcdisp">
                    <img src="/images/ad_example1.gif" alt="" />
                </div>
                <div class="box_adx spdisp">
                    <img src="/images/ad_example_sp1.jpg" alt="" />
                </div>
            </div>
        </div>
        <span class="cf" />
        <?php $this->load->view('common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
