<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="copyright" content="&copy;hareco" />
<meta property="og:title" content="<?php echo isset($header_title) ? $header_title : $this->lang->line('header_title'); ?>" />
<meta property="og:type" content="<?php echo isset($isHome) ? 'website' : 'article' ?>" />
<meta property="og:image" content="<?php echo isset($og_image) ? $og_image : 'http://hareco.jp/images/apple-touch-icon-precomposed.png' ?>" />
<meta property="og:url" content="<?php echo site_url($this->uri->uri_string()); ?>" />
<meta property="og:description" content="<?php echo isset($header_description) ? $header_description : $this->lang->line('header_description'); ?>" />

<meta name="viewport" content="user-scalable=yes" />
<title><?php echo isset($header_title) ? $header_title : $this->lang->line('header_title'); ?></title>
<meta name="keywords" content="<?php echo isset($header_keywords) ? $header_keywords : $this->lang->line('header_keywords'); ?>" />
<meta name="description" content="<?php echo isset($header_description) ? $header_description : $this->lang->line('header_description'); ?>" />
<link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon-precomposed.png" />
<link href="/favicon.ico" rel="shortcut icon" />
<?php foreach($this->config->item('stylesheets') as $css) : ?>
<?php echo link_tag($css) . "\n"; ?>
<?php endforeach; ?>

<?php foreach($this->config->item('javascripts') as $js) : ?>
<?php echo script_tag($js); ?>
<?php endforeach; ?>
<!--[if IE 6]><script type="text/javascript" src="/js/DD_belatedPNG.js"></script><![endif]-->
<!--[if IE 8]><script type="text/javascript" src="js/jquery.backgroundSize.js"></script><![endif]-->
<!--[if lte IE 9]><script type="text/javascript" src="js/textshadow.js"></script><![endif]--> 
<script type="text/javascript">
$(function(){
    <?php if(isset($isSlide)) : ?>
    /*- スライダー */
    $('#slider').bxSlider({
        auto:true,
        speed:3000,
        pause:10000,
        mode: 'fade',
        hideControlOnEnd:false,
        pager:false,
        captions: false,
        autoHover:true
    });
    <?php if(isset($isBigSlide)) : ?>
    $('#slider').append('<div class="big_gradationLeft"></div><div class="big_gradationRight"></div>');
    <?php else: ?>
    $('#slider').append('<div class="gradationLeft"></div><div class="gradationRight"></div>');
    <?php endif; ?>
    <?php endif; ?>
    /* 検索ボックス */
    $(".focus").focus(function(){
    if(this.value == "<?php echo $this->lang->line('search_box_default') ?>"){
            $(this).val("").css("color","#333");
            }
        });
        $(".focus").blur(function(){
            if(this.value == ""){
            $(this).val("<?php echo $this->lang->line('search_box_default') ?>").css("color","#a0a09f");
        }
    });

    /* PC用プルダウンメニュー */
    $(".navPc li").click(function() {
        $(this).children('ul').fadeToggle(300);
        $(this).nextAll().children('ul').hide();
        $(this).prevAll().children('ul').hide();
    });
    /* スマホ用メニュー */
    $('#right-menu').sidr({
      name: 'sidr-right',
      side: 'right'
    });
    /* リンク画像マウスオーバー処理 */
    $("a img, div.box").live({ // イベントを取得したい要素
        mouseenter:function(){
            $(this).fadeTo("fast", 0.7);
        },
        mouseleave:function(){
            $(this).fadeTo("fast", 1.0);
        }
    });



    /* IE8 background-size対策 */
    jQuery('#cloud,#header h1 a,#header h2, #header .navPc li a.ttl').css({backgroundSize: "cover"});
});

function s_confirm () {
    if($(".focus").val() != '' && $(".focus").val() != "<?php echo $this->lang->line('search_box_default') ?>") $('#search').submit();
}
</script>

</head>
<body id="<?php echo $bodyId; ?>">
<?php if(ENVIRONMENT == 'production'): ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-56610687-1', 'auto');
  ga('send', 'pageview');

</script>
<?php endif; ?>
<?php if($notify = $this->session->flashdata('notify')): ?>
<script type="text/javascript">
$(function() {
    $.notifyBar({
        html: "<?php echo $notify; ?>",
        cssClass: "success",
        opacity:0.9,
        delay:4000,
        animationSpeed:400
    });
});
</script>
<?php endif; ?>