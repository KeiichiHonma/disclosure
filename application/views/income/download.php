<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">
        <h1 class="l1"><?php echo $company->col_name; ?>のダウンロード</h1>
        <div id="document">
            <?php $this->load->view('layout/common/document_sns'); ?>
                <img src="/images/ad_example1.gif" alt="ad" class="ml10"/><img src="/images/ad_example1.gif" alt="ad" class="mr10 fr" />
            <span class="cf" />
            
            <div id="download" class="cf">
                <h3 class="l2"><span><?php echo $download_string; ?>ファイルダウンロード</span></h2>
                <table>
                    <tr>
                        <td><img src="/images/icon/<?php echo $download_string; ?>_50.png" alt="<?php echo $download_string; ?>" /></td>
                        <td>
                            <div><b>ファイル詳細</b>：<?php echo $company->col_name.$download_string.'ファイル'; ?></div>
                            <div><b>ファイル情報</b>：<?php echo $company->col_code.'_'.strftime($this->lang->line('setting_date_under_score_format'), reset($cdatas)->col_disclosure).'.'.$download_string; ?> / <?php echo strftime($this->lang->line('setting_date_format'), reset($cdatas)->col_disclosure); ?></div>
                            <div class="dlBtn"><a href="<?php echo '/income/prepare/'.$company->_id.'/'.$download_string ?>">このファイルをダウンロード</a></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div id="sidebar">
            <?php $this->load->view('layout/common/income_side'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
