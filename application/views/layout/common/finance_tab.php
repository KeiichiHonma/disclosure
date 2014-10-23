        <ul class="tabrow">
            <li<?php if($finance_tab_current == 'top'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key, 'ファイナンストップ'); ?></li>
            <li<?php if($finance_tab_current == 'pl'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/pl', $this->lang->line('common_title_pl')); ?></li>
            <li<?php if($finance_tab_current == 'bs'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/bs', $this->lang->line('common_title_bs')); ?></li>
            <li<?php if($finance_tab_current == 'cf'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/cf', $this->lang->line('common_title_cf')); ?></li>
        </ul>