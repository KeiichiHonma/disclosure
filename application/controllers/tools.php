<?php
class Tools extends CI_Controller {

    var $CI;
    var $ATTR = "_attributes";
    var $NS = "_namespace";
    var $VAL = "_value";
    var $log_echo = FALSE;
    var $is_parse = TRUE;
    var $is_tenmono = TRUE;
    var $is_tenmono_cdata_all = TRUE;
    var $is_memory_dump = FALSE;
    var $xbrls_informations;
    var $xbrl_files;
    var $htmls_informations;
    
    
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
        //$this->load->model('Category_model');
        //$this->load->model('Security_model');
        //$this->load->model('Presenter_model');
        $this->load->model('Edinet_model');
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        $this->load->library('upload_folder');
        $this->load->library('Xbrl_lib');
        //$this->categories = $this->Category_model->getAllcategories();
        
        $this->archiver = new ZipArchive();
        $this->extractFiles = array();
    }

    public function update_edinet_csv()
    {
        $csv  = array();
        $file = '/usr/local/apache2/htdocs/disclosure/uploads/EdinetcodeDlInfo.csv';
        $fp   = fopen($file, "r");
         
        while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
          $csv[] = $data;
        }
        fclose($fp);
        $this->load->model('Category_model');
        $categories = $this->Category_model->getAllCategories('name');
        
        foreach ($csv as $key => $value){
            if($key > 1){
                $presenter_name = mb_convert_encoding($value[6],"UTF-8","SJIS-win");
                $presenter_name = str_replace(array('　株式会社','　有限会社','　合同会社'),array('','',''),$presenter_name);
                $presenter_name = str_replace(array('株式会社　','有限会社　','合同会社　'),array('','',''),$presenter_name);
                $presenter_name = str_replace(array('株式会社','有限会社','合同会社'),array('','',''),$presenter_name);
                if(isset($value[11])){
                    if(strlen($value[11]) == 5 && preg_match('/0$/', $value[11])){//0で終わる
                        $security_code = substr($value[11], 0, -1);   //最後の「,」を削除
                    }else{
                        $security_code = $value[11];
                    }
                }else{
                    $value[11] = 0;
                    $security_code = 0;
                }

                /*
                csvが空の場合がある、重複する場合がある
                */
                $presenter_name_key = mb_convert_encoding($value[7],"UTF-8","SJIS-win");
                $presenter_name_key = trim(str_replace(array('corporation japan','company,','corporation','inc','limited','incorporated','co','ltd','.',','),array('','','','','','','','',''),strtolower($presenter_name_key)));
                $ng = array('\\','\'','|','`','^','"','<','>',')','(','}','{',']','[',')',';','/','?',':','@','&','=','+','$',',','%');
                $rep = array('','','','','','','','','','','','','','','','','','','','','','','','','');
                $presenter_name_key = str_replace($ng,$rep,$presenter_name_key);
                if($presenter_name_key == ''){
                    //echo mb_convert_encoding($value[6],"UTF-8","SJIS-win");
                    //continue;
                    $presenter_name_key = $security_code != 0 ? $security_code : $value[0];
                }else{
                    $presenter_name_key = str_replace(' ','_',$presenter_name_key);
                    $category_name = isset($value[10]) ? mb_convert_encoding($value[10],"UTF-8","SJIS-win") : 0;

                    //重複チェック;
                    $ed = $this->Edinet_model->getEdinetByPresenterNameKey($presenter_name_key);
                    if(!empty($ed)) $presenter_name_key = $presenter_name_key.'_'.$security_code;
                }

                $data = array(
                    'presenter_type' => trim(mb_convert_encoding($value[1],"UTF-8","SJIS-win")),
                    'presentation_class' => trim(mb_convert_encoding($value[2],"UTF-8","SJIS-win")),
                    'concatenated' => trim(mb_convert_encoding($value[3],"UTF-8","SJIS-win")),
                    'capital' => trim(mb_convert_encoding($value[4],"UTF-8","SJIS-win")),
                    'closing_date' => trim(mb_convert_encoding($value[5],"UTF-8","SJIS-win")),
                    'presenter_name' => trim($presenter_name),
                    'presenter_name_en' => trim(mb_convert_encoding($value[7],"UTF-8","SJIS-win")),
                    'presenter_name_kana' => trim(mb_convert_encoding($value[8],"UTF-8","SJIS-win")),
                    //'presenter_name_key' => trim($presenter_name_key),//危険なので通常は変えない
                    'address' => trim(mb_convert_encoding($value[9],"UTF-8","SJIS-win")),
                    'category_name' => $category_name,
                    'category_id' => isset($categories[$category_name]) ? $categories[$category_name]->id : 0,
                    'edinet_security_code' => $value[11],
                    'security_code' => $security_code
                );
                $ed = $this->Edinet_model->getEdinetByEdinetCode($value[0]);
                if(!empty($ed)){
                    $this->db->where('edinet_code', $value[0]);
                    $this->db->update('edinets', $data);
                }else{
                    $data['edinet_code'] = $value[0];
                    $data['presenter_name_key'] = trim($presenter_name_key);//追加だけ更新
                    $this->db->insert('edinets', $data);
                }

            }

        }
        //market
        $this->update_market_id();
        //最後にkeyがないものを更新
        //$this->update_edinet_presenter_name_key();
    }

    public function update_presenter_name_key(){
        $edinets = $this->Edinet_model->getAllEdinets();
        foreach ($edinets as $edinet){
            $str = trim(str_replace(array('company,','corporation','inc','limited','incorporated','co','ltd','.',',',''),array('','','','','','','','',''),strtolower($edinet->presenter_name_en)));
            $str = str_replace(' ','_',$str);
            $this->db->where('id', $edinet->id);
            $this->db->update('edinets', array('presenter_name_key'=>$str));
        }
        
    }

    public function update_market_id()
    {
        $markets = $this->Edinet_model->getAllMarkets();
        $edinets = $this->Edinet_model->getAllEdinets();
        foreach ($edinets as $edinet){
            if($edinet->security_code > 0){
                $security = $this->Edinet_model->getSecurityBySecurityCode($edinet->security_code);
                if(!empty($security) && isset($markets[$security->place_name])){
                    $data = array(
                        'market_id' => $markets[$security->place_name]->id
                    );
                    $this->db->where('id', $edinet->id);
                    $this->db->update('edinets', $data);

                }else{
                    //マーケットと対応したコードがない
                    echo $edinet->security_code.$edinet->presenter_name."\n";
                }

            }
        }
    }
    
    //tenmonoDBにedinet番号を入れる
    function update_tenmono_company_edinet(){
        $companies = $this->Tenmono_model->getAllCompany();
        foreach ($companies as $company){
            $edinet = $this->Edinet_model->getEdinetBySecurityCode($company->col_code);
            if(!empty($edinet)){
                $this->db->where('col_code', $company->col_code);
                $data['col_edinet_code'] = $edinet->edinet_code;
                $this->db->update('tab_job_company', $data);
            }else{
                echo $company->col_name.':'.$company->col_code."\n";
            }
        }
        
    }

    
    //edinetsの中で証券番号ない、英語の名前もない企業にIDを入れる
    function update_edinet_presenter_name_key(){
        $edinets = $this->Edinet_model->getAllEdinets();
        foreach ($edinets as $edinet){
            if($edinet->security_code == 0 && $edinet->presenter_name_key == ''){
                $this->db->where('id', $edinet->id);
                $this->db->update('edinets', array('presenter_name_key'=>$edinet->edinet_code));
            }
        }
    }

    /*
    対象の項目がドキュメントに必ずあるわけではない。
    つまり項目ごとの一括のループではなく、
    全ての有価証券報告書ドキュメントを取得して、
    IDごとに項目があるかどうか確認しながら挿入していく必要がある
    */
    //public function finance($start = 0,$limit = 50)
    public function finance()
    {
        $finances = array
        (
            //損益計算書
            array('net_sales',789,'当期'),//売上高
            array('cost_of_sales',3432,'当期'),//売上原価
            array('gross_profit',3638,'当期'),//売上総利益
            array('operating_income',796,'当期'),//営業利益
            array('ordinary_income',797,'当期'),//経常利益
            array('extraordinary_income',808,'当期'),//特別利益
            array('extraordinary_losses',810,'当期'),//特別損失
            array('net_income',799,'当期'),//当期純利益
            //貸借対照表
            array('current_assets',2874,'当期末'),//流動資産
            array('noncurrent_assets',3090,'当期末'),//固定資産
            array('assets',800,'当期末'),//資産
            array('current_liabilities',3206,'当期末'),//流動負債
            array('noncurrent_liabilities',3269,'当期末'),//固定負債
            array('liabilities',801,'当期末'),//負債
            array('capital_stock',3277,'当期末'),//資本金
            array('shareholders_equity',3324,'当期末'),//株主資本 //資本金 資本剰余金 利益剰余金 自己株式の合計、合計の株主資本を使用
            //キャッシュフロー計算書
            array('depreciation_and_amortization',4680,'当期'),//減価償却費
            array('net_cash_provided_by_used_in_operating_activities',4944,'当期'),//営業活動によるキャッシュ・フロー
            array('net_cash_provided_by_used_in_investing_activities',5055,'当期'),//投資活動によるキャッシュ・フロー
            array('net_cash_provided_by_used_in_financing_activities',5105,'当期'),//財務活動によるキャッシュ・フロー
            array('net_increase_decrease_in_cash_and_cash_equivalents',5107,'当期'),//キャッシュ・フロー → 現金及び現金同等物の増減額（△は減少）
        );
        //$documents =$this->Document_model->getAllDocuments($start,$limit);
        $documents =$this->Document_model->getAllDocuments();
        $batch_data = array();
        $i = 0;
        foreach ($documents as $index => $document){
            $batch_data[$i]['document_id'] = $document->id;
            $batch_data[$i]['created'] = date("Y-m-d H:i:s", time());
            foreach ($finances as $finance){
                $finance_data = $this->Document_model->getDocumentDataByDocumentIdByTarget($document->id,$finance[1],$finance[2]);
                if(!empty($finance_data)){
                    if($finance[0] == 'extraordinary_income'){
                        $extraordinary_income = $finance_data[0]->int_data;
                        $batch_data[$i][$finance[0]] = floor($finance_data[0]->int_data / 1000000);
                    }elseif($finance[0] == 'extraordinary_losses'){
                        $extraordinary_losses = $finance_data[0]->int_data;
                        $extraordinary_total = $extraordinary_income - $extraordinary_losses;
                        $batch_data[$i]['extraordinary_losses'] = floor($finance_data[0]->int_data / 1000000);
                        $batch_data[$i]['extraordinary_total'] = floor($extraordinary_total / 1000000);
                    }else{
                        $batch_data[$i][$finance[0]] = floor($finance_data[0]->int_data / 1000000);
                    }
                }else{
                    //個別を確認
                    $finance_data = $this->Document_model->getDocumentDataByDocumentIdByTarget($document->id,$finance[1],$finance[2],'個別');
                    if(!empty($finance_data)){
                        if($finance[0] == 'extraordinary_income'){
                            $extraordinary_income = $finance_data[0]->int_data;
                            $batch_data[$i][$finance[0]] = floor($finance_data[0]->int_data / 1000000);
                        }elseif($finance[0] == 'extraordinary_losses'){
                            $extraordinary_losses = $finance_data[0]->int_data;
                            $extraordinary_total = $extraordinary_income - $extraordinary_losses;
                            $batch_data[$i]['extraordinary_losses'] = floor($finance_data[0]->int_data / 1000000);
                            $batch_data[$i]['extraordinary_total'] = floor($extraordinary_total / 1000000);
                        }else{
                            $batch_data[$i][$finance[0]] = floor($finance_data[0]->int_data / 1000000);
                        }
                    }else{
                        //当期に対象のデータがない場合等
                        $batch_data[$i][$finance[0]] = 0;
                    }
                }
                if(!isset($batch_data[$i]['extraordinary_total'])) $batch_data[$i]['extraordinary_total'] = 0;//無い可能性あり
            }
            $i++;
        }
        $this->db->insert_batch('document_finances', $batch_data);
    }
    
    //csv書き出し
    private $pattern = array('A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    private $replacement = array(' A',' B',' C',' D',' E',' F',' G',' H',' J',' K',' L',' M',' N',' O',' P',' Q',' R',' S',' T',' U',' V',' W',' X',' Y',' Z');
    public function export_file($start = 0,$limit = 50)
    {
        $documents =$this->Document_model->getAllDocuments($start,$limit);
        foreach ($documents as $document){
            $csv_data = array();
            $document_datas =$this->Document_model->getDocumentDataByDocumentId($document->id);
            $line = 0;
            foreach ($document_datas as $document_data){
                if($document_data->item_id == 0){
                    $name = str_replace($this->pattern, $this->replacement, $document_data->element_name);
                }else{
                    if($document_data->redundant_label_ja != ''){
                        $name = $document_data->redundant_label_ja;
                    }elseif ($document_data->style_tree != ''){
                        $name = $document_data->style_tree;
                    }elseif ($document_data->detail_tree != ''){
                        $name = $document_data->detail_tree;
                    }else{
                        $name = $document_data->element_name;
                    }
                    
                }
                $csv_data[$line][] = $name;
                $csv_data[$line][] = $document_data->context_period;
                $csv_data[$line][] = $document_data->context_consolidated;
                $csv_data[$line][] = $document_data->context_term;
                $csv_data[$line][] = $document_data->unit;
                if( $document_data->text_data != '' ){
                    $value = $document_data->text_data;
                }elseif ($document_data->mediumtext_data != ''){
                    $value = $document_data->mediumtext_data;
                }elseif (is_numeric($document_data->int_data)){
                    $value = $document_data->int_data;
                }else{
                    $value = '';
                }
                $csv_data[$line][] = $value;
                $line++;
            }
            //path
            $csv_path = $document->format_path.'.csv';
            $excel_path = $document->format_path.'.xlsx';
            $paths = explode('/',$document->format_path);
            $excel_sheet_name = end($paths);
            
            // CSVファイル書き込み
            $fp = fopen($csv_path, 'w+');
            foreach ($csv_data as $line => $value){
                $byte = $this->_fputcsv($fp, $value, FALSE);
            }
            fclose($fp);
            
            // EXCELファイル書き込み
            $this->xbrl_lib->put_excel($excel_path,$csv_data,$excel_sheet_name);
            echo  $document->format_path."\n";
        }
    }
    
    public function analyze()
    {
        $start_date =  date('Ymd H:i',time())."\n";
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
                        $move_path = $move_ymd_path . '/' . $tempFolder;
                        $bl = FALSE;
                        if( chown($tmp_dir_path, 'apache') && chgrp($tmp_dir_path, 'apache') && chmod($tmp_dir_path,0770) ) $bl = TRUE;
                        if(!$bl){
                        echo ('unzip error1');
                        die();
                        }
                        
                        //renameを記録
                        $rename_index = explode('disclosure/',$move_path);//xbrlsからランダム文字列ディレクトリ20140806123925fb71まで
                        $rename_paths[$rename_index[1]] = array('zip_path'=>$zip_file_path,'tmp_path'=>$tmp_dir_path , 'move_path'=>$move_path);
                        
                        //tmp pathを記録
                        $tmp_dir_paths[] = $tmp_dir_path;
                    }else{
                        echo ('unzip error2');
                        die();
                    }
                }
            }
        }
        if($this->is_memory_dump) echo '1 : '.memory_get_usage() . "\n";
        if(!$is_analyze){
            $this->_remove_directory($tmp_path.$year);
            die();
        }
        $insert_data = array();
        $csv_datas = array();
        $base_datas = array();
        foreach ($tmp_dir_paths as $tmp_dir_path){
            $this->_list_files($tmp_dir_path. '/',$move_ymd_path);
        }
/*
        $xbrl_dir_id = 'S1001MZP';//アークコア
        //$xbrl_dir_id = 'S1002AXB';//ヤマダコーポレーション
        if(!empty($this->htmls_informations[$xbrl_dir_id])){
            foreach ($this->htmls_informations[$xbrl_dir_id] as $file_number => $html_file){
                $html_index = array();
                $last_val = end(explode('/',$html_file));
                if(!preg_match('/^0000000/', $last_val)){
                    $insert_html_data['document_id'] = 1;
                    $insert_html_data['filename'] = $last_val;
                    $insert_html_data['html_data'] = $this->xbrl_lib->_makeHtmlData($insert_html_data['document_id'],$html_file,$html_index,$file_number,$move_ymd_path);
                    $insert_html_data['html_index_serialize'] = empty($html_index) ? '' : serialize($html_index);
                    $this->db->insert('document_htmls', $insert_html_data);
                }
            }
        }
        die();
*/

        $tenmono_datas = array('companies'=>array(),'cdatas'=>array());
        if(!empty($this->xbrls_informations)){
            $analyze_list = array();
            //先に企業名をDBに挿入 presenter_id が必要なため
            foreach ($this->xbrls_informations as $unzip_dir_name => $xbrls_information){
                foreach ($xbrls_information as $xbrl_dir_id => $value){
                    //株式会社だけ
                    if(preg_match('/株式会社/', $value['presenter_name'])){
                        $analyze_list[$unzip_dir_name][] = $xbrl_dir_id;
                        $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'] = preg_replace('/^[　]+/u', '', trim(str_replace('株式会社','',$value['presenter_name'])));

                        $edinet_code = $value['edinet_code'];
                        $edinet = $this->Edinet_model->getEdinetByEdinetCode($edinet_code);
                        if(empty($edinet)){
                            //停止 edinet一覧を更新すべき
                            echo $edinet_code.' : update edinet csv !!';
                            die();
                        }
                        //tenmono
                        if($value['document_name'] == '有価証券報告書'){
                            if(!empty($edinet)){
                                //業界名がない場合は上場義務がないやつ
                                if($edinet->category_id > 0){
                                    $tenmono_datas['companies'][$edinet_code]['col_code'] = $edinet->security_code;
                                    $tenmono_datas['companies'][$edinet_code]['col_vid'] = $edinet->category_id;
                                    $tenmono_datas['companies'][$edinet_code]['col_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'];
                                }
                            }
                        }
                    }
                }
            }
        }
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
        if($this->is_memory_dump) echo '2 : '.memory_get_usage() . "\n";
        $analyze_zip_number = 0;
        foreach ($this->xbrl_files as $unzip_dir_name =>  $xbrls){
            $analyze_file_number = 0;
            foreach ($xbrls as $xbrl_dir_id =>  $xbrl_paths){
                //start transaction manually
                $this->db->trans_begin();
                if(!in_array($xbrl_dir_id,$analyze_list[$unzip_dir_name]) ) continue;
                $xbrl_paths_count = count($xbrl_paths);
                $xbrl_path_loop_number = 0;
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
                    //$count = count($format_path_ex);
                    $edinet_code = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['edinet_code'];
                    $edinet = $this->Edinet_model->getEdinetByEdinetCode($edinet_code);
                    
                    //ファイル命名
                    $new_format_arr = array_slice($format_path_ex,6,5);
                    $new_format_arr[] = $date.'_'.$edinet_code;//日付+edinetcode
                    $format_path = implode('/',$new_format_arr);//xbrlsからランダム文字列ディレクトリ20140806123925fb71まで
                    if($this->is_memory_dump) echo '3 : '.memory_get_usage() . "\n";
                    //最初の1ファイル分だけDBデータ生成
                    if($xbrl_number == 0){
                        $insert_data['document'][$xbrl_dir_id]['edinet_id'] = $edinet->id;
                        $insert_data['document'][$xbrl_dir_id]['manage_number'] = $xbrl_dir_id;
                        $insert_data['document'][$xbrl_dir_id]['xbrl_count'] = $xbrl_paths_count;//xbrlファイルの数
                        $insert_data['document'][$xbrl_dir_id]['format_path'] = $format_path;//xbrlsから
                        //zip name
                        $paths = explode('/',$format_path);
                        array_pop($paths);
                        $rename_index = implode('/',$paths);
                        $zip_name = end(explode('/',$rename_paths[$rename_index]['zip_path']));

                        $insert_data['document'][$xbrl_dir_id]['zip_name'] = $zip_name;
                        $insert_data['document'][$xbrl_dir_id]['date'] = $date;
                        $insert_data['document'][$xbrl_dir_id]['document_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['document_name'];
                        $insert_data['document'][$xbrl_dir_id]['presenter_name'] = $this->xbrls_informations[$unzip_dir_name][$xbrl_dir_id]['presenter_name'];
                        $insert_data['document'][$xbrl_dir_id]['created'] = $created;
                        $insert_data['document'][$xbrl_dir_id]['category_id'] = $edinet->category_id;
                        //code生成
                        $code = $edinet->id.'_'.$date.'_'.$xbrl_dir_id;
                        $insert_data['document'][$xbrl_dir_id]['code'] = $code;
                        //codeチェック
                        $check_xbrl = $this->Document_model->getDocumentByCode($code);
                    }
                    
                    $xbrl_path_arr[] = $xbrl_path;//複数の可能性あり
                    
                    //excel csvを都度書き出しに変更したため、必要なくなった
                    if($this->is_parse){
                        if($this->is_memory_dump) echo '3.5 : '.memory_get_usage() . "\n";
                        $xbrl_datas = $this->xbrl_lib->_parseXml($xbrl_path);
                        if($this->is_memory_dump) echo '4 : '.memory_get_usage() . "\n";
                        //$csv_datas[$xbrl_count] = $this->xbrl_lib->_makeCsvSqlData($xbrl_datas,$xbrl_path,$insert_data['document_data'][$xbrl_dir_id][$xbrl_number],$edinet_code,$tenmono_datas);
                        $this->xbrl_lib->_makeCsvSqlData($xbrl_datas,$xbrl_path,$insert_data['document_data'][$xbrl_dir_id][$xbrl_number],$edinet_code,$tenmono_datas);
                        unset($xbrl_datas);
                        if($this->is_memory_dump) echo '5 : '.memory_get_usage() . "\n";
                        $xbrl_count++;
                    }

                    $xbrl_path_loop_number++;
                }

                //DB追加、更新///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                //既に存在していたら除去して個別更新
                if(!empty($check_xbrl)){
                    //個別のデータは一旦削除
                    $this->db->where('document_id', $check_xbrl->id);
                    $this->db->delete('document_datas');
                    
                    //document_datas開始
                    for ($i=0;$i<$xbrl_paths_count;$i++){
                        $batch_data = array();
                        foreach ($insert_data['document_data'][$xbrl_dir_id][$i] as $value){
                            $value['document_id'] = $check_xbrl->id;
                            $batch_data[] = $value;
                        }
                        $this->db->insert_batch('document_datas', $batch_data);
                    }

                    //個別のデータは一旦削除
                    $this->db->where('document_id', $check_xbrl->id);
                    $this->db->delete('document_htmls');

                    if(!empty($this->htmls_informations[$xbrl_dir_id])){
                        $html_index = array();
                        foreach ($this->htmls_informations[$xbrl_dir_id] as $file_number => $html_file){
                            $last_val = end(explode('/',$html_file));
                            $insert_html_data['document_id'] = $check_xbrl->id;
                            $insert_html_data['filename'] = $last_val;
                            $insert_html_data['html_data'] = $this->xbrl_lib->_makeHtmlData($insert_html_data['document_id'],$html_file,$html_index,$file_number,$move_ymd_path);
                            $this->db->insert('document_htmls', $insert_html_data);
                        }
                        if(!empty($html_index)){
                            $insert_data['document'][$xbrl_dir_id]['html_index_serialize'] = serialize($html_index);
                        }
                    }
                    //最後にdocument更新
                    $this->db->where('code', $code);
                    $this->db->update('documents', $insert_data['document'][$xbrl_dir_id]);
                }else{
                    if($this->is_memory_dump) echo '6 : '.memory_get_usage() . "\n";
                    //DB追加
                    $insert_data['document'][$xbrl_dir_id]['xbrl_path'] = serialize($xbrl_path_arr);//複数の可能性あり
                    //空の場合は空フラグを立てる
                    if(empty($this->htmls_informations[$xbrl_dir_id])){
                        $insert_data['document'][$xbrl_dir_id]['is_html'] = 1;
                    }
                    $this->db->insert('documents', $insert_data['document'][$xbrl_dir_id]);
                    $document_id = $this->db->insert_id();

                    echo 'document_id done : '.$document_id."\n";
                    
                    //document_datas開始
                    for ($i=0;$i<$xbrl_paths_count;$i++){
                        $batch_data = array();
                        foreach ($insert_data['document_data'][$xbrl_dir_id][$i] as $value){
                            $value['document_id'] = $document_id;
                            $batch_data[] = $value;
                        }
                        $this->db->insert_batch('document_datas', $batch_data);
                    }
                    if($this->is_memory_dump) echo '7 : '.memory_get_usage() . "\n";
                    if(!empty($this->htmls_informations[$xbrl_dir_id])){
                        $html_index = array();
                        foreach ($this->htmls_informations[$xbrl_dir_id] as $file_number => $html_file){
                            $last_val = end(explode('/',$html_file));
                            $insert_html_data['document_id'] = $document_id;
                            $insert_html_data['filename'] = $last_val;
                            if($this->is_memory_dump) echo '7.4 : '.memory_get_usage() . "\n";
                            $insert_html_data['html_data'] = $this->xbrl_lib->_makeHtmlData($insert_html_data['document_id'],$html_file,$html_index,$file_number,$move_ymd_path);
                            if($this->is_memory_dump) echo '7.5 : '.memory_get_usage() . "\n";
                            $this->db->insert('document_htmls', $insert_html_data);
                        }
                        if(!empty($html_index)){
                            $this->db->where('id', $document_id);
                            $this->db->update('documents', array('html_index_serialize'=>serialize($html_index)));
                        }
                    }

                }
                if($this->is_memory_dump) echo '8 : '.memory_get_usage() . "\n";
                echo $format_path.' - '.$xbrl_dir_id."\n";
                //1documentごとにコミット
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    //return false;
                } else {
                    $this->db->trans_commit();
                    //return $coupon_id;
                }
                //unset($xbrls[$xbrl_dir_id]);//memory unset
                if($this->is_memory_dump) echo '9 : '.memory_get_usage() . "\n";
                echo 'zip_number:'.$analyze_zip_number.' - '.'file_number:'.$analyze_file_number."\n";
                echo 'memory : '.memory_get_usage() . "\n";
                $analyze_file_number++;
                //tenmono
                if($this->is_tenmono){
                    $this->_do_tenmono($tenmono_datas,$edinet_code);
                }
            }
            if($this->is_memory_dump) echo '10 : '.memory_get_usage() . "\n";
            $analyze_zip_number++;
            
        }
        //最後にディレクトリを削除
        $this->_remove_directory($rename_path['move_path'],TRUE,array($rename_path['move_path']));
        echo 'start'.$start_date."\n";
        echo 'ebd'.date('Ymd H:i',time())."\n";
    }

    function _do_tenmono($tenmono_datas,$edinet_code){
        $tenmono_company_ids = array();
        //company
        //foreach ($tenmono_datas['companies'] as $edinet_code => $tenmono_company){
            $is_xbrl = FALSE;
            //$company = $this->Tenmono_model->getCompanyBySecurityCode($tenmono_datas['companies'][$edinet_code]['col_code']);
            $company = $this->Tenmono_model->getCompanyByEdinetCode($edinet_code);
            
            if(!empty($company)){
                $is_xbrl = TRUE;
                //updateは停止
                //$this->db->where('col_code', $tenmono_company['col_code']);
                //$this->db->update('tab_job_company', $tenmono_company);
                $company_id = $company->_id;
                
            }elseif( isset($tenmono_datas['cdatas'][$edinet_code]['col_income']) &&is_numeric($tenmono_datas['cdatas'][$edinet_code]['col_income']) ){
                $is_xbrl = TRUE;
                echo 'add - '.$tenmono_datas['companies'][$edinet_code]['col_name']."\n";
                $time = time();
                $tenmono_datas['companies'][$edinet_code]['col_edinet_code'] = $edinet_code;
                $tenmono_datas['companies'][$edinet_code]['col_ctime'] = $time;
                $tenmono_datas['companies'][$edinet_code]['col_mtime'] = $time;
                $this->db->insert('tab_job_company', $tenmono_datas['companies'][$edinet_code]);
                $company_id = $this->db->insert_id();
            }else{
                echo $edinet_code."\n";
            }

            //xbrlで登録した企業は年収データ登録
            if( $is_xbrl || ( !empty($company) && $company->is_xbrl == 0 && isset($tenmono_datas['cdatas'][$edinet_code]['col_income']) &&is_numeric($tenmono_datas['cdatas'][$edinet_code]['col_income']) ) ){
                if(isset($tenmono_datas['companies'][$edinet_code]['col_vid'])){
                    $vid  = $tenmono_datas['companies'][$edinet_code]['col_vid'];
                }else{
                    $vid  = 0;
                }
                echo $edinet_code.$tenmono_datas['companies'][$edinet_code]['col_name'];
                
                //code生成 $vid.$cid.$disclosure_time;
                $code = $vid.$company_id.$tenmono_datas['cdatas'][$edinet_code]['col_disclosure'];
                $cdata = $this->Tenmono_model->getCdataByCode($code);

                //edition及びtrend,pace更新
                $old_cdatas = $this->Tenmono_model->getCdatasByCompanyId($company_id,'tab_job_cdata.col_disclosure ASC');//古い順に取得
                $trend = 0;
                if(!empty($old_cdatas)){
                    $before_income = end($old_cdatas)->col_income;
                    if($before_income < $tenmono_datas['cdatas'][$edinet_code]['col_income']){
                        $trend = 1;
                    }elseif($before_income == $tenmono_datas['cdatas'][$edinet_code]['col_income']){
                        $trend = 3;
                    }elseif($before_income > $tenmono_datas['cdatas'][$edinet_code]['col_income']){
                        $trend = 2;
                    }
                }
                $time = time();
                $tenmono_datas['cdatas'][$edinet_code]['col_mtime'] = $time;
                $tenmono_datas['cdatas'][$edinet_code]['col_code'] = $code;
                $tenmono_datas['cdatas'][$edinet_code]['col_vid'] = $vid;
                $tenmono_datas['cdatas'][$edinet_code]['col_cid'] = $company_id;
                $tenmono_datas['cdatas'][$edinet_code]['col_edition'] = 1;//一旦0
                $tenmono_datas['cdatas'][$edinet_code]['col_pace'] = round($tenmono_datas['cdatas'][$edinet_code]['col_income'] / $tenmono_datas['cdatas'][$edinet_code]['col_age'],1);
                $tenmono_datas['cdatas'][$edinet_code]['col_income_trend'] = $trend;
                $tenmono_datas['cdatas'][$edinet_code]['col_income_lifetime'] = $this->_getIncomeLifetime($tenmono_datas['cdatas'][$edinet_code]['col_income'],$tenmono_datas['cdatas'][$edinet_code]['col_age']);

                if(!empty($cdata)){
                    $this->db->where('col_code', $code);
                    $this->db->update('tab_job_cdata', $tenmono_datas['cdatas'][$edinet_code]);
                }else{
                    $tenmono_datas['cdatas'][$edinet_code]['col_ctime'] = $time;
                    $this->db->insert('tab_job_cdata', $tenmono_datas['cdatas'][$edinet_code]);
                }
                
                //過去のedition及びtrend,pace更新
                if(!empty($old_cdatas)){
                    $i = 0;
                    $max_edition = count($old_cdatas)+1;//最新のデータは入っていないので、+1する
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
        //}
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
                }elseif($pathinfo['filename'] == 'manifest_PublicDoc' && $pathinfo['extension'] == 'xml'){//htmlのファイル構造
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
                    $move_xbrl_path = $move_ymd_path.$xbrl_add_path.'/';
                    $xbrl_datas = $this->xbrl_lib->_parseXml($tmp_dir_path . $file);//manifest_PublicDoc.xml
                    /*
                    tmpで処理したい時
                    */
                    //$this->htmls_informations[$dirs[12]] = $this->xbrl_lib->_listedXbrlHtml($xbrl_datas,$tmp_dir_path,$move_xbrl_path);
                    
                    $this->htmls_informations[$dirs[12]] = $this->xbrl_lib->_listedXbrlHtml($xbrl_datas,$move_xbrl_path);
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
        if( is_dir($path) || ( mkdir($path) && chown($path, 'apache') && chgrp($path, 'apache')&& chmod($path,0770) ) )$bl = TRUE;
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
  unset($doc);
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
