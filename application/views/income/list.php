<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner"><?php $this->load->view('layout/common/topicpath'); ?>
        <?php if(isset($is_index)): ?>
        <div id="document_navi"><h3 class="center_dot"><span><?php echo $page_title; ?></span></h3></div>
        <?php else: ?>
        <h3 class="l1"><?php echo $page_title; ?></h3>
        <?php endif; ?>
        
        
        <div id="document"<?php if(!isset($is_index)) echo ' class="pt10"'; ?>>
            <?php if(!empty($cdatas)): ?>
                <?php if(!isset($is_index)) $this->load->view('layout/common/select_year'); ?>
                <?php if(!isset($is_index)) $this->load->view('layout/common/pager'); ?>
                <table class="finance">
                    <tr>
                        <th class="date">
                        <?php if(isset($is_index)): ?>
                        提出日
                        <?php else: ?>
                        <?php echo anchor('income/'.$function_name.'/'.$object_id.'/'.$year.'/'.($order == 'disclosure' ? 'disclosureRev' : 'disclosure').'/'.$page,'提出日'.($order == 'disclosure' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'disclosureRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?>
                        <?php endif; ?>
                        </th>
                        <th class="code">証券<br />コード</th>
                        <th class="company_name">企業名</th>
                        <th class="income">
                        <?php if(isset($is_index)): ?>
                        年収
                        <?php else: ?>
                        <?php echo anchor('income/'.$function_name.'/'.$object_id.'/'.$year.'/'.($order == 'income' ? 'incomeRev' : 'income').'/'.$page,'年収'.($order == 'income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?>
                        <?php endif; ?>
                        </th>
                        <th class="trend">前年比</th>
                        <th class="market">市場<br />業種</th>
                    </tr>
                    <?php $count = count($cdatas);$i = 1; ?>
                    <?php foreach ($cdatas as $cdata) : ?>
                    <tr<?php if($count == $i) echo ' class="last"'; ?>>
                        <td class="txt"><?php echo strftime("%y/%m/%d", $cdata->col_disclosure); ?></td>
                        <td class="txt"><?php echo anchor('income/show/'.$cdata->presenter_name_key, $cdata->col_code); ?></td>
                        <td class="txt">
                        <?php echo anchor('income/show/'.$cdata->presenter_name_key, $cdata->col_name); ?><br />
                        <?php echo anchor('finance/show/'.$cdata->presenter_name_key.'/pl','P/L'); ?>&nbsp;&nbsp;<?php echo anchor('finance/show/'.$cdata->presenter_name_key.'/bs','BS'); ?>&nbsp;&nbsp;<?php echo anchor('finance/show/'.$cdata->presenter_name_key.'/cf','CF'); ?>
                        </td>
                        <td class="data"><?php echo $cdata->col_income; ?>万円</td>
                        <?php
                        if($cdata->col_income_trend == 1){
                            $trend_image = 'up.gif';
                        }elseif($cdata->col_income_trend == 2){
                            $trend_image = 'down.gif';
                        }elseif($cdata->col_income_trend == 0){
                            $trend_image = 'new.gif';
                        }elseif($cdata->col_income_trend == 3){
                            $trend_image = 'stay.gif';
                        }
                        ?>
                        <td class="txt"><img src="/images/income/<?php echo $trend_image; ?>" /></td>
                        <td class="txt">
                        <?php echo anchor('income/category/'.$cdata->category_id,$cdata->category_name); ?>
                        <?php if(isset($markets[$cdata->market_id])): ?><br /><?php echo anchor('income/market/'.$cdata->market_id,$markets[$cdata->market_id]->name); ?><?php endif; ?>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                </table>
                <?php if(!isset($is_index)) $this->load->view('layout/common/pager'); ?>
            <?php else: ?>
                <div class="blank">指定の年収情報がありません。</div>
            <?php endif; ?>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div id="side_cat">
                <?php if(isset($market_id)): ?>
                    <?php $this->load->view('layout/common/income_markets'); ?>
                    <?php $this->load->view('layout/common/income_categories'); ?>
                <?php else: ?>
                    <?php $this->load->view('layout/common/income_categories'); ?>
                    <?php $this->load->view('layout/common/income_markets'); ?>
                <?php endif; ?>
                
            </div><!-- /side_cat -->
            <?php $this->load->view('layout/common/ads/adsense_side_2'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
