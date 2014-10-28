<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h3 class="l1"><?php echo $edinet->presenter_name; ?>の<?php echo $this->lang->line('common_title_bs'); ?></h3>
        <?php $this->load->view('layout/common/finance_tab'); ?>
        <div id="document">
            <?php if(!empty($finances)): ?>
            <?php $this->load->view('layout/common/document_sns'); ?>
            <?php
                $count=count($finances);
                $end_year = $from_year+$count;
            ?>
            <table class="finance">
                <tr>
                <th>&nbsp;</th>
                <?php for ($i=$from_year;$i<$end_year;$i++): ?>
                    <th><?php echo $i; ?>年</th>
                <?php endfor; ?>
                </tr>
                <?php if($type == "top" || $type == "bs"): ?>
                <tr><th class="l1">貸借対照表</th><?php for ($i=0;$i<$count;$i++): ?><td>&nbsp;</td><?php endfor; ?></tr>
                <tr><th class="l2">流動資産</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->current_assets); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">固定資産</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->noncurrent_assets); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">資産合計</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->assets); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">流動負債</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->current_liabilities); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">固定負債</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->noncurrent_liabilities); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">負債合計</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->liabilities); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">資本金</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->capital_stock); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">株主資本</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->shareholders_equity); ?></td><?php endfor; ?></tr>
                <?php endif; ?>
                <?php if($type == "top" || $type == "pl"): ?>
                <tr><th class="l1">損益計算書</th><?php for ($i=0;$i<$count;$i++): ?><td>&nbsp;</td><?php endfor; ?></tr>
                <tr><th class="l2">売上高</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->net_sales); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">売上原価</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->cost_of_sales); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">売上総利益</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->gross_profit); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">営業利益</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->operating_income); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">経常利益</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->ordinary_income); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">特別利益</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->extraordinary_income); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">特別損失</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->extraordinary_losses); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">当期純利益</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->net_income); ?></td><?php endfor; ?></tr>
                <?php endif; ?>
                <?php if($type == "top" || $type == "cf"): ?>
                <tr><th class="l1">キャッシュフロー計算書</th><?php for ($i=0;$i<$count;$i++): ?><td>&nbsp;</td><?php endfor; ?></tr>
                <tr><th class="l2">減価償却</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->depreciation_and_amortization); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">営業CF</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->net_cash_provided_by_used_in_operating_activities); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">投資CF</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->net_cash_provided_by_used_in_investing_activities); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">財務CF</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->net_cash_provided_by_used_in_financing_activities); ?></td><?php endfor; ?></tr>
                <tr><th class="l2">キャッシュフロー</th><?php for ($i=0;$i<$count;$i++): ?><td><?php echo number_format($finances[$i]->net_increase_decrease_in_cash_and_cash_equivalents); ?></td><?php endfor; ?></tr>
                <?php endif; ?>
            </table>
            <?php if($type != "top" && $count > 1): ?>
                <?php foreach ($graphs[$type] as $key => $target) : ?>
                    <?php
                        $values_string = implode(',',$target_values[$target]);
                        $years_string = implode(',',$target_years[$target]);
                        list($scale_start_value,$scale_steps,$scale_steps_width) = get_scale(min($target_values[$target]),max($target_values[$target]));
                    ?>
                    <div class="finance_graph">
                        <canvas id="<?php echo $target; ?>" width="320" height="300"></canvas>
                        <div class="count">
                           <em><?php echo $this->lang->line('finance_title_'.$target); ?>の推移</em><br>
                           <span class="caption">(jpy)</span>
                        </div>
                    </div>
                    <script>
                        var linedata = {
                            labels : [<?php echo $years_string; ?>],
                            datasets : [
                                {
                                    fillColor : "rgba(220, 220, 220, 0.5)",
                                    strokeColor : "#66ccff",
                                    pointColor : "#66ccff",
                                    pointStrokeColor : "#fff",
                                    data : [<?php echo $values_string; ?>]
                                }
                            ]
                        }
                        var ctx<?php echo $key; ?> = document.getElementById("<?php echo $target; ?>").getContext("2d");
                        new Chart(ctx<?php echo $key; ?>).Line(linedata,{
                            scaleOverlay : true,
                            scaleOverride : true,
                            scaleSteps : <?php echo $scale_steps; ?>,
                            scaleStepWidth : <?php echo $scale_steps_width; ?>,
                            scaleStartValue : <?php echo $scale_start_value; ?>,
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
                <?php endforeach; ?>
            <?php endif; ?>
            <?php else: ?>
                <div class="blank">指定のファイナンス情報がありません。</div>
            <?php endif; ?>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/switch_side'); ?>
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div class="side_list">
                <?php $this->load->view('layout/common/finance_categories'); ?>
                <?php $this->load->view('layout/common/finance_markets'); ?>
            </div>
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
