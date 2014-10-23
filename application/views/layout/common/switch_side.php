            <div id="side_cat">
                <h1 class="side_title"><?php echo $edinet->presenter_name; ?></h1>
                <ul>
                <li<?php if(isset($switch_side_current) && $switch_side_current == 'finance'): ?> class="current"<?php endif; ?>><a href="<?php echo '/finance/show/'.$edinet->presenter_name_key; ?>"><span>ファイナンス</span><span class="ico"><i class="fa fa-bar-chart fa-2x"></i></span></a></li>
                <?php if(!empty($etc_documents)): ?><li<?php if(isset($switch_side_current) && $switch_side_current == 'document_company'): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/company/'.$edinet->presenter_name_key; ?>"><span>有価証券報告書一覧</span><span class="ico"><i class="fa fa-files-o fa-2x"></i></span></a></li><?php endif; ?>
                <?php if(!empty($company)): ?>
                <li<?php if(isset($switch_side_current) && $switch_side_current == 'company_show'): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/show/'.$edinet->presenter_name_key; ?>"><span>年収情報</span><span class="ico"><i class="fa fa-jpy fa-2x"></i></span></a></li>
                <?php endif; ?>
                </ul>
            </div><!-- /side_cat -->