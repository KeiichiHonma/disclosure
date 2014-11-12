<?php if ($maxPageCount > 1) : ?>
<div class="pager pb10">
    <ul>
    <?php if ($page > 1) : ?>
    <li><?php echo anchor(sprintf($pageFormat, $page-1), ' < '); ?></li>
    <?php endif ; ?>

    <?php
        $base_number = intval(floor($pageLinkNumber / 2));
        if($maxPageCount <= $page){
            $end = $maxPageCount;
        }elseif($maxPageCount > $page){
            if($page + $base_number >= $maxPageCount){
                $end = $maxPageCount;
            }elseif($pageLinkNumber > $maxPageCount){
                $end = $maxPageCount;
            }elseif($page <= $base_number){
                $end = $pageLinkNumber;
            }else{
                $end = $page + $base_number;
            }
        }else{
            $end = $maxPageCount;
        }
        
        if($page - $base_number > 0){
            if($page + $base_number > $maxPageCount){
                $start = $maxPageCount - ($pageLinkNumber - 1);
                if($start <= 0) $start = 1;
            }else{
                $start = $page - $base_number;
            }
        }else{
            $start = 1;
        }
    ?>
    
    <?php for($index = $start ; $index <= $end; $index++) : ?>
    <li><?php echo anchor(sprintf($pageFormat, $index), $index,'class="'.($index == $page ? 'active' : '').'"'.' rel="next"'); ?></li>
    <?php endfor; ?>
    
    <?php if ($page + 1 <= $maxPageCount) : ?>
    <li><?php echo anchor(sprintf($pageFormat, $page+1), ' > '); ?></li>
    <?php endif ; ?>
    
    </ul>
</div>
<?php endif ; ?>