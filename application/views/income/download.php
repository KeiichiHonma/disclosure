<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner"><?php $this->load->view('layout/common/topicpath'); ?>
        <h3 class="l1"><?php echo $page_title; ?></h3>
        <div id="document">
            <?php $this->load->view('layout/common/document_sns'); ?>
            <?php if(ENVIRONMENT == 'production'): ?>
                <span class="ml10">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- opendata_300_250 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:300px;height:250px"
                         data-ad-client="ca-pub-0723627700180622"
                         data-ad-slot="1852139908"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </span>
                <span class="mr10 fr">
                    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                    <!-- opendata_300_250_2 -->
                    <ins class="adsbygoogle"
                         style="display:inline-block;width:300px;height:250px"
                         data-ad-client="ca-pub-0723627700180622"
                         data-ad-slot="4805606304"></ins>
                    <script>
                    (adsbygoogle = window.adsbygoogle || []).push({});
                    </script>
                </span>
            <?php else: ?>
                <img src="/images/ad_example1.gif" alt="ad" class="ml10"/><img src="/images/ad_example1.gif" alt="ad" class="mr10 fr" />
            <?php endif; ?>
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
            <?php $this->load->view('layout/common/switch_side'); ?>
            <div class="side_list">
                <?php $this->load->view('layout/common/income_categories'); ?>
                <?php $this->load->view('layout/common/income_markets'); ?>
            </div>
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>

<?php $this->load->view('layout/footer/footer'); ?>
