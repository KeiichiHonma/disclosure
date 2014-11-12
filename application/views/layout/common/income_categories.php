                <h3 class="side_title">業界カテゴリー</h3>
                <ul>
                <?php foreach ($income_categories as $income_category) : ?>
                <li<?php if(isset($category_id) && $income_category->_id == $category_id): ?> class="current"<?php endif; ?>><a href="<?php echo '/income/category/'.$income_category->_id; ?>"><span><?php if(isset($category_id) && $income_category->_id == $category_id): ?><i class="fa fa-hand-o-right"></i><?php endif; ?><?php echo $income_category->col_name; ?></span><span class="new">平均<?php echo $income_category->col_income_average; ?>万円</span></a></li>
                <?php endforeach; ?>
                </ul>