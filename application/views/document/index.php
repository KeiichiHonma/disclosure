<?php $this->load->view('layout/header/header'); ?>
    <script>
    $(function() {
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
        <div class="box_adx spdisp">
            <?php if(ENVIRONMENT == 'production'): ?>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- opendata_300_250 -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-0723627700180622"
                     data-ad-slot="1852139908"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            <?php else: ?>
                <img src="/images/ad_example1.gif" alt="" />
            <?php endif; ?>
        </div>
        <div id="document_navi">

            <div id="top-news1" class="cf">
                <div id="top-news1-inner" class="cf">
                    <div id="photo-wrapper">
                        <div class="top-news1-photo"><a href="/document/category/1"><img src="/images/yuuka.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/income/"><img src="/images/logos.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/finance/"><img src="/images/zaimu.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/articles/-/44494"><img src="/images/screening.jpg" /></a></div>
                    </div><!-- /photo-wrapper -->

                    <div class="top-news1-list">
                    <ul>
                        <li>
                            <a href="/document/category/1" class="link-box">
                                <span class="column-ttl">有価証券報告書を速報でお届け</span><br /><span class="column-main-ttl">エクセルフォーマットでダウンロード！</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/income/category/1" class="link-box">
                                <span class="column-ttl">年収情報を速報でお届け</span><br /><span class="column-main-ttl">企業の年収情報をいち早く確認できる</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/finance/" class="link-box">
                                <span class="column-ttl">各企業のP/L,BS,CFを速報でお届け</span><br /><span class="column-main-ttl">企業の財務状況を把握。財務ランキングも！</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void;" class="link-box">
                                <span class="column-ttl">財務数値でのスクリーニングを提供</span><br /><span class="column-main-ttl">Coming Soon</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                    </ul>
                    </div><!-- /top-news1-list -->
                </div><!-- /top-news1-inner -->
            </div>
            <h3 class="center_dot"><span><?php echo $this->lang->line('common_title_home'); ?></span></h3>
        </div>
        <div id="document_navi_ad">
            <?php if(ENVIRONMENT == 'production'): ?>
                <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
                <!-- opendata_300_250_2 -->
                <ins class="adsbygoogle"
                     style="display:inline-block;width:300px;height:250px"
                     data-ad-client="ca-pub-0723627700180622"
                     data-ad-slot="4805606304"></ins>
                <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
                </script>
            <?php else: ?>
            <img src="/images/ad_example1.gif" alt="" />
            <?php endif; ?>
        </div>
        <div id="document">
            <table>
                <tr>
                    <th class="cell01 date">提出日</th>
                    <th>書類</th>
                    <th class="dl undisp">ダウンロード</th>
                </tr>
                <?php $count = count($xbrls);$i = 1; ?>
                <?php foreach ($xbrls as $xbrl) : ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="cell02"><span class="undisp"><?php echo strftime("%Y年", strtotime($xbrl->date)); ?></span><?php echo strftime("%m月%d日", strtotime($xbrl->date)); ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor('document/'.( $xbrl->is_html == 1 ? 'data' : 'show' ).'/'.$xbrl->id, $xbrl->document_name.' - '.$xbrl->presenter_name); ?></td>
                    <td class="undisp">
                        <?php echo anchor('document/download/'.$xbrl->id.'/csv','<img src="/images/icon/csv_30.png" alt="csv" />'); ?>
                        <?php echo anchor('document/download/'.$xbrl->id.'/xlsx','<img src="/images/icon/xlsx_30.png" alt="xlsx" />'); ?>
                    </td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
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


<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/slide/jcarousellite_1.0.1.pack.js"></script>
<script type="text/javascript" src="/js/slide/jquery.tools.min.js"></script>

<script type="text/javascript" src="/js/slide/jquery.fancybox-1.3.4.custom.js"></script>
<script type="text/javascript" src="/js/slide/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/js/slide/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="/js/slide/jquery.hoverIntent.minified.js" type="text/javascript"></script>
<script src="/js/slide/tools.js" type="text/javascript"></script>

<?php $this->load->view('layout/footer/footer'); ?>
