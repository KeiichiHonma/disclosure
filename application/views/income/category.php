<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $categories[$category_id]->name; ?>カテゴリの年収速報</h1>
        <div id="document">
            <?php if(!empty($cdatas)): ?>
                <?php $this->load->view('layout/common/pager'); ?>
                <table>
                    <tr>
                        <th class="cell01 date">提出日</th>
                        <th class="code undisp">証券コード</th>
                        <th>企業名</th>
                        <th class="income">年収</th>
                        <th class="trend undisp">前年比</th>
                    </tr>
                    <?php $count = count($cdatas);$i = 1; ?>
                    <?php foreach ($cdatas as $cdata) : ?>
                    <tr<?php if($count == $i) echo ' class="last"'; ?>>
                        <td class="cell02"><span class="undisp"><?php echo strftime("%Y年", $cdata->col_disclosure); ?></span><?php echo strftime("%m月%d日", $cdata->col_disclosure); ?></td>
                        <td class="undisp"><?php echo $cdata->col_code; ?></td>
                        <td style="font-size:90%;text-align:left;"><?php echo anchor('income/show/'.$cdata->presenter_name_key, $cdata->col_name); ?></td>
                        <td><?php echo $cdata->col_income; ?>万円</td>
                        <?php
                        if($cdata->col_income_trend == 1){
                            $trend_image = 'up.gif';
                        }elseif($cdata->col_income_trend == 2){
                            $trend_image = 'down.gif';
                        }elseif($cdata->col_income_trend == 0){
                            $trend_image = 'new.gif';
                        }elseif($cdata->col_income_trend == 3){
                            $trend_image = 'stay.gif';
                        }
                        ?>
                        <td class="undisp"><img src="/images/income/<?php echo $trend_image; ?>" /></td>
                    </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <div class="blank">指定の年収情報がありません。</div>
            <?php endif; ?>
        </div>
        <div id="sidebar">
            <div id="side_cat">
                <h1 class="side_title">業界カテゴリ</h1>
                <ul>
                <?php foreach ($income_categories as $income_category) : ?>
                <li<?php if($income_category->_id == $category_id): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/category/'.$income_category->_id; ?>"><span><?php echo $income_category->col_name; ?></span><span class="new">平均    <?php echo $income_category->col_income_average; ?>万円</span></a></li>
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
