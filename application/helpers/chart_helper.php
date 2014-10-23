<?php
/*
startは一番下の値で計算するが、区切っていく数字次第で変動する
例）
-900でもアップ値が10000なら
-10000から50000

*/
function get_scale ($min_value,$max_value) {
    $math_int = 10;//計算は10分割
    $devide_int = 12;//表示は12分割
    
    $abs_min_value = abs($min_value);
    $abs_max_value = abs($max_value);
    
    //最小値と最大値の絶対値で大きい方の値の桁数を調査
    if($abs_min_value < $abs_max_value){
        $keta = strlen($abs_max_value);
        $sample_int = $abs_max_value;
    }else{
        $keta = strlen($abs_min_value);
        $sample_int = $abs_min_value;
    }

    //桁数+1桁の半分よりも大きいかどうか
    $p_one = '1';
    for ($i=0;$i<$keta;$i++){
        $p_one .= '0';
    }
    $p_one_int = intval($p_one);
    $half_int = ($p_one_int / 2);
    if($half_int < $sample_int){
        $left_max_or_min_int = $p_one_int;
    }else{
        $left_max_or_min_int = $half_int;
    }
    $left_max_or_min__keta = strlen($left_max_or_min_int);//5000 4桁

    //上か下のどちらが最大の数字を使用するか判定
    if($abs_min_value < $abs_max_value){
        //例 30000 -2061
        //最小値判定 最大値の絶対値が大きいため、1から始まる最大値の桁数がstart値となる
        if($min_value < 0){
            $head_int = substr($abs_min_value, 0, 1);
            $p_one = '1';
            $keta = $left_max_or_min__keta - 1;
            for ($i=0;$i<$keta;$i++){
                $p_one .= '0';
            }
            $start_int = -1 * intval($p_one);
        }else{
            $start_int = 0;
        }
        //アップ数字判定
        //例 30000 -2061の場合、20000?
        $up_sample_int = $abs_max_value + abs($start_int);
        $up_int = ceil($up_sample_int / $math_int);//10分割
    }elseif($abs_min_value == $abs_max_value){
        //最小値判定
        if($min_value < 0){
            $head_int = substr($abs_min_value, 0, 1);
            $p_one = strval($head_int+1);
            $keta = $left_max_or_min__keta - 1;
            for ($i=0;$i<$keta;$i++){
                $p_one .= '0';
            }
            $start_int = -1 * intval($p_one);
        }else{
            $start_int = 0;
        }
        //アップ数字判定
        //例 -30000 2061の場合 
        $up_sample_int = $abs_max_value + abs($start_int);
        $up_tmp_int = round($up_sample_int / $math_int,-1);//10分割 1の位を四捨五入

        //$abs_max_valueがキリの良い数字ではないため、
        $up_tmp_int_keta = strlen($up_tmp_int);
        $head_up_tmp_int = substr($up_tmp_int, 0, 1);
        $p_one = strval($head_up_tmp_int);
        $keta = $up_tmp_int_keta - 1;
        if($keta == 0) $keta = 1;

        for ($i=0;$i<$keta;$i++){
            $p_one .= '0';
        }
        $up_int = intval($p_one);
    }else{
        //最小値判定 最小値の絶対値が大きいため、最小値の先頭数字+1から始まる値がstart値となる
        if($min_value < 0){
            $head_int = substr($abs_min_value, 0, 1);
            $p_one = strval($head_int+1);
            $keta = $left_max_or_min__keta - 1;
            for ($i=0;$i<$keta;$i++){
                $p_one .= '0';
            }
            $start_int = -1 * intval($p_one);
        }else{
            $start_int = 0;
        }
        //アップ数字判定
        //例 -30000 2061の場合 
        $up_sample_int = $abs_max_value + abs($start_int);
        $up_tmp_int = round($up_sample_int / $math_int,-1);//10分割 1の位を四捨五入
        //$abs_max_valueがキリの良い数字ではないため、
        $up_tmp_int_keta = strlen($up_tmp_int);
        $head_up_tmp_int = substr($up_tmp_int, 0, 1);
        $p_one = strval($head_up_tmp_int);
        $keta = $up_tmp_int_keta - 1;
        if($keta == 0) $keta = 1;
        for ($i=0;$i<$keta;$i++){
            $p_one .= '0';
        }
        $up_int = intval($p_one);
    };
    return array($start_int,$devide_int,$up_int);
}
?>
