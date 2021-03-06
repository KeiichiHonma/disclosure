<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Document extends MY_Controller
{
var $tags = array();
var $values = array();

    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->lang->load('setting');
        $this->load->database();
        $this->load->model('Item_model');
        $this->load->model('Category_model');
        $this->load->model('Market_model');
        $this->load->model('Edinet_model');
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        //$this->data['income_categories'] = $this->Tenmono_model->getAllTenmonoCategories();
        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['markets'] = $this->Market_model->getAllMarkets();
    }

    /**
     * home
     *
     */
    function index()
    {
        $data['bodyId'] = 'ind';
        $data['new_categories'] = $this->Document_model->getDocumentsCategoryByDateGroupByCategory(date("Y-m-d H:i:s",strtotime("-7 day")));
        $year = date("Y",time());
        $order = "date";
        $orderExpression = "date DESC";//作成新しい
        $xbrls =$this->Document_model->getDocumentsOrder($year,$orderExpression,1);
        $data['xbrls'] = $xbrls['data'];
        
        //set header title
        $data['header_title'] = $this->lang->line('header_title');
        $data['header_keywords'] = $this->lang->line('header_keywords');
        $data['header_description'] = $this->lang->line('header_description');
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css','/css/tabulous.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('http://code.jquery.com/ui/1.9.2/jquery-ui.js','http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js')));

        $this->load->view('document/index', array_merge($this->data,$data));
    }

    /**
     * search category action
     *
     */
    function category($category_id, $year = null, $page = 1)
    {
        $data['bodyId'] = 'ind';
        $data['category_id'] = $category_id;
        $data['class_name'] = 'document';
        $data['function_name'] = 'category';
        $data['object_id'] = $category_id;
        
        //$data['new_categories'] = $this->Document_model->getDocumentsCategoryByDateGroupByCategory(date("Y-m-d H:i:s",strtotime("-7 day")));
        $order = "date";
        $orderExpression = "date DESC";//作成新しい
        $category_id = intval($category_id);
        $data['category_id'] = $category_id;
        
        if(!isset($this->data['categories'][$category_id])) show_404();
        
        $data['year'] = is_null($year) ? date("Y",time()) : intval($year);
        if($category_id == 1){
            $documents =$this->Document_model->getDocumentsOrder($data['year'], $orderExpression,$page);
        }else{
            $documents =$this->Document_model->getDocumentsByCategoryIdOrder($category_id,$data['year'],$orderExpression,$page);
        }
        
        $data['documents'] = $documents['data'];
        $data['page'] = $page;
        $data['pageFormat'] = "document/category/{$category_id}/{$data['year']}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($documents['count']) / intval($this->config->item('paging_count_per_page')));

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/document/category/'.$category_id,$this->data['categories'][$category_id]->name.'の'.$this->lang->line('common_title_documents'));

        //set header title
        $data['page_title'] = $this->data['categories'][$category_id]->name.'の'.$this->lang->line('common_title_documents');
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);
        $this->load->view('document/list', array_merge($this->data,$data));
    }

    /**
     * search market action
     *
     */
    function market($market_id, $year = null, $page = 1)
    {
        $data['bodyId'] = 'ind';
        $data['market_id'] = $market_id;
        $data['class_name'] = 'document';
        $data['function_name'] = 'market';
        $data['object_id'] = $data['market_id'];

        $order = "date";
        $orderExpression = "date DESC";//作成新しい
        $market_id = intval($market_id);
        $data['market_id'] = $market_id;
        if(!isset($this->data['markets'][$market_id])) show_404();
        $documents =$this->Document_model->getDocumentsByMarketIdOrder($market_id,$data['year'],$orderExpression,$page);
        
        $data['documents'] = $documents['data'];
        $data['page'] = $page;
        $data['pageFormat'] = "document/market/{$market_id}/{$data['year']}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($documents['count']) / intval($this->config->item('paging_count_per_page')));

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/document/market/'.$market_id,$this->data['markets'][$market_id]->name.'の'.$this->lang->line('common_title_documents'));

        //set header title
        $data['page_title'] = $this->data['markets'][$market_id]->name.'の'.$this->lang->line('common_title_documents');
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);
        $this->load->view('document/list', array_merge($this->data,$data));
    }


    function company($presenter_name_key = '',$page = 1)
    {
        $data['bodyId'] = 'ind';
        if(empty($presenter_name_key))  show_404();
        $data['edinet'] = $this->Edinet_model->getEdinetByPresenterNameKey($presenter_name_key);
        if(empty($data['edinet']))  show_404();
        
        $data['switch_side_current'] = 'document_company';
        
        //文書一覧
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $documentsResult = $this->Document_model->getDocumentsByEdinetIdOrder($data['edinet']->id,$orderExpression,$page);
        $data['documents'] = $documentsResult['data'];

        $data['order'] = $order;
        $data['pageFormat'] = "document/company/{$presenter_name_key}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($documentsResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');

        //tenmonoデータ
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['edinet']->id);
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        //sns用URL
        $data['sns_url'] = '/document/company/'.$presenter_name_key;

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/document/category/'.$data['edinet']->category_id,$this->data['categories'][$data['edinet']->category_id]->name.'の'.$this->lang->line('common_title_documents'));
        $data['topicpaths'][] = array('/document/company/'.$data['edinet']->presenter_name_key,$data['edinet']->presenter_name.'の'.$this->lang->line('common_title_documents'));

        $data['page_title'] = $data['edinet']->presenter_name.'の'.$this->lang->line('common_title_documents');
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/start/jquery-ui-1.9.2.custom.css','/css/tabulous.css')));
        $this->load->view('document/company', array_merge($this->data,$data));
    }


    function iframe($document_id,$target_html_number = 0)//1から
    {
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;

        $data['document'] = $this->Document_model->getDocumentById($document_id);
        if(empty($data['document'])) die();

        //企業情報
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['document']->edinet_id);
        $data['html_index'] = unserialize($data['document']->html_index_serialize);

        $document_htmls = $this->Document_model->getDocumentHtmlByDocumentId($document_id);

        //htmlがないため、最新の有価証券のHTMLへ飛ばす
        if(empty($document_htmls)){
            $is_htmnl_documents = $this->Document_model->getDocumentsByEdinetIdByIsHtml($data['document']->edinet_id);
            if(!empty($is_htmnl_documents)){
                die();
            }else{
                die();
            }
        }
        $data['document_htmls'] = $document_htmls[$target_html_number];
        $data['target_html_number'] = $target_html_number;
        $this->load->view('document/iframe', array_merge($this->data,$data));
    }
    
    function show($document_id,$target_html_number = 0)//1から
    {
        $data['bodyId'] = 'ind';
        $data['pageId'] = 'document_show';
        $data['document_side_current'] = 'document_show';
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;

        $data['document'] = $this->Document_model->getDocumentById($document_id);
        if(empty($data['document']))  show_404();
        
        $data['switch_side_current'] = 'document_company';
        $data['document_tab_current'] = 'document_show';
        
        //企業情報
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['document']->edinet_id);
        $data['html_index'] = unserialize($data['document']->html_index_serialize);

        //$document_htmls = $this->Document_model->getDocumentHtmlByDocumentId($document_id);

        //htmlがないため、最新の有価証券のHTMLへ飛ばす
/*
        if(empty($document_htmls)){
            $is_htmnl_documents = $this->Document_model->getDocumentsByEdinetIdByIsHtml($data['document']->edinet_id);
            if(!empty($is_htmnl_documents)){
                redirect("document/show/".reset($is_htmnl_documents)->id);
            }else{
                show_404();
            }
        }

        $data['document_htmls'] = $document_htmls[$target_html_number];
*/
        $data['target_html_number'] = $target_html_number;

        //tenmonoデータ
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        //sns用URL
        $data['sns_url'] = '/document/show/'.$document_id;
        $data['document_download'] = TRUE;

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/document/category/'.$data['edinet']->category_id,$this->data['categories'][$data['edinet']->category_id]->name.'の'.$this->lang->line('common_title_documents'));
        $data['topicpaths'][] = array('/document/company/'.$data['edinet']->presenter_name_key,$data['edinet']->presenter_name.'の'.$this->lang->line('common_title_documents'));
        $data['topicpaths'][] = array('/document/show/'.$data['document']->id,strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出-' .$this->lang->line('common_title_document'));

        //set header title
        $data['page_title'] = strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出-' .$data['edinet']->presenter_name.'の'.$this->lang->line('common_title_document');
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/tab.css')));
        $this->load->view('document/show', array_merge($this->data,$data));
    }

    function data($document_id)
    {
        $data['bodyId'] = 'ind';
        $data['document_tab_current'] = 'document_data';
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;
        $data['document'] = $this->Document_model->getDocumentById($document_id);
        if(empty($data['document']))  show_404();
        
        $data['switch_side_current'] = 'document_company';
        
        $data['document_datas'] = $this->Document_model->getDocumentDataByDocumentId($document_id);
        
        //tenmonoデータ
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['document']->edinet_id);
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        //sns用URL
        $data['sns_url'] = '/document/show/'.$document_id;
        $data['document_download'] = TRUE;
        
        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/document/category/'.$data['edinet']->category_id,$this->data['categories'][$data['edinet']->category_id]->name.'の'.$this->lang->line('common_title_documents'));
        $data['topicpaths'][] = array('/document/company/'.$data['edinet']->presenter_name_key,$data['edinet']->presenter_name.'の'.$this->lang->line('common_title_documents'));
        if($data['document']->is_html == 0){
            $data['page_title'] = strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出-'.$data['edinet']->presenter_name.'の'.$this->lang->line('common_title_document').'数値データ';
            $data['topicpaths'][] = array('/document/show/'.$data['document']->id,strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出-'.$this->lang->line('common_title_document'));
            $data['topicpaths'][] = array('/document/data/'.$data['document']->id,'数値データ');
        }else{
            $data['page_title'] = $data['edinet']->presenter_name.'の'.$this->lang->line('common_title_document');
            $data['topicpaths'][] = array('/document/data/'.$data['document']->id,$data['page_title']);
        }
        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);

        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/tab.css')));
        $this->load->view('document/data', array_merge($this->data,$data));
    }

    function download($document_id,$download_string)
    {
        $data['bodyId'] = 'ind';
        if(empty($download_string) || !in_array($download_string,$this->config->item('allowed_download_file_type'))) show_404();
        
        $data['download_string'] = $download_string;
        $data['document_tab_current'] = 'document_dl_'.$download_string;
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;
        $data['document'] = $this->Document_model->getDocumentById($document_id);
        if(empty($data['document']))  show_404();
        
        $data['switch_side_current'] = 'document_company';
        
        //tenmonoデータ
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['document']->edinet_id);
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        //sns用URL
        $data['sns_url'] = '/document/show/'.$document_id;

        //set header title
        if($download_string == 'xlsx'){
            $download_name = 'エクセルファイル';
        }elseif($download_string == 'csv'){
            $download_name = 'CSVファイル';
        }

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/document/category/'.$data['edinet']->category_id,$this->data['categories'][$data['edinet']->category_id]->name.'の'.$this->lang->line('common_title_documents'));
        $data['topicpaths'][] = array('/document/company/'.$data['edinet']->presenter_name_key,$data['edinet']->presenter_name.'の'.$this->lang->line('common_title_documents'));

        if($data['document']->is_html == 0){
            $data['topicpaths'][] = array('/document/show/'.$data['document']->id,strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出-'.$this->lang->line('common_title_document'));
            $data['topicpaths'][] = array('/document/data/'.$data['document']->id,'数値データ');
        }else{
            $data['topicpaths'][] = array('/document/data/'.$data['document']->id,$data['page_title']);
        }
        $data['topicpaths'][] = array('/document/download/'.$data['document']->id.'/'.$download_string,$download_name.'ダウンロード');

        $data['page_title'] = strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出-' .$data['edinet']->presenter_name.'の'.$data['document']->document_name.$download_name.'をダウンロード';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/tab.css')));
        $this->load->view('document/download', array_merge($this->data,$data));
    }

    function prepare($document_id,$download_string)
    {
        $index = array_search($download_string,$this->config->item('allowed_download_file_type'));
        if(empty($download_string) || $index === FALSE) show_404();
        $document = $this->Document_model->getDocumentById($document_id);
        if(empty($document))  show_404();
        $document_datas = $this->Document_model->getDocumentDataByDocumentId($document_id);
        $this->export_document_data($document,$document_datas,$download_string);
die();
/*
        $path = $document->format_path.'.'.$download_string;
        if(!is_file($path))  show_404();
        $data = array(
            'edinet_id' => $document->edinet_id,
            'document_id' => $document->id,
            'type' => $index,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert('document_downloads', $data);
        $this->db->insert_id();

        $data = file_get_contents($path); // ファイルの内容を読み取る
*/
        $this->load->helper('download');
        force_download(end(explode('/',$document->format_path)).".".$download_string, $data);
    }

    // ----------------------------------------------------------------
    // CSV出力 
    // ----------------------------------------------------------------
    function put_csv($document_datas) {
        $csv_path = $document->format_path.'.csv';
        
        foreach ($document_datas as $document_data){
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
        }
        $this->load->helper('download');
        force_download($csv_path, $byte);
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
    private $pattern = array('A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    private $replacement = array(' A',' B',' C',' D',' E',' F',' G',' H',' J',' K',' L',' M',' N',' O',' P',' Q',' R',' S',' T',' U',' V',' W',' X',' Y',' Z');
    function export_document_data($document,$document_datas,$download_string) {
        if($download_string == 'xlsx'){
            $is_excel = TRUE;
        }else{
            $is_excel = FALSE;
        }
        //path
        $paths = explode('/',$document->format_path);
        $file_name = end($paths);
        if($is_excel){
            $this->load->library('PHPExcel');;
            $objPHPExcel = null;
            // 新規作成の場合
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->getDefaultStyle()->getFont()->setName( 'ＭＳ Ｐゴシック' )->setSize( 11 );
            
            // 0番目のシートをアクティブにする（シートは左から順に、0、1，2・・・）
            $objPHPExcel->setActiveSheetIndex(0);
            // アクティブにしたシートの情報を取得
            $objSheet = $objPHPExcel->getActiveSheet();
            // シート名を変更する
            $objSheet->setTitle($file_name);
        }else{
            $toEncoding='SJIS-win';
            $srcEncoding='UTF-8';
        }
        
        $excel_tate = 1;
        $csv = '';
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
            if($is_excel){
                //各値
                is_numeric($name) ? $objSheet->setCellValue('A'.$excel_tate, $name) : $objSheet->setCellValue('A'.$excel_tate, str_replace('"', '""', $name));
                is_numeric($document_data->context_period) ? $objSheet->setCellValue('B'.$excel_tate, $document_data->context_period) : $objSheet->setCellValue('B'.$excel_tate, str_replace('"', '""', $document_data->context_period));
                is_numeric($document_data->context_consolidated) ? $objSheet->setCellValue('C'.$excel_tate, $document_data->context_consolidated) : $objSheet->setCellValue('C'.$excel_tate, str_replace('"', '""', $document_data->context_consolidated));
                is_numeric($document_data->context_term) ? $objSheet->setCellValue('D'.$excel_tate, $document_data->context_term) : $objSheet->setCellValue('D'.$excel_tate, str_replace('"', '""', $document_data->context_term));
                is_numeric($document_data->unit) ? $objSheet->setCellValue('E'.$excel_tate, $document_data->unit) : $objSheet->setCellValue('E'.$excel_tate, str_replace('"', '""', $document_data->unit));
            }else{
                $csv .= is_numeric($name) ? $name : '"' . str_replace('"', '""', mb_convert_encoding($name, $toEncoding, $srcEncoding)) . '",';
                $csv .= is_numeric($document_data->context_period) ? $document_data->context_period : '"' . str_replace('"', '""', mb_convert_encoding($document_data->context_period, $toEncoding, $srcEncoding)) . '",';
                $csv .= is_numeric($document_data->context_consolidated) ? $document_data->context_consolidated : '"' . str_replace('"', '""', mb_convert_encoding($document_data->context_consolidated, $toEncoding, $srcEncoding)) . '",';
                $csv .= is_numeric($document_data->context_term) ? $document_data->context_term : '"' . str_replace('"', '""', mb_convert_encoding($document_data->context_term, $toEncoding, $srcEncoding)) . '",';
                $csv .= is_numeric($document_data->unit) ? $document_data->unit : '"' . str_replace('"', '""', mb_convert_encoding($document_data->unit, $toEncoding, $srcEncoding)) . '",';
            }

            
            //実際の値
            if( $document_data->text_data != '' ){
                $value = $document_data->text_data;
            }elseif ($document_data->mediumtext_data != ''){
                $value = $document_data->mediumtext_data;
            }elseif (is_numeric($document_data->int_data)){
                $value = $document_data->int_data;
            }else{
                $value = '';
            }
            if($is_excel){
                is_numeric($value) ? $objSheet->setCellValue('F'.$excel_tate, $value) : $objSheet->setCellValue('F'.$excel_tate, str_replace('"', '""', $value));
            }else{
                $csv .= is_numeric($value) ? $value : '"' . str_replace('"', '""', mb_convert_encoding($value, $toEncoding, $srcEncoding)) . '"';
                $csv .= "\r\n";
            }
            $excel_tate++;
        }
        if($is_excel){
            // IOFactory.phpを利用する場合
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            //$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.$file_name.'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            unset($objWriter);
            unset($objPHPExcel);
        }else{
            $this->load->helper('download');
            force_download($file_name.'.csv', $csv);
        }
    }
    
    function _prepare($document_id,$download_string)
    {
        $index = array_search($download_string,$this->config->item('allowed_download_file_type'));
        if(empty($download_string) || $index === FALSE) show_404();
        $document = $this->Document_model->getDocumentById($document_id);
        if(empty($document))  show_404();

        $path = $document->format_path.'.'.$download_string;
        if(!is_file($path))  show_404();
        $data = array(
            'edinet_id' => $document->edinet_id,
            'document_id' => $document->id,
            'type' => $index,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert('document_downloads', $data);
        $this->db->insert_id();

        $data = file_get_contents($path); // ファイルの内容を読み取る
        
        $this->load->helper('download');
        force_download(end(explode('/',$document->format_path)).".".$download_string, $data);
    }

    function _date($date,$page = 1)
    {
        //書式：2012-01-01
        if(0 === preg_match('/^([1-9][0-9]{3})\-(0[1-9]{1}|1[0-2]{1})\-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        $data['bodyId'] = 'ind';
        $data['date'] = $date;
        $data['seven_dates'] = $this->Document_model->getDocumentDateGroupByDate();
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $documentsResult = $this->Document_model->getDocumentsByDateOrder($date,$orderExpression,$page);

        $data['documents'] = $documentsResult['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        
        $data['pageFormat'] = "date/{$date}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($documentsResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');
        
        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/start/jquery-ui-1.9.2.custom.css','/css/tabulous.css')));
        $this->load->view('document/date', array_merge($this->data,$data));
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */