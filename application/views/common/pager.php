<?php if ($maxPageCount > 1) : ?>
    <ul>
    <?php if ($page > 1) : ?>
    <li><?php echo anchor(sprintf($pageFormat, $page-1), 'PREV'); ?></li>
    <?php endif ; ?>

    <?php
        $end = $page + $pageLinkNumber > $maxPageCount ? $maxPageCount : $page + $pageLinkNumber;
        $start = $page - $pageLinkNumber > 0 ? $page - $pageLinkNumber : 1;
    ?>
    
    <?php for($index = $start ; $index <= $end; $index++) : ?>
    <li><?php echo anchor(sprintf($pageFormat, $index), $index,'class="'.($index == $page ? 'active' : '').'"'.' rel="next"'); ?></li>
    <?php endfor; ?>
    
    <?php if ($page + 1 <= $maxPageCount) : ?>
    <li><?php echo anchor(sprintf($pageFormat, $page+1), 'NEXT'); ?></li>
    <?php endif ; ?>
    
    </ul>
<?php endif ; ?>