            <div class="sort pager pb10">
                <ul class="clearfix">
                    <?php $now_year = date("Y",time()); ?>
                    <?php for ($i=$now_year;$i>=2009;$i--): ?>
                    <?php $year_string = $i == $now_year ? '' : '/'.$i; ?>
                    <li><?php echo anchor($class_name.'/'.$function_name.'/'.$object_id.$year_string, $i.'年',($year == $i ? 'class="active"' : '')); ?></li>
                    <?php endfor; ?>
                </ul>
            </div>