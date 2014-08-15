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
    </div>
    <!-- パンクズ -->
    <div id="breadcrumb" class="scrolltop">
        <div id="breadcrumbInner" class="cf">
            <span><a href="/">財務速報</a></span>
            <!-- <span><a href="/">今週の人気</a></span> -->
            <span><a href="/income/">年収速報</a></span>
            <span><a href="/ipo/">IPO</a></span>
            <!-- <span><a href="/change/">転職速報</a></span> -->
            
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