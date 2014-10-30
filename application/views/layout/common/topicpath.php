            <div class="topicpath">
                <ul>
                    <?php foreach ($topicpaths as $key => $topicpath) : ?>
                        <li<?php echo $key == 0 ? ' class="home"' : ''; ?>><?php echo $key == 0 ? '' : '<i class="fa fa-angle-right"></i>'; ?><?php echo is_null($topicpath[0]) ? $topicpath[1] :  anchor($topicpath[0], $topicpath[1]); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>