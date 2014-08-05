<?php
if(!isset($searchPageFormat)) $searchPageFormat = '';
?>
<?php if ($maxPageCount > 1) : ?>

    <ul>
    <?php if (1 != $page) : ?>
    <li><a href="<?php echo lang_base_url(sprintf($pageFormat, 1).$searchPageFormat); ?>">≪</a></li>
    <?php endif ; ?>

    <?php if ($page > 1) : ?>
    <li><a href="<?php echo lang_base_url(sprintf($pageFormat, $page-1).$searchPageFormat); ?>"><</a></li>
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
    <li><a rel="next"  class="<?php if($index == $page) echo 'active'; ?>" href="<?php echo lang_base_url(sprintf($pageFormat, $index).$searchPageFormat); ?>"><?php echo $index; ?></a></li>
    <?php endfor; ?>

    <?php if ($page + 1 <= $maxPageCount) : ?>
    <li><a href="<?php echo lang_base_url(sprintf($pageFormat, $page+1).$searchPageFormat); ?>">></a></li>
    <?php endif ; ?>
    
    <?php if ($maxPageCount != $page) : ?>
    <li><a href="<?php echo lang_base_url(sprintf($pageFormat, $maxPageCount).$searchPageFormat); ?>">≫</a></li>
    <?php endif ; ?>
    </ul>
<?php endif ; ?>
