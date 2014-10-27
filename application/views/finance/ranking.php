<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">

        <div id="document_navi">
            <h3 class="center_dot"><span><?php echo $page_title; ?></span></h3>
        </div>

        <div id="document">
            <?php $this->load->view('layout/common/pager'); ?>
            <table class="finance">
                <tr>
                    <th>提出日</th>
                    <th class="code">証券<br />コード</th>
                    <th class="company_name">企業名</th>
                    <?php if($type == "pl"): ?>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'net-sales' ? 'net-salesRev' : 'net-sales').'/'.$page,'売上高'.($order == 'net-sales' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-salesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'cost-of-sales' ? 'cost-of-salesRev' : 'cost-of-sales').'/'.$page,'売上原価'.($order == 'cost-of-sales' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'cost-of-salesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'gross-profit' ? 'gross-profitRev' : 'gross-profit').'/'.$page,'売上<br />総利益'.($order == 'gross-profit' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'gross-profitRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'operating-income' ? 'operating-incomeRev' : 'operating-income').'/'.$page,'営業<br />利益'.($order == 'operating-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'operating-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'ordinary-income' ? 'ordinary-incomeRev' : 'ordinary-income').'/'.$page,'経常<br />利益'.($order == 'ordinary-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'ordinary-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'extraordinary-total' ? 'extraordinary-totalRev' : 'extraordinary-total').'/'.$page,'特別損益<br />収支'.($order == 'extraordinary-total' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'extraordinary-totalRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'net-income' ? 'net-incomeRev' : 'net-income').'/'.$page,'当期<br />純利益'.($order == 'net-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <?php endif; ?>
                    <?php if($type == "bs"): ?>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'assets' ? 'assetsRev' : 'assets').'/'.$page,'資産合計'.($order == 'assets' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'assetsRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'liabilities' ? 'liabilitiesRev' : 'liabilities').'/'.$page,'負債合計'.($order == 'liabilities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'liabilitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'capital-stock' ? 'capital-stockRev' : 'capital-stock').'/'.$page,'資本金'.($order == 'capital-stock' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'capital-stockRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'shareholders-equity' ? 'shareholders-equityRev' : 'shareholders-equity').'/'.$page,'株主資本'.($order == 'shareholders-equity' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'shareholders-equityRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <?php endif; ?>
                    <?php if($type == "cf"): ?>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'net-income' ? 'net-incomeRev' : 'net-income').'/'.$page,'当期<br />純利益'.($order == 'net-income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'depreciation-and-amortization' ? 'depreciation-and-amortizationRev' : 'depreciation-and-amortization').'/'.$page,'減価償却'.($order == 'depreciation-and-amortization' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'depreciation-and-amortizationRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'net-cash-provided-by-used-in-operating-activities' ? 'net-cash-provided-by-used-in-operating-activitiesRev' : 'net-cash-provided-by-used-in-operating-activities').'/'.$page,'営業CF'.($order == 'net-cash-provided-by-used-in-operating-activities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-cash-provided-by-used-in-operating-activitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'net-cash-provided-by-used-in-investing-activities' ? 'net-cash-provided-by-used-in-investing-activitiesRev' : 'net-cash-provided-by-used-in-investing-activities').'/'.$page,'投資CF'.($order == 'net-cash-provided-by-used-in-investing-activities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-cash-provided-by-used-in-investing-activitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'net-cash-provided-by-used-in-financing-activities' ? 'net-cash-provided-by-used-in-financing-activitiesRev' : 'net-cash-provided-by-used-in-financing-activities').'/'.$page,'財務CF'.($order == 'net-cash-provided-by-used-in-financing-activities' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-cash-provided-by-used-in-financing-activitiesRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <th class="data"><?php echo anchor('finance/ranking/'.$type.'/'.$year.'/'.($order == 'net-increase-decrease-in-cash-and-cash-equivalents' ? 'net-increase-decrease-in-cash-and-cash-equivalentsRev' : 'net-increase-decrease-in-cash-and-cash-equivalents').'/'.$page,'CF'.($order == 'net-increase-decrease-in-cash-and-cash-equivalents' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'net-increase-decrease-in-cash-and-cash-equivalentsRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?></th>
                    <?php endif; ?>
                    <th class="market">市場<br />業種</th>
                </tr>
                <?php $count = count($finances);$i = 1; ?>
                <?php foreach ($finances as $finance) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="txt"><?php echo strftime("%y/", strtotime($finance->date)); ?></span><?php echo strftime("%m/%d", strtotime($finance->date)); ?></td>
                    <td class="txt"><?php echo $finance->security_code > 0 ? anchor('finance/show/'.$finance->presenter_name_key,$finance->security_code) : '-'; ?></td>
                    <td class="txt"><?php echo anchor('finance/show/'.$finance->presenter_name_key,$finance->presenter_name); ?></td>
                    <?php if($type == "pl"): ?>
                    <td><?php echo number_format($finance->net_sales); ?></td>
                    <td><?php echo number_format($finance->cost_of_sales); ?></td>
                    <td><?php echo number_format($finance->gross_profit); ?></td>
                    <td><?php echo number_format($finance->operating_income); ?></td>
                    <td><?php echo number_format($finance->ordinary_income); ?></td>
                    <td><?php echo number_format($finance->extraordinary_total); ?></td>
                    <td><?php echo number_format($finance->net_income); ?></td>
                    <?php endif; ?>
                    <?php if($type == "bs"): ?>
                    <td><?php echo number_format($finance->assets); ?></td>
                    <td><?php echo number_format($finance->liabilities); ?></td>
                    <td><?php echo number_format($finance->capital_stock); ?></td>
                    <td><?php echo number_format($finance->shareholders_equity); ?></td>
                    <?php endif; ?>
                    <?php if($type == "cf"): ?>
                    <td><?php echo number_format($finance->net_income); ?></td>
                    <td><?php echo number_format($finance->depreciation_and_amortization); ?></td>
                    <td><?php echo number_format($finance->net_cash_provided_by_used_in_operating_activities); ?></td>
                    <td><?php echo number_format($finance->net_cash_provided_by_used_in_investing_activities); ?></td>
                    <td><?php echo number_format($finance->net_cash_provided_by_used_in_financing_activities); ?></td>
                    <td><?php echo number_format($finance->net_increase_decrease_in_cash_and_cash_equivalents); ?></td>
                    <?php endif; ?>

                    <td class="txt">
                    <?php echo anchor('finance/category/'.$finance->category_id.'/'.$type,$finance->category_name); ?>
                    <?php if(isset($markets[$finance->market_id])): ?><br /><?php echo anchor('finance/market/'.$finance->market_id.'/'.$type,$markets[$finance->market_id]->name); ?><?php endif; ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <?php $this->load->view('layout/common/pager'); ?>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div id="side_cat">
                <?php $this->load->view('layout/common/finance_category'); ?>
            </div><!-- /side_cat -->
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
