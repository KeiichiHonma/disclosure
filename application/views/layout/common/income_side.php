        <div id="sidebar">
            <?php $this->load->view('layout/common/switch_side'); ?>
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