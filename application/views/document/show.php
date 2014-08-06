<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <div id="weather">
            <h2><?php echo $document->presenter_name.' - '.$document->document_name; ?></h2>
            <table>
                <tr>
                    <th class="cell01">提出日</th>
                    <td colspan="4"><?php echo strftime($this->lang->line('setting_date_format'), strtotime($document->date)); ?></td>
                </tr>
                <?php foreach ($document_datas as $number => $document_data) : ?>
                <tr>
                    <th class="cell01"><?php echo $document_data->element_title; ?></th>
                    <td nowrap><?php echo $document_data->context_period; ?></td>
                    <td nowrap><?php echo $document_data->context_consolidated; ?></td>
                    <td nowrap><?php echo $document_data->context_term; ?></td>
                    <?php if( $document_data->unit == 'JPY' && is_numeric($document_data->int_data) ): ?>
                    <td class="jpy"><?php echo number_format($document_data->int_data).'円'; ?></td>
                    <?php elseif( $document_data->text_data != '' ): ?>
                    <td><?php echo $document_data->text_data; ?></td>
                    <?php elseif( $document_data->mediumtext_data != '' ): ?>
                    <td><?php //echo $document_data->mediumtext_data; ?></td>
                    <?php else: ?>
                    <td>&nbsp;</td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
