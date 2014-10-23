<?php $this->load->view('layout/header/header'); ?>
    <script>
    $(function() {
        $( "#datepicker" ).datepicker({
            inline: true
        });

        // Hover states on the static widgets
        $( "#dialog-link, #icons li" ).hover(
            function() {
                $( this ).addClass( "ui-state-hover" );
            },
            function() {
                $( this ).removeClass( "ui-state-hover" );
            }
        );
    });
    </script>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <div id="document_navi">
            <?php $this->load->view('layout/common/date_navi'); ?>
            <h3 class="center_dot"><span><?php echo strftime($this->lang->line('setting_date_format'), strtotime($date)); ?>の開示情報</span></h3>
            <div class="pager mb10">
                <?php $this->load->view('common/pager'); ?>
            </div>
        </div>
        <div id="document">
            <table>
                <tr>
                    <th class="cell01 date">提出日</th>
                    <th>書類</th>
                    <th class="dl">ダウンロード</th>
                </tr>
                <?php $count = count($documents);$i = 1; ?>
                <?php foreach ($documents as $document) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="cell02"><?php echo strftime($this->lang->line('setting_date_format'), strtotime($document->date)); ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor(sprintf('document/show/'.$document->id), $document->document_name.' - '.$document->presenter_name); ?></td><td>
                    <?php if($document->xbrl_count > 1): ?>
                        <?php for ($i=0;$i<$document->xbrl_count;$i++): ?>
                        <?php echo anchor($document->format_path.'_'.$i.'.csv','<img src="/images/icon/csv_30.png" alt="csv" />'); ?>
                        <?php endfor; ?>
                    <?php else: ?>
                        <?php echo anchor($document->format_path.'.csv','<img src="/images/icon/csv_30.png" alt="csv" />'); ?>
                    <?php endif; ?>
                    <?php echo anchor($document->format_path.'.xlsx','<img src="/images/icon/excel_30.png" alt="csv" />'); ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>
        </div>
        <div id="sidebar">

            <div id="side_cat">
                <h1 class="side_title">開示情報 カテゴリー</h1>

                <ul>
                <?php foreach ($categories as $category) : ?>
                <li><a href="http://liginc.co.jp/news"><span><?php echo $category->name; ?></span></a></li>
                <?php endforeach; ?>
                </ul>
            </div><!-- /side_cat -->
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
