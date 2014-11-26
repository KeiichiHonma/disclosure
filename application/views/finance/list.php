<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner"><?php $this->load->view('layout/common/topicpath'); ?>

        <div id="document_navi">
            <h3 class="center_dot"><span><?php echo $page_title; ?></span></h3>
        </div>

        <div id="document" class="pt10">
            <?php $this->load->view('layout/common/select_year'); ?>
            <?php $this->load->view('layout/common/pager'); ?>
            <table class="finance">
                <tr>
                    <?php $this->load->view('layout/common/finance_list_header'); ?>
                </tr>
                <?php $count = count($finances);$i = 1; ?>
                <?php foreach ($finances as $finance) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="txt"><?php echo strftime("%y/", strtotime($finance->date)); ?></span><?php echo strftime("%m/%d", strtotime($finance->date)); ?></td>
                    <td class="txt"><?php echo $finance->security_code > 0 ? anchor('finance/show/'.$finance->presenter_name_key,$finance->security_code) : '-'; ?></td>
                    <td class="txt"><?php echo anchor('finance/show/'.$finance->presenter_name_key,$finance->presenter_name); ?></td>
                    <?php if($type == "pl"): ?>
                    <td><?php echo new_number_format($finance->net_sales); ?></td>
                    <td><?php echo new_number_format($finance->cost_of_sales); ?></td>
                    <td><?php echo new_number_format($finance->gross_profit); ?></td>
                    <td><?php echo new_number_format($finance->operating_income); ?></td>
                    <td><?php echo new_number_format($finance->ordinary_income); ?></td>
                    <td><?php echo new_number_format($finance->extraordinary_total); ?></td>
                    <td><?php echo new_number_format($finance->net_income); ?></td>
                    <?php endif; ?>
                    <?php if($type == "bs"): ?>
                    <td><?php echo new_number_format($finance->assets); ?></td>
                    <td><?php echo new_number_format($finance->liabilities); ?></td>
                    <td><?php echo new_number_format($finance->capital_stock); ?></td>
                    <td><?php echo new_number_format($finance->shareholders_equity); ?></td>
                    <?php endif; ?>
                    <?php if($type == "cf"): ?>
                    <td><?php echo new_number_format($finance->net_income); ?></td>
                    <td><?php echo new_number_format($finance->depreciation_and_amortization); ?></td>
                    <td><?php echo new_number_format($finance->net_cash_provided_by_used_in_operating_activities); ?></td>
                    <td><?php echo new_number_format($finance->net_cash_provided_by_used_in_investing_activities); ?></td>
                    <td><?php echo new_number_format($finance->net_cash_provided_by_used_in_financing_activities); ?></td>
                    <td><?php echo new_number_format($finance->net_increase_decrease_in_cash_and_cash_equivalents); ?></td>
                    <?php endif; ?>

                    <td class="txt">
                    <?php echo anchor('finance/category/'.$finance->category_id.'/'.$type,$finance->category_name); ?>
                    <?php if(isset($markets[$finance->market_id])): ?><br /><?php echo anchor('finance/market/'.$finance->market_id.'/'.$type,$markets[$finance->market_id]->name); ?><?php endif; ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
            <div class="mt10"><?php $this->load->view('layout/common/pager'); ?></div>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div id="side_cat">
                <?php if(isset($market_id)): ?>
                    <?php $this->load->view('layout/common/finance_markets'); ?>
                    <?php $this->load->view('layout/common/finance_categories'); ?>
                <?php else: ?>
                    <?php $this->load->view('layout/common/finance_categories'); ?>
                    <?php $this->load->view('layout/common/finance_markets'); ?>
                <?php endif; ?>
                
            </div><!-- /side_cat -->
            <?php $this->load->view('layout/common/ads/adsense_side_2'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
