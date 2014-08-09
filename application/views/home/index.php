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

            <div id="top-news1" class="cf">
                
                <h2 class="sprite"><span>TOP NEWS</span></h2>
                <div id="top-news1-inner" class="cf">
                    <div id="photo-wrapper">
                        <div class="top-news1-photo"><a href="/articles/-/44758"><img src="http://tk.ismcdn.jp/mwimgs/1/c/329/img_1c14496878109f2bae41082816c793d129413.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/articles/-/44770"><img src="http://tk.ismcdn.jp/mwimgs/8/1/329/img_81db201ef8db4076d5fdc878e47607bb54498.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/articles/-/44494"><img src="http://tk.ismcdn.jp/mwimgs/8/7/329/img_879406c1dd7b70abcece5caa0cda376c34641.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/articles/-/44740"><img src="http://tk.ismcdn.jp/mwimgs/f/e/329/img_fee70d8ffb087f29dec3bca5312b8567128494.jpg" /></a></div>
                    </div><!-- /photo-wrapper -->

                    <div class="top-news1-list">
                    <ul>
                        <li>
                            <a href="/articles/-/44758" class="link-box">
                    <span class="column-ttl">迫る独ＶＷの影、世界一の座は盤石と言えない</span><br /><span class="column-main-ttl">トヨタ､足元は過去最高益でも浮かれず</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/articles/-/44770" class="link-box">
                    <span class="column-ttl">川内原発審査の問題②高橋正樹・日本大学教授</span><br /><span class="column-main-ttl">｢火山影響評価は科学的とはいえない｣</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/articles/-/44494" class="link-box">
                    <span class="column-ttl">既存事業に加え､積極的なＭ＆Ａでレバレッジ</span><br /><span class="column-main-ttl">じげん､時価総額1兆円のジゲノミクスとは？</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/articles/-/44740" class="link-box">
                    <span class="column-ttl">妻､まさかの決断｡そして｢夫への嫉妬｣が消えた！</span><br /><span class="column-main-ttl">｢同学歴夫婦｣が､"幸せな関係"を築くまで</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                    </ul>
                    </div><!-- /top-news1-list -->
                </div><!-- /top-news1-inner -->

            </div>

            <?php $this->load->view('layout/common/date_navi'); ?>
            <h3 class="center_dot"><span>最新の開示情報</span></h3>
        </div>
        <div id="document_navi_ad">
        <img src="/images/ad_example1.gif" alt="csv" />
        </div>
        <div id="document">
            <table>
                <tr>
                    <th class="cell01 date">提出日</th>
                    <th>書類</th>
                    <th class="dl">ダウンロード</th>
                </tr>
                <?php $count = count($xbrls);$i = 1; ?>
                <?php foreach ($xbrls as $xbrl) : ?>
                <?php $new_categories[] = $xbrl->document_name; ?>
                <tr<?php if($count == $i) echo ' class="last"'; ?>>
                    <td class="cell02"><?php echo strftime($this->lang->line('setting_date_format'), strtotime($xbrl->date)); ?></td>
                    <td style="font-size:90%;text-align:left;"><?php echo anchor('document/show/'.$xbrl->id, $xbrl->document_name.' - '.$xbrl->presenter_name); ?></td><td>
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
                <h1 class="side_title">開示情報 カテゴリー</h1>
                <ul>
                <?php foreach ($categories as $category) : ?>
                <li><a href="http://liginc.co.jp/news"><span><?php echo $category->name; ?></span><?php if(in_array($category->name,$new_categories)): ?><span class="new">new</span><?php endif; ?></a></li>
                <?php endforeach; ?>
                </ul>
            </div><!-- /side_cat -->
            <div class="box_wrap">
                <div id="datepicker"></div>
                <div class="box_adx">
                    <img src="/images/ad_example1.gif" alt="csv" />
                </div>
            </div>
        </div>
        <span class="cf" />
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
