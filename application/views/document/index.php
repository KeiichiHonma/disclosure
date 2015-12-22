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
            <h3 class="center_dot"><span><?php echo $this->lang->line('common_title_home'); ?></span></h3>
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
            <?php $this->load->view('layout/common/ads/adsense_side'); ?>
            <div id="side_cat">
                <?php $this->load->view('layout/common/categories'); ?>
            </div><!-- /side_cat -->
            <div class="box_wrap">
                <div class="box_adx pcdisp">
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
