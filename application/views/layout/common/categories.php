                <h3 class="side_title">業界カテゴリー</h3>
                <dl class="all">
                <?php foreach ($categories as $category) : ?>
                    <dt<?php if(isset($category_id) && $category->id == $category_id): ?> class="current"<?php endif; ?>><span><?php if(isset($category_id) && $category->id == $category_id): ?><i class="fa fa-hand-o-right"></i><?php endif; ?><?php echo $category->name; ?></span></dt>
                    <dd><?php echo anchor('document/category/'.$category->id, '有報'); ?>&nbsp;&nbsp;<?php echo anchor('income/category/'.$category->id, '年収'); ?>&nbsp;&nbsp;<?php echo anchor('finance/category/'.$category->id.'/pl', 'P/L'); ?>&nbsp;&nbsp;<?php echo anchor('finance/category/'.$category->id.'/bs', 'BS'); ?>&nbsp;&nbsp;<?php echo anchor('finance/category/'.$category->id.'/cf', 'CF'); ?></dd>
                <?php endforeach; ?>
                </dl>