<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $document->presenter_name.' - '.$document->document_name; ?>のダウンロード</h1>
        <div id="document">
            <?php $this->load->view('layout/common/document_sns'); ?>
                <img src="/images/ad_example1.gif" alt="ad" /><img src="/images/ad_example1.gif" alt="ad" class="fr" />
            <span class="cf" />
            <h3 class="l2"><span><?php echo $download_string; ?>ファイルダウンロード</span></h2>
            <div id="download" class="cf">
                <div>この開示情報ファイルを共有する<br /><a href=""><img src="/images/icon/facebook.png" /></a>&nbsp;<a href=""><img src="/images/icon/twitter.png" /></a></div>
                <?php $document->xbrl_count = 2; ?>
                <?php if($document->xbrl_count > 1): ?>
                    <?php for ($i=0;$i<$document->xbrl_count;$i++): ?>
                        <div>ファイル詳細：<?php echo $document->presenter_name.' - '.$document->document_name.$download_string.'ファイル'.$i; ?></div>
                        <div>ファイル情報：<?php echo end(explode('/',$document->format_path)).'_'.$i.'.'.$download_string; ?> / <?php echo strftime($this->lang->line('setting_date_format'), strtotime($document->date)); ?></div>
                        <div class="dlBtn"><a href="<?php echo '/document/prepare/'.$document->id.'/'.$download_string ?>">このファイルをダウンロード</a></div>
                    <?php endfor; ?>
                <?php else: ?>
                    <div>ファイル詳細：<?php echo $document->presenter_name.' - '.$document->document_name.$download_string.'ファイル'; ?></div>
                    <div>ファイル情報：<?php echo end(explode('/',$document->format_path)).'.'.$download_string; ?> / <?php echo strftime($this->lang->line('setting_date_format'), strtotime($document->date)); ?></div>
                    <div class="dlBtn"><a href="<?php echo '/document/prepare/'.$document->id.'/'.$download_string ?>">このファイルをダウンロード</a></div>
                <?php endif; ?>

                
                
            </div>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/document_side'); ?>
        </div>
        <span class="cf" />
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
