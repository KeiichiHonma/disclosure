                <h3 class="side_title">業界カテゴリー</h3>
                <dl>
                <?php foreach ($markets as $market) : ?>
                <?php if($market->id > 1): ?>
                    <dt<?php if(isset($market_id) && $market->id == $market_id): ?> class="current"<?php endif; ?>><span><?php if(isset($market_id) && $market->id == $market_id): ?><i class="fa fa-hand-o-right"></i><?php endif; ?><?php echo $market->name; ?></span></dt>
                    <dd><?php echo anchor('finance/market/'.$market->id.'/pl', 'P/L'); ?>&nbsp;&nbsp;<?php echo anchor('finance/market/'.$market->id.'/bs', 'BS'); ?>&nbsp;&nbsp;<?php echo anchor('finance/market/'.$market->id.'/cf', 'CF'); ?></dd>
                <?php endif; ?>
                <?php endforeach; ?>
                </dl>