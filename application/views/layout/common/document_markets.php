                <h3 class="side_title">マーケット</h3>
                <ul>
                <?php foreach ($markets as $market) : ?>
                <li<?php if(isset($market_id) && $market->id == $market_id): ?> class="current"<?php endif; ?>><a href="<?php echo '/document/market/'.$market->id; ?>"><span><?php if(isset($market_id) && $market->id == $market_id): ?><i class="fa fa-hand-o-right"></i><?php endif; ?><?php echo $market->name; ?></span></a></li>
                <?php endforeach; ?>
                </ul>