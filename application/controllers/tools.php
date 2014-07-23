<?php
class Tools extends CI_Controller {

    var $CI;
    var $ATTR = "_attributes";
    var $NS = "_namespace";
    var $VAL = "_value";
    var $log_echo = FALSE;
    var $is_parse = TRUE;
    var $xbrls_informations;
    var $tmp_xbrl_files;
    var $xbrl_files;
    
    function __construct(){
        parent::__construct();
        $this->CI =& get_instance();
        if ( ! $this->input->is_cli_request() )     {
            //die('Permission denied.');
        }
        $this->load->library('tank_auth');
        //connect database
        $this->load->database();
        $this->load->model('Item_model');
        $this->load->model('Category_model');
        $this->load->model('Security_model');
        $this->load->model('Presenter_model');
        $this->load->model('Xbrl_model');
        $this->categories = $this->Category_model->getAllcategories();
        
        $this->archiver = new ZipArchive();
        $this->extractFiles = array();
    }

    public function analyze()
    {
        //zip
        $path = '/usr/local/apache2/htdocs/disclosure/tmp/';
        $move_path = '/usr/local/apache2/htdocs/disclosure/xbrls/'.date('Ymd',time());
        $rename_paths = array();
        @mkdir($move_path);
        @chown($move_path, 'apache');
        @chmod($move_path,0770);

        $list = scandir($path);
        foreach($list as $file){
            $file_path = $path . $file;
            if($file == '.' || $file == '..'){
                continue;
            } else if (is_file($file_path)){
                $pathinfo = pathinfo($file_path);
                //if(!is_dir($path . $pathinfo['filename']) && $pathinfo['extension'] == 'zip'){
                if($pathinfo['extension'] == 'zip'){
                    
                    // ZIPファイルをオープン
                    $res = $this->archiver->open($file_path);
                     
                    // zipファイルのオープンに成功した場合
                    if ($res === true) {
                        //@mkdir($path . $pathinfo['filename']);
                        // 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
                        //$this->archiver->extractTo($path . $pathinfo['filename']);
                        $this->archiver->extractTo($path);
                     
                        // ZIPファイルをクローズ
                        $this->archiver->close();
                        
                        //renameを記録
                        $rename_paths[] = array('zip_path'=>$file_path,'tmp_path'=>$path . $pathinfo['filename'] , 'move_path'=>$move_path . '/' . $pathinfo['filename']);
                    }else{
var_dump('unzip error');
die();
                    }
                }
            }
        }

        $insert_data = array();
        $csv_datas = array();
        require_once('application/libraries/simple_html_dom.php');
        $this->_list_files($path,$move_path);
        
        if(!empty($this->xbrls_informations)){
            //挿入順をコントロール
            foreach ($this->xbrls_informations as $unzip_dir_name => $xbrls_information){
                foreach ($xbrls_information as $xbrl_dir_id => $value){
                    $this->xbrl_files[$unzip_dir_name][$xbrl_dir_id] = $this->tmp_xbrl_files[$unzip_dir_name][$xbrl_dir_id];
                }
            }

            //先に企業名をDBに挿入 presenter_id が必要なため
            foreach ($this->xbrls_informations as $unzip_dir_name => $xbrls_information){
                foreach ($xbrls_information as $xbrl_dir_id => $value){
                    $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'] = str_replace('株式会社','',$value['presenter_name']);
                    $presenter = $this->Presenter_model->getPresenterByName($value['presenter_name']);
                    if(empty($presenter)){
                        //企業名は重複を防ぐため、edinetcodeで
                        $edinet_code = $value['edinet_code'];
                        $insert_data['presenter'][$edinet_code]['edinet_code'] = $edinet_code;
                        $insert_data['presenter'][$edinet_code]['name'] = $value['presenter_name'];
                        $security = $this->Security_model->getSecurityByName($value['presenter_name']);
                        $insert_data['presenter'][$edinet_code]['securities_code'] = empty($security) ? '' : $security->id;
                    }
                }
            }
        }

        
        if(!empty($insert_data['presenter']))$this->db->insert_batch('presenters', $insert_data['presenter']);

        //実際にファイル解析
        $csv_line = 0;
        $xbrl_count = 0;
        $created = date("Y-m-d H:i:s", time());
        foreach ($this->tmp_xbrl_files as $unzip_dir_name =>  $xbrls){
            foreach ($xbrls as $xbrl_dir_id =>  $file){
                $is_document_info = FALSE;
                $parse = $this->_parseXml($file);

                $csv_line = 0;
                //文書提出日時
                $pathinfo = pathinfo($file);
                $exp = explode('-',$pathinfo['filename']);
                $count = count($exp);
                $day = $exp[$count-1];
                $month = $exp[$count-2];
                $year = substr($exp[$count-3],-4);
                $date = $year.'-'.$month.'-'.$day;

                $insert_data['xbrl'][$xbrl_dir_id]['edinet_code'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['edinet_code'];
                $insert_data['xbrl'][$xbrl_dir_id]['presenter_id'] = 0;
                $insert_data['xbrl'][$xbrl_dir_id]['category_id'] = 0;
                $insert_data['xbrl'][$xbrl_dir_id]['manage_number'] = $xbrl_dir_id;
                $insert_data['xbrl'][$xbrl_dir_id]['xbrl_path'] = $file;
                //format
                $format_path_ex = explode('/', $file);
                $count = count($format_path_ex);
                $format_path_ex[$count-1] = $date.$this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['edinet_code'];
                $insert_data['xbrl'][$xbrl_dir_id]['format_path'] = implode('/',$format_path_ex);
                $insert_data['xbrl'][$xbrl_dir_id]['date'] = $date;
                $insert_data['xbrl'][$xbrl_dir_id]['document_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['document_name'];
                $insert_data['xbrl'][$xbrl_dir_id]['presenter_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'];
                $insert_data['xbrl'][$xbrl_dir_id]['created'] = $created;

                //文書のカテゴリチェック
                if(isset($this->categories[$this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['document_name']])){
                    $insert_data['xbrl'][$xbrl_dir_id]['category_id'] = $this->categories[$this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['document_name']]->id;
                }
                //企業チェック
                $presenter = $this->Presenter_model->getPresenterByName($this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name']);
                if(!empty($presenter)){
                    $insert_data['xbrl'][$xbrl_dir_id]['presenter_id'] = $presenter->id;
                }
                //code生成
                $code = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['edinet_code'].'_'.$date.'_'.$xbrl_dir_id.'_'.$insert_data['xbrl'][$xbrl_dir_id]['presenter_id'];
                $insert_data['xbrl'][$xbrl_dir_id]['code'] = $code;
                //codeチェック
                $check_xbrl = $this->Xbrl_model->getXbrlByCode($code);

                if($this->is_parse){
                    foreach ($parse as $xbrl){
                        foreach ($xbrl as $namespace => $children){
                            if($namespace == '_namespace'){
                            
                            }elseif($namespace == 'xbrli'){
                                /*
                                提出日の取得なのだが、階層が深いため断念、ファイル名から取得にする
                                                            //ex $index context unit 
                                                            foreach ($children['context'] as $xbrli_contexts){
                                                                foreach ($xbrli_contexts as $number => $child){
                                                                    foreach ($child as $type => $item_value){

                                                                    }
                                                                }
                                                            }
                                */
                            }elseif($namespace == 'link'){
                                
                            }else{
                                $csv_paths[$xbrl_count] = $insert_data['xbrl'][$xbrl_dir_id]['format_path'].'.csv';
                                $xls_paths[$xbrl_count] = $insert_data['xbrl'][$xbrl_dir_id]['format_path'].'.xlsx';
                                //ex $index PurchaseOfInvestmentsInAssociatedCompaniesInvCF
                                foreach ($children as $index => $items){
                                    //ex $number 項目の数分存在 前期 前前期
                                    foreach ($items as $number => $child){
                                        $element = $this->Item_model->getItemByElementName($index);
                                        if(!empty($element)){
                                            $csv_datas[$xbrl_count][$csv_line][] = $namespace;
                                            $csv_datas[$xbrl_count][$csv_line][] = $element[0]->style_tree != '' ? $element[0]->style_tree : $element[0]->detail_tree;
                                            //ex $type _attributes or _value
                                            foreach ($child as $type => $item_value){
                                                if(is_array($item_value)){
                                                    //ex $item_index contextRef unitRef decimals
                                                    foreach ($item_value as $item_index => $value){
                                                        $csv_datas[$xbrl_count][$csv_line][] = $value;
                                                    }
                                                }else{
                                                    //ex _value 実際の値はここ
                                                    $csv_datas[$xbrl_count][$csv_line][] = $item_value;
                                                }
                                            }
                                        }else{
                                            //$csv_datas[$xbrl_count][$csv_line][] = $index;
                                            //項目がない場合、無効なもの
                                            if($this->log_echo)log_message('error','none index '.$namespace.':'.$index.':'.$file);
                                        }
                                        $csv_line++;
                                    }
                                }
                                
                            }
                        }
                        $xbrl_count++;
                    }
                }
                //処理完了後に除去
                if(!empty($check_xbrl)) unset($insert_data['xbrl'][$xbrl_dir_id]);
            }
        }
        if(!empty($insert_data['xbrl'])) $this->db->insert_batch('xbrls', $insert_data['xbrl']);
        //move 
        foreach ($rename_paths as $rename_path){
            @rename($rename_path['tmp_path'],$rename_path['move_path']);
            //@unlink($rename_path['zip_path']);
        }
        $this->put_csv($csv_paths,$csv_datas);
die();
        //$this->_make_file('/usr/local/apache2/htdocs/hareco/xbrl0501.csv',$csv);
    }
    
    function _list_files($path,$move_path){
        $files = array();
        $list = scandir($path);
        foreach($list as $file){
            if($file == '.' || $file == '..'){
                continue;
            } else if (is_file($path . $file)){
                $pathinfo = pathinfo($path . $file);
                if($pathinfo['extension'] == 'xbrl'){
                    $dirs = explode('/',$path);
                    /*
                    array(10) {
                      [0]=>
                      string(0) ""
                      [1]=>
                      string(3) "usr"
                      [2]=>
                      string(5) "local"
                      [3]=>
                      string(7) "apache2"
                      [4]=>
                      string(6) "htdocs"
                      [5]=>
                      string(10) "disclosure"
                      [6]=>
                      string(5) "xbrls"
                      [7]=>
                      string(27) "Xbrl_Search_20140709_124651"
                      [8]=>
                      string(8) "S1001W9I"
                      [9]=>
                      string(0) ""
                    }
                    */
                    
                    
                    //move
                    $base_path = '';
                    $xbrl_path = '';
                    $xbrl_add_path = '';
                    foreach ($dirs as $key => $value){
                        if(!empty($value)){
                            if($key >= 7){
                                $xbrl_add_path .= '/'.$value;
                            }else{
                                $base_path .= '/'.$value;
                            }
                        }

                    }
                    $xbrl_path = $move_path.$xbrl_add_path;
                    //$files[$dirs[7]][$dirs[8]][$file] = $xbrl_path . '/' . $file;
                    //$files[$dirs[8]][$file] = $xbrl_path . '/' . $file;
                    //$this->tmp_xbrl_files[$dirs[7]][$dirs[8]][$file] = $xbrl_path . '/' . $file;
                    $this->tmp_xbrl_files[$dirs[7]][$dirs[8]] = $xbrl_path . '/' . $file;
                }elseif($pathinfo['filename'] == 'XbrlSearchDlInfo' && $pathinfo['extension'] == 'csv'){
                    $dirs = explode('/',$path);
                    //XbrlSearchDlInfo.csv
                    $this->xbrls_informations[$dirs[7]] = $this->_read_form_item_csv($path . $file);
                }
            } else if( is_dir($path . $file) ) {
                $files = array_merge($files, $this->_list_files($path . $file . '/',$move_path));
            }
        }
        return $files;
    }

    function _fputcsv($fp, $data, $toEncoding='Shift-JIS', $srcEncoding='UTF-8') {
        //require_once 'mb_str_replace.php';
        $csv = '';
        foreach ($data as $col) {
            if (is_numeric($col)) {
                $csv .= $col;
            } else {
                if(is_array($col)){
var_dump($col);
die();
                }
                $col = mb_convert_encoding($col, $toEncoding, $srcEncoding);
                //$col = mb_str_replace('"', '""', $col, $toEncoding);
                $col = str_replace('"', '""', $col);
                $csv .= '"' . $col . '"';
            }
            $csv .= ',';
        }
        fwrite($fp, $csv);
        fwrite($fp, "\r\n");
    }

    // ----------------------------------------------------------------
    // CSV出力 
    // ----------------------------------------------------------------
    function put_csv($csv_paths,$csv_datas) {
        foreach ($csv_datas as $key => $csv_data){
            // ファイル書き込み
            $fp = fopen($csv_paths[$key], 'w+');
            foreach ($csv_data as $line => $value){
                $byte = $this->_fputcsv($fp, $value);
            }
            fclose($fp);
            //@chown($csv_paths[$key], 'apache');
            //@chmod($csv_paths[$key],0770);
        }

        return $byte;
    }

    // ----------------------------------------------------------------
    // CSV入力
    // ----------------------------------------------------------------
    function _fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof = false; // Added for PHP Warning.
        while ( $eof != true ) {
        $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
        $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
        if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';

        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

        $_csv_data = $_csv_matches[1];

        for ( $_csv_i=0; $_csv_i<count($_csv_data); $_csv_i++ ) {
        $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
        $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }

    function _read_form_item_csv($csv_file,$skip = TRUE){
        $fp=@fopen($csv_file,"r");
        $line = 0;
        $csv = array();
        while ($CSVRow = @$this->_fgetcsv_reg($fp,1024)){//ファイルを一行ずつ読み込む
            //XbrlSearchDlInfo.csvは上部2行が不要
            if($skip && $line === 0){
                $skip = TRUE;
            }elseif($skip && $line === 1){
                $skip = FALSE;
            }else{
                //なぜか重複した値がある
                if(!isset($csv[$CSVRow[0]])){
                    $csv[$CSVRow[0]]['manage_number'] = mb_convert_encoding($CSVRow[0],"UTF-8","SJIS-win");//S1002HZ2
                    $csv[$CSVRow[0]]['document_name'] = mb_convert_encoding($CSVRow[1],"UTF-8","SJIS-win");//四半期報告書
                    $csv[$CSVRow[0]]['edinet_code'] = mb_convert_encoding($CSVRow[2],"UTF-8","SJIS-win");//E03086
                    $csv[$CSVRow[0]]['presenter_name'] = mb_convert_encoding($CSVRow[3],"UTF-8","SJIS-win");//株式会社カス
                }
            }
            $line++;
        }
        fclose( $fp );
        return $csv;
    }

    public function sitemap()
    {

        try {
            $file= '/usr/local/apache2/htdocs/hareco/sitemap.xml';
            $this->_get_sitemap_data('main');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_area_date.xml';
            $this->_get_sitemap_data('area_date');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_spring_date.xml';
            $this->_get_sitemap_data('spring_date');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_airport_date.xml';
            $this->_get_sitemap_data('airport_date');
            $this->_make_file($file,$this->sitemap_line);

            $file= '/usr/local/apache2/htdocs/hareco/sitemap_leisure_date.xml';
            $this->_get_sitemap_data('leisure_date');
            $this->_make_file($file,$this->sitemap_line);

            print 'Sitemap: update success';
        } catch (Exception $e) { 
            print 'Error: ' . $e->getMessage();
        }
    }

    function _make_sitemap_url($url,$lastmod = null,$changefreq = 'weekly'){
        $string = '';
        $string .= '    <url>'."\n";
        $string .= '        <loc>'.$url.'</loc>'."\n";
        if($lastmod != null) $string .= '        <lastmod>'.$lastmod.'</lastmod>'."\n";
        if($changefreq != null) $string .= '        <changefreq>'.$changefreq.'</changefreq>'."\n";
        $string .= '    </url>'."\n";
        return $string;
    }
    function _make_file($file,$data){
        umask(0);
        $file=trim($file);
        $file_dat=fopen($file,"w+");
        flock($file_dat, LOCK_EX);
        fputs($file_dat, $data);
        flock($file_dat, LOCK_UN);
        chmod($file,0666);
    }

 public function _parseXml($file="") {
  libxml_use_internal_errors(true);
  $doc = @simplexml_load_file($file, null, LIBXML_COMPACT|LIBXML_NOCDATA|LIBXML_NOBLANKS|LIBXML_NOENT);
  if(!is_object($doc)) {
   $err["status"] = "Failed loading XML.";
   foreach(libxml_get_errors() as $error) {
    $err["error"][] = $error->message;
   }
   return $err;
  }
  $ns = $data[$this->NS] = $doc->getDocNamespaces();
  if(!count($ns)) {
   $body = $this->_parseXmlLoop($doc, "", 1);
  }else {
   $ns = $this->_nameSpace($ns, 1);
   foreach($ns as $key=>$val) {
    $obj_attr = ($val) ? $doc->attributes($val) : $doc->attributes();
    if($obj_attr) {
     $data[$this->ATTR.$key] = $this->_perseAttributes($obj_attr);
    }
   }
   $root = $doc->getName();
   $body[$root] = $data;
   foreach($ns as $key=>$val) {
    if($obj_data = $this->_parseXmlLoop($doc, $val)) {
     $body[$root][$key] = $obj_data;
    }
   }
  }
  return $body;
 }
/**********************************************************
Name Space.
*********************************************************/
 private function _nameSpace($array="") {
  if(!array_key_exists("", $array) || !in_array("", $array)) {
   $array[""] = "";
  }
  return $array;
 }
/**********************************************************
Perse Xml Attributes.
*********************************************************/
 private function _perseAttributes($attributes="") {
  foreach($attributes as $attr) {
   $data[(string)$attr->getName()] = (string)$attr;
  }
  return $data;
 }
/**********************************************************
Perse Xml Loop.
*********************************************************/
 private function _parseXmlLoop($doc="", $vals="", $not="") {
  if(!is_object($doc)) {
   return;
  }
  $ns = $this->_nameSpace($doc->getNamespaces(true));
  $vals_ = (!$vals) ? $doc->children() : $doc->children($vals);
  foreach($vals_ as $obj_key=>$obj_val) {
   if($not===1) {
    if($obj_attr = $doc->attributes()) {
     $body[$obj_key][$this->ATTR] = $this->_perseAttributes($obj_attr);
    }
   }
   $data = "";
   foreach($ns as $key=>$val) {
    if($obj_loop = $this->_parseXmlLoop($obj_val, $val)) {
     $data[$key] = $obj_loop;
    }
   }
   if(count($ns)===1) {
    $data = $data[""];
   }
   foreach($ns as $key=>$val) {
    $obj_attr = ($val) ? $obj_val->attributes($val) : $obj_val->attributes();
    if($obj_attr) {
     $data[$this->ATTR.$key] = $this->_perseAttributes($obj_attr);
    }
   }
   if((string)$obj_val && !is_array($obj_val)) {
    $data[$this->VAL] = (string)$obj_val;
   }
   $body[$obj_key][] = $data;
  }
  if(isset($body)) return $body;
 }


}
?>
