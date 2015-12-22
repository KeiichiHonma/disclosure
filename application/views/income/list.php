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
        
        <?php if(isset($is_index)): ?>
        <div id="document_navi">
            <div id="top-news1" class="cf">
                <div id="top-news1-inner" class="cf">
                    <div id="photo-wrapper">
                        <div class="top-news1-photo"><a href="/income/"><img src="/images/logos.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/site/api"><img src="/images/api.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/document/category/1"><img src="/images/yuuka.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/finance/"><img src="/images/zaimu.jpg" /></a></div>
                    </div><!-- /photo-wrapper -->

                    <div class="top-news1-list">
                    <ul>
                        <li>
                            <a href="/income/category/1" class="link-box">
                                <span class="column-ttl">年収情報を速報でお届け</span><br /><span class="column-main-ttl">企業の年収情報をいち早く確認できる</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/site/api" class="link-box">
                                <span class="column-ttl">年収データAPIが額30,000円で使いたい放題！！</span><br /><span class="column-main-ttl">上場企業の年収データを使って貴社サイトのPVアップに効果絶大！！</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/document/category/1" class="link-box">
                                <span class="column-ttl">有価証券報告書を速報でお届け</span><br /><span class="column-main-ttl">エクセルフォーマットでダウンロード！</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/finance/" class="link-box">
                                <span class="column-ttl">各企業のP/L,BS,CFを速報でお届け</span><br /><span class="column-main-ttl">企業の財務状況を把握。財務ランキングも！</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                    </ul>
                    </div><!-- /top-news1-list -->
                </div><!-- /top-news1-inner -->
            </div>
            <h3 class="center_dot"><span><?php echo $page_title; ?></span></h3>
        </div>
        <div id="document_navi_ad">
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
        <?php else: ?>
            <h3 class="l1"><?php echo $page_title; ?></h3>
        <?php endif; ?>
        <div id="document"<?php if(!isset($is_index)) echo ' class="pt10"'; ?>>
            <?php if(!empty($cdatas)): ?>
                <?php if(!isset($is_index)) $this->load->view('layout/common/select_year'); ?>
                <?php if(!isset($is_index)) $this->load->view('layout/common/pager'); ?>
                <table class="finance">
                    <tr>
                        <th class="date">
                        <?php if(isset($is_index)): ?>
                        提出日
                        <?php else: ?>
                        <?php echo anchor('income/'.$function_name.'/'.$object_id.'/'.$year.'/'.($order == 'disclosure' ? 'disclosureRev' : 'disclosure').'/'.$page,'提出日'.($order == 'disclosure' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'disclosureRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?>
                        <?php endif; ?>
                        </th>
                        <th class="code">証券<br />コード</th>
                        <th class="company_name">企業名</th>
                        <th class="income">
                        <?php if(isset($is_index)): ?>
                        年収
                        <?php else: ?>
                        <?php echo anchor('income/'.$function_name.'/'.$object_id.'/'.$year.'/'.($order == 'income' ? 'incomeRev' : 'income').'/'.$page,'年収'.($order == 'income' ? '<i class="fa fa-long-arrow-up"></i>' : ($order == 'incomeRev' ? '<i class="fa fa-long-arrow-down"></i>' : ''))); ?>
                        <?php endif; ?>
                        </th>
                        <th class="trend">前年比</th>
                        <th class="market">市場<br />業種</th>
                    </tr>
                    <?php $count = count($cdatas);$i = 1; ?>
                    <?php foreach ($cdatas as $cdata) : ?>
                    <tr<?php if($count == $i) echo ' class="last"'; ?>>
                        <td class="txt"><?php echo strftime("%y/%m/%d", $cdata->col_disclosure); ?></td>
                        <td class="txt"><?php echo $cdata->col_code != 0 ? anchor('income/show/'.$cdata->presenter_name_key.$year_url, $cdata->col_code) : '-'; ?></td>
                        <td class="txt">
                        <?php echo anchor('income/show/'.$cdata->presenter_name_key.$year_url, $cdata->col_name); ?><br />
                        <?php echo anchor('finance/show/'.$cdata->presenter_name_key.'/pl','P/L'); ?>&nbsp;&nbsp;<?php echo anchor('finance/show/'.$cdata->presenter_name_key.'/bs','BS'); ?>&nbsp;&nbsp;<?php echo anchor('finance/show/'.$cdata->presenter_name_key.'/cf','CF'); ?>
                        </td>
                        <td class="data"><?php echo $cdata->col_income; ?>万円</td>
                        <?php
                        if($cdata->col_income_trend == 1){
                            $trend_image = 'up.gif';
                        }elseif($cdata->col_income_trend == 2){
                            $trend_image = 'down.gif';
                        }elseif($cdata->col_income_trend == 0){
                            $trend_image = 'new.gif';
                        }elseif($cdata->col_income_trend == 3){
                            $trend_image = 'stay.gif';
                        }
                        ?>
                        <td class="txt"><img src="/images/income/<?php echo $trend_image; ?>" /></td>
                        <td class="txt">
                        <?php echo anchor('income/category/'.$cdata->category_id.$year_url,$cdata->category_name); ?>
                        <?php if(isset($markets[$cdata->market_id])): ?><br /><?php echo anchor('income/market/'.$cdata->market_id.$year_url,$markets[$cdata->market_id]->name); ?><?php endif; ?>
                        </td>
                    </tr>
                    <?php $i++; ?>
                    <?php endforeach; ?>
                </table>
                <?php if(!isset($is_index)) $this->load->view('layout/common/pager'); ?>
            <?php else: ?>
                <div class="blank">指定の年収情報がありません。</div>
            <?php endif; ?>
        </div>
        <div id="sidebar">
            <?php if(!isset($is_index)): ?>
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <?php endif; ?>
            <div id="side_cat">
                <?php if(isset($market_id)): ?>
                    <?php $this->load->view('layout/common/income_markets'); ?>
                    <?php $this->load->view('layout/common/income_categories'); ?>
                <?php else: ?>
                    <?php $this->load->view('layout/common/income_categories'); ?>
                    <?php $this->load->view('layout/common/income_markets'); ?>
                <?php endif; ?>
                
            </div><!-- /side_cat -->
            <?php $this->load->view('layout/common/ads/adsense_side_2'); ?>
        </div>
        <span class="cf" />
        <?php $this->load->view('layout/common/ads/adsense_bottom'); ?>
    </div>
</div>
<?php if(isset($is_index)): ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/slide/jcarousellite_1.0.1.pack.js"></script>
<script type="text/javascript" src="/js/slide/jquery.tools.min.js"></script>

<script type="text/javascript" src="/js/slide/jquery.fancybox-1.3.4.custom.js"></script>
<script type="text/javascript" src="/js/slide/jquery.easing-1.3.pack.js"></script>
<script type="text/javascript" src="/js/slide/jquery.mousewheel-3.0.4.pack.js"></script>
<script src="/js/slide/jquery.hoverIntent.minified.js" type="text/javascript"></script>
<script src="/js/slide/tools.js" type="text/javascript"></script>
<?php endif; ?>
<?php $this->load->view('layout/footer/footer'); ?>
