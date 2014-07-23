// jquery ScrollTop
// version 3.0
// GPL licenses
// Copyright 2013 irohane.com
$(function(){
	$(document).ready(function(){
		var len = $("body .scrolltop").length;
		var windowst = $(window).scrollTop();
		var st = new Array();
		var sl = new Array();
		var sw = new Array();
		var sh = new Array();
		var one = new Array();
		var bs = new Array();
		$("<div class='scrolltop'></div>").insertAfter("body .scrolltop");
		for (var i = 0; i < len*2; i+=2) {
			bs[i] = $("body .scrolltop").eq(i);
			bs[i+1] = $("body .scrolltop").eq(i+1);
			var id = bs[i].attr("id");
			bs[i+1].attr("id", id);
			bs[i+1].css({
				display: "none",
				opacity: "0",
				zIndex: "-1"
			});
			
			st[i/2] = bs[i].offset().top;
			sl[i/2] = bs[i].offset().left;
			sw[i/2] = bs[i].width();
			sh[i/2] = bs[i].height();
			one[i/2] = !(st[i/2] > windowst);
		};
		scr();
		
		$(window).resize(function() {
			for (var i = 0; i < len*2; i+=2) {
				bs[i].css({
					position: "",
					top: "",
					left: "",
					width: "",
					margin: ""
				});
				bs[i+1].css({
					display: "none"
				});
				st[i/2] = bs[i].offset().top;
				sl[i/2] = bs[i].offset().left;
				sw[i/2] = bs[i].width();
				sh[i/2] = bs[i].height();
				one[i/2] = true;
			}
			scr();
		});
		
		$(window).scroll(function(){
			windowst = $(this).scrollTop();
			scr();
		});
		
		function scr(){
			for (var i = 0; i < len*2; i+=2) {
				if(st[i/2] > windowst && !one[i/2]) {
					bs[i].css({
						position: "",
						top: "",
						left: "",
						width: "",
						margin: ""
					});
					bs[i+1].css({
						display: "none"
					});
					one[i/2] = true;
				} else if(!(st[i/2] > windowst) && one[i/2]) {
					bs[i].css({
						position: "fixed",
						top: 0,
						left: sl[i/2]+"px",
						width: sw[i/2]+"px",
						margin: "0px"
					});
					bs[i+1].css({
						display: "",
						width: sw[i/2]+"px",
						height: sh[i/2]+"px"
					});
					one[i/2] = false;
				}
			}
		}
	});
});