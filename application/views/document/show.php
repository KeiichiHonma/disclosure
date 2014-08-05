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
            <h2><?php echo $xbrl->presenter_name.' - '.$xbrl->document_name; ?></h2>
            <table>
                <tr>
                    <th class="cell01">提出日</th>
                    <td colspan="4"><?php echo strftime($this->lang->line('setting_date_format'), strtotime($xbrl->date)); ?></td>
                </tr>
                <?php foreach ($xbrls as $xbrl_count => $document_datas) : ?>
                <?php foreach ($document_datas as $document_data) : ?>
                <tr>
                    <th class="cell01"><?php echo $document_data['element_name']; ?></th>
                    <td nowrap><?php echo $document_data['context_period']; ?></td>
                    <td nowrap><?php echo $document_data['context_consolidated']; ?></td>
                    <td nowrap><?php echo $document_data['context_term']; ?></td>
                    <?php if( $document_data['unit'] == 'JPY' && is_numeric($document_data['value']) ): ?>
                    <td class="jpy"><?php echo number_format($document_data['value']).'円'; ?></td>
                    <?php else: ?>
                    <td><?php echo $document_data['value']; ?></td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
