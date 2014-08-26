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
                    <th class="cell01"><?php echo preg_match("/^[a-zA-Z]+$/", $document_data->element_title) ? str_replace($pattern, $replacement, $document_data->element_title) : $document_data->element_title; ?></th>
                    <td class="period" nowrap><?php echo $document_data->context_period; ?></td>
                    <td class="undisp" nowrap><?php echo $document_data->context_consolidated; ?></td>
                    <td class="undisp" nowrap><?php echo $document_data->context_term; ?></td>
                    <?php if( $document_data->unit == 'JPY' && is_numeric($document_data->int_data) ): ?>
                        <td class="jpy value"><?php echo number_format($document_data->int_data).'円'; ?></td>
                    <?php elseif( is_numeric($document_data->int_data) ): ?>
                        <td class="value"><?php echo number_format($document_data->int_data); ?></td>
                    <?php elseif( $document_data->text_data != '' ): ?>
                        <td class="value"><?php echo $document_data->text_data; ?></td>
                    <?php elseif( $document_data->mediumtext_data != '' ): ?>
                        <td class="value"><?php //echo $document_data->mediumtext_data; ?></td>
                    <?php else: ?>
                        <td class="value">&nbsp;</td>
                    <?php endif; ?>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/document_side'); ?>
        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
