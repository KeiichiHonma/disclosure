<?php $this->load->view('layout/header/header'); ?>
    <script>
    $(function() {
        $( "#datepicker" ).datepicker({
            inline: true
        });

        // Hover states on the static widgets
        $( "#dialog-link, #icons li" ).hover(
            function() {
                $( this ).addClass( "ui-state-hover" );
            },
            function() {
                $( this ).removeClass( "ui-state-hover" );
            }
        );
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

        <div id="document_navi">
            <h3 class="center_dot"><span>企業年収速報</span></h3>
        </div>

        <div id="income">
            <table>
                <tr>
                    <th class="cell01 date">提出日</th>
                    <th class="code">証券コード</th>
                    <th>企業名</th>
                    <th class="income">年収</th>
                    <th class="trend">前年比較</th>
                </tr>
                <?php $count = count($cdatas);$i = 1; ?>
                <?php foreach ($cdatas as $cdata) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="cell02"><?php echo strftime($this->lang->line('setting_date_format'), $cdata->col_disclosure); ?></td>
                    <td><?php echo $cdata->col_code; ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor('income/show/'.$cdata->col_cid, $cdata->col_name); ?></td>
                    <td><?php echo $cdata->col_income; ?>万円</td>
                    <?php
                    if($cdata->col_income_trend == 1){
                        $trend_image = 'up.gif';
                    }elseif($cdata->col_income_trend == 2){
                        $trend_image = 'down.gif';
                    }elseif($cdata->col_income_trend == 0){
                        $trend_image = 'new.gif';
                    }elseif($high_and_low_cdata->col_income_trend == 3){
                        $trend_image = 'stay.gif';
                    }
                    ?>
                    <td><img src="/images/income/<?php echo $trend_image; ?>" /></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="sidebar">
            <div id="side_cat">
                <h1 class="side_title">業界カテゴリ</h1>
                <ul>
                <?php foreach ($income_categories as $income_category) : ?>
                <li><a href="<?php echo '/income/category/'.$income_category->_id; ?>"><span><?php echo $income_category->col_name; ?></span><span class="new">平均    <?php echo $income_category->col_income_average; ?>万円</span></a></li>
                <?php endforeach; ?>
                </ul>
            </div><!-- /side_cat -->
            <div class="box_wrap">
                <div class="box_adx">
                    <img src="/images/ad_example1.gif" alt="csv" />
                </div>
            </div>
        </div>
        <span class="cf" />
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
