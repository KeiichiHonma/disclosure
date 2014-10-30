        <ul class="tabrow">
            <li<?php if($finance_tab_current == 'top'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key, $this->lang->line('common_title_finance_top')); ?></li>
            <li<?php if($finance_tab_current == 'pl'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/pl', $this->lang->line('common_title_finance_pl')); ?></li>
            <li<?php if($finance_tab_current == 'bs'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/bs', $this->lang->line('common_title_finance_bs')); ?></li>
            <li<?php if($finance_tab_current == 'cf'): ?> class="selected"<?php endif; ?>><?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/cf', $this->lang->line('common_title_finance_cf')); ?></li>
        </ul>