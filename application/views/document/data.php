<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<?php
$pattern = array('A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$replacement = array(' A',' B',' C',' D',' E',' F',' G',' H',' J',' K',' L',' M',' N',' O',' P',' Q',' R',' S',' T',' U',' V',' W',' X',' Y',' Z');
?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $document->presenter_name.' - '.strftime($this->lang->line('setting_date_format'), strtotime($document->date)).' '.$document->document_name; ?></h1>
        <div class="box_adx mb20 spdisp">
            <img src="/images/ad_example_sp1.jpg" alt="" />
        </div>

        <?php $this->load->view('layout/common/document_tab'); ?>
        <div id="document">
            <?php $this->load->view('layout/common/document_sns'); ?>
            <table>
                <tr>
                    <th class="cell01">提出日</th>
                    <td class="first">&nbsp;</td>
                    <td class="first undisp">&nbsp;</td>
                    <td class="first undisp">&nbsp;</td>
                    <td class="first value"><?php echo strftime($this->lang->line('setting_date_format'), strtotime($document->date)); ?></td>
                </tr>
                <?php $count = count($document_datas);$i = 1; ?>
                <?php foreach ($document_datas as $number => $document_data) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <th class="cell01">
                    <?php if($document_data->item_id == 0): ?>
                        <?php echo str_replace($pattern, $replacement, $document_data->element_name); ?>
                    <?php else: ?>
                        <?php if($document_data->redundant_label_ja != ''): ?>
                            <?php echo $document_data->redundant_label_ja; ?>
                        <?php elseif($document_data->style_tree != ''): ?>
                            <?php echo $document_data->style_tree; ?>
                        <?php elseif($document_data->detail_tree != ''): ?>
                            <?php echo $document_data->detail_tree; ?>
                        <?php else: ?>
                            <?php echo $document_data->element_name; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php //echo '<br />'.$document_data->element_name; ?>
                    </th>
                    <td class="period" nowrap><?php echo $document_data->context_period; ?></td>
                    <td class="undisp" nowrap><?php echo $document_data->context_consolidated; ?></td>
                    <td class="undisp" nowrap><?php echo $document_data->context_term; ?></td>
                    <?php if( $document_data->unit == 'JPY' && is_numeric($document_data->int_data) ): ?>
                        <td class="jpy value"><?php echo number_format($document_data->int_data).'円'; ?></td>
                    <?php elseif( $document_data->text_data != '' ): ?>
                        <td class="value"><?php echo $document_data->text_data; ?></td>
                    <?php elseif( $document_data->mediumtext_data != '' ): ?>
                        <td class="value"><?php //echo $document_data->mediumtext_data; ?></td>
                    <?php elseif( is_numeric($document_data->int_data) ): ?>
                        <td class="value"><?php echo number_format($document_data->int_data); ?></td>
                    <?php else: ?>
                        <td class="value">&nbsp;</td>
                    <?php endif; ?>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/switch_side'); ?>
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div class="side_list">
                <?php $this->load->view('layout/common/document_categories'); ?>
            </div>
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
