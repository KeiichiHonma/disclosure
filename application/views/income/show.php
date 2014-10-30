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
        <div id="document">
            <?php $this->load->view('layout/common/document_sns'); ?>
            <div class="chart"  id="chart_daytimes">
                <canvas id="daytimes" height="200" width="200"></canvas>
                <div class="count">
                    <?php $recent_cdata = reset($cdatas); ?>
                    <?php echo $recent_cdata->col_income; ?>万円<br />業界順位<?php echo $recent_cdata->col_v_rank; ?>位
                </div>
            </div>


            <script>
                var doughnutData = [{value: <?php echo $income_categories[$company->col_vid]->col_company_count - $recent_cdata->col_v_rank; ?>,color:"#F38630"},{value: <?php echo $recent_cdata->col_v_rank; ?>,color:"#69D2E7"}];

            //var myDoughnut = new Chart(document.getElementById("canvas").getContext("2d")).Doughnut(doughnutData);
            //var ctx = new Chart(document.getElementById("canvas").getContext("2d");
            var ctx = document.getElementById("daytimes").getContext("2d");
            new Chart(ctx).Doughnut(doughnutData,{
                segmentShowStroke : false,
                segmentStrokeWidth : 1,
                percentageInnerCutout : 70, // **** Border width
                animation : true,
                animationSteps : 100,
                animationEasing : "easeOutBounce",
                animateRotate : true,
                animateScale : false,
                onAnimationComplete : null
            });
            </script>

            <?php
            $incomes = array();
            $years = array();
            $reverse_cdatas = array_reverse($cdatas);
            $i = 0;
            foreach ($reverse_cdatas as $reverse_cdata){
                if($i == 7) break;
                $incomes[] = $reverse_cdata->col_income;
                $years[] = strftime("%Y.%m", $reverse_cdata->col_disclosure);
                $i++;
            }
            $income_string = implode(',',$incomes);
            $year_string = implode(',',$years);
            ?>
            
            <div id="chart_precipitation_total" class="undisp">
                <canvas id="precipitation_total" width="400" height="300"></canvas>
                <div class="count">
                   <em>年収の推移</em><br>
                   <span class="caption">(jpy)</span>
                </div>
            </div>
            <?php
                $scaleSteps = ceil($recent_cdata->col_income / 100) + 2;
            ?>
            <script>
                var linedata = {
                    labels : [<?php echo $year_string; ?>],
                    datasets : [
                        {
                            fillColor : "rgba(0,180,255,0.1)",
                            strokeColor : "#66ccff",
                            pointColor : "#66ccff",
                            pointStrokeColor : "#fff",
                            data : [<?php echo $income_string; ?>]
                        }
                    ]
                }
                var ctx2 = document.getElementById("precipitation_total").getContext("2d");
                new Chart(ctx2).Line(linedata,{
                    scaleOverlay : true,
                    scaleOverride : true,
                    scaleSteps : <?php echo $scaleSteps; ?>,
                    scaleStepWidth : 100,
                    scaleStartValue : null,
                    scaleLineColor : "#ccc",
                    scaleLineWidth : 1,
                    scaleShowLabels : true,
                    scaleLabel : "<%=value%>",
                    scaleFontFamily : "'Arial'",
                    scaleFontSize : 11,
                    scaleFontStyle : "normal",
                    scaleFontColor : "#ccc",    
                    scaleShowGridLines : false,
                    scaleGridLineColor : "#ccc",
                    scaleGridLineWidth : 1,    
                    bezierCurve : false,
                    pointDot : true,
                    pointDotRadius : 6,
                    pointDotStrokeWidth : 0,
                    datasetStroke : true,
                    datasetStrokeWidth : 3,
                    datasetFill : true,
                    animation : true,
                    animationSteps : 60,
                    animationEasing : "easeOutQuart",
                    onAnimationComplete : null    
                });
            </script>

            <table>
                <tr>
                    <th>公開日</th>
                    <th>年収</th>
                    <th class="undisp">従業員数</th>
                    <th class="undisp">平均年齢</th>
                    <th class="undisp">平均勤続年数</th>
                </tr>
                <?php $count = count($cdatas);$i = 1; ?>
                <?php foreach ($cdatas as $cdata) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="date<?php if($i == 1) echo ' new'; ?>"><?php echo strftime($this->lang->line('setting_date_format'), $cdata->col_disclosure); ?></td>
                    <?php if($i == 1):  ?>
                    <td class="new"><strong><?php echo $cdata->col_income; ?>万円</strong></td>
                    <?php else: ?>
                    <td><?php echo $cdata->col_income; ?>万円</td>
                    <?php endif ?>
                    
                    <td class="undisp<?php if($i == 1) echo ' new';  ?>"><?php echo $cdata->col_person; ?>人</td>
                    <td class="undisp<?php if($i == 1) echo ' new';  ?>"><?php echo $cdata->col_age; ?>歳</td>
                    <td class="undisp<?php if($i == 1) echo ' new';  ?>"><?php echo $cdata->col_employ; ?>年</td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>

            <h3 class="center_dot mt30 mr10 ml10"><span>同業界年収</span></h3>
            <table>
                <tr>
                    <th class="cell01 date">提出日</th>
                    <th class="code undisp">証券コード</th>
                    <th>企業名</th>
                    <th class="income">年収</th>
                    <th class="trend undisp">前年比較</th>
                </tr>
                <?php $count = count($high_and_low_cdatas);$i = 1; ?>
                <?php foreach ($high_and_low_cdatas as $high_and_low_cdata) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="cell02"><span class="undisp"><?php echo strftime("%Y年", $high_and_low_cdata->col_disclosure); ?></span><?php echo strftime("%m月%d日", $high_and_low_cdata->col_disclosure); ?></td>
                    <td class="undisp"><?php echo $high_and_low_cdata->col_code; ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor('income/show/'.$high_and_low_cdata->presenter_name_key, $high_and_low_cdata->col_name); ?></td>
                    <td><?php echo $high_and_low_cdata->col_income; ?>万円</td>
                    <?php
                    if($high_and_low_cdata->col_income_trend == 1){
                        $trend_image = 'up.gif';
                    }elseif($high_and_low_cdata->col_income_trend == 2){
                        $trend_image = 'down.gif';
                    }elseif($high_and_low_cdata->col_income_trend == 0){
                        $trend_image = 'new.gif';
                    }elseif($high_and_low_cdata->col_income_trend == 3){
                        $trend_image = 'stay.gif';
                    }
                    ?>
                    <td class="undisp"><img src="/images/income/<?php echo $trend_image; ?>" /></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>


        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/switch_side'); ?>
            <div class="side_list">
                <?php $this->load->view('layout/common/income_categories'); ?>
                <?php $this->load->view('layout/common/income_markets'); ?>
            </div>
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
