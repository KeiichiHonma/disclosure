<?php
class Tools extends CI_Controller {

    var $CI;
    var $ATTR = "_attributes";
    var $NS = "_namespace";
    var $VAL = "_value";
    var $log_echo = FALSE;
    var $is_parse = TRUE;
    var $is_tenmono = FALSE;
    var $xbrls_informations;
    var $xbrl_files;
    
    
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
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        $this->load->library('upload_folder');
        $this->load->library('Xbrl_lib');
        $this->categories = $this->Category_model->getAllcategories();
        
        $this->archiver = new ZipArchive();
        $this->extractFiles = array();
    }

    public function analyze()
    {
        //$today = date('Ymd',time());
        $time = time();
        $today = date('Y',$time).'/'.date('m',$time).'/'.date('d',$time);
        list($year,$month,$day) = explode('/',$today);

        //zip
        $zip_path = '/usr/local/apache2/htdocs/disclosure/uploads/zip/';
        $tmp_path = '/usr/local/apache2/htdocs/disclosure/uploads/tmp/';
        $move_path = '/usr/local/apache2/htdocs/disclosure/xbrls/';
        $tmp_ymd_path = '/usr/local/apache2/htdocs/disclosure/uploads/tmp/'.$year.'/'.$month.'/'.$day;
        $move_ymd_path = '/usr/local/apache2/htdocs/disclosure/xbrls/'.$year.'/'.$month.'/'.$day;
        $rename_paths = array();
        
        if(is_dir($tmp_ymd_path)) $this->_remove_directory($tmp_ymd_path);
        
        //dir作成
        $is_dir_go = FALSE;
        if( $this->_make_date_directory($tmp_path.$year) && $this->_make_date_directory($tmp_path.$year.'/'.$month) && $this->_make_date_directory($tmp_path.$year.'/'.$month.'/'.$day))$is_dir_go = TRUE;
        if(!$is_dir_go){
            log_message('error','tmp dir error'.$tmp_ymd_path);
            echo 'tmp dir error';
            die();
        }
        
        
        
        
        if( $this->_make_date_directory($move_path.$year) && $this->_make_date_directory($move_path.$year.'/'.$month) && $this->_make_date_directory($move_path.$year.'/'.$month.'/'.$day))$is_dir_go = TRUE;
        if(!$is_dir_go){
            log_message('error','move dir error'.$tmp_ymd_path);
            echo 'move dir error';
            die();
        }
        //@mkdir($tmp_ymd_path);
        //@chown($tmp_ymd_path, 'apache');
        //@chmod($tmp_ymd_path,0770);

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
                    $tmp_dir_path = $tmp_ymd_path .'/' . $tempFolder;
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
                        //$rename_paths[] = array('zip_path'=>$zip_file_path,'tmp_path'=>$tmp_dir_path . $pathinfo['filename'] . $pathinfo['filename'] , 'move_path'=>$move_ymd_path . '/' . $pathinfo['filename']);
                        $rename_paths[] = array('zip_path'=>$zip_file_path,'tmp_path'=>$tmp_dir_path , 'move_path'=>$move_ymd_path . '/' . $tempFolder);
                    }else{
var_dump('unzip error');
die();
                    }
                }
            }
        }
        if(!$is_analyze){
            $this->_remove_directory($tmp_path.$year);
            die();
        }
        //@mkdir($move_ymd_path);
        //@chown($move_ymd_path, 'apache');
        //@chmod($move_ymd_path,0770);
        $insert_data = array();
        $csv_datas = array();
        $base_datas = array();
        $this->_list_files($tmp_dir_path. '/',$move_ymd_path);
        
        $tenmono_datas = array('companies'=>array(),'cdatas'=>array());

        $presenters_map = array();
        if(!empty($this->xbrls_informations)){

            //先に企業名をDBに挿入 presenter_id が必要なため
            foreach ($this->xbrls_informations as $unzip_dir_name => $xbrls_information){
                foreach ($xbrls_information as $xbrl_dir_id => $value){
                    $is_tenmono = FALSE;
                    if(preg_match('/株式会社/', $value['presenter_name'])) $is_tenmono = TRUE;
                    $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'] = str_replace('株式会社','',$value['presenter_name']);
                    
                    $presenter = $this->Presenter_model->getPresenterByName($value['presenter_name']);
                    $security = $this->Security_model->getSecurityByName( $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'] );
                    $edinet_code = $value['edinet_code'];
                    if(empty($presenter)){
                        //企業名は重複を防ぐため、edinetcodeで
                        $insert_data['presenter'][$edinet_code]['edinet_code'] = $edinet_code;
                        $insert_data['presenter'][$edinet_code]['name'] = $value['presenter_name'];
                        $insert_data['presenter'][$edinet_code]['securities_code'] = empty($security) ? '' : $security->id;
                    }
                    //tenmono
                    if($is_tenmono && $value['document_name'] == '有価証券報告書'){
                        if(!empty($security)){
                            //業界IDはこの段階ではまだ。既にtenmono側に登録があるかもしれないし、新規の企業かもしれない
                            $tenmono_datas['companies'][$edinet_code]['col_code'] = $security->id;
                            $variety = $this->Tenmono_model->getVarietyByVarietyName($security->category_name);
                            $tenmono_datas['companies'][$edinet_code]['col_vid'] = $variety->_id;
                            $tenmono_datas['companies'][$edinet_code]['col_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'];
                        }else{
                            //証券コードがない。記録して後日対応しないとだめ
                            $this->xbrl_lib->_insert_log_message(array('error','none serucity_code '.$this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'].':edinet_code'.$edinet_code));
                            $tenmono_datas['companies'][$edinet_code]['col_code'] = 0;//空のデータを入れておいて後で対応する
                            $tenmono_datas['companies'][$edinet_code]['col_vid'] = 0;//空のデータを入れておいて後で対応する
                            $tenmono_datas['companies'][$edinet_code]['col_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'];
                        }
                    }
                    $presenters_map[$xbrl_dir_id] = $value['presenter_name'];
                }
            }
        }

        if(!empty($insert_data['presenter']))$this->db->insert_batch('presenters', $insert_data['presenter']);//myisam

        //move
        foreach ($rename_paths as $rename_path){
            if(is_dir($rename_path['move_path'])) $this->_remove_directory($rename_path['move_path']);
            rename($rename_path['tmp_path'],$rename_path['move_path']);
            $zip_name = end(explode('/',$rename_path['zip_path']));
            rename($rename_path['zip_path'],$rename_path['move_path'].'/'.$zip_name);
        }
        $this->_remove_directory($tmp_path.$year);

        //実際にファイル解析
        $xbrl_count = 0;
        $created = date("Y-m-d H:i:s", time());

        foreach ($this->xbrl_files as $unzip_dir_name =>  $xbrls){
            //start transaction manually
            $this->db->trans_begin();

            foreach ($xbrls as $xbrl_dir_id =>  $xbrl_paths){
                $xbrl_paths_count = count($xbrl_paths);
                $xbrl_path_loop_number = 0;
                $excel_map = array();
                $xbrl_path_arr = array();
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
                    array(15) {
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
                      string(4) "2014"
                      [8]=>
                      string(2) "08"
                      [9]=>
                      string(2) "06"
                      [10]=>
                      string(18) "20140806123925fb71"
                      [11]=>
                      string(8) "S1001W63"
                      [12]=>
                      string(4) "XBRL"
                      [13]=>
                      string(9) "PublicDoc"
                      [14]=>
                      string(60) "jpsps070000-asr-001_G07041-000_2014-05-12_01_2014-07-31.xbrl"
                    }
                    */
                    $format_path_ex = explode('/', $xbrl_path);
                    $count = count($format_path_ex);
                    $edinet_code = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['edinet_code'];
                    //ファイル命名
                    $new_format_arr = array_slice($format_path_ex,6,5);
                    $new_format_arr[] = $date.'_'.$edinet_code;//日付+edinetcode
                    $format_path = implode('/',$new_format_arr);//xbrlsからランダム文字列ディレクトリ20140806123925fb71まで

                    //最初の1ファイル分だけDBデータ生成
                    if($xbrl_number == 0){
                        $insert_data['document'][$xbrl_dir_id]['edinet_code'] = $edinet_code;
                        $insert_data['document'][$xbrl_dir_id]['presenter_id'] = 0;
                        $insert_data['document'][$xbrl_dir_id]['category_id'] = 0;
                        $insert_data['document'][$xbrl_dir_id]['manage_number'] = $xbrl_dir_id;
                        $insert_data['document'][$xbrl_dir_id]['xbrl_count'] = $xbrl_paths_count;//xbrlファイルの数
                        
                        $insert_data['document'][$xbrl_dir_id]['format_path'] = $format_path;//xbrlsから
                        $insert_data['document'][$xbrl_dir_id]['date'] = $date;
                        $insert_data['document'][$xbrl_dir_id]['document_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['document_name'];
                        $insert_data['document'][$xbrl_dir_id]['presenter_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'];
                        $insert_data['document'][$xbrl_dir_id]['created'] = $created;

                        //文書のカテゴリチェック
                        if(isset($this->categories[$this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['document_name']])){
                            $insert_data['document'][$xbrl_dir_id]['category_id'] = $this->categories[$this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['document_name']]->id;
                        }
                        //企業チェック
                        $presenter_name = $presenters_map[$xbrl_dir_id];//XbrlSearchDlInfo.csvを使う
                        $presenter = $this->Presenter_model->getPresenterByName($presenter_name);
                        if(!empty($presenter)){
                            $insert_data['document'][$xbrl_dir_id]['presenter_id'] = $presenter->id;
                        }
                        //code生成
                        $code = $edinet_code.'_'.$date.'_'.$xbrl_dir_id.'_'.$insert_data['document'][$xbrl_dir_id]['presenter_id'];
                        $insert_data['document'][$xbrl_dir_id]['code'] = $code;
                        //codeチェック
                        $check_xbrl = $this->Document_model->getDocumentByCode($code);

                        //excelは複数ファイルではなくセルで分けるため
                        $excel_path = $format_path.'.xlsx';
                    }
                    
                    $xbrl_path_arr[] = $xbrl_path;//複数の可能性あり
                    
                    if($this->is_parse){
                        $xbrl_datas = $this->xbrl_lib->_parseXml($xbrl_path);
                        $csv_datas[$xbrl_count] = $this->xbrl_lib->_makeCsvSqlData($xbrl_datas,$xbrl_path,$insert_data['document_data'][$xbrl_dir_id][$xbrl_number],$edinet_code,$tenmono_datas);
                        $csv_paths[$xbrl_count] = $xbrl_number > 0 ? $format_path.'_'.$xbrl_number.'.csv' : $format_path.'.csv';
                        $excel_map[$xbrl_count]  = $xbrl_path_loop_number;
                        $excel_sheet_name[$xbrl_dir_id][$xbrl_path_loop_number] = end($new_format_arr).'_'.$xbrl_path_loop_number;
                        $xbrl_count++;
                    }
                    $xbrl_path_loop_number++;
                }

                //DB追加、更新///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //既に存在していたら除去して個別更新
                if(!empty($check_xbrl)){
                    $this->db->where('code', $code);
                    $this->db->update('documents', $insert_data['document'][$xbrl_dir_id]);
                    //個別のデータは一旦削除
                    $this->db->where('document_id', $check_xbrl->id);
                    $this->db->delete('document_datas');

                    for ($i=0;$i<$xbrl_paths_count;$i++){
                        $batch_data = array();
                        foreach ($insert_data['document_data'][$xbrl_dir_id][$i] as $value){
                            $value['document_id'] = $check_xbrl->id;
                            $batch_data[] = $value;
                        }
                        $this->db->insert_batch('document_datas', $batch_data);
                    }
                }else{
                    //DB追加
                    $insert_data['document'][$xbrl_dir_id]['xbrl_path'] = serialize($xbrl_path_arr);//複数の可能性あり
                    $this->db->insert('documents', $insert_data['document'][$xbrl_dir_id]);
                    $document_id = $this->db->insert_id();
                    //echo $document_id."\n";
                    for ($i=0;$i<$xbrl_paths_count;$i++){
                        $batch_data = array();
                        foreach ($insert_data['document_data'][$xbrl_dir_id][$i] as $value){
                            $value['document_id'] = $document_id;
                            $batch_data[] = $value;
                        }
                        $this->db->insert_batch('document_datas', $batch_data);
                    }
                }

                //ここでexcel
                echo $excel_path."\n";
                $this->xbrl_lib->put_excel($excel_path,$csv_datas,$excel_sheet_name[$xbrl_dir_id],$excel_map);
            }
            //1documentごとにコミット
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                //return false;
            } else {
                $this->db->trans_commit();
                //return $coupon_id;
            }
        }
        if($this->is_parse){
            //csv書き出し
            $this->put_csv($csv_paths,$csv_datas);
            
            //tenmono更新///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            /*
            array(2) {
              ["companies"]=>
              array(1) {
                ["E04896"]=>
                array(4) {
                  ["code"]=>
                  string(4) "9627"
                  ["vid"]=>
                  string(2) "28"
                  ["name"]=>
                  string(30) "アインファーマシーズ"
                  ["address"]=>
                  string(53) "札幌市白石区東札幌５条２丁目４番30号"
                }
              }
              ["cdatas"]=>
              array(1) {
                ["E04896"]=>
                array(7) {
                  ["code"]=>
                  string(4) "9627"
                  ["date"]=>
                  string(10) "2014/07/31"
                  ["fiscalyear"]=>
                  string(74) "第45期（自　平成25年５月１日　至　平成26年４月30日）"
                  ["person"]=>
                  string(4) "2517"
                  ["age"]=>
                  string(4) "32.0"
                  ["employ"]=>
                  string(3) "4.5"
                  ["income"]=>
                  float(429.3)
                }
              }
            }
            */
            $tenmono_company_ids = array();
            //company
            foreach ($tenmono_datas['companies'] as $edinet_code => $tenmono_company){
                $company = $this->Tenmono_model->getCompanyBySecurityCode($tenmono_company['col_code']);
                if(!empty($company)){
                    $this->db->where('col_code', $tenmono_company['col_code']);
                    $this->db->update('tab_job_company', $tenmono_company);
                    $tenmono_company_ids[$edinet_code] = $company->_id;
                }else{
                    $this->db->insert('tab_job_company', $tenmono_company);
                    $tenmono_company_ids[$edinet_code] = $this->db->insert_id();
                }
            }

            //cdata
            foreach ($tenmono_datas['cdatas'] as $edinet_code => $tenmono_cdata){
                if(!isset($tenmono_datas['companies'][$edinet_code]['col_vid'])){
                    echo $edinet_code."\n";
                    var_dump($tenmono_datas);
                    die();
                }
                
                
                //code生成 $vid.$cid.$disclosure_time;
                $code = $tenmono_datas['companies'][$edinet_code]['col_vid'].$tenmono_company_ids[$edinet_code].$tenmono_cdata['col_disclosure'];
                $cdata = $this->Tenmono_model->getCdataByCode($code);
                
                //edition及びtrend,pace更新
                $old_cdatas = $this->Tenmono_model->getCdatasByCompanyId($tenmono_company_ids[$edinet_code],'tab_job_cdata.col_disclosure ASC');//古い順に取得
                $trend = 0;
                if(!empty($old_cdatas)){
                    $before_income = end($old_cdatas)->col_income;
                    if($before_income < $tenmono_cdata['col_income']){
                        $trend = 1;
                    }elseif($before_income == $tenmono_cdata['col_income']){
                        $trend = 3;
                    }elseif($before_income > $tenmono_cdata['col_income']){
                        $trend = 2;
                    }
                }
                $time = time();
                $tenmono_cdata['col_mtime'] = $time;
                $tenmono_cdata['col_code'] = $code;
                $tenmono_cdata['col_vid'] = $tenmono_datas['companies'][$edinet_code]['col_vid'];
                $tenmono_cdata['col_cid'] = $tenmono_company_ids[$edinet_code];
                $tenmono_cdata['col_edition'] = 1;//一旦0
                $tenmono_cdata['col_pace'] = round($tenmono_cdata['col_income'] / $tenmono_cdata['col_age'],1);
                $tenmono_cdata['col_income_trend'] = $trend;
                $tenmono_cdata['col_income_lifetime'] = $this->_getIncomeLifetime($tenmono_cdata['col_income'],$tenmono_cdata['col_age']);

                if(!empty($cdata)){
                    $this->db->where('col_code', $code);
                    $this->db->update('tab_job_cdata', $tenmono_cdata);
                }else{
                    $tenmono_cdata['col_ctime'] = $time;
                    $this->db->insert('tab_job_cdata', $tenmono_cdata);
                }
                
                //過去のedition及びtrend,pace更新
                if(!empty($old_cdatas)){
                    $i = 0;
                    $max_edition = count($old_cdatas);
                    foreach ($old_cdatas as $key => $old_cdata){
                        $trend = 0;
                        $pace = round($old_cdata->col_income / $old_cdata->col_age,1);
                        if($i == 0){
                            $before_income = $old_cdata->col_income;
                        }else{
                            if($before_income < $old_cdata->col_income){
                                $trend = 1;
                            }elseif($before_income == $old_cdata->col_income){
                                $trend = 3;
                            }elseif($before_income > $old_cdata->col_income){
                                $trend = 2;
                            }
                            $before_income = $old_cdata->col_income;
                        }
                        $this->db->where('col_code', $old_cdata->col_code);
                        $this->db->update('tab_job_cdata', array( 'col_edition'=>$max_edition,'col_income_trend'=>$trend,'col_pace'=>$pace ));
                        $i++;
                        $max_edition--;
                    }
                }
            }
        }
        //最後にディレクトリを削除
        $this->_remove_directory($rename_path['move_path'],TRUE,array($rename_path['move_path']));
    }

    private $per0 = 0.39;
    private $per1 = 0.49;
    private $per2 = 0.62;
    private $per3 = 0.74;
    private $per4 = 0.86;
    private $per5 = 0.97;
    private $per6 = 1;
    private $per7 = 0.98;
    private $per8 = 0.92;
    private $per9 = 0.67;
    private $per10 = 0.58;

    //増加率
    function _getAgePer($age){
        if(20 <= $age && $age < 25){
            return $this->per1;
        }elseif(25 <= $age && $age < 30){
            return $this->per2;
        }elseif(30 <= $age && $age < 35){
            return $this->per3;
        }elseif(35 <= $age && $age < 40){
            return $this->per4;
        }elseif(40 <= $age && $age < 45){
            return $this->per5;
        }elseif(45 <= $age && $age < 50){
            return $this->per6;
        }elseif(50 <= $age && $age < 55){
            return $this->per7;
        }elseif(55 <= $age && $age < 60){
            return $this->per8;
        }elseif(60 <= $age && $age < 65){
            return $this->per9;
        }elseif(65 <= $age){
            return $this->per10;
        }else{
            //若すぎる
            return $this->per0;
        }
    }

    //生涯賃金
    function _getIncomeLifetime($income,$age){
        $base_per = $this->_getAgePer($age);
        $lifetie_income = 0;
        //38年間
        for($i=23;$i<=60;$i++){
            $i_per = $this->_getAgePer($i);
            $lifetie_income += $i_per / $base_per * $income;
        }
        return round($lifetie_income);
    }

    function _list_files($tmp_dir_path,$move_ymd_path){
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
                    array(16) {
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
                      string(4) "2014"
                      [9]=>
                      string(2) "08"
                      [10]=>
                      string(2) "06"
                      [11]=>
                      string(18) "201408061232131ac3"
                      [12]=>
                      string(8) "S1001W63"
                      [13]=>
                      string(4) "XBRL"
                      [14]=>
                      string(9) "PublicDoc"
                      [15]=>
                      string(0) ""
                    }
                    */
                    
                    
                    //move
                    $base_path = '';
                    $xbrl_path = '';
                    $xbrl_add_path = '';
                    foreach ($dirs as $key => $value){
                        if(!empty($value)){
                            if($key >= 11){
                                $xbrl_add_path .= '/'.$value;
                            }else{
                                $base_path .= '/'.$value;
                            }
                        }

                    }
                    $xbrl_path = $move_ymd_path.$xbrl_add_path;
                    $this->xbrl_files[$dirs[11]][$dirs[12]][] = $xbrl_path . '/' . $file;//xbrlが複数ある場合があります
                }elseif($pathinfo['filename'] == 'XbrlSearchDlInfo' && $pathinfo['extension'] == 'csv'){
                    $dirs = explode('/',$tmp_dir_path);
                    //XbrlSearchDlInfo.csv
                    $this->xbrls_informations[$dirs[11]] = $this->_read_form_edinetinfo_csv($tmp_dir_path . $file);
                }
            } else if( is_dir($tmp_dir_path . $file) && $file != 'AuditDoc') {
                $files = array_merge($files, $this->_list_files($tmp_dir_path . $file . '/',$move_ymd_path));
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
        if ($this->CI->upload_folder->createFolder($this->CI->upload_folder->getTemporaryFolder($folderName,$date))) {
            return $folderName;
        } else {
            $this->CI->logger->emerg(sprintf('failed to create temporary folder:%s', $folderName));
            return false;
        }
    }

    function _remove_directory($dir,$is_directory_only = FALSE,$not_delete_path_arr = array()) {
      if ($handle = opendir("$dir")) {
       while (false !== ($item = readdir($handle))) {
         if ($item != "." && $item != "..") {
           if (is_dir("$dir/$item")) {
             $this->_remove_directory("$dir/$item",FALSE);
           } else {
             if($is_directory_only){
                 $pathinfo = pathinfo($item);
                 /*
                 2014-07-31_G07041.xlsx
                 XbrlSearchDlInfo.csv
                 Xbrl_Search_20140805_110324.zip
                 S1001W63 ← これを削除
                 */
                 if($pathinfo['extension'] != 'csv' && $pathinfo['extension'] != 'xlsx' && $pathinfo['extension'] != 'zip'){
                     unlink("$dir/$item");
                 }
             }else{
                unlink("$dir/$item");
             }
           }
         }
       }
       closedir($handle);
       if(!in_array($dir,$not_delete_path_arr)) rmdir($dir);
       //echo "removing $dir<br>\n";
      }
    }

    function _make_date_directory($path) {
        $bl = FALSE;
        if( is_dir($path) || ( mkdir($path) && chown($path, 'apache') && chmod($path,0770) ) )$bl = TRUE;
        return $bl;
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
