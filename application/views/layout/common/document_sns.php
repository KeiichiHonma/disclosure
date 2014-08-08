            <ul class="box_snsbtn cf">
                <li class="fb">
                    <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) return;
                    js = d.createElement(s); js.id = id;
                    js.src = "//connect.facebook.net/ja_JP/all.js#xfbml=1";
                    fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>

                    <div class="fb-like" data-send="false" data-href="<?php echo '/document/show/'.$document->id; ?>" data-layout="box_count" data-width="70" data-show-faces="false" data-font="arial"></div>
                </li>
                <li class="tw">
                    <a href='https://twitter.com/share' class='twitter-share-button' data-lang='ja' data-count='vertical'>ツイート</a>
                    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
                </li>
                <li class="hb"><a href="http://b.hatena.ne.jp/entry/<?php echo '/document/show/'.$document->id; ?>" class="hatena-bookmark-button" data-hatena-bookmark-layout="vertical-balloon" title="このエントリーをはてなブックマークに追加"><img src="http://b.st-hatena.com/images/entry-button/button-only.gif" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;" /></a>
                    <script type="text/javascript" src="http://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
                </li>
                <li class="gl">
                    <div class="g-plusone" data-size="tall"></div>
                    <script type="text/javascript">
                    window.___gcfg = {lang: 'ja'};

                    (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                    })();
                    </script>
                </li>
                <li class="po">
                    <a data-pocket-label="pocket" data-pocket-count="vertical" class="pocket-btn" data-lang="en" data-save-url="<?php echo '/document/show/'.$document->id; ?>"></a>
                    <script type="text/javascript">!function(d,i){if(!d.getElementById(i)){var j=d.createElement("script");j.id=i;j.src="https://widgets.getpocket.com/v1/j/btn.js?v=1";var w=d.getElementById(i);d.body.appendChild(j);}}(document,"pocket-btn-js");</script>
                </li>
            </ul>