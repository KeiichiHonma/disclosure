<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $search_keywords; ?>の検索</h1>
        <div id="document">
            <table class="finance">
                <tr>
                    <th class="code">証券<br />コード</th>
                    <th class="company_name">企業名</th>
                    <th class="market">市場</th>
                    <th class="market">業種</th>
                </tr>
                <?php $count = count($edinets);$i = 1; ?>
                <?php foreach ($edinets as $edinet) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="txt"><?php echo $edinet->security_code; ?></td>
                    <td class="txt">
                    <?php echo $edinet->presenter_name; ?><br />
                    <?php echo anchor('document/show/'.$edinet->presenter_name_key, '有報'); ?>&nbsp;&nbsp;<?php echo anchor('income/show/'.$edinet->presenter_name_key, '年収'); ?>&nbsp;&nbsp;<?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/pl','P/L'); ?>&nbsp;&nbsp;<?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/bs','BS'); ?>&nbsp;&nbsp;<?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/cf','CF'); ?>
                    </td>
                    <td class="txt">
                    <?php if(isset($markets[$edinet->market_id])): ?>
                    <?php echo $markets[$edinet->market_id]->name; ?><br />
                    <?php echo anchor('document/market/'.$edinet->market_id, '有報'); ?>&nbsp;&nbsp;<?php echo anchor('income/market/'.$edinet->market_id, '年収'); ?>&nbsp;&nbsp;<?php echo anchor('finance/market/'.$edinet->market_id.'/pl','P/L'); ?>&nbsp;&nbsp;<?php echo anchor('finance/market/'.$edinet->market_id.'/bs','BS'); ?>&nbsp;&nbsp;<?php echo anchor('finance/market/'.$edinet->market_id.'/cf','CF'); ?>
                    <?php endif; ?>
                    </td>
                    <td class="txt">
                    <?php echo $edinet->category_name; ?><br />
                    <?php echo anchor('document/category/'.$edinet->category_id, '有報'); ?>&nbsp;&nbsp;<?php echo anchor('income/category/'.$edinet->category_id, '年収'); ?>&nbsp;&nbsp;<?php echo anchor('finance/category/'.$edinet->category_id.'/pl','P/L'); ?>&nbsp;&nbsp;<?php echo anchor('finance/category/'.$edinet->category_id.'/bs','BS'); ?>&nbsp;&nbsp;<?php echo anchor('finance/category/'.$edinet->category_id.'/cf','CF'); ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div id="side_cat">
                <?php $this->load->view('layout/common/categories'); ?>
            </div><!-- /side_cat -->
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
