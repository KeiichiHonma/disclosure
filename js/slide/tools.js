// トップNEWS画像切り替え用
topFeatures = {}
//topFeatures.count = 2;
topFeatures.current = 0;
topFeatures.task = null;
topFeatures.delay = 300;
topFeatures.interval = 2500;
topFeatures.timer = null;
topFeatures.navi = function(_id){
 if (topFeatures.current != _id) {
  var l=$('#top-news1-inner div.top-news1-list ul li');
  l.eq(topFeatures.current).removeClass('current');
  l.eq(_id).addClass('current');
  if (_id < topFeatures.count) {
   if (topFeatures.current <topFeatures.count){
    $('#top-news1-inner .top-news1-photo').eq(topFeatures.current).hide();
   } else {
    $('#top-news1-inner .top-news1-photo').hide();
   }
   //$('#top-news1-inner .top-news1-photo').eq(_id).slideDown(1000);
   //$('#top-news1-inner .top-news1-photo').eq(_id).show('puff', {times: 2}, 300);
   $('#top-news1-inner .top-news1-photo').eq(_id).show('drop',{direction:"left"},300);
   //$('#top-news1-inner .top-news1-photo').eq(_id).show('explode',{pieces:36},300);
  }
  topFeatures.current = _id;
 }
};
topFeatures.auto = function(){
 var next = topFeatures.current +1;
 if (next ==topFeatures.count){
  next = 0;
 }
 topFeatures.navi(next);
};
topFeatures.restart = function(){
 try{clearInterval(topFeatures.timer);}catch(e){topFeatures.timer=null}
 try{topFeatures.task.cancel();}catch(e){topFeatures.task=null}
 topFeatures.timer = setInterval(topFeatures.auto,topFeatures.interval);
};

// movies init
(function() {
 if (document.getElementsByClassName('BrightcoveExperience').length){
  if (document.getElementById('bc-sdk')){
  } else {
   var bc = document.createElement('script');bc.type = 'text/javascript';bc.id = 'bc-sdk';bc.async = true;
   bc.src = ('https:' == document.location.protocol ? 'https://sadmin.brightcove.co.jp/' : 'http://admin.brightcove.co.jp') + '/js/BrightcoveExperiences.js';
   var s = document.getElementsByTagName('script')[0];
   s.parentNode.insertBefore(bc, s);
   setTimeout(function() {
    try{
     brightcove.createExperiences();
    } catch(err){
     setTimeout(arguments.callee, 100);
    }
   },100);
  }
 }
})();
// --


// TOP FEATURE Effect
if ($('#top-news1-inner div.top-news1-list ul li').length){
 topFeatures.count = $('#top-news1-inner div.top-news1-list ul li').length;
 $('#top-news1-inner div.top-news1-list ul li').hover(
  function(){
   var id = $('#top-news1-inner div.top-news1-list ul li').index(this);
   topFeatures.task = topFeatures.navi.later(topFeatures.delay)(id);
  },
  function(){
   try{topFeatures.task.cancel();}catch(e){topFeatures.task=null}
  }
 );
 $('top-news1-inner').hover(
  function(){
   try{clearInterval(topFeatures.timer);}catch(e){topFeatures.timer=null}
  },
  function(){
   topFeatures.timer = setInterval(topFeatures.auto,topFeatures.interval);
  }
 );

  //$('#contents-menu ul li').click(function(){
   //if ($(this).hasClass('topics')) {
    //try{clearInterval(topFeatures.timer);}catch(e){topFeatures.timer=null}
    //topFeatures.timer = setInterval(topFeatures.auto,topFeatures.interval);
   //} else {
    //try{clearInterval(topFeatures.timer);}catch(e){topFeatures.timer=null}
   //}
  //});

 topFeatures.current = topFeatures.count;
 topFeatures.navi(0);
 topFeatures.timer = setInterval(topFeatures.auto,topFeatures.interval);
 $(window).focus(function() {
  $('#top-news1-inner .top-news1-photo').stop(true,true);
  try{clearInterval(topFeatures.timer);}catch(e){topFeatures.timer=null}
  topFeatures.timer = setInterval(topFeatures.auto,topFeatures.interval);
 });
 $(window).blur(function() {
  $('#top-news1-inner .top-news1-photo').stop(true,true);
  try{clearInterval(topFeatures.timer);}catch(e){topFeatures.timer=null}
 });
}
