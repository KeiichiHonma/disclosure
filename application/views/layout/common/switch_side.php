            <div id="side_cat">
                <h1 class="side_title"><?php echo $edinet->presenter_name; ?></h1>
                <ul>
                <li<?php if(isset($switch_side_current) && $switch_side_current == 'finance'): ?> class="current"<?php endif; ?>><a href="<?php echo '/finance/show/'.$edinet->presenter_name_key; ?>"><span>ファイナンス</span><span class="ico"><img src="/images/icon/comnpany_info_20.png" alt="document" /></span></a></li>
                <?php if(!empty($etc_documents)): ?><li<?php if(isset($switch_side_current) && $switch_side_current == 'document_company'): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/company/'.$edinet->presenter_name_key; ?>"><span>有価証券報告書一覧</span><span class="ico"><img src="/images/icon/presenter_documents_20.png" alt="presenter_documents" /></span></a></li><?php endif; ?>
                <?php if(!empty($company)): ?>
                <li<?php if(isset($switch_side_current) && $switch_side_current == 'company_show'): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/show/'.$edinet->presenter_name_key; ?>"><span>年収情報</span><span class="ico"><img src="/images/icon/comnpany_info_20.png" alt="omnpany_info" /></span></a></li>
                <?php endif; ?>
                </ul>
            </div><!-- /side_cat -->