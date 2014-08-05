<?php
class Tools extends CI_Controller {

    var $CI;
    var $ATTR = "_attributes";
    var $NS = "_namespace";
    var $VAL = "_value";
    var $log_echo = FALSE;
    var $is_parse = TRUE;
    var $xbrls_informations;
    var $xbrl_files;
    var $alphabet = array('A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ');
    
    function __construct(){
        set_time_limit(0);
        ini_set('max_execution_time', 0);
        parent::__construct();
        $this->CI =& get_instance();
        if ( ! $this->input->is_cli_request() )     {
            //die('Permission denied.');
        }
        $this->load->library('tank_auth');
        //connect database
        $this->load->database();
        $this->load->model('Category_model');
        $this->load->model('Security_model');
        $this->load->model('Presenter_model');
        $this->load->model('Xbrl_model');
        $this->load->library('upload_folder');
        $this->load->library('Xbrl_lib');
        $this->load->library('PHPExcel');
        $this->categories = $this->Category_model->getAllcategories();
        
        $this->archiver = new ZipArchive();
        $this->extractFiles = array();
    }

    public function analyze()
    {
        $today = date('Ymd',time());
        //zip
        $zip_path = '/usr/local/apache2/htdocs/disclosure/uploads/zip/';
        $tmp_path = '/usr/local/apache2/htdocs/disclosure/uploads/tmp/'.$today;
        
        $move_path = '/usr/local/apache2/htdocs/disclosure/xbrls/'.$today;
        $rename_paths = array();

        
        if(is_dir($tmp_path)) $this->_remove_directory($tmp_path);
        @mkdir($tmp_path);
        @chown($tmp_path, 'apache');
        @chmod($tmp_path,0770);

        $list = scandir($zip_path);
        $is_analyze = FALSE;
        foreach($list as $file){
            $zip_file_path = $zip_path . $file;
            if($file == '.' || $file == '..'){
                continue;
            } else if (is_file($zip_file_path)){
                $pathinfo = pathinfo($zip_file_path);
                if($pathinfo['extension'] == 'zip'){
                    $is_analyze = TRUE;
                    $tempFolder = $this->_createTemporaryFolder($today);
                    $tmp_dir_path = $tmp_path .'/' . $tempFolder;
                    // ZIPファイルをオープン
                    $res = $this->archiver->open($zip_file_path);
                     
                    // zipファイルのオープンに成功した場合
                    if ($res === true) {
                        //@mkdir($path . $pathinfo['filename']);
                        // 圧縮ファイル内の全てのファイルを指定した解凍先に展開する
                        //$this->archiver->extractTo($path . $pathinfo['filename']);
                        $this->archiver->extractTo($tmp_dir_path. '/');
                     
                        // ZIPファイルをクローズ
                        $this->archiver->close();
                        
                        //renameを記録
                        //$rename_paths[] = array('zip_path'=>$zip_file_path,'tmp_path'=>$tmp_dir_path . $pathinfo['filename'] . $pathinfo['filename'] , 'move_path'=>$move_path . '/' . $pathinfo['filename']);
                        $rename_paths[] = array('zip_path'=>$zip_file_path,'tmp_path'=>$tmp_dir_path , 'move_path'=>$move_path . '/' . $tempFolder);
                    }else{
var_dump('unzip error');
die();
                    }
                }
            }
        }
        if(!$is_analyze){
            $this->_remove_directory($tmp_path);
            die();
        }
        @mkdir($move_path);
        @chown($move_path, 'apache');
        @chmod($move_path,0770);
        $insert_data = array();
        $csv_datas = array();
        $base_datas = array();
        $this->_list_files($tmp_dir_path. '/',$move_path);

        if(!empty($this->xbrls_informations)){
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

        //move 
        foreach ($rename_paths as $rename_path){
            if(is_dir($rename_path['move_path'])) $this->_remove_directory($rename_path['move_path']);
            rename($rename_path['tmp_path'],$rename_path['move_path']);
            //@unlink($rename_path['zip_path']);
        }
        $this->_remove_directory($tmp_path);

        //実際にファイル解析
        $xbrl_count = 0;
        $created = date("Y-m-d H:i:s", time());

        foreach ($this->xbrl_files as $unzip_dir_name =>  $xbrls){
            foreach ($xbrls as $xbrl_dir_id =>  $xbrl_paths){
                $xbrl_paths_count = count($xbrl_paths);
                $xbrl_path_loop_number = 0;
                $excel_map = array();
                foreach ($xbrl_paths as $xbrl_number => $xbrl_path){//xbrlが複数ある場合があります
                    //文書提出日時
                    $pathinfo = pathinfo($xbrl_path);
                    $exp = explode('-',$pathinfo['filename']);
                    $count = count($exp);
                    $day = $exp[$count-1];
                    $month = $exp[$count-2];
                    $year = substr($exp[$count-3],-4);
                    $date = $year.'-'.$month.'-'.$day;
                    //format
                    /*
                    array(11) {
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
                      string(8) "20140723"
                      [8]=>
                      string(32) "Xbrl_Search_20140718_102237_test"
                      [9]=>
                      string(8) "S1001O6T"
                      [10]=>
                      string(49) "jpfr-ssr-G08995-000-2014-04-21-01-2014-07-18.xbrl"
                    }
                    */
                    $format_path_ex = explode('/', $xbrl_path);
                    $count = count($format_path_ex);
                    //ファイル命名
                    $format_path_ex[$count-1] = $date.'_'.$this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['edinet_code'];//日付+edinetcode
                    $format_path = implode('/',array_slice($format_path_ex,6,$count));//xbrlsから
                    //最初の1ファイル分だけDBデータ生成
                    if($xbrl_number == 0){
                        $insert_data['xbrl'][$xbrl_dir_id]['edinet_code'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['edinet_code'];
                        $insert_data['xbrl'][$xbrl_dir_id]['presenter_id'] = 0;
                        $insert_data['xbrl'][$xbrl_dir_id]['category_id'] = 0;
                        $insert_data['xbrl'][$xbrl_dir_id]['manage_number'] = $xbrl_dir_id;
                        $insert_data['xbrl'][$xbrl_dir_id]['xbrl_path'] = $xbrl_path;
                        $insert_data['xbrl'][$xbrl_dir_id]['xbrl_count'] = $xbrl_paths_count;//xbrlファイルの数
                        
                        $insert_data['xbrl'][$xbrl_dir_id]['format_path'] = $format_path;//xbrlsから
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
                        //既に存在していたら除去して個別更新
                        if(!empty($check_xbrl)){
                            $this->CI->db->where('code', $code);
                            $this->CI->db->update('xbrls', $insert_data['xbrl'][$xbrl_dir_id]);
                            unset($insert_data['xbrl'][$xbrl_dir_id]);
                        }
                        //excelは複数ファイルではなくセルで分けるため
                        $excel_path = $format_path.'.xlsx';
                    }
                    if($this->is_parse){
                        $xbrl_datas = $this->xbrl_lib->_parseXml($xbrl_path);
                        $csv_datas[$xbrl_count] = $this->xbrl_lib->_makeCsvSqlData($xbrl_datas,$xbrl_path,$insert_data['xbrl'][$xbrl_dir_id]);
                        //$base_datas[$xbrl_count] = $this->xbrl_lib->_makeCsv($xbrl_datas,$xbrl_path,TRUE);
                        $csv_paths[$xbrl_count] = $xbrl_number > 1 ? $format_path.'_'.$xbrl_number.'.csv' : $format_path.'.csv';
                        //$base_paths[$xbrl_count] = $xbrl_number > 1 ? $format_path.'_'.$xbrl_number.'.base' : $format_path.'.base';
                        $excel_map[$xbrl_count]  = $xbrl_path_loop_number;
                        $excel_sheet_name[$xbrl_dir_id][$xbrl_path_loop_number] = $format_path_ex[$count-1].'_'.$xbrl_path_loop_number;
                        $xbrl_count++;
                    }
                    $xbrl_path_loop_number++;
                }
                //ここでexcel
                echo $excel_path."\n";
                $this->put_excel($excel_path,$csv_datas,$excel_sheet_name[$xbrl_dir_id],$excel_map);
            }
        }
        //if(!empty($insert_data['xbrl'])) $this->db->insert_batch('xbrls', $insert_data['xbrl']);

        if($this->is_parse){
            $this->put_csv($csv_paths,$csv_datas);
            $this->put_csv($base_paths,$base_datas,TRUE);
            //$this->put_excel($excel_path,$csv_datas,$excel_sheet_name);
        }
    }
    
    function _list_files($tmp_dir_path,$move_path){
        $files = array();
        $list = scandir($tmp_dir_path);
        foreach($list as $file){
            if($file == '.' || $file == '..'){
                continue;
            } else if (is_file($tmp_dir_path . $file)){
                $pathinfo = pathinfo($tmp_dir_path . $file);
                if($pathinfo['extension'] == 'xbrl'){
                    $dirs = explode('/',$tmp_dir_path);
                    /*
                    array(14) {
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
                      string(7) "uploads"
                      [7]=>
                      string(3) "tmp"
                      [8]=>
                      string(8) "20140723"
                      [9]=>
                      string(18) "20140723155311982f"
                      [10]=>
                      string(8) "S10024IQ"
                      [11]=>
                      string(4) "XBRL"
                      [12]=>
                      string(9) "PublicDoc"
                      [13]=>
                      string(0) ""
                    }
                    */
                    
                    
                    //move
                    $base_path = '';
                    $xbrl_path = '';
                    $xbrl_add_path = '';
                    foreach ($dirs as $key => $value){
                        if(!empty($value)){
                            if($key >= 9){
                                $xbrl_add_path .= '/'.$value;
                            }else{
                                $base_path .= '/'.$value;
                            }
                        }

                    }
                    $xbrl_path = $move_path.$xbrl_add_path;
                    $this->xbrl_files[$dirs[9]][$dirs[10]][] = $xbrl_path . '/' . $file;//xbrlが複数ある場合があります
                }elseif($pathinfo['filename'] == 'XbrlSearchDlInfo' && $pathinfo['extension'] == 'csv'){
                    $dirs = explode('/',$tmp_dir_path);
                    //XbrlSearchDlInfo.csv
                    $this->xbrls_informations[$dirs[9]] = $this->_read_form_edinetinfo_csv($tmp_dir_path . $file);
                }
            } else if( is_dir($tmp_dir_path . $file) && $file != 'AuditDoc') {
                $files = array_merge($files, $this->_list_files($tmp_dir_path . $file . '/',$move_path));
            }
        }
        return $files;
    }

    function _fgetcsv_edinetinfo_reg (&$handle, $length = null, $d = ',', $e = '"') {
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

    function _read_form_edinetinfo_csv($csv_file,$skip = TRUE){
        $fp=@fopen($csv_file,"r");
        $line = 0;
        $csv = array();
        while ($CSVRow = @$this->_fgetcsv_edinetinfo_reg($fp,1024)){//ファイルを一行ずつ読み込む
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

    // ----------------------------------------------------------------
    // CSV出力 
    // ----------------------------------------------------------------
    function put_csv($csv_paths,$csv_datas,$is_base = FALSE) {
        foreach ($csv_datas as $key => $csv_data){
            // ファイル書き込み
            $fp = fopen($csv_paths[$key], 'w+');
            foreach ($csv_data as $line => $value){
                $byte = $this->_fputcsv($fp, $value,$is_base);
            }
            fclose($fp);
            //@chown($csv_paths[$key], 'apache');
            //@chmod($csv_paths[$key],0770);
        }

        return $byte;
    }

    function _fputcsv($fp, $data,$is_base , $toEncoding='SJIS-win', $srcEncoding='UTF-8') {
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
                if(!$is_base) $col = mb_convert_encoding($col, $toEncoding, $srcEncoding);
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
    // EXCEL出力 
    // ----------------------------------------------------------------
    function put_excel($excel_path,$csv_datas,$excel_sheet_name,$excel_map) {
        $objPHPExcel = null;
        // 新規作成の場合
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getDefaultStyle()->getFont()->setName( 'ＭＳ Ｐゴシック' )->setSize( 11 );
        
        foreach ($excel_map as $xbrl_count => $xbrl_path_loop_number){
            if($xbrl_path_loop_number > 0) $objPHPExcel->createSheet();
            // 0番目のシートをアクティブにする（シートは左から順に、0、1，2・・・）
            $objPHPExcel->setActiveSheetIndex($xbrl_path_loop_number);
            // アクティブにしたシートの情報を取得
            $objSheet = $objPHPExcel->getActiveSheet();
            // シート名を変更する
            $objSheet->setTitle($excel_sheet_name[$xbrl_path_loop_number]);
            $excel_tate = 0;
            $line = 0;
            foreach ($csv_datas[$xbrl_count] as $values){
                $excel_tate = $line + 1;
                foreach ($values as $value_number => $col) {
                    if(!isset($this->alphabet[$value_number])){
                        log_message('error','none alphabet '.$value_number.':'.$excel_path);
                    }
                    $excel_yoko = $this->alphabet[$value_number];
                    $excel_column_name = $excel_yoko.$excel_tate;
                    
                    if (is_numeric($col)) {
                        $data[$xbrl_path_loop_number][$excel_column_name] = $col;
                        $objSheet->setCellValue($excel_column_name, $col);
                    } else {
                        if(is_array($col)){
                            var_dump($col);
                            die();
                        }
                        //$col = mb_convert_encoding($col, $toEncoding, $srcEncoding);
                        $col = str_replace('"', '""', $col);
                        $data[$xbrl_path_loop_number][$excel_column_name] = $col;
                        $objSheet->setCellValue($excel_column_name, $col);
                    }
                }
                $line++;
            }
            $objPHPExcel->setActiveSheetIndex(0);//sheet選択
            // IOFactory.phpを利用する場合
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save($excel_path);
        }
        // Excel2007.phpを利用する場合
        //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
        //$objWriter->save("sample2.xlsx");
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

    function _createTemporaryFolder($date = '') {
        $folderName = date('YmdHis') . substr(md5(uniqid(mt_rand())), 0, 4);
        $test = $this->CI->upload_folder->getTemporaryFolder($folderName,$date);
        if ($this->CI->upload_folder->createFolder($this->CI->upload_folder->getTemporaryFolder($folderName,$date))) {
            return $folderName;
        } else {
            $this->CI->logger->emerg(sprintf('failed to create temporary folder:%s', $folderName));
            return false;
        }
    }

    function _remove_directory($dir) {
      if ($handle = opendir("$dir")) {
       while (false !== ($item = readdir($handle))) {
         if ($item != "." && $item != "..") {
           if (is_dir("$dir/$item")) {
             $this->_remove_directory("$dir/$item");
           } else {
             unlink("$dir/$item");
             //echo " removing $dir/$item<br>\n";
           }
         }
       }
       closedir($handle);
       rmdir($dir);
       //echo "removing $dir<br>\n";
      }
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
