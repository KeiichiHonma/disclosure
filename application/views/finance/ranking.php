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
            <?php $this->load->view('common/pager'); ?>
            <table>
                <tr>
                    <th class="code undisp">証券コード</th>
                    <th>企業名</th>
                    <?php if($type == "bs"): ?>
                    <th>流動資産</th>
                    <th>固定資産</th>
                    <th>資産合計</th>
                    <th>流動負債</th>
                    <th>固定負債</th>
                    <th>負債合計</th>
                    <th>資本金</th>
                    <th>株主資本</th>
                    <?php endif; ?>
                    <?php if($type == "pl"): ?>
                    <th>売上高</th>
                    <th>売上原価</th>
                    <th>売上総利益</th>
                    <th>営業利益</th>
                    <th>経常利益</th>
                    <th>特別利益</th>
                    <th>特別損失</th>
                    <th>特別損益収支</th>
                    <th>当期純利益</th>
                    <?php endif; ?>
                    <?php if($type == "cf"): ?>
                    <th>当期純利益</th>
                    <th>減価償却</th>
                    <th>営業CF</th>
                    <th>投資CF</th>
                    <th>財務CF</th>
                    <th>キャッシュフロー</th>
                    <?php endif; ?>
                    <th>業種</th>
                    <th>市場</th>
                    
                </tr>
                <?php $count = count($finances);$i = 1; ?>
                <?php foreach ($finances as $finance) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td><?php echo $finance->security_code > 0 ? anchor('finance/show/'.$finance->presenter_name_key,$finance->security_code) : '-'; ?></td>
                    <td><?php echo anchor('finance/show/'.$finance->presenter_name_key,$finance->presenter_name); ?></td>
                    <?php if($type == "bs"): ?>
                    <td><?php echo number_format($finance->current_assets); ?></td>
                    <td><?php echo number_format($finance->noncurrent_assets); ?></td>
                    <td><?php echo number_format($finance->assets); ?></td>
                    <td><?php echo number_format($finance->current_liabilities); ?></td>
                    <td><?php echo number_format($finance->noncurrent_liabilities); ?></td>
                    <td><?php echo number_format($finance->liabilities); ?></td>
                    <td><?php echo number_format($finance->capital_stock); ?></td>
                    <td><?php echo number_format($finance->shareholders_equity); ?></td>
                    <?php endif; ?>
                    <?php if($type == "pl"): ?>
                    <td><?php echo number_format($finance->net_sales); ?></td>
                    <td><?php echo number_format($finance->cost_of_sales); ?></td>
                    <td><?php echo number_format($finance->gross_profit); ?></td>
                    <td><?php echo number_format($finance->operating_income); ?></td>
                    <td><?php echo number_format($finance->ordinary_income); ?></td>
                    <td><?php echo number_format($finance->extraordinary_income); ?></td>
                    <td><?php echo number_format($finance->extraordinary_losses); ?></td>
                    <td><?php echo number_format($finance->extraordinary_total); ?></td>
                    <td><?php echo number_format($finance->net_income); ?></td>
                    <?php endif; ?>
                    <?php if($type == "cf"): ?>
                    <td><?php echo number_format($finance->net_income); ?></td>
                    <td><?php echo number_format($finance->depreciation_and_amortization); ?></td>
                    <td><?php echo number_format($finance->net_cash_provided_by_used_in_operating_activities); ?></td>
                    <td><?php echo number_format($finance->net_cash_provided_by_used_in_investing_activities); ?></td>
                    <td><?php echo number_format($finance->net_cash_provided_by_used_in_financing_activities); ?></td>
                    <td><?php echo number_format($finance->net_increase_decrease_in_cash_and_cash_equivalents); ?></td>
                    <?php endif; ?>

                    <td><?php echo anchor('finance/category/'.$finance->category_id.'/'.$type.(!is_null($year) && is_numeric($year) ? '/'.$year : ''),$finance->category_name); ?></td>
                    <td><?php if(isset($markets[$finance->market_id])): ?><?php echo anchor('finance/market/'.$finance->market_id.'/'.$type.(!is_null($year) && is_numeric($year) ? '/'.$year : ''),$markets[$finance->market_id]->name); ?><?php endif; ?></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="sidebar">
            <div id="side_cat">
                <h1 class="side_title">業界カテゴリ</h1>
                <ul>
                <?php foreach ($categories as $category) : ?>
                <?php if($category->id > 1): ?>
                <li<?php if(isset($category_id) && $category->id == $category_id): ?> class="current"<?php endif; ?>><?php echo anchor('finance/category/'.$category->id.'/'.$type.(!is_null($year) && is_numeric($year) ? '/'.$year : ''),'<span>'.$category->name.'</span>'); ?></li>
                <?php endif; ?>
                <?php endforeach; ?>
                </ul>
            </div><!-- /side_cat -->
            <div class="box_wrap">
                <div class="box_adx pcdisp">
                    <img src="/images/ad_example1.gif" alt="" />
                </div>
                <div class="box_adx spdisp">
                    <img src="/images/ad_example_sp1.jpg" alt="" />
                </div>
            </div>
        </div>
        <span class="cf" />
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
