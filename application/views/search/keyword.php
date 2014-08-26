<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $search_keywords; ?>の検索</h1>
        <div id="document">
            <table>
                <tr>
                    <th class="code undisp">証券コード</th>
                    <th>企業名</th>
                    <th>&nbsp;</th>
                </tr>
                <?php $count = count($edinets);$i = 1; ?>
                <?php foreach ($edinets as $edinet) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td><?php echo $edinet->security_code; ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor('document/company/'.$edinet->presenter_name_key, $edinet->presenter_name); ?></td>
                    <td><?php echo anchor('document/company/'.$edinet->presenter_name_key, '開示情報一覧'); ?>&nbsp;|&nbsp;<?php echo anchor('income/show/'.$edinet->presenter_name_key, '企業年収'); ?></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="sidebar">
            <div id="side_cat">
                <h1 class="side_title">業界カテゴリ</h1>
                <ul>
                <?php foreach ($categories as $category) : ?>
                <li><a href="<?php echo '/document/category/'.$category->id; ?>"><span><?php echo $category->name; ?></span></a></li>
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
