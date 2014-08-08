<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $document->presenter_name.' - '.$document->document_name; ?></h1>
        <div id="document">
            
            <table>
                <tr>
                    <td colspan="5"  class="value"><?php $this->load->view('layout/common/document_sns'); ?></td>
                </tr>
                <tr>
                    <th class="cell01">提出日</th>
                    <td class="first value" colspan="4"><?php echo strftime($this->lang->line('setting_date_format'), strtotime($document->date)); ?></td>
                </tr>
                <?php $count = count($document_datas);$i = 1; ?>
                <?php foreach ($document_datas as $number => $document_data) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <th class="cell01"><?php echo $document_data->element_title; ?></th>
                    <td nowrap><?php echo $document_data->context_period; ?></td>
                    <td nowrap><?php echo $document_data->context_consolidated; ?></td>
                    <td nowrap><?php echo $document_data->context_term; ?></td>
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
            <div class="box_wrap">
                <div class="box_adx">
                    <img src="/images/ad_example1.gif" alt="csv" />
                </div>
            </div>
        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
