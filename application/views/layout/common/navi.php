<!-- 
//////////////////////////////////////////////////////////////////////////////
header
//////////////////////////////////////////////////////////////////////////////
-->
<div id="header" class="cf">
    <div id="headerInner" class="cf">
        <h1><a href="/">ハレコ</a></h1>
        <h2>晴れてよかった！を創るレコメンドサービス</h2>
        <!-- PC用ナビゲーション -->
        <ul class="navPc">
            <li><a href="javascript:void(0)" class="ttl nav06"><span>▼レジャー施設の天気</span></a>
            </li>
        </ul>

        <!-- スマホ用ナビゲーション -->
        <div class="navSp">
            <span><a id="right-menu" href="javascript:void(0)">スマホ用ナビゲーション</a></span>
            <div id="sidr-right">
                <ul>
                    <li class="ttl">カテゴリ</li>
                    <li><a href="/area/">エリアから探す</a></li>
                    <li><a href="/spring/">温泉地から探す</a></li>
                    <li><a href="/airport/">空港から探す</a></li>
                    <li><a href="/leisure/">レジャー・行楽地から探す</a></li>
                </ul>
            </div>
        </div>
    <?php if(isset($isHome)) : ?>
        <div id="cloud">
            <h3>予測正答率</h3>
            <span><?php echo $odds->percentage; ?>%</span>
        </div>
        <div id="desc"><span>ハレコへようこそ！</span><br />ハレコは過去50年分の天気データを元にして、独自の天気予測エンジンで各地の天気を1年先まで予測しています。</div>
    <?php endif; ?>
    </div>
    <!-- パンクズ -->
    <div id="breadcrumb" class="scrolltop">
        <div id="breadcrumbInner" class="cf">
            <?php if(isset($isIndex)) : ?><div class="undisp"><?php endif; ?>
            <?php if(isset($topicpaths)) : ?>
            <?php
                $count = count($topicpaths);
                $validate_number = $count >= 2 ? $count - 2 : 1;
                $i = 1;
            ?>
            <?php foreach ($topicpaths as $key => $topicpath) : ?>
                <?php if(strcasecmp($key,'news') == 0): ?>
                <span class="news"><p><?php echo $topicpath[1]; ?></p></span>
                <?php else: ?>
                <span<?php if($i <= $validate_number) echo ' class="undisp"'; ?>><?php echo is_null($topicpath[0]) ? $topicpath[1] :  '<a href="'.$topicpath[0].'">'.$topicpath[1].'</a>'; ?></span>
                <?php endif; ?>
                <?php $i++; ?>
            <?php endforeach; ?>
            <?php if(isset($isIndex)) : ?></div><?php endif; ?>
            <?php endif; ?>
    <?php if(!isset($isIndex)) : ?>
            <div class="searchBox">
                <div class="searchBoxInner">
                <?php echo form_open('/search','method="get" onsubmit="s_confirm();return false;" id="search"'); ?>
                    <input type="text" name="keyword" value="<?php echo !empty($keyword) ? $keyword : $this->lang->line('search_box_default'); ?>" class="focus" /><input type="image" src="/images/btn_search_min.png" align="top" alt="検索" class="btnSearch" />
                <?php echo form_close(); ?>
                </div>
            </div>
    <?php endif; ?>
        </div>
    </div>
</div>