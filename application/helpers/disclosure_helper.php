<?php
function new_number_format ($int) {
    return $int == 0 ? '-' : number_format($int);
}
?>
