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
            <table class="weather_index">
                <tr>
                    <th class="cell01">提出日</th>
                    <td><?php echo strftime($this->lang->line('setting_date_format'), strtotime($xbrl->date)); ?></td>
                </tr>
                <?php foreach ($csv_datas as $csv_data) : ?>
                <?php $index = count($csv_data) - 1; ?>
                <?php if(isset($csv_data[$index]) && $csv_data[$index] != 'false'): ?>
                <tr>
                    <th class="cell01"><?php echo $csv_data[1]; ?></th>
                    <td><?php echo $csv_data[$index]; ?></td>
                </tr>
                <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
