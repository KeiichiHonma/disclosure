<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>
<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner"><?php $this->load->view('layout/common/topicpath'); ?>
        <h3 class="l1"><?php echo $this->lang->line('common_title_api'); ?></h3>
        <div id="site">
            オープンデータ.companyではwebサイトやアプリ制作などに利用できる、有価証券報告書データ、年収データ、財務データといった企業データコンテンツをAPIで提供しています。<br />
            APIに関するお問い合わせは <img src="/images/ad_mail.gif" alt="" style="vertical-align: middle;" /> までお気軽にお問い合わせください。 
            <h4 class="l2"><span>APIコンテンツ一覧</span></h4>
            <h5 class="l3">有価証券報告書API</h5>
            <h5 class="l3">財務データAPI</h5>
            <h5 class="l3">年収データAPI</h5>
            <h4 class="l2"><span>初期費用</span></h4>
            <p class="firstprice">50,000円(税抜)</p>
            <p>※初期費用は1企業様あたりとなります。</p>
            
            <h4 class="l2"><span>月額費用</span></h4>
            <table class="api" border="0" cellspacing="1" cellpadding="3">
              <colgroup span="2" align="left">
              </colgroup>
              <thead>
                <tr>
                  <td width="171">情報タイプ</td>
                  <td width="236">API（コンテンツの種類)</td>
                  <td width="159">月額利用料</td>
                </tr>
              </thead>
              <tr>
                <td class="category" height="18" rowspan="3">企業データ情報API</td>
                <td>有価証券報告書データ</td>
                <td>30,000円</td>
              </tr>
              <tr>
                <td>財務データ</td>
                <td>30,000円</td>
              </tr>
              <tr>
                <td>年収データ</td>
                <td>20,000円</td>
              </tr>
            </table>
            <p class="caution">※リクエスト数に応じて表の基本利用料が上記の月額利用料となります。 <br />
            
            <h4 class="l2"><span>リクエスト料金</span></h4>
            <table class="api" cellspacing="1" cellpadding="3">
              <thead>
                <tr>
                  <td width="416">リクエスト数</td>
                  <td width="168">基本利用料</td>
                </tr>
              </thead>
              <tr>
                <td>100万 リクエスト/月 未満かつ、1万リクエスト/時 未満</td>
                <td>30,000円</td>
              </tr>
              <tr>
                <td>200万 リクエスト/月 未満かつ、2万リクエスト/時 未満</td>
                <td>40,000円</td>
              </tr>
              <tr>
                <td>400万 リクエスト/月 未満かつ、3万リクエスト/時 未満</td>
                <td>60,000円</td>
              </tr>
              <tr>
                <td>800万 リクエスト/月 未満かつ、4万リクエスト/時 未満</td>
                <td>80,000円</td>
              </tr>
              <tr>
                <td>800万リクエスト/月 以上かつ、4万リクエスト/時 以上</td>
                <td>要見積</td>
              </tr>
            </table>
            <p class="caution">※リクエスト数に応じて表の基本利用料が上表の月額利用料となります。</p>

            <h4 class="l2"><span>セット割引料金</span></h4>
            <table class="api" cellspacing="1" cellpadding="3">
              <thead>
                <tr>
                  <td width="266">購入API数</td>
                  <td width="145">月額割引料金</td>
                </tr>
              </thead>
              <tr>
                <td>2種類</td>
                <td>-10,000円</td>
              </tr>
              <tr>
                <td>3種類</td>
                <td>-20,000円</td>
              </tr>
            </table>

        </div>
        <div id="sidebar">
            <div id="side_cat">
                <?php $this->load->view('layout/common/categories'); ?>
            </div><!-- /side_cat -->
            <div class="box_wrap">
                <div class="box_adx pcdisp">
                    <img src="/images/ad_example1.gif" alt="" />
                </div>
            </div>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
