<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $categories[$category_id]->name; ?>カテゴリの開示情報</h1>
        <div id="document">
            <?php if(!empty($xbrls)): ?>
            <?php $this->load->view('common/pager'); ?>
            <table>
                <tr>
                    <th class="cell01 date">提出日</th>
                    <th>書類</th>
                    <th class="dl undisp">ダウンロード</th>
                </tr>
                <?php $count = count($xbrls);$i = 1; ?>
                <?php foreach ($xbrls as $xbrl) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="cell02"><span class="undisp"><?php echo strftime("%Y年", strtotime($xbrl->date)); ?></span><?php echo strftime("%m月%d日", strtotime($xbrl->date)); ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor('document/show/'.$xbrl->id, $xbrl->document_name.' - '.$xbrl->presenter_name); ?></td>
                    <td class="undisp">
                    <?php echo anchor('document/download/'.$xbrl->id.'/csv','<img src="/images/icon/csv_30.png" alt="csv" />'); ?>
                    <?php echo anchor('document/download/'.$xbrl->id.'/xlsx','<img src="/images/icon/xlsx_30.png" alt="xlsx" />'); ?>
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
            <div id="side_cat">
                <h1 class="side_title">業界カテゴリ</h1>
                <ul>
                <?php foreach ($categories as $category) : ?>
                <li<?php if($category->id == $category_id): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/category/'.$category->id; ?>"><span><?php echo $category->name; ?></span></a></li>
                <?php endforeach; ?>
                </ul>
            </div><!-- /side_cat -->
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
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
