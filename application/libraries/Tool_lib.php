<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tool_lib
{
    function __construct()
    {
        $this->ci =& get_instance();
    }

    function get_icon ($weather_string) {

        $icon_type_01 = '01';
        $icon_type_02 ='02';
        $icon_type_03 ='03';
        $icon_type_04 ='04';
        $icon_type_05 ='05';
        $icon_type_06 ='06';
        $icon_type_07 ='07';
        $icon_type_08 ='08';
        $icon_type_09 ='09';
        $icon_type_10 ='10';
        $icon_type_11 ='11';
        $icon_type_12 ='12';
        $icon_type_13 ='13';
        $icon_type_14 ='14';
        $icon_type_15 ='15';
        $icon_type_16 ='16';
        $icon_type_17 ='17';
        $icon_type_18 ='18';
        $icon_type_19 ='19';
        $icon_type_20 ='20';
        $icon_type_21 ='21';
        $icon_type_22 ='22';
        $icon_type_23 ='23';
        $icon_type_24 ='24';
        $icon_type_25 ='25';
        $icon_type_26 ='26';
        $icon_type_27 ='27';
        $icon_type_28 ='28';
        $icon_type_29 ='29';
        $icon_type_30 ='30';
        $icon_type_31 ='31';
        $icon_type_32 ='32';
        $icon_type_33 ='33';
        $icon_type_34 ='34';
        $icon_type_35 ='35';
        $icon_type_36 ='36';
        $icon_type_37 ='37';
        $icon_type_38 ='38';
        $icon_type_39 ='39';
        $icon_type_40 ='40';
        $icon_type_41 ='41';
        $icon_type_42 ='42';
        $icon_type_43 ='43';
        $icon_type_44 ='44';
        $icon_type_45 ='45';
        $icon_type_46 ='46';
        $icon_type_47 ='47';
        $icon_type_48 ='48';
        $icon_type_49 ='49';
        $icon_type_50 ='50';
        $icon_type_51 ='51';
        $icon_type_52 ='52';
        $icon_type_53 ='53';
        $icon_type_54 ='54';
        $icon_type_55 ='55';
        $icon_type_56 ='56';
        $icon_type_56 ='56';
        $icon_type_57 ='57';
        $icon_type_58 ='58';
        $icon_type_59 ='59';
        $icon_type_60 ='60';
        $icon_type_61 ='61';
        $icon_type_62 ='62';
        $icon_type_63 ='63';
        $icon_type_64 ='64';
        $icon_type_65 ='65';
        $icon_type_66 ='66';


        //除去パターン
        $ng_pat1='/^一時/u';
        $ng_pat2='/^時々/u';
        $ng_pat3='/^大風/u';
        $ng_pat3_1='/^暴風/u';
        
        //基本は始まる文字で選定
        $pat1='/^快晴/u';
        $pat2='/^晴/u';
        $pat2_1='/^降水なし/u';
        
        
        $pat3='/^雨/u';
        $pat4='/^大雨/u';
        $pat5='/^霧雨/u';

        $pat6='/^曇/u';
        $pat7='/^薄曇/u';
        $pat8='/^煙霧/u';
        
        $pat9='/^雷/u';

        $pat10='/^雪/u';
        $pat10_1='/^大雪/u';
        $pat11='/^みぞれ/u';
        $pat12='/^あられ/u';
        $pat13='/^雹/u';
        
        $pat14='/^霧/u';

        //晴の条件
        $after_shine_pat1 = '/後晴/u';
        $after_shine_pat2 = '/後快晴/u';
        $after_shine_pat3 = '/後時々晴/u';
        $after_shine_pat4 = '/後時々快晴/u';
        $after_shine_pat5 = '/後一時晴/u';
        $after_shine_pat6 = '/後一時快晴/u';
        $after_shine_pat7 = '/後晴を伴う/u';
        $after_shine_pat8 = '/後快晴を伴う/u';
        
        $sometimes_shine_pat1 = '/時々晴/u';
        $sometimes_shine_pat2 = '/時々快晴/u';
        $sometimes_shine_pat3 = '/一時晴/u';
        $sometimes_shine_pat4 = '/一時快晴/u';
        $sometimes_shine_pat5 = '/晴を伴う/u';
        $sometimes_shine_pat6 = '/快晴を伴う/u';

        //雨の条件
        $after_rain_pat1 = '/後雨/u';
        $after_rain_pat2 = '/後大雨/u';
        $after_rain_pat3 = '/後霧雨/u';
        $after_rain_pat4 = '/後時々雨/u';
        $after_rain_pat5 = '/後時々大雨/u';
        $after_rain_pat6 = '/後時々霧雨/u';
        $after_rain_pat7 = '/後一時雨/u';
        $after_rain_pat8 = '/後一時大雨/u';
        $after_rain_pat9 = '/後一時霧雨/u';
        $after_rain_pat10 = '/後雨を伴う/u';
        $after_rain_pat11 = '/後大雨を伴う/u';
        $after_rain_pat12 = '/後霧雨を伴う/u';
        
        $sometimes_rain_pat1 = '/時々雨/u';
        $sometimes_rain_pat2 = '/時々大雨/u';
        $sometimes_rain_pat3 = '/時々霧雨/u';
        $sometimes_rain_pat4 = '/一時雨/u';
        $sometimes_rain_pat5 = '/一時大雨/u';
        $sometimes_rain_pat6 = '/一時霧雨/u';
        $sometimes_rain_pat7 = '/雨を伴う/u';
        $sometimes_rain_pat8 = '/大雨を伴う/u';
        $sometimes_rain_pat9 = '/霧雨を伴う/u';
        
        //曇の条件
        $after_cloud_pat1 = '/後曇/u';
        $after_cloud_pat2 = '/後薄曇/u';
        $after_cloud_pat3 = '/後煙霧/u';
        $after_cloud_pat4 = '/後時々曇/u';
        $after_cloud_pat5 = '/後時々薄曇/u';
        $after_cloud_pat6 = '/後時々煙霧/u';
        $after_cloud_pat7 = '/後一時曇/u';
        $after_cloud_pat8 = '/後一時薄曇/u';
        $after_cloud_pat9 = '/後一時煙霧/u';
        $after_cloud_pat10 = '/後曇を伴う/u';
        $after_cloud_pat11 = '/後薄曇を伴う/u';
        $after_cloud_pat12 = '/後煙霧を伴う/u';
        
        $sometimes_cloud_pat1 = '/時々曇/u';
        $sometimes_cloud_pat2 = '/時々薄曇/u';
        $sometimes_cloud_pat3 = '/時々煙霧/u';
        $sometimes_cloud_pat4 = '/一時曇/u';
        $sometimes_cloud_pat5 = '/一時薄曇/u';
        $sometimes_cloud_pat6 = '/一時煙霧/u';
        $sometimes_cloud_pat7 = '/曇を伴う/u';
        $sometimes_cloud_pat8 = '/薄曇を伴う/u';
        $sometimes_cloud_pat9 = '/煙霧を伴う/u';

        //雷の条件
        $after_thunder_pat1 = '/後雷/u';
        $after_thunder_pat2 = '/後時々雷/u';
        $after_thunder_pat3 = '/後一時雷/u';
        $after_thunder_pat4 = '/後雷を伴う/u';
        
        $sometimes_thunder_pat1 = '/時々雷/u';
        $sometimes_thunder_pat2 = '/一時雷/u';
        $sometimes_thunder_pat3 = '/雷を伴う/u';

        //雪の条件
        $after_snow_pat1 = '/後雪/u';
        $after_snow_pat2 = '/後大雪/u';
        $after_snow_pat3 = '/後みぞれ/u';
        $after_snow_pat4 = '/後あられ/u';
        $after_snow_pat5 = '/後雹/u';
        
        $after_snow_pat6 = '/後時々雪/u';
        $after_snow_pat7 = '/後時々大雪/u';
        $after_snow_pat8 = '/後時々みぞれ/u';
        $after_snow_pat9 = '/後時々あられ/u';
        $after_snow_pat10 = '/後時々雹/u';
        
        $after_snow_pat11 = '/後一時雪/u';
        $after_snow_pat12 = '/後一時大雪/u';
        $after_snow_pat13 = '/後一時みぞれ/u';
        $after_snow_pat14 = '/後一時あられ/u';
        $after_snow_pat15 = '/後一時雹/u';
        
        $after_snow_pat16 = '/後雪を伴う/u';
        $after_snow_pat17 = '/後大雪を伴う/u';
        $after_snow_pat18 = '/後みぞれを伴う/u';
        $after_snow_pat19 = '/後あられを伴う/u';
        $after_snow_pat20 = '/後雹を伴う/u';

        $sometimes_snow_pat1 = '/時々雪/u';
        $sometimes_snow_pat2 = '/時々大雪/u';
        $sometimes_snow_pat3 = '/時々みぞれ/u';
        $sometimes_snow_pat4 = '/時々あられ/u';
        $sometimes_snow_pat5 = '/時々雹/u';
        
        $sometimes_snow_pat6 = '/一時雪/u';
        $sometimes_snow_pat7 = '/一時大雪/u';
        $sometimes_snow_pat8 = '/一時みぞれ/u';
        $sometimes_snow_pat9 = '/一時あられ/u';
        $sometimes_snow_pat10 = '/一時雹/u';
        
        $sometimes_snow_pat11 = '/雪を伴う/u';
        $sometimes_snow_pat12 = '/大雪を伴う/u';
        $sometimes_snow_pat13 = '/みぞれを伴う/u';
        $sometimes_snow_pat14 = '/あられを伴う/u';
        $sometimes_snow_pat15 = '/雹を伴う/u';

        //霧の条件
        $after_mist_pat1 = '/後霧/u';
        $after_mist_pat2 = '/後時々霧/u';
        $after_mist_pat3 = '/後一時霧/u';
        $after_mist_pat4 = '/後霧を伴う/u';
        
        $sometimes_mist_pat1 = '/時々霧/u';
        $sometimes_mist_pat2 = '/一時霧/u';
        $sometimes_mist_pat3 = '/霧を伴う/u';

        //ng確認
        if(preg_match($ng_pat1, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));
        if(preg_match($ng_pat2, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));
        if(preg_match($ng_pat3, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));
        if(preg_match($ng_pat3_1, $weather_string)) $weather_string = mb_substr($weather_string,2,strlen($weather_string));

        //晴グループ
        if(preg_match($pat1, $weather_string) || preg_match($pat2, $weather_string) || preg_match($pat2_1, $weather_string)){
            //後グループ
            if( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_32;//晴後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_33;//晴後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_35;//晴後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_34;//晴後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_36;//晴後霧
            //時々グループ
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_07;//晴時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_08;//晴時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_10;//晴時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_09;//晴時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_11;//晴時々霧
            }
            return $icon_type_01;//晴
        }

        //雨グループ
        if(preg_match($pat3, $weather_string) || preg_match($pat4, $weather_string) || preg_match($pat5, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_37;//雨後晴
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_38;//雨後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_40;//雨後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_39;//雨後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_41;//雨後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_12;//雨時々晴
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_13;//雨時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_15;//雨時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_14;//雨時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_16;//雨時々霧
            }
            return $icon_type_02;//雨
        }

        //曇グループ
        if(preg_match($pat6, $weather_string) || preg_match($pat7, $weather_string) || preg_match($pat8, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_42;//曇後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_43;//曇後雨
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_45;//曇後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_44;//曇後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_46;//曇後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_17;//曇時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_18;//曇時々雨
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_20;//曇時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_19;//曇時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_21;//曇時々霧
            }
            return $icon_type_03;//曇
        }

        //雷グループ
        if(preg_match($pat9, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_52;//雷後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_53;//雷後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_55;//雷後曇
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_54;//雷後雪
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_56;//雷後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_27;//雷時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_28;//雷時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_30;//雷時々曇
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_29;//雷時々雪
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_31;//雷時々霧
            }
            return $icon_type_04;//雷
        }

        //雪グループ
        if(preg_match($pat10, $weather_string) || preg_match($pat10_1, $weather_string) || preg_match($pat11, $weather_string) || preg_match($pat12, $weather_string) || preg_match($pat13, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_47;//雪後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_48;//雪後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_50;//雪後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_49;//雪後雷
            }elseif( preg_match($after_mist_pat1, $weather_string) ||  preg_match($after_mist_pat2, $weather_string) || preg_match($after_mist_pat3, $weather_string) || preg_match($after_mist_pat4, $weather_string) ){
                return $icon_type_51;//雪後霧
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_22;//雪時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_23;//雪時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_25;//雪時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_24;//雪時々雷
            }elseif( preg_match($sometimes_mist_pat1, $weather_string) ||  preg_match($sometimes_mist_pat2, $weather_string) || preg_match($sometimes_mist_pat3, $weather_string) ){
                return $icon_type_26;//雪時々霧
            }
            return $icon_type_05;//雪
        }

        //霧グループ
        if(preg_match($pat14, $weather_string)){
            //後グループ
            if( preg_match($after_shine_pat1, $weather_string) ||  preg_match($after_shine_pat2, $weather_string) || preg_match($after_shine_pat3, $weather_string) || preg_match($after_shine_pat4, $weather_string) || preg_match($after_shine_pat5, $weather_string) || preg_match($after_shine_pat6, $weather_string) || preg_match($after_shine_pat7, $weather_string) || preg_match($after_shine_pat8, $weather_string) ){
                return $icon_type_62;//霧後晴
            }elseif( preg_match($after_rain_pat1, $weather_string) ||  preg_match($after_rain_pat2, $weather_string) || preg_match($after_rain_pat3, $weather_string) || preg_match($after_rain_pat4, $weather_string) || preg_match($after_rain_pat5, $weather_string) || preg_match($after_rain_pat6, $weather_string) || preg_match($after_rain_pat7, $weather_string) || preg_match($after_rain_pat8, $weather_string) || preg_match($after_rain_pat9, $weather_string) || preg_match($after_rain_pat10, $weather_string) || preg_match($after_rain_pat11, $weather_string) || preg_match($after_rain_pat12, $weather_string) ){
                return $icon_type_63;//霧後雨
            }elseif( preg_match($after_cloud_pat1, $weather_string) ||  preg_match($after_cloud_pat2, $weather_string) || preg_match($after_cloud_pat3, $weather_string) || preg_match($after_cloud_pat4, $weather_string) || preg_match($after_cloud_pat5, $weather_string) || preg_match($after_cloud_pat6, $weather_string) || preg_match($after_cloud_pat7, $weather_string) || preg_match($after_cloud_pat8, $weather_string) || preg_match($after_cloud_pat9, $weather_string) || preg_match($after_cloud_pat10, $weather_string) || preg_match($after_cloud_pat11, $weather_string) || preg_match($after_cloud_pat12, $weather_string) ){
                return $icon_type_64;//霧後曇
            }elseif( preg_match($after_thunder_pat1, $weather_string) ||  preg_match($after_thunder_pat2, $weather_string) || preg_match($after_thunder_pat3, $weather_string) || preg_match($after_thunder_pat4, $weather_string) ){
                return $icon_type_66;//霧後雷
            }elseif( preg_match($after_snow_pat1, $weather_string) ||  preg_match($after_snow_pat2, $weather_string) || preg_match($after_snow_pat3, $weather_string) || preg_match($after_snow_pat4, $weather_string) || preg_match($after_snow_pat5, $weather_string) || preg_match($after_snow_pat6, $weather_string) || preg_match($after_snow_pat7, $weather_string) || preg_match($after_snow_pat8, $weather_string) || preg_match($after_snow_pat9, $weather_string) || preg_match($after_snow_pat10, $weather_string) || preg_match($after_snow_pat11, $weather_string) || preg_match($after_snow_pat12, $weather_string) || preg_match($after_snow_pat13, $weather_string) || preg_match($after_snow_pat14, $weather_string) || preg_match($after_snow_pat15, $weather_string) || preg_match($after_snow_pat16, $weather_string) || preg_match($after_snow_pat17, $weather_string) || preg_match($after_snow_pat18, $weather_string) || preg_match($after_snow_pat19, $weather_string) || preg_match($after_snow_pat20, $weather_string) ){
                return $icon_type_65;//霧後雪
            //時々グループ
            }elseif( preg_match($sometimes_shine_pat1, $weather_string) ||  preg_match($sometimes_shine_pat2, $weather_string) || preg_match($sometimes_shine_pat3, $weather_string) || preg_match($sometimes_shine_pat4, $weather_string) || preg_match($sometimes_shine_pat5, $weather_string) || preg_match($sometimes_shine_pat6, $weather_string) ){
                return $icon_type_57;//霧時々晴
            }elseif( preg_match($sometimes_rain_pat1, $weather_string) ||  preg_match($sometimes_rain_pat2, $weather_string) || preg_match($sometimes_rain_pat3, $weather_string) || preg_match($sometimes_rain_pat4, $weather_string) || preg_match($sometimes_rain_pat5, $weather_string) || preg_match($sometimes_rain_pat6, $weather_string) || preg_match($sometimes_rain_pat7, $weather_string) || preg_match($sometimes_rain_pat8, $weather_string) || preg_match($sometimes_rain_pat9, $weather_string) ){
                return $icon_type_58;//霧時々雨
            }elseif( preg_match($sometimes_cloud_pat1, $weather_string) ||  preg_match($sometimes_cloud_pat2, $weather_string) || preg_match($sometimes_cloud_pat3, $weather_string) || preg_match($sometimes_cloud_pat4, $weather_string) || preg_match($sometimes_cloud_pat5, $weather_string) || preg_match($sometimes_cloud_pat6, $weather_string) || preg_match($sometimes_cloud_pat7, $weather_string) || preg_match($sometimes_cloud_pat8, $weather_string) || preg_match($sometimes_cloud_pat9, $weather_string) ){
                return $icon_type_60;//霧時々曇
            }elseif( preg_match($sometimes_thunder_pat1, $weather_string) ||  preg_match($sometimes_thunder_pat2, $weather_string) || preg_match($sometimes_thunder_pat3, $weather_string) ){
                return $icon_type_59;//霧時々雷
            }elseif( preg_match($sometimes_snow_pat1, $weather_string) ||  preg_match($sometimes_snow_pat2, $weather_string) || preg_match($sometimes_snow_pat3, $weather_string) || preg_match($sometimes_snow_pat4, $weather_string) || preg_match($sometimes_snow_pat5, $weather_string) || preg_match($sometimes_snow_pat6, $weather_string) || preg_match($sometimes_snow_pat7, $weather_string) || preg_match($sometimes_snow_pat8, $weather_string) || preg_match($sometimes_snow_pat9, $weather_string) || preg_match($sometimes_snow_pat10, $weather_string) || preg_match($sometimes_snow_pat11, $weather_string) || preg_match($sometimes_snow_pat12, $weather_string) || preg_match($sometimes_snow_pat13, $weather_string) || preg_match($sometimes_snow_pat14, $weather_string) || preg_match($sometimes_snow_pat15, $weather_string) ){
                return $icon_type_61;//霧時々
            }
            return $icon_type_06;//霧
        }
        return FALSE;
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */