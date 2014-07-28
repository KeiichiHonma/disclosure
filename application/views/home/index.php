<?php $this->load->view('layout/header/header'); ?>
<?php $this->load->view('layout/common/navi'); ?>

<!--
//////////////////////////////////////////////////////////////////////////////
contents
//////////////////////////////////////////////////////////////////////////////
-->
<div id="contents">
    <div id ="contentsInner">

        <div class="howtoBox cf">
            <h3 class="center_dot"><span>ハレコの使い方</span></h3>
            
            <div class="step step01">
                <h4>1.お出かけ場所を決める</h4>
                <p>行きたい場所や温泉、家の近くの公園等でかける場所を選びます。</p>
            </div>
            <div class="step step02">
                <h4>2.晴れの提案を受ける</h4>
                <p>ハレコは各エリアの未来に晴れる日程を提案します。</p>
            </div>
            <div class="step step03">
                <h4>3.晴れる予定を選択</h4>
                <p>晴れる日を選んだらおでかけの予定を立ててください。</p>
            </div>
            <div class="step step04">
                <h4>4.晴れてよかった！</h4>
                <p>晴れの日程でおでかけすることができましたね！</p>
            </div>
        </div>

        <div id="weather">
            <div id="tabs" class="cf">
                <ul id="tabs_ul">
                    <li><a href="#tabs-1" id="tab1" class="change_tab">北海道</a></li>
                    <li><a href="#tabs-2" id="tab2" class="change_tab">東北</a></li>
                    <li><a href="#tabs-3" id="tab3" class="change_tab tabulous_active">関東・信越</a></li>
                    <li><a href="#tabs-4" id="tab4" class="change_tab">東海・北陸・近畿</a></li>
                    <li><a href="#tabs-5" id="tab5" class="change_tab">中国・四国</a></li>
                    <li><a href="#tabs-6" id="tab6" class="change_tab">九州</a></li>
                    <li><a href="#tabs-7" id="tab7" class="change_tab">沖縄</a></li>
                    <li class="undisp"><a href="#tabs-8" id="tab8" class="next_tab">次週を見る ></a></li>
                </ul>
            </div><!--End tabs-->
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
                        <td><?php echo $xbrl->presenter_name; ?></td>
                        <td><?php echo $xbrl->manage_number; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
<script type="text/javascript">
    $(document).ready(function($) {
        var page = 1;
        $( '.change_tab' ) . click(
            function() {
                $('#weathers').block({
                    message: '<img src="/images/loadinfo.net.gif" alt="" />',
                    overlayCSS:  {
                        backgroundColor: '#fdfdfd', 
                        opacity:         0.8,
                        cursor:          'wait' 
                    },
                    css: {
                       backgroundColor: '#fdfdfd',
                       opacity:         0.8,
                       color:'#fff',
                       height:  '0px',
                       width:   '0px',
                       border:  'none'
                   }
                });
                var links = $(this).parent().parent().find('a');
                links.removeClass('tabulous_active');
                $(this).addClass('tabulous_active');
                
                jQuery . post(
                    '/json/weathers',
                    { <?php echo $csrf_token; ?>:"<?php echo $csrf_hash; ?>",tab_id:$(this).attr('id') },
                    function( data, textStatus ) {
                        if( textStatus == 'success' ) {
                            try {
                                $('#weathers').unblock();
                                var jsonobj = jQuery.parseJSON( data );
                                $( '#weathers' ) . html( jsonobj.html );
                                
                            } catch (e) {

                            }
                        }
                    }
                    ,'html'
                );
            }
        );
        $( '.next_tab' ) . click(
            function() {
                $('#weathers').block({
                    message: '<img src="/images/loadinfo.net.gif" alt="" />',
                    overlayCSS:  {
                        backgroundColor: '#fdfdfd', 
                        opacity:         0.8,
                        cursor:          'wait' 
                    },
                    css: {
                       backgroundColor: '#fdfdfd',
                       opacity:         0.8,
                       color:'#fff',
                       height:  '0px',
                       width:   '0px',
                       border:  'none'
                   }
                });
                $('#tabs_ul li a[class="change_tab tabulous_active"]').each(function(idx, obj){
                    target_id = $(obj).attr("id");
                });
                page = page + 1;
                jQuery . post(
                    '/json/weathers',
                    { <?php echo $csrf_token; ?>:"<?php echo $csrf_hash; ?>",tab_id:target_id,page:page },
                    function( data, textStatus ) {
                        if( textStatus == 'success' ) {
                            try {
                                $('#weathers').unblock();
                                var jsonobj = jQuery.parseJSON( data );
                                $( '#weathers' ) . html( jsonobj.html );
                                
                            } catch (e) {

                            }
                            
                        }
                    }
                    ,'html'
                );
            }

        );

    });
</script>
        </div>
    </div>
</div>
<?php $this->load->view('layout/footer/footer'); ?>
