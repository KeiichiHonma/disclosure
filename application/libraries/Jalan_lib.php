<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Jalan_lib
{
    public  $cache_life            = 1440;                         // Cache lifetime
    public  $cache_dir             = 'cache/';     // Cache directory
    public  $write_cache_flag     = FALSE;                     // Flag to write to cache
    private $start_date;
    public  $hotel_count = 20;
    public  $stock_count = 20;
    function __construct()
    {
        $this->ci =& get_instance();
        $this->start_date = date("Ymd",strtotime("+30 day"));
    }

    function makeSpringHotelsByOAreaId(&$data,$spring_id,$jalan_o_area){
        //o_areaで取得
        $o_area_hotels = $this->getHotelsByOAreaId($jalan_o_area);
        if(!empty($o_area_hotels)){
            $data['o_area_hotels'][$spring_id] = array_chunk($o_area_hotels,3);
        }
    }

    function makeSpringsHotelsByAreaId(&$data,$area_id){
        //温泉
        $data['springs'] = $this->ci->Spring_model->getSpringsOrderTodoufukenIdByAreaId($area_id,TRUE);

        //温泉地の数で挙動を変える。箱根エリアの温泉等
        if(!empty($data['springs'])){
            if(count($data['springs']) == 1){
                //指定日、s_areaで空いているプランを表示
                $index = key($data['springs']);
                $data['spring'] = $data['springs'][$index];
                $s_area_hotelss = $this->getHotelsBySAreaId($data['springs'][$index]->jalan_s_area);
                if(!empty($s_area_hotelss)){
                    $data['s_area_hotels'] = array_chunk($s_area_hotelss,3);
                }
            }else{
                //対応の温泉が2つ以上ある。ランダムに2つ取り出す
                $data['stop_line'] = 1;//lineを1つで停止
                $rand = array_rand($data['springs'],2);
                //o_areaで取得
                $o_area_hotels = $this->getHotelsByOAreaId($data['springs'][$rand[0]]->jalan_o_area);
                if(!empty($o_area_hotels)){
                    $data['o_area_hotels'][$data['springs'][$rand[0]]->id] = array_chunk($o_area_hotels,3);
                }
                $o_area_hotels = $this->getHotelsByOAreaId($data['springs'][$rand[1]]->jalan_o_area);
                if(!empty($o_area_hotels)){
                    $data['o_area_hotels'][$data['springs'][$rand[1]]->id] = array_chunk($o_area_hotels,3);
                }
/*
                foreach ($data['springs'] as $spring){
                    //o_areaで取得
                    $o_area_hotels = $this->getHotelsByOAreaId($spring->jalan_o_area);
                    if(!empty($o_area_hotels)){
                        $data['o_area_hotels'][$spring->id] = array_chunk($o_area_hotels,3);
                    }
                }
*/
            }
        }
    }
    
    //ホテルの日付指定しないでプランを生成
    function makeHotelPlansByJalan_h_idByShineSequence(&$data,$jalan_h_id,$shine_sequence){
        $plans = $this->getStocksByHotelIdByShineSequence($jalan_h_id,$shine_sequence);
        if(!empty($plans)){
            $data['plans'] = array_chunk($plans,3);
        }
    }

    function makeSpringsPlansByAreaIdByDate(&$data,$area_id,$date){
        //温泉
        $data['springs'] = $this->ci->Spring_model->getSpringsOrderTodoufukenIdByAreaId($area_id,TRUE);

        /*
        jalan data
        */
        //温泉地の数で挙動を変える。箱根エリアの温泉等
        $sequence = 2;
        $data['plans'] = array();
        $data['o_area_plans'] = array();
        $jalan_date = str_replace('-','',$date);
        if(!empty($data['springs'])){
            if(count($data['springs']) == 1){
                //指定日、s_areaで空いているプランを表示
                $index = key($data['springs']);
                $data['spring'] = $data['springs'][$index];
                $plans = $this->getStocksBySAreaIdBySequenceByDate($data['springs'][$index]->jalan_s_area,$sequence,$jalan_date);
                if(!empty($plans)){
                    $data['plans'] = array_chunk($plans,3);
                }
            }else{
                //対応の温泉が2つ以上あるため、指定日、o_areaで空いているプランを表示
                $data['stop_line'] = 1;//lineを1つで停止
                $rand = array_rand($data['springs'],2);
                //o_areaで取得
                $o_area_plans = $this->getStocksByOAreaIdBySequenceByDate($data['springs'][$rand[0]]->jalan_o_area,$sequence,$jalan_date);
                if(!empty($o_area_plans)){
                    $data['o_area_plans'][$data['springs'][$rand[0]]->id] = array_chunk($o_area_plans,3);
                }
                
                $o_area_plans = $this->getStocksByOAreaIdBySequenceByDate($data['springs'][$rand[1]]->jalan_o_area,$sequence,$jalan_date);
                if(!empty($o_area_plans)){
                    $data['o_area_plans'][$data['springs'][$rand[1]]->id] = array_chunk($o_area_plans,3);
                }

/*
                foreach ($data['springs'] as $spring){
                    //o_areaで取得
                    $o_area_plans = $this->getStocksByOAreaIdBySequenceByDate($spring->jalan_o_area,$sequence,$jalan_date);
                    if(!empty($o_area_plans)){
                        //$data['o_area_plans'][$spring->id] = array_chunk($o_area_plans,3);
                        $data['o_area_plans'][$spring->id] = $o_area_plans;
                    }
                }
*/
            }
        }
    }
    
    //ホテル指定晴れ日付空室確認
    function getStocksByHotelIdBySequenceByDate($jalan_h_id,$shine_sequence,$stay_date){
        $this->cache_dir = 'cache/stocks/';
        $stay_count = $shine_sequence - 1;//2連続晴れの場合は1泊だけということ
        $url='http://jws.jalan.net/APIAdvance/StockSearch/V1/?key='.$this->ci->config->item('jalan_key').'&h_id='.$jalan_h_id.'&stay_date='.$stay_date.'&stay_count='.$stay_count.'&order=4&count='.$this->stock_count.'&picts=3&pict_size=4&adult_num=2';
        return $this->_parseStockXML($this->_getXML($url));
    }

    //空室確認。日付は関係なく、プランがあるかどうか
    function getStocksByHotelIdByShineSequence($jalan_h_id,$shine_sequence){
        $this->cache_dir = 'cache/stocks/';
        $stay_count = $shine_sequence - 1;//2連続晴れの場合は1泊だけということ
        $url='http://jws.jalan.net/APIAdvance/StockSearch/V1/?key='.$this->ci->config->item('jalan_key').'&h_id='.$jalan_h_id.'&stay_count='.$stay_count.'&order=4&count='.$this->stock_count.'&picts=3&pict_size=4&adult_num=2';
        return $this->_parseStockXML($this->_getXML($url));
    }

    //ホテルID指定晴れ日付空室確認
    function getStocksByHotelIdByShineSequenceByDate($jalan_h_id,$shine_sequence,$stay_date){
        $this->cache_dir = 'cache/stocks/';
        $stay_count = $shine_sequence - 1;//2連続晴れの場合は1泊だけということ
        $stock_count = 5;
        $url='http://jws.jalan.net/APIAdvance/StockSearch/V1/?key='.$this->ci->config->item('jalan_key').'&h_id='.$jalan_h_id.'&stay_date='.$stay_date.'&stay_count='.$stay_count.'&order=4&count='.$stock_count.'&picts=3&pict_size=4&adult_num=2';
        return $this->_parseStockXML($this->_getXML($url));
    }

    //a_area指定晴れ日付空室確認
    function getStocksBySAreaIdBySequenceByDate($jalan_s_area,$sequence,$stay_date){
        $this->cache_dir = 'cache/stocks/';
        $stay_count = $sequence - 1;//2連続晴れの場合は1泊だけということ
        $url='http://jws.jalan.net/APIAdvance/StockSearch/V1/?key='.$this->ci->config->item('jalan_key').'&s_area='.$jalan_s_area.'&stay_date='.$stay_date.'&stay_count='.$stay_count.'&order=4&count='.$this->stock_count.'&picts=3&pict_size=4&adult_num=2';
        return $this->_parseStockXML($this->_getXML($url));
    }

    //o_area指定晴れ日付空室確認
    function getStocksByOAreaIdBySequenceByDate($jalan_o_area,$shine_sequence,$stay_date){
        $this->cache_dir = 'cache/stocks/';
        $stay_count = $shine_sequence - 1;//2連続晴れの場合は1泊だけということ
        $url='http://jws.jalan.net/APIAdvance/StockSearch/V1/?key='.$this->ci->config->item('jalan_key').'&o_area_id='.$jalan_o_area.'&stay_date='.$stay_date.'&stay_count='.$stay_count.'&order=4&count='.$this->stock_count.'&picts=3&pict_size=4&adult_num=2';
        return $this->_parseStockXML($this->_getXML($url));
    }

    function getHotelByHotelId($jalan_h_id){
        $this->cache_dir = 'cache/hotels/';
        $url='http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key='.$this->ci->config->item('jalan_key').'&picts=5&pict_size=4&h_id='.$jalan_h_id;
        return $this->_parseHotelXML($this->_getXML($url),FALSE);
    }
    
    //o_areaでホテル検索
    function getHotelsByOAreaId($jalan_o_area){
        $this->cache_dir = 'cache/hotels/';
        $url = 'http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key='.$this->ci->config->item('jalan_key').'&o_area_id='.$jalan_o_area.'&order=4&count='.$this->hotel_count.'&pict_size=4';
        return $this->_parseHotelXML($this->_getXML($url));
    }
    
    //s_areaでホテル検索
    function getHotelsBySAreaId($jalan_s_area){
        $this->cache_dir = 'cache/hotels/';
        $url = 'http://jws.jalan.net/APIAdvance/HotelSearch/V1/?key='.$this->ci->config->item('jalan_key').'&s_area='.$jalan_s_area.'&order=4&count='.$this->hotel_count.'&pict_size=4';
        return $this->_parseHotelXML($this->_getXML($url));
    }

    private function _getXML($url){
        // Are we caching?
        if ($this->cache_life != 0)
        {
            $filename = APPPATH.$this->cache_dir.md5($url).'.xml';
            // Is there a cache file ?
            if (file_exists($filename))
            {
                // Has it expired?
                $timedif = (time() - filemtime($filename));

                if ($timedif < ( $this->cache_life * 60))
                {
                    //$xml = file_get_contents($filename);
                    $xml = @simplexml_load_file($filename);
                }
                else
                {
                    // So raise the falg
                    $this->write_cache_flag = true;
                }
            }
            else
            {
                // Raise the flag to write the cache
                $this->write_cache_flag = true;
            }
        }

        // Parse the document
        if (!isset($xml))
        {
            $xml_write = @file_get_contents($url);
            if($xml_write !== FALSE){
                // Do we need to write the cache file?
                if ($this->write_cache_flag)
                {
                    if (!$fp = @fopen($filename, 'w+b'))
                    {
                        log_message('error', "Unable to write cache file: ".$filename);
                        return;
                    }
                    flock($fp, LOCK_EX);
                    fwrite($fp, $xml_write);
                    flock($fp, LOCK_UN);
                    fclose($fp);
                }
                $xml = @simplexml_load_file($filename);
                return $xml;
            }
        }else{
            return $xml;
        }
        return FALSE;
    }
    private function _parseHotelXML($xml_hotels,$is_multi = TRUE){
        if(!$xml_hotels) return FALSE;
        $hotelsData = array();
        $i = 0;
        foreach ($xml_hotels->Hotel as $xml_hotel){
            $AccessInformation = array();
            foreach ($xml_hotel as $item){
                if($item->getName() != 'Area' && $item->getName() != 'WifiHikariStation'){
                    if($item->getName() == 'AccessInformation'){
                        $AccessInformation[] = strval($item->attributes()->name) . ':' . strval($item);
                        if($is_multi){
                            $hotelsData[$i][$item->getName()] = implode("\n",$AccessInformation);
                        }else{
                            $hotelsData[$item->getName()] = implode("\n",$AccessInformation);
                        }
                    }elseif ($item->getName() == 'PictureURL'){
                        if(!$is_multi){
                            $pic_i = 0;
                            foreach ($xml_hotel->PictureURL as $picture_url){
                                $hotelsData['Picture'][$pic_i][$item->getName()] = strval($picture_url);
                                $pic_i++;
                            }
                        }else{
                            $hotelsData[$i][$item->getName()] = strval($item);
                        }
                    }elseif ($item->getName() == 'PictureCaption'){
                        if(!$is_multi){
                            $pic_i = 0;
                            foreach ($xml_hotel->PictureCaption as $picture_caption){
                                $hotelsData['Picture'][$pic_i][$item->getName()] = strval($picture_caption);
                                $pic_i++;
                            }
                        }else{
                            $hotelsData[$i][$item->getName()] = strval($item);
                        }
                    }else{
                        if($is_multi){
                            $hotelsData[$i][$item->getName()] = strval($item);
                        }else{
                            $hotelsData[$item->getName()] = strval($item);
                        }
                    }
                }
            }
            $i++;
        }
        return $hotelsData;
    }
    
    private function _parseStockXML($xml_stocks){
        if(!$xml_stocks) return FALSE;
        $stockData = array();
        $plan_name = '';
        $before_plan_name = '';
        $ymd = '';
        $before_ymd = '';
        $i = 0;
        foreach ($xml_stocks->Plan as $xml_stock){

            $stock_tmp_data = array();
            $stock_number = isset($xml_stock->Stay->Date->Stock) ? intval($xml_stock->Stay->Date->Stock) : TRUE;
            
            if($stock_number || ($stock_number > 0)){
                $facilities = array();
                
                foreach ($xml_stock as $item){
                    if($item->getName() == 'Facilities'){
                        foreach ($item as $facility){
                            $facilities[] = strval($facility);
                        }
                        $stock_tmp_data['Facilities'] = implode(',',$facilities);
                    }elseif ($item->getName() == 'PlanName'){
                        $plan_name = strval($item);
                        $stock_tmp_data['PlanName'] = $plan_name;
                    }elseif ($item->getName() == 'Stay'){
                        $stock_tmp_data['Stay']['PlanDetailURL'] = strval($item->PlanDetailURL);
                        foreach ($item->Date as $date){
                            $ymd = strval($date->attributes()->year).strval($date->attributes()->month).strval($date->attributes()->date);
                            $stock_tmp_data['Stay']['Date'][$ymd]['Rate'] = strval($date->Rate);
                            $stock_tmp_data['Stay']['Date'][$ymd]['Stock'] = intval($date->Stock);
                        }
                    }elseif ($item->getName() == 'PlanPictureURL'){
                        $pic_i = 0;
                        foreach ($xml_stock->PlanPictureURL as $plan_picture_url){
                            $stock_tmp_data['PlanPicture'][$pic_i][$item->getName()] = strval($plan_picture_url);
                            $pic_i++;
                        }
                    }elseif ($item->getName() == 'PlanPictureCaption'){
                        $pic_i = 0;
                        foreach ($xml_stock->PlanPictureCaption as $plan_picture_caption){
                            $stock_tmp_data['PlanPicture'][$pic_i][$item->getName()] = strval($plan_picture_caption);
                            $pic_i++;
                        }
                    }elseif ($item->getName() == 'Hotel'){
                        foreach ($item as $hotel){
                            $stock_tmp_data[$item->getName()][$hotel->getName()] = strval($hotel);
                        }
                    }else{
                        $stock_tmp_data[$item->getName()] = strval($item);
                    }
                }
                //プラン名が同じ、日付も同じ場合は除去。禁煙、喫煙の違い等で発生する
                if($before_plan_name != $plan_name || $before_ymd != $ymd){
                    $stockData[$i] = $stock_tmp_data;
                    $i++;
                }
                $before_plan_name = $plan_name;
                $before_ymd = $ymd;
            }
        }
        return $stockData;
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */