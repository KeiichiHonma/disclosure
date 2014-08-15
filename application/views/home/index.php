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
        <div id="document_navi">

            <div id="top-news1" class="cf">
                
                <div id="top-news1-inner" class="cf">
                    <div id="photo-wrapper">
                        <div class="top-news1-photo"><a href="/articles/-/44758"><img src="/images/yuuka.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/income/"><img src="/images/logos.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/articles/-/44494"><img src="/images/change.jpg" /></a></div>
                        <div class="top-news1-photo"><a href="/articles/-/44494"><img src="/images/change.jpg" /></a></div>
                    </div><!-- /photo-wrapper -->

                    <div class="top-news1-list">
                    <ul>
                        <li>
                            <a href="/articles/-/44758" class="link-box">
                    <span class="column-ttl">エクセル、CSV、txtファイルでダウンロードできる</span><br /><span class="column-main-ttl">開示書類をわかりやすいフォーマットで提供</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/articles/-/44770" class="link-box">
                    <span class="column-ttl">年収情報を速報でお届け</span><br /><span class="column-main-ttl">企業の年収情報をいち早く確認できる</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/articles/-/44494" class="link-box">
                    <span class="column-ttl">今一番転職すべき企業を速報でお届け</span><br /><span class="column-main-ttl">Coming Soon</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                        <li>
                            <a href="/articles/-/44494" class="link-box">
                    <span class="column-ttl">今一番転職すべき企業を速報でお届け</span><br /><span class="column-main-ttl">Coming Soon</span><span class="more sprite">MORE</span>
                            </a>
                        </li>
                    </ul>
                    </div><!-- /top-news1-list -->
                </div><!-- /top-news1-inner -->

            </div>


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
                <h1 class="side_title">業界カテゴリ</h1>
                <ul>
                <?php foreach ($income_categories as $income_category) : ?>
                <li><a href="<?php echo '/income/category/'.$income_category->_id; ?>"><span><?php echo $income_category->col_name; ?></span></a></li>
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
