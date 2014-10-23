                <h3 class="side_title">業界カテゴリー</h3>
                <ul>
                <?php foreach ($categories as $category) : ?>
                <?php if($category->id > 1): ?><li<?php if(isset($category_id) && $category->id == $category_id): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/category/'.$category->id; ?>"><span><?php echo $category->name; ?></span></a></li><?php endif; ?>
                <?php endforeach; ?>
                </ul>