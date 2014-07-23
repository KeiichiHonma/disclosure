<?php $this->load->view('layout/header/headerPre'); ?>
<script type="text/javascript">
$(function() {
    $('#send').click(function() {
        
        var user_email = $("#user_email").val();
        if(user_email.length > 0){
            
            $.post("<?php echo force_lang_base_url('site/pre'); ?>",{<?php echo $csrf_token; ?>:"<?php echo $csrf_hash; ?>",email:user_email},function(result){
                if(result == 'registered'){
                    var html = "";
                    html += "<p>";
                    html += "Balloooooon!事前登録受け付け済みでございます。";
                    html += "</p>";
                    html += "<p>";
                    html += '事前にメールアドレスを登録していただいた方には、<br />"Balloooooon!"で使用できる特別クーポンをプレゼント！<br />サービスは2014年1月に提供開始を予定しています。';
                    html += "</p>";
                    $(".pre").html(html);
                }else if(result == 'success'){
                    var html = "";
                    html += "<p>";
                    html += "Balloooooon!事前登録受け付けへのご登録ありがとうございます。";
                    html += "</p>";
                    html += "<p>";
                    html += '事前にメールアドレスを登録していただいた方には、<br />"Balloooooon!"で使用できる特別クーポンをプレゼント！<br />サービスは2014年1月に提供開始を予定しています。';
                    html += "</p>";
                    $(".pre").html(html);
                }else{
                    alert(result);
                }
            });
        }else{
            alert("送信できませんでした。");
        }
    });
});
</script>
<div id="page">
    <p style="text-align:center;"><img src="/images/pre_logo.jpg" width="550" height="146" alt="Balloooooon!事前登録フォーム"><p>
    <div>
        <p style="padding:30px 42px;">
            <span style="font-size:142%;font-weight:bold;">週刊ワイズがクーポンサイト"Balloooooon!（バルーン）"を開始！</span><br />
            <br />
            <span style="color:#F09712;font-size:120%;font-weight:bold;">公開に先立ち、事前にメールアドレスを登録していただいた方には、<br />"Balloooooon!"で使用できる特別クーポンをプレゼント！</span><br />
            <br />
            "Balloooooon!"は、バンコクで使用できるお得なクーポンを購入できるサイトです。<br />
            飲食店だけではなく、美容やホテル、アクティビティまで、皆様のバンコクでの生活をより豊かにします。<br />
            また、メールマガジンを登録することで、特価商品のお知らせや、"Balloooooon!"でしか購入できないお得な情報が皆様の元に届きます。<br />
            <br />
            サービスは2014年1月に提供開始を予定しています。<br />
            サービス開始のお知らせや先行キャンペーン等のお得な情報をご希望の方は、下記ボタンからメールアドレスを登録ください。（本プレサイトへのご登録で料金が発生することはございません）
        </p>
        <div class='top_formbox'>
            <form accept-charset="UTF-8" action="<?php echo 'https://'.DOMAIN.'/intl/ja/site/pre'; ?>" autocomplete="off" class="new_user" id="new_user" method="post">
            <input class="field" id="user_email" name="user_email" placeholder="mail-address@mail.com" size="30" title="mail-address@mail.com" type="text" />
            <a href="#" id="send" rel="close" class="bt_l_o">同意して登録</a>
            </form>
        </div>
    </div>
    
</div><!-- eng mc wrapper -->
    </body>
</html>
