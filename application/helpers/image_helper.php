<?php

function get_thumb ($image) {
    $array = pathinfo($image);
    return $array['dirname'].'/'.$array['filename'].'_m_thumb.'.$array['extension'];
}
?>
