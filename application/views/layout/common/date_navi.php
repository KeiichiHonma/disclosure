            <div id="tabs" class="cf mb20">
                <ul id="tabs_ul">
                    <li class="undisp"><a href="#tabs-8" id="tab8" class="next_tab">< 前週を見る</a></li>
                    <?php
                        $i = 1;
                        $count = count($seven_dates);
                    ?>
                    <?php foreach ($seven_dates as $seven_date) : ?>
                    <li><a href="<?php echo '/document/date/'.$seven_date->date; ?>" id="tab<?php echo $i; ?>" class="change_tab<?php if($i == $count) echo ' tabulous_active'; ?>"><?php echo date("n月j日",strtotime($seven_date->date)); ?></a></li>
                    <?php $i++; ?>
                    <?php endforeach ?>
                </ul>
            </div><!--End tabs-->