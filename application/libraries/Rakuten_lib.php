<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rakuten_lib
{
    public  $cache_life            = 1440;                         // Cache lifetime
    public  $cache_dir             = 'cache/';     // Cache directory
    public  $write_cache_flag     = FALSE;                     // Flag to write to cache
    private $start_date;
    function __construct()
    {
        $this->ci =& get_instance();
        $this->start_date = date("Ymd",strtotime("+30 day"));
    }

    function getCoursesByLatitudeByLongitude($latitude,$longitude){
        $this->cache_dir = 'cache/courses/';
        $url = 'http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraGolfCourseSearch&version=2010-06-30&sort=evaluation&latitude='.$latitude.'&longitude='.$longitude.'&searchRadius=150';
        return $this->_parseCoursesXML($this->_getXML($url));
    }

    /*
    楽天のエリアとは都道府県のこと
    */
    function getCoursesByRakutenAreaCode($rakuten_AreaCode){
        $this->cache_dir = 'cache/courses/';
        $url = 'http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraGolfCourseSearch&version=2010-06-30&areaCode='.$rakuten_AreaCode.'&sort=evaluation';
        return $this->_parseCoursesXML($this->_getXML($url));
    }

    function getCourseByRakutenGolfCourseId($rakuten_golfCourseId){
        $this->cache_dir = 'cache/courses/';
        $url='http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraGolfCourseDetail&version=2010-06-30&golfCourseId='.$rakuten_golfCourseId;
        return $this->_parseCourseXML($this->_getXML($url));
    }

    function getPlansByRakutenGolfCourseIdBydate($rakuten_golfCourseId,$date){
        $this->cache_dir = 'cache/plans/';
        $url='http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraPlanSearch&version=2012-12-10&sort=evaluation&golfCourseId='.$rakuten_golfCourseId.'&playDate='.$date;
        return $this->_parsePlansXML($this->_getXML($url));
    }

    function getPlansByRakutenAreaCodeBydate($rakuten_AreaCode,$date,$hits = 4){
        $this->cache_dir = 'cache/plans/';
        $url='http://api.rakuten.co.jp/rws/3.0/rest?developerId='.$this->ci->config->item('rakuten_key').'&operation=GoraPlanSearch&version=2012-12-10&sort=evaluation&areaCode='.$rakuten_AreaCode.'&playDate='.$date.'&hits='.$hits;
        return $this->_parsePlansXML($this->_getXML($url));
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
            $xml_write = file_get_contents($url);
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
        }

        return $xml;
    }

    private function _parseCourseXML($xml_course){
        $nameSpaces = $xml_course->getNamespaces(true);
        $xml_course->registerXPathNamespace("goraGolfCourseDetail", $nameSpaces['goraGolfCourseDetail']);
        $xpath_xml_course = $xml_course->xpath("//goraGolfCourseDetail:GoraGolfCourseDetail");
        return (array) $xpath_xml_course[0]->Item;
    }

    private function _parseCoursesXML($xml_courses){
        $nameSpaces = $xml_courses->getNamespaces(true);
        $xml_courses->registerXPathNamespace("goraGolfCourseSearch", $nameSpaces['goraGolfCourseSearch']);
        $xpath_xml_courses = $xml_courses->xpath("//goraGolfCourseSearch:GoraGolfCourseSearch");
        $coursesData = array();
        $i = 0;
        foreach ($xpath_xml_courses[0]->Items->children()->Item as $course){
            foreach ($course as $value){
                $coursesData[$i][$value->getName()] = strval($value);
            }
            $i++;
        }
        return $coursesData;
    }
    
    private function _parsePlansXML($xml_plans){
        $nameSpaces = $xml_plans->getNamespaces(true);
        //存在チェック
        $xml_plans->registerXPathNamespace("header", $nameSpaces['header']);
        $xpath_xml_plans_header = $xml_plans->xpath("//header:Header");
        if(strval($xpath_xml_plans_header[0]->Status) == 'NotFound') return array();

        $xml_plans->registerXPathNamespace("goraPlanSearch", $nameSpaces['goraPlanSearch']);
        $xpath_xml_plans = $xml_plans->xpath("//goraPlanSearch:GoraPlanSearch");
        $plansData = array();
        $i = 0;
        foreach ($xpath_xml_plans[0]->Items->children()->Item as $item){
            foreach ($item as $value){
                if($value->getName() == 'planInfo'){
                    //$plan_info = (array) $value->children()->plan;
                    //foreach ($value->children()->plan as $plan){
                    foreach ($value->children()->plan as $plan){
                        foreach ($plan as $planinfo){
                            if($planinfo->getName() == 'callInfo'){
                                $plansData[$i][$value->getName()][$planinfo->getName()] = (array) $planinfo;
                            }else{
                                $plansData[$i][$value->getName()][$planinfo->getName()] = strval($planinfo);
                            }
                        }
                    }
                }else{
                    $plansData[$i][$value->getName()] = strval($value);
                }
                
            }
            $i++;
        }
        return $plansData;
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */