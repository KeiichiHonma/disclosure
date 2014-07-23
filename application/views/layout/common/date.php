<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
<?php if(isset($jalan_h_id)) : ?>
        <h2><?php echo $display_date.'-'.$hotel['HotelName']; ?></h2>
        <div id="boxLeisure">
            <div class="title cf">
                <div class="photo"><img src="<?php echo $hotel['Picture'][0]['PictureURL']; ?>" alt="" /></div>
                <div class="text">
                    <h3><?php echo $hotel['HotelCatchCopy'] ?></h3>
                    <p><?php echo $hotel['HotelCaption'] ?></p>
                    <div class="btnBooking"><a href="<?php echo $hotel['HotelDetailURL']; ?>" target="_blank"><img src="/images/btn_booking.png" alt="この旅を予約する" /></a></div>
                </div>
            </div>
            <div class="plan">
                <ul class="cf">
                <?php foreach ($hotel['Picture'] as $key => $picture) : ?>
                    <?php if($key != 0): ?><li><img src="<?php echo $picture['PictureURL']; ?>" alt="<?php echo $picture['PictureCaption']; ?>" width="270" /><br /><?php echo $picture['PictureCaption']; ?></li><?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div>
        </div>
<?php endif; ?>
        <div id="weather">
<?php
    $i = 1;
    $class_array = array();
    $local_sp_style = array();
    foreach ($week_futures as $week_future){
        if($i == 8) break;
        if($week_future->day_of_the_week == 6){
            $class_array[$i] = 'day0'.$i.' sat';
        }elseif($week_future->day_of_the_week == 7){
            $class_array[$i] = 'day0'.$i.' sun';
        }else{
            $class_array[$i] = 'day0'.$i;
            $local_sp_style[] = '#weather td.day0'.$i;
        }
        $i++;
    }
?>
<style type="text/css">
<!--
@media only screen and (max-width: 640px){
<?php echo implode(',',$local_sp_style); ?>{ display:none; }
}
-->
</style>
            <h2><?php echo $weather_title; ?></h2>
            <table>
                <tr class="title">
                    <th class="cell01">日付</th>
                    <td class="<?php echo $class_array[1]; ?>"><?php echo $week_futures[0]->month.'/'.$week_futures[0]->day.get_day_of_the_week($week_futures[0]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[2]; ?>"><?php echo $week_futures[1]->month.'/'.$week_futures[1]->day.get_day_of_the_week($week_futures[1]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[3]; ?>"><?php echo $week_futures[2]->month.'/'.$week_futures[2]->day.get_day_of_the_week($week_futures[2]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[4]; ?>"><?php echo $week_futures[3]->month.'/'.$week_futures[3]->day.get_day_of_the_week($week_futures[3]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[5]; ?>"><?php echo $week_futures[4]->month.'/'.$week_futures[4]->day.get_day_of_the_week($week_futures[4]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[6]; ?>"><?php echo $week_futures[5]->month.'/'.$week_futures[5]->day.get_day_of_the_week($week_futures[5]->day_of_the_week,FALSE,FALSE) ?></td>
                    <td class="<?php echo $class_array[7]; ?>"><?php echo $week_futures[6]->month.'/'.$week_futures[6]->day.get_day_of_the_week($week_futures[6]->day_of_the_week,FALSE,FALSE) ?></td>
                </tr>
                <?php $count = count($week_futures); ?>
                <tr>
                    
                    <th class="cell02">天気(昼)</th><?php for ($index = 0; $index < $count; $index++) : ?><td class="<?php echo $class_array[$index+1]; ?>"><img src="/images/weather/icon/<?php echo $week_futures[$index]->daytime_icon_image; ?>" alt="<?php echo $week_futures[$index]->daytime; ?>" class="icon" /><br /><?php echo $week_futures[$index]->daytime; ?></td><?php endfor; ?>
                </tr>
                <tr>
                    <th class="cell02">天気(夜)</th><?php for ($index = 0; $index < $count; $index++) : ?><td class="<?php echo $class_array[$index+1]; ?>"><img src="/images/weather/icon/<?php echo $week_futures[$index]->night_icon_image; ?>" alt="<?php echo $week_futures[$index]->night; ?>" class="icon" /><br /><?php echo $week_futures[$index]->daytime; ?></td><?php endfor; ?>
                </tr>
                <tr>
                    <th class="cell02">気温</th><?php for ($index = 0; $index < $count; $index++) : ?><td class="<?php echo $class_array[$index+1]; ?>">
                            <div class="highTemp">最高気温 <em><?php echo $week_futures[$index]->temperature_max; ?>°C</em></div>
                            <div class="lowTemp">最低気温 <em><?php echo $week_futures[$index]->temperature_min; ?>°C</em></div>
                    </td><?php endfor; ?>
                </tr>
            </table>
        </div>

        <div class="guide">
            <h2><?php echo $history_title; ?></h2>
            <div class="line cf">
                <div class="history_box">
                    <div class="chart"  id="chart_daytimes">
                        <canvas id="daytimes" height="278" width="278"></canvas>
                        <?php
                            $daytimes_count = array_sum($daytimes);
                            $first_daytime = key($daytimes);
                            switch ($first_daytime){
                            case '晴':
                                $class = 'shine';
                                break;
                            case '雨':
                                $class = 'rain';
                                break;
                            case '曇':
                                $class = 'cloud';
                                break;
                            case '雪':
                                $class = 'snow';
                                break;
                            case '雷':
                                $class = 'thunder';
                                break;
                            case '霧':
                                $class = 'mist';
                                break;
                            default:
                                $class = '';
                            }
                        ?>
                        <div class="count <?php echo $class; ?>">
                            <?php $string = ''; ?>
                            <table>
                                 <tr><td>天気</td><td>登場数</td></tr>
                                 <?php foreach ($daytimes as $daytime_weather => $daytime_count) : ?>
                                 <?php
                                    if($daytime_weather == '晴'){
                                        $string .= '{value: '.$daytime_count.',color:"#F38630"},';//FDB45C
                                        $image = '/images/weather/shine_color.gif';
                                    }elseif ($daytime_weather == '雨'){
                                        $string .= '{value: '.$daytime_count.',color:"#69D2E7"},';//#66ccff
                                        $image = '/images/weather/rain_color.gif';
                                    }elseif ($daytime_weather == '曇'){
                                        $string .= '{value: '.$daytime_count.',color:"#949FB1"},';//E0E4CC
                                        $image = '/images/weather/cloud_color.gif';
                                    }elseif ($daytime_weather == '雪'){
                                        $string .= '{value: '.$daytime_count.',color:"#E2EAE9"},';
                                        $image = '/images/weather/snow_color.gif';
                                    }elseif ($daytime_weather == '雷'){
                                        $string .= '{value: '.$daytime_count.',color:"#F3E91F"},';
                                        $image = '/images/weather/thunder_color.gif';
                                    }elseif ($daytime_weather == '霧'){
                                        $string .= '{value: '.$daytime_count.',color:"#D4CCC5"},';
                                        $image = '/images/weather/fog_color.gif';
                                    }else{
                                        $string .= '{value: 0,color:"#FFFFFF"},';
                                        $image = '/images/weather/null_color.gif';
                                    }
                                 ?>
                                 <tr><td><img src="<?php echo $image; ?>"><?php echo $daytime_weather; ?></td><td><?php echo $daytime_count; ?>回 <?php echo round( ($daytime_count / $daytimes_count) * 100); ?>%</td></tr>
                                 <?php endforeach; ?>
                            </table>
                        </div>
                    </div>

                    <script>
                        var doughnutData = [<?php echo $string; ?>];

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
                </div>
                <div class="history_box">
                    <?php
                    $data_string = implode(',',$precipitation_total['data']);
                    $year_string = implode(',',$precipitation_total['year']);
                    ?>
                    <div id="chart_precipitation_total">
                        <canvas id="precipitation_total" width="278" height="278"></canvas>
                        <div class="count">
                           <em>降水量の推移</em><br>
                           <span class="caption">(mm)</span>
                        </div>
                    </div>

                    <script>
                        var linedata = {
                            labels : [<?php echo $year_string; ?>],
                            datasets : [
                                {
                                    fillColor : "rgba(0,180,255,0.1)",
                                    strokeColor : "#66ccff",
                                    pointColor : "#66ccff",
                                    pointStrokeColor : "#fff",
                                    data : [<?php echo $data_string; ?>]
                                }
                            ]
                        }
                        var ctx2 = document.getElementById("precipitation_total").getContext("2d");
                        new Chart(ctx2).Line(linedata,{
                            scaleOverlay : true,
                            scaleOverride : true,
                            scaleSteps : 3,
                            scaleStepWidth : 5,
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
                </div>
                <div class="history_box">
                    <div class="chart"  id="chart_feel_temperatures">
                        <canvas id="feel_temperatures" height="278" width="278"></canvas>
                        <div class="count">
                            <?php $string = ''; ?>
                            <table>
                                <?php $feel_temperatures_count = array_sum($feel_temperatures); ?>
                                <tr><td>体感</td><td>回数</td></tr>
                                <?php $i = 0; ?>
                                <?php foreach ($feel_temperatures as $feel_temperature_name => $feel_temperature_count) : ?>
                                 <?php
                                    if($feel_temperature_name == '暑い'){
                                        $string .= '{value: '.$feel_temperature_count.',color:"#FF3300"},';//FDB45C
                                        if($i == 0) $image = '/images/weather/hot.jpg';
                                    }elseif ($feel_temperature_name == '暖かい'){
                                        $string .= '{value: '.$feel_temperature_count.',color:"#FF9200"},';//#66ccff
                                        if($i == 0) $image = '/images/weather/warm.jpg';
                                    }elseif ($feel_temperature_name == '寒い'){
                                        $string .= '{value: '.$feel_temperature_count.',color:"#3ED0FC"},';//E0E4CC
                                        if($i == 0) $image = '/images/weather/cool.jpg';
                                    }elseif ($feel_temperature_name == '凍える'){
                                        $string .= '{value: '.$feel_temperature_count.',color:"#1765BB"},';
                                        if($i == 0) $image = '/images/weather/freeze.jpg';
                                    }else{
                                        $string .= '{value: 0,color:"#FFFFFF"},';
                                    }
                                 ?>
                                 <tr><td><?php echo $feel_temperature_name; ?></td><td><?php echo $feel_temperature_count; ?>回 <?php echo round( ($feel_temperature_count / $feel_temperatures_count) * 100); ?>%</td></tr>
                                 <?php $i++; ?>
                                 <?php endforeach; ?>
                            </table>
                            <style type="text/css">
                            <!--
                            #chart_feel_temperatures .count {
                                background: url(<?php echo $image; ?>) -40px -20px no-repeat;
                            }
                            -->
                            </style>
                        </div>
                    </div>

                    <script>
                        var doughnutData = [<?php echo $string; ?>];

                    //var myDoughnut = new Chart(document.getElementById("canvas").getContext("2d")).Doughnut(doughnutData);
                    //var ctx = new Chart(document.getElementById("canvas").getContext("2d");
                    var ctx = document.getElementById("feel_temperatures").getContext("2d");
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
                </div>
            </div>
        </div>
<?php if(!empty($springs)) : ?>
<?php $this->load->view('layout/parts/date_area_plans'); ?>
<?php else: ?>
<?php $this->load->view('layout/parts/date_spring_plans'); ?>
<?php endif; ?>
<?php $this->load->view('layout/common/recommend_futures'); ?>

        <div id="backnumber" class="undisp">
            <h2><?php echo $backnumber_title; ?></h2>
            <table>
                <tr><th class="top" rowspan="4">日</th><th class="top" colspan="2">気圧(hPa)</th><th class="top" rowspan="2" colspan="3">降水量(mm)</th><th class="top" rowspan="2" colspan="3">気温(℃)</th><th class="top" rowspan="2" colspan="2">湿度(％)</th><th class="top" rowspan="2" colspan="5">風向・風速(m/s)</th><th class="top" rowspan="4">日照<br>時間<br>(h)</th><th class="top" rowspan="2" colspan="2">雪(cm)</th><th class="top" rowspan="2" colspan="2">天気概況</th></tr>
                <tr><th>現地</th><th colspan="1">海面</th></tr>
                <tr><th rowspan="2">平均</th><th rowspan="2">平均</th><th rowspan="2">合計</th><th colspan="2">最大</th><th rowspan="2">平均</th><th rowspan="2">最高</th><th rowspan="2">最低</th><th rowspan="2">平均</th><th rowspan="2">最小</th><th rowspan="2">平均<br>風速</th><th colspan="2">最大風速</th><th colspan="2">最大瞬間風速</th><th colspan="1">降雪</th><th colspan="1">最深積雪</th><th rowspan="2">昼<br>(06:00-18:00)</th><th rowspan="2">夜<br>(18:00-翌日06:00)</th></tr>
                <tr><th>1時間</th><th>10分間</th><th>風速</th><th>風向</th><th>風速</th><th>風向</th><th>合計</th><th>値</th></tr>
                <?php foreach ($month_day_weathers as $month_day_weather) : ?>
                <tr>
                    <td class="date"><?php echo $month_day_weather->year; ?><br /><?php echo $month_day_weather->month; ?>/<?php echo $month_day_weather->day; ?></td>
                    <td><?php echo $month_day_weather->atmosphere_spot; ?></td>
                    <td><?php echo $month_day_weather->atmosphere_ocean; ?></td>
                    <td><?php echo $month_day_weather->precipitation_total; ?></td>
                    <td><?php echo $month_day_weather->precipitation_one_hour; ?></td>
                    <td><?php echo $month_day_weather->precipitation_ten_minute; ?></td>
                    <td><?php echo $month_day_weather->temperature_average; ?></td>
                    <td><?php echo $month_day_weather->temperature_max; ?></td>
                    <td><?php echo $month_day_weather->temperature_min; ?></td>
                    <td><?php echo $month_day_weather->moisture_average; ?></td>
                    <td><?php echo $month_day_weather->moisture_min; ?></td>
                    <td><?php echo $month_day_weather->wind_speed_average; ?></td>
                    <td><?php echo $month_day_weather->wind_speed_max; ?></td>
                    <td style="text-align:left"><?php echo $month_day_weather->wind_speed_max_direction; ?></td>
                    <td><?php echo $month_day_weather->instant_wind_speed_max; ?></td>
                    <td style="text-align:left"><?php echo $month_day_weather->instant_wind_speed_max_direction; ?></td>
                    <td><?php echo $month_day_weather->sunshine_hours; ?></td>
                    <td><?php echo $month_day_weather->snowfall; ?></td>
                    <td><?php echo $month_day_weather->snow_deepest; ?></td>
                    <td style="text-align:left"><?php echo $month_day_weather->daytime; ?></td>
                    <td style="text-align:left"><?php echo $month_day_weather->night; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>