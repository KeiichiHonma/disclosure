<!-- 
//////////////////////////////////////////////////////////////////////////////
header
//////////////////////////////////////////////////////////////////////////////
-->
<div id="header" class="cf">
    <div id="headerInner" class="cf">
        <h1><a href="/">オープンデータ.company</a></h1>
        <h2>企業情報のオープンデータ活用サービス</h2>
    </div>
    <!-- パンクズ -->
    <div id="breadcrumb"<?php if(!isset($pageId) || $pageId != 'document_show'): ?><?php echo ' class="scrolltop"'; ?><?php endif; ?>>
        <div id="breadcrumbInner" class="cf">
            <span><a href="/"><?php echo $this->lang->line('common_title_home'); ?></a></span>
            <span><a href="/income/"><?php echo $this->lang->line('common_title_income'); ?></a></span>
            <span><a href="/finance/category/1/pl"><?php echo $this->lang->line('common_title_pl'); ?></a></span>
            <span><a href="/finance/category/1/bs"><?php echo $this->lang->line('common_title_bs'); ?></a></span>
            <span><a href="/finance/category/1/cf"><?php echo $this->lang->line('common_title_cf'); ?></a></span>
            <span><a href="/site/issues"><?php echo $this->lang->line('common_title_issues'); ?></a></span>
            
    <?php if(!isset($isIndex)) : ?>
            <div class="searchBox undisp">
                <div class="searchBoxInner">
                <?php echo form_open('/search/keyword','method="get" onsubmit="s_confirm();return false;" id="search"'); ?>
                    <input type="text" name="keyword" value="<?php echo !empty($keyword) ? $keyword : $this->lang->line('search_box_default'); ?>" class="focus" /><input type="image" src="/images/btn_search_min.png" align="top" alt="検索" class="btnSearch" />
                <?php echo form_close(); ?>
                </div>
            </div>
    <?php endif; ?>
        </div>
    </div>
</div>