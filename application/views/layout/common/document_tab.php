        <ul class="tabrow">
            <?php if($document->is_html == 0): ?>
            <li<?php if($document_tab_current == 'document_show'): ?> class="selected"<?php endif; ?>><?php echo anchor('document/show/'.$document->id, $document->document_name); ?></li>
            <li<?php if($document_tab_current == 'document_data'): ?> class="selected"<?php endif; ?>><?php echo anchor('document/data/'.$document->id,'数値データ'); ?></li>
            <?php else: ?>
            <li<?php if($document_tab_current == 'document_data'): ?> class="selected"<?php endif; ?>><?php echo anchor('document/data/'.$document->id,$document->document_name); ?></li>
            <?php endif; ?>
            
            <li<?php if($document_tab_current == 'document_dl_csv'): ?> class="selected"<?php endif; ?>><a href="<?php echo '/document/download/'.$document->id.'/csv'; ?>">CSVファイルダウンロード</a></li>
            <li<?php if($document_tab_current == 'document_dl_xlsx'): ?> class="selected"<?php endif; ?>><a href="<?php echo '/document/download/'.$document->id.'/xlsx'; ?>">エクセルファイルダウンロード</a></li>
        </ul>