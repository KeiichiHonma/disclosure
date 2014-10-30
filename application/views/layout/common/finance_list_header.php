                    <th>提出日</th>
                    <th class="code">証券<br />コード</th>
                    <th class="company_name">企業名</th>
                    <?php if($type == "pl"): ?>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'net-sales' ? 'net-salesRev' : 'net-sales').'/'.$page,'売上高'.($order == 'net-sales' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-salesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>売上高<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'cost-of-sales' ? 'cost-of-salesRev' : 'cost-of-sales').'/'.$page,'売上原価'.($order == 'cost-of-sales' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'cost-of-salesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>売上原価<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'gross-profit' ? 'gross-profitRev' : 'gross-profit').'/'.$page,'売上<br />総利益'.($order == 'gross-profit' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'gross-profitRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>売上<br />総利益<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'operating-income' ? 'operating-incomeRev' : 'operating-income').'/'.$page,'営業<br />利益'.($order == 'operating-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'operating-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>営業<br />利益<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'ordinary-income' ? 'ordinary-incomeRev' : 'ordinary-income').'/'.$page,'経常<br />利益'.($order == 'ordinary-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'ordinary-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>経常<br />利益<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'extraordinary-total' ? 'extraordinary-totalRev' : 'extraordinary-total').'/'.$page,'特別損益<br />収支'.($order == 'extraordinary-total' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'extraordinary-totalRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>特別損益<br />収支<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'net-income' ? 'net-incomeRev' : 'net-income').'/'.$page,'当期<br />純利益'.($order == 'net-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>当期<br />純利益<?php endif; ?></th>
                    <?php endif; ?>
                    <?php if($type == "bs"): ?>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'assets' ? 'assetsRev' : 'assets').'/'.$page,'資産合計'.($order == 'assets' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'assetsRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>資産合計<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'liabilities' ? 'liabilitiesRev' : 'liabilities').'/'.$page,'負債合計'.($order == 'liabilities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'liabilitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>負債合計<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'capital-stock' ? 'capital-stockRev' : 'capital-stock').'/'.$page,'資本金'.($order == 'capital-stock' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'capital-stockRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>資本金<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'shareholders-equity' ? 'shareholders-equityRev' : 'shareholders-equity').'/'.$page,'株主資本'.($order == 'shareholders-equity' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'shareholders-equityRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>株主資本<?php endif; ?></th>
                    <?php endif; ?>
                    <?php if($type == "cf"): ?>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'net-income' ? 'net-incomeRev' : 'net-income').'/'.$page,'当期<br />純利益'.($order == 'net-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>当期<br />純利益<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'depreciation-and-amortization' ? 'depreciation-and-amortizationRev' : 'depreciation-and-amortization').'/'.$page,'減価償却'.($order == 'depreciation-and-amortization' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'depreciation-and-amortizationRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>減価償却<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'net-cash-provided-by-used-in-operating-activities' ? 'net-cash-provided-by-used-in-operating-activitiesRev' : 'net-cash-provided-by-used-in-operating-activities').'/'.$page,'営業CF'.($order == 'net-cash-provided-by-used-in-operating-activities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-cash-provided-by-used-in-operating-activitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>営業CF<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'net-cash-provided-by-used-in-investing-activities' ? 'net-cash-provided-by-used-in-investing-activitiesRev' : 'net-cash-provided-by-used-in-investing-activities').'/'.$page,'投資CF'.($order == 'net-cash-provided-by-used-in-investing-activities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-cash-provided-by-used-in-investing-activitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>投資CF<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'net-cash-provided-by-used-in-financing-activities' ? 'net-cash-provided-by-used-in-financing-activitiesRev' : 'net-cash-provided-by-used-in-financing-activities').'/'.$page,'財務CF'.($order == 'net-cash-provided-by-used-in-financing-activities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-cash-provided-by-used-in-financing-activitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>財務CF<?php endif; ?></th>
                    <th class="data"><?php if(!isset($finance_index)): ?><?php echo anchor('finance/'.$function_name.'/'.$object_id.'/'.$type.'/'.$year.'/'.($order == 'net-increase-decrease-in-cash-and-cash-equivalents' ? 'net-increase-decrease-in-cash-and-cash-equivalentsRev' : 'net-increase-decrease-in-cash-and-cash-equivalents').'/'.$page,'CF'.($order == 'net-increase-decrease-in-cash-and-cash-equivalents' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-increase-decrease-in-cash-and-cash-equivalentsRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?><?php else: ?>CF<?php endif; ?></th>
                    <?php endif; ?>
                    <th class="market">市場<br />業種</th>