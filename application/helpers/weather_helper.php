<?php
function get_day_of_the_week ($day_of_the_week,$is_public_holiday,$is_strong = FALSE) {
    $string = $is_public_holiday !== FALSE ? ' class="sun"' : '';
    switch ($day_of_the_week){
        case 1:
            return $is_strong ? '<em'.$string.'>(月)</em>' : '(月)';
        break;
        case 2:
            return $is_strong ? '<em'.$string.'>(火)</em>' : '(火)';
        break;
        case 3:
            return $is_strong ? '<em'.$string.'>(水)</em>' : '(水)';
        break;
        case 4:
            return $is_strong ? '<em'.$string.'>(木)</em>' : '(木)';
        break;
        case 5:
            return $is_strong ? '<em'.$string.'>(金)</em>' : '(金)';
        break;
        case 6:
            return $is_strong ? '<em class="sat">(土)</em>' : '(土)';
        break;
        case 7:
            return $is_strong ? '<em class="sun">(日)</em>' : '(日)';
        break;
    }
}
function get_class_day_of_the_week ($day_of_the_week) {
    switch ($day_of_the_week){
        case 6:
            return 'sat';
        break;
        case 7:
            return 'sun';
        break;
        default:
            return 'undisp';
    }
}
?>
