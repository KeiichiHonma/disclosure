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

<meta name="viewport" content="width=device-width,user-scalable=0" />
<title><?php echo isset($header_title) ? $header_title : $this->lang->line('header_title'); ?></title>
<meta name="keywords" content="<?php echo isset($header_keywords) ? $header_keywords : $this->lang->line('header_keywords'); ?>" />
<meta name="description" content="<?php echo isset($header_description) ? $header_description : $this->lang->line('header_description'); ?>" />
<link rel="shortcut icon" type="image/x-icon" href="/images/favicon.ico" />
<link rel="icon" type="image/png" href="/images/favicon.png" />
<link rel="apple-touch-icon-precomposed" href="/images/apple-touch-icon-precomposed.png" />

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
    /* カレンダー */
    $("#datepicker").datepicker();            
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
