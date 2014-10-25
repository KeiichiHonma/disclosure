<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $this->lang->line('common_title_issues'); ?></h1>
            <div class="sort pager">
                <ul class="clearfix">
                    <li><?php echo anchor('site/issues/1', '1000番台',($page == 1 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/2', '2000番台',($page == 2 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/3', '3000番台',($page == 3 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/4', '4000番台',($page == 4 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/5', '5000番台',($page == 5 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/6', '6000番台',($page == 6 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/7', '7000番台',($page == 7 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/8', '8000番台',($page == 8 ? 'class="active"' : '')); ?></li>
                    <li><?php echo anchor('site/issues/9', '9000番台',($page == 9 ? 'class="active"' : '')); ?></li>
                </ul>
            </div>
            <div id="issues">
            <table>
                <?php foreach ($chunk_edinets as $edinets) : ?>
                <tr>
                    <?php foreach ($edinets as $edinet) : ?>
                    <td>
<strong><?php echo anchor('finance/show/'.$edinet->presenter_name_key,$edinet->security_code.'<br />'.$edinet->presenter_name); ?></strong><br />
<span style="font-size:90%;"><?php echo anchor('income/show/'.$edinet->presenter_name_key,'年収'); ?>&nbsp;<?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/pl','P/L'); ?>&nbsp;<?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/bs','BS'); ?>&nbsp;<?php echo anchor('finance/show/'.$edinet->presenter_name_key.'/cf','CF'); ?><br />
<?php echo anchor('finance/category/'.$edinet->category_id.'/pl',$edinet->category_name); ?>
<?php if(isset($markets[$edinet->market_id])): ?><br /><?php echo anchor('finance/market/'.$edinet->market_id.'/pl',$markets[$edinet->market_id]->name); ?><?php endif; ?></span>

                    
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </table>
            </div>
            <?php $this->load->view('common/ads/adsense_bottom'); ?>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
