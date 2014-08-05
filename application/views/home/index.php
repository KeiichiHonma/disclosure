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
        <div id="weather">
            

            <div id="tabs" class="cf mb20">
                <ul id="tabs_ul">
                    <?php
                    $today = time();
                    $time = time() - 604800;//1week
                    ?>
                    <li class="undisp"><a href="#tabs-8" id="tab8" class="next_tab">< 前週を見る</a></li>
                    <?php for ($i=1;$i<8;$i++): ?>
                    <li><a href="#tabs-<?php echo $i; ?>" id="tab<?php echo $i; ?>" class="change_tab<?php if($i == 7) echo ' tabulous_active'; ?>"><?php echo date("n月j日",$time); ?></a></li>
                    <?php $time = $time + 86400; ?>
                    <?php endfor ?>
                    
                </ul>
            </div><!--End tabs-->

            <h3 class="center_dot"><span>最新の開示情報</span></h3>
            <div class="pager mb10">
                <?php $this->load->view('common/pager'); ?>
                <div class="allDlBtn"><a href="javascript:void(0)">CSV一括<br />ダウンロード</a></div>
                <div class="allDlBtn"><a href="javascript:void(0)">EXCEL一括<br />ダウンロード</a></div>
            </div>

            <div id="weathers">
            
                <table class="weather_index">
                    <tr class="title">
                        <th class="cell01">提出日</th>
                        <th>提出書類</th>
                        <th>提出者</th>
                        <th>フォーマット</th>
                    </tr>
                    <?php foreach ($xbrls as $xbrl) : ?>
                    <tr>
                    
                        <td><?php echo strftime($this->lang->line('setting_date_format'), strtotime($xbrl->date)); ?></td>
                        <td>
                        <?php echo anchor(sprintf('document/show/'.$xbrl->id), $xbrl->document_name); ?>
                        </td>
                        <td style="font-size:90%;text-align:left;"><?php echo anchor(sprintf('document/show/'.$xbrl->id), $xbrl->presenter_name); ?></td>
                        <td>
                        <?php if($xbrl->xbrl_count > 1): ?>
                            <?php for ($i=0;$i<$xbrl->xbrl_count;$i++): ?>
                            <?php echo anchor($xbrl->format_path.'_'.$i.'.csv','<img src="/images/icon/csv_30.png" alt="csv" />'); ?>
                            <?php endfor; ?>
                        <?php else: ?>
                            <?php echo anchor($xbrl->format_path.'.csv','<img src="/images/icon/csv_30.png" alt="csv" />'); ?>
                        <?php endif; ?>
                        <?php echo anchor($xbrl->format_path.'.xlsx','<img src="/images/icon/excel_30.png" alt="csv" />'); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <div id="sidebar">
            <img src="/images/ad_example1.gif" alt="csv" />
            <div id="datepicker"></div>
        </div>
        <span class="cf" />
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
