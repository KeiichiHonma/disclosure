/*
* Notify Bar - $ plugin
*
* Copyright (c) 2009-2013 Dmitri Smirnov
*
* Licensed under the MIT license:
* http://www.opensource.org/licenses/mit-license.php
*
* Version: 1.4
*
* Project home:
* http://www.whoop.ee/posts/2013-04-05-the-resurrection-of-jquery-notify-bar/
*
* Modifier home:
* http://www.printf.jp/
*/
"use strict";

(function ($) {
    function getLanguage() {
        try {
            return (navigator.browserLanguage || navigator.language || navigator.userLanguage).substr(0, 2);
        }
        catch(e) {
            return 'en';
        }
    }

    $.notifyBar = function (options) {
        switch (options) {
            case 'closeAll':
                var obj = $('.jquery-notify-bar:visible');
                obj.each(function() {
                    $(this).hideNB();
                });
                return;
            case 'zIndex':
                $.notifyBar.single.zIndex = arguments[1];
                return;
        };

        var rand = parseInt(Math.random() * 100000000, 0),
            text_wrapper,
            bar = {},
            settings = {};

        var closeTexts = {
            en: 'Close [X]',
            ja: '閉じる'
        }
        var lang = getLanguage();

        var settings = $.extend({
            html           : 'Your message here',
            delay          : 2000,
            animationSpeed : 200,
            cssClass       : 'normal',
            jqObject       : '',
            close          : false,
            closeText      : closeTexts[lang],
            closeOnClick   : true,
            closeOnOver    : false,
            onBeforeShow   : null,
            onShow         : null,
            onBeforeHide   : null,
            onHide         : null,
            position       : 'top',

            showEffect     : 'slide',
            dismissEffect  : 'slide',
            width          : '100%',
            minWidth       : 0,
            opacity        : 1.0,
            align          : 'center',
            closeCondition : 'align',
            icon           : true
        }, options);

        // Use these methods as private.
        function callHandler(handler, bar) {
            if (typeof handler === 'function') {
                handler.call(null, bar);
            }
        }

        // show notify bar
        this.fn.showNB = function () {
            // 表示前
            var self = $(this);
            callHandler(settings.onBeforeShow, self);

            // 表示
            var showEffect = $.notifyBar.single.effects[self.data('showEffect') + 'In'];
            showEffect(self.stop(), asTime, function ()
            {
                // 表示後
                self = $(this);
                callHandler(settings.onShow, self);
            });
        };

        // hide notify bar
        this.fn.hideNB = function (/*delayed*/) {
            var self = $(this);

            // 自分のsettingsを取得
            var barsData = $.notifyBar.single.barsData;
            var mySettings = null;
            var id = self.attr('id');

            for (var i = 0; i < barsData.length; i++) {
                if (barsData[i].id === id) {
                    mySettings = barsData[i].settings;
                    barsData.splice(i, 1);
                    break;
                }
            }

            if (mySettings === null)
                return;

            // 閉じる前
            callHandler(mySettings.onBeforeHide, self);

            // 閉じる
            var dismissEffect = $.notifyBar.single.effects[self.data('dismissEffect') + 'Out'];
            dismissEffect(self.stop(), asTime, function ()
            {
                // 閉じた後
                self = $(this);
                self.remove();
                callHandler(mySettings.onHide, self);
            });
        };

        // get animation speed
        function animationSpeed() {
            switch (settings.animationSpeed) {
                case "slow":
                    return 600;

                case "default":
                case "normal":
                    return 400;

                case "fast":
                    return 200;
            }
            return settings.animationSpeed;
        }

        // create notify bar
        function createNotifyBar() {
            var scrollbar = false;

            if (settings.jqObject) {
                bar = settings.jqObject.clone(false);
                bar.css({textAlign: 'left'});
                settings.html = bar.html();

                if (bar.data('notify-bar-scroll') === true)
                    scrollbar = true;
            } else {
                bar = $("<div></div>")
                    .addClass(settings.cssClass)
                    .attr('id', '');
            }

            bar.addClass("jquery-notify-bar");
            bar.data("showEffect",    settings.showEffect);
            bar.data("dismissEffect", settings.dismissEffect);

            text_wrapper = $("<span></span>")
                .html(settings.html);

            if (settings.icon === true) {
                text_wrapper
                    .addClass("notify-bar-text-wrapper");
            }

            bar.html(text_wrapper).hide();

            // id 付きのアンカーをクリックした場合は、元々のアンカーにクリック処理を渡す
            bar.find('a').each(function () {
                var id = $(this).attr('id');
                if (id) {
                    $(this).attr('id', id + '__NB').click(function(e) {
                        id = $(this).attr('id').slice(0, -4);
                        $('#' + id).eq(0).click();
                        return false;
                    });
                }
            });

            // Style close button in CSS file
            if (settings.close) {
                // If close settings is true. Set delay to one billion seconds.
                // It'a about 31 years - mre than enough for cases when notify bar is used.
                settings.delay = Math.pow(10, 9);
                bar.addClass("notify-bar-has-close");
                var link = $("<a class='notify-bar-close'>" + settings.closeText + "</a>");
                bar.append(link);
                link.click(function (event) {
                    event.preventDefault();
                    bar.hideNB();
                });
            }

            $("body").prepend(bar);

            // 以下、prepend()した後でないと正しく機能しない処理

            if (scrollbar || $(window).height() < bar.height()) {
                // 'auto' でブラウザに処理を任せると一瞬ガタつくので自前で判断する
                bar.css({overflow: 'scroll'});
            }

            return bar;
        }

        // set left, right and width
        function setLeftRightWidth(obj) {
            var width = settings.width;

            if (isNaN(width) && width.slice(-1) === '%') {
                width = width.slice(0, -1);
                width = $('body').width() / 100 * width;
            }
            else if (width === 'auto') {
                width = bar.width();
            }

            width = Math.max(width, settings.minWidth);
            var padding = parseInt(bar.css('paddingLeft')) + parseInt(bar.css('paddingRight'));
            var left =  ($(window).width() - (width + padding)) / 2;
            var right = "auto";

            switch (settings.align) {
                case "left":
                    left = 0;
                    break;
                case "right":
                    left = "auto";
                    right = 0;
                    break;
            }

            obj.left =  left;
            obj.right = right;
            obj.width = width;
        }

        // set top, bottom and height
        function setTopBottomHeight(obj) {
            var height = Math.min(bar.height(), $(window).height() - (50 + 50));
            var padding = parseInt(bar.css('paddingTop')) + parseInt(bar.css('paddingBottom'));
            var _top;
            var _bottom;

            switch (settings.position) {
                case "top":
                    _top = 0;
                    _bottom = "auto";
                    break;
                case "bottom":
                    _top = "auto";
                    _bottom = 0;
                    break;
                case "center":
                    _top = ($(window).height() - (height + padding)) / 2;
                    _bottom = "auto";
                    break;
            }

            obj.top = _top;
            obj.bottom = _bottom;
            obj.height = height;
        }

        // create notify bar
        bar = createNotifyBar();

        // set notify bar css
        var css = {
            opacity: settings.opacity,
            zIndex : $.notifyBar.single.zIndex++
        };

        setLeftRightWidth(css);
        setTopBottomHeight(css);
        bar.css(css);

        // Allow the user to click on the bar to close it
        if (settings.closeOnClick) {
            bar.css({cursor: 'pointer'});
            bar.click(function () {
                bar.hideNB();
            });
        }

        // Allow the user to move mouse on the bar to close it
        if (settings.closeOnOver) {
            bar.mouseover(function () {
                bar.hideNB();
            });
        }

        // set timeout
        var asTime = animationSpeed();
        setTimeout(function () {
            bar.hideNB();
        }, settings.delay + asTime);

        // add classes to notify bar
        var orgid = bar.attr('id');
        var classes = {
            all:      orgid + "__notifyBar_",
            position: orgid + "__notifyBar_" + 'P' + settings.position,
            align:    orgid + "__notifyBar_" + 'A' + settings.align,
            one:      orgid + "__notifyBar_" + 'P' + settings.position + 'A' + settings.align
        };

        for (var key in classes)
            bar.addClass(classes[key]);

        // set new id
        var newid = classes['one'] + rand;
        bar.attr('id', newid);

        // add notify bar data
        $.notifyBar.single.barsData.push({settings: settings, id: newid});

        // Close if there is a match condition.
        if (settings.close) {
            var closeClass = classes[settings.closeCondition];
            obj = $('.jquery-notify-bar:visible');

            obj.each(function() {
                var self = $(this);

                if (self.hasClass("notify-bar-has-close") && self.hasClass(closeClass))
                    self.hideNB();
            });
        }

        bar.showNB();
    };

    $.notifyBar.single = {
        zIndex: 32700,
        effects: {
            slideIn:  function(obj, time, func) {obj.slideDown(time, func);},
            slideOut: function(obj, time, func) {obj.slideUp(  time, func);},
            fadeIn:   function(obj, time, func) {obj.fadeIn(   time, func);},
            fadeOut:  function(obj, time, func) {obj.fadeOut(  time, func);}
        },
        barsData: new Array()
    };
})(jQuery);