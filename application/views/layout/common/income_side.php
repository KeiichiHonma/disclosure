        <div id="sidebar">
            <div id="side_cat">
                <h1 class="side_title"><?php echo $company->col_name; ?></h1>
                <ul>
                    <li<?php if($income_side_current == 'income_show'): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/show/'.$edinet->presenter_name_key; ?>"><span>年収情報</span><span class="ico"><img src="/images/icon/comnpany_info_20.png" alt="omnpany_info" /></span></a></li>
                    <li<?php if($income_side_current == 'income_dl_csv'): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/download/'.$edinet->presenter_name_key.'/csv'; ?>"><span>CSVファイルダウンロード</span><span class="ico"><img src="/images/icon/csv_20.png" alt="csv" /></a></li>
                    <li<?php if($income_side_current == 'income_dl_xlsx'): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/download/'.$edinet->presenter_name_key.'/xlsx'; ?>"><span>EXCELファイルダウンロード</span><span class="ico"><img src="/images/icon/xlsx_20.png" alt="xlsx" /></span></a></li>
                    <?php if(!empty($documents)): ?>
                    <li<?php if($income_side_current == 'document_show'): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/company/'.$edinet->presenter_name_key; ?>"><span>開示情報一覧</span><span class="ico"><img src="/images/icon/document_20.png" alt="document" /></span></a></li>
                    <?php endif; ?>
                </ul>
            </div><!-- /side_cat -->
            <div class="side_list">
                <h1 class="side_title">業界カテゴリ</h1>
                <ul>
                <?php foreach ($income_categories as $income_category) : ?>
                <li><a href="<?php echo '/income/category/'.$income_category->_id ?>"><span><?php echo $income_category->col_name; ?></span><span class="new">平均<?php echo $income_category->col_income_average; ?>万円</span></a></li>
                <?php endforeach; ?>
                </ul>
            </div>
            
            <div class="box_wrap">
                <div class="box_adx pcdisp">
                    <img src="/images/ad_example1.gif" alt="" />
                </div>
                <div class="box_adx spdisp">
                    <img src="/images/ad_example_sp1.jpg" alt="" />
                </div>
            </div>

        </div>