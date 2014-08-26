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
        $this->load->model('Edinet_model');
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        $this->categories = $this->Category_model->getAllCategories();
        $this->data = array();
    }

    function date($date,$page = 1)
    {
        //書式：2012-01-01
        if(0 === preg_match('/^([1-9][0-9]{3})\-(0[1-9]{1}|1[0-2]{1})\-(0[1-9]{1}|[1-2]{1}[0-9]{1}|3[0-1]{1})$/', $date)) show_404();
        
        $data['bodyId'] = 'ind';
        $data['date'] = $date;
        $data['seven_dates'] = $this->Document_model->getDocumentDateGroupByDate();
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $xbrlsResult = $this->Document_model->getDocumentsByDateOrder($date,$orderExpression,$page);

        $data['xbrls'] = $xbrlsResult['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        
        $data['pageFormat'] = "date/{$date}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($xbrlsResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');
        
        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
        
        $data['categories'] = $this->categories;
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/start/jquery-ui-1.9.2.custom.css','/css/tabulous.css')));
        $this->load->view('document/date', array_merge($this->data,$data));
    }

    /**
     * search category action
     *
     */
    function category($category_id,$page = 1)
    {
        $data['bodyId'] = 'ind';
        $data['new_categories'] = $this->Document_model->getDocumentsCategoryByDateGroupByCategory(date("Y-m-d H:i:s",strtotime("-7 day")));


        $order = "date";
        $orderExpression = "date DESC";//作成新しい
        $category_id = intval($category_id);
        $data['category_id'] = $category_id;
        
        if(!isset($data['categories'][$category_id])) show_404();
        
        if($category_id == 1){
            $xbrls =$this->Document_model->getDocumentsOrder($orderExpression,$page);
        }else{
            $xbrls =$this->Document_model->getDocumentsByCategoryIdOrder($category_id,$orderExpression,$page);
        }
        
        $data['xbrls'] = $xbrls['data'];

        $data['page'] = $page;
        $data['pageFormat'] = "document/category/{$category_id}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($xbrls['count']) / intval($this->config->item('paging_count_per_page')));

        $data['categories'] = $this->categories;

        //set header title
        $header_string = $data['categories'][$category_id]->name.'カテゴリの有価証券報告書一覧';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $header_string);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $header_string);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $header_string);
        $this->load->view('document/category', array_merge($this->data,$data));
    }


    function show($document_id,$html_number = 1)//1から
    {
        $data['bodyId'] = 'ind';
        $data['document_side_current'] = 'document_show';
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;

        $data['document'] = $this->Document_model->getDocumentById($document_id);
        if(empty($data['document']))  show_404();
        $data['html_index'] = unserialize($data['document']->html_index_serialize);

        $document_htmls = $this->Document_model->getDocumentHtmlByDocumentId($document_id);
        $data['document_htmls'] = $document_htmls[$html_number];
        
        //その他の文書
        $orderExpression = "created DESC";//作成新しい
        $etc_documents = $this->Document_model->getDocumentsByEdinetIdOrder($data['document']->edinet_id,$orderExpression,1);
        $data['etc_documents'] = $etc_documents['data'];
        
        //tenmonoデータ
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['document']->edinet_id);
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        $data['categories'] = $this->categories;
        
        //sns用URL
        $data['sns_url'] = '/document/show/'.$document_id;
        $data['document_download'] = TRUE;

        //set header title

        $header_string = strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出 ' .$data['edinet']->presenter_name.'の有価証券報告書';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $header_string);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $header_string);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $header_string);

        $this->load->view('document/show', array_merge($this->data,$data));
    }

    function data($document_id)
    {
        $data['bodyId'] = 'ind';
        $data['document_side_current'] = 'document_show';
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;
        $data['document'] = $this->Document_model->getDocumentById($document_id);
        if(empty($data['document']))  show_404();
        $data['document_datas'] = $this->Document_model->getDocumentDataByDocumentId($document_id);

        //その他の文書
        $orderExpression = "created DESC";//作成新しい
        $etc_documents = $this->Document_model->getDocumentsByEdinetIdOrder($data['document']->edinet_id,$orderExpression,1);
        $data['etc_documents'] = $etc_documents['data'];
        
        //tenmonoデータ
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['document']->edinet_id);
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        $data['categories'] = $this->categories;
        
        //sns用URL
        $data['sns_url'] = '/document/show/'.$document_id;
        $data['document_download'] = TRUE;
        
        //set header title
        $header_string = strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出 ' .$data['edinet']->presenter_name.'の有価証券報告書';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $header_string);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $header_string);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $header_string);
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/start/jquery-ui-1.9.2.custom.css','/css/tabulous.css')));
        $this->load->view('document/data', array_merge($this->data,$data));
    }

    function download($document_id,$download_string)
    {
        $data['bodyId'] = 'ind';
        if(empty($download_string) || !in_array($download_string,$this->config->item('allowed_download_file_type'))) show_404();
        
        $data['download_string'] = $download_string;
        $data['document_side_current'] = 'document_dl_'.$download_string;
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;
        $data['document'] = $this->Document_model->getDocumentById($document_id);
        if(empty($data['document']))  show_404();
        $data['document_datas'] = $this->Document_model->getDocumentDataByDocumentId($document_id);

        //その他の文書
        $orderExpression = "created DESC";//作成新しい
        $etc_documents = $this->Document_model->getDocumentsByEdinetIdOrder($data['document']->edinet_id,$orderExpression,1);
        $data['etc_documents'] = $etc_documents['data'];
        
        //tenmonoデータ
        $data['edinet'] = $this->Edinet_model->getEdinetById($data['document']->edinet_id);
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        $data['categories'] = $this->categories;

        //sns用URL
        $data['sns_url'] = '/document/show/'.$document_id;

        //set header title
        if($download_string == 'xlsx'){
            $download_name = 'エクセルファイル';
        }elseif($download_string == 'csv'){
            $download_name = 'CSVファイル';
        }
        $header_string = strftime($this->lang->line('setting_date_format'), strtotime($data['document']->date)).'提出 ' .$data['edinet']->presenter_name.'の'.$data['document']->document_name.$download_name.'をダウンロード';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $header_string);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $header_string);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $header_string);
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/start/jquery-ui-1.9.2.custom.css','/css/tabulous.css')));
        $this->load->view('document/download', array_merge($this->data,$data));
    }

    function prepare($document_id,$download_string)
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
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */