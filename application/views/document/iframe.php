<!DOCTYPE html>
<html>
<head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $("#parent-iframe", window.parent.document).height(document.body.scrollHeight);
    var param;
    param = parent.location.search;
    param = param.substr(1, param.length);
    var target = $("#"+param);
    //$('body,html', window.parent.document).animate({scrollTop: target.offset().top+200 }, 400, 'linear');//ヘッダ200px
    $('body,html', window.parent.document).scrollTop( target.offset().top+200 );
});
</script>   
</head>
<body>
<?php echo $document_htmls->html_data; ?>
</body>
</html>
