<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Income extends MY_Controller
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
        $this->load->model('Edinet_model');
        $this->load->model('Category_model');
        $this->load->model('Market_model');
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        $this->data['income_categories'] = $this->Tenmono_model->getAllTenmonoCategories();
        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['markets'] = $this->Market_model->getAllMarkets();
    }

    /**
     * search area action
     *
     */
    function index($page = 1)
    {
        $data['bodyId'] = 'ind';
        $order = "disclosure";
        $orderExpression = "col_disclosure DESC";//公開日
        $data['year'] = date("Y",time());
        $cdatas = $this->Tenmono_model->getCdataOrder($data['year'],$orderExpression,$page);
        $data['cdatas'] = $cdatas['data'];
        $data['is_index'] = TRUE;
        //set header title
        $data['page_title'] = '企業年収速報';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);
        
        $this->load->view('income/list', array_merge($this->data,$data));
    }

    /**
     * search category action
     *
     */
    function category($category_id, $year = null, $order = null, $page = 1)
    {
        $category_id = intval($category_id);
        if(!isset($this->data['categories'][$category_id])) show_404();
        $data['bodyId'] = 'ind';
        $data['category_id'] = $category_id;
        $data['page_name'] = 'category';
        $data['object_id'] = $category_id;
        
        if(is_null($year) && is_null($order)){
            $order = "disclosure";
            $orderExpression = "col_disclosure DESC";//公開日
        }else{
            list($order,$orderExpression) = $this->_set_order($order);
        }
        $data['year'] = is_null($year) ? date("Y",time()) : intval($year);
        
        if($category_id == 1){//全体
            $cdatas =$this->Tenmono_model->getCdataOrder($data['year'],$orderExpression,$page);
        }else{
            $cdatas =$this->Tenmono_model->getCdataByCategoryIdOrderDisclosure($category_id,$data['year'],$orderExpression,$page);
        }
        
        $data['cdatas'] = $cdatas['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "income/category/{$category_id}/{$data['year']}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($cdatas['count']) / intval($this->config->item('paging_count_per_page')));

        //set header title
        $data['page_title'] = $category_id != 1 ? $data['year'].'年 '.$this->data['income_categories'][$category_id]->col_name.'業界の企業年収' : $data['year'].'年の企業年収';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);
        
        $this->load->view('income/list', array_merge($this->data,$data));
    }

    /**
     * search market action
     *
     */
    function market($market_id, $year = null, $order = null, $page = 1)
    {
        $market_id = intval($market_id);
        if(!isset($this->data['markets'][$market_id])) show_404();
        $data['bodyId'] = 'ind';
        $data['market_id'] = $market_id;
        $data['page_name'] = 'market';
        $data['object_id'] = $data['market_id'];
        
        if(is_null($year) && is_null($order)){
            $order = "disclosure";
            $orderExpression = "col_disclosure DESC";//公開日
        }else{
            list($order,$orderExpression) = $this->_set_order($order);
        }
        $data['year'] = is_null($year) ? date("Y",time()) : intval($year);
        $cdatas =$this->Tenmono_model->getCdataByMarketIdOrderDisclosure($market_id,$data['year'],$orderExpression,$page);
        
        $data['cdatas'] = $cdatas['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "income/market/{$market_id}/{$data['year']}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($cdatas['count']) / intval($this->config->item('paging_count_per_page')));

        //set header title
        $data['page_title'] = $market_id != 1 ? $data['year'].'年 '.$this->data['markets'][$market_id]->name.'の企業年収' : $data['year'].'年の企業年収';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);
        
        $this->load->view('income/list', array_merge($this->data,$data));
    }

    function show($presenter_name_key = '')
    {
        $data['bodyId'] = 'ind';
        $data['switch_side_current'] = 'income_show';
        if(empty($presenter_name_key))  show_404();
        $data['edinet'] = $this->Edinet_model->getEdinetByPresenterNameKey($presenter_name_key);
        if(empty($data['edinet']))  show_404();
        
        $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);
        if(empty($data['company']))  show_404();
        
        $data['switch_side_current'] = 'income_show';
        
        $data['cdatas'] = $this->Tenmono_model->getCdatasByCompanyId($data['company']->_id);
        $first_cdata = reset($data['cdatas']);

        //Higher
        $higher_cdatas = $this->Tenmono_model->getCdatasNotInCompanyIdHighAndLowIncomeByVarietyid($data['company']->_id,$first_cdata->col_income,$data['company']->col_vid,'>=','ASC');

        //Lower
        $lower_cdatas = $this->Tenmono_model->getCdatasNotInCompanyIdHighAndLowIncomeByVarietyid($data['company']->_id,$first_cdata->col_income,$data['company']->col_vid,'<=','DESC');
        if(!empty($higher_cdatas) || !empty($lower_cdatas)){
            $data['high_and_low_cdatas'] = array_merge(array_reverse($higher_cdatas),$lower_cdatas);
        }

        //文書情報
        $data['documents'] = $this->Document_model->getDocumentsByEdinetId($data['edinet']->id);

        //sns用URL
        $data['sns_url'] = '/income/show/'.$presenter_name_key;
        $data['cdata_download'] = TRUE;
        
        //set header title
        $header_string = strftime($this->lang->line('setting_date_format'), $first_cdata->col_disclosure).'公開 ' .$data['edinet']->presenter_name.'の年収';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $header_string);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $header_string);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $header_string);
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/Chart.js')));
        $this->load->view('income/show', array_merge($this->data,$data));
    }

    function download($presenter_name_key = '',$download_string)
    {
        $data['bodyId'] = 'ind';
        if(empty($download_string) || !in_array($download_string,$this->config->item('allowed_download_file_type'))) show_404();

        $data['download_string'] = $download_string;
        $data['income_side_current'] = 'income_dl_'.$download_string;

        if(empty($presenter_name_key))  show_404();
        $data['edinet'] = $this->Edinet_model->getEdinetByPresenterNameKey($presenter_name_key);
        if(empty($data['edinet']))  show_404();

        $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);
        if(empty($data['company']))  show_404();
        
        $data['cdatas'] = $this->Tenmono_model->getCdatasByCompanyId($data['company']->_id);
        if(empty($data['cdatas']))  show_404();
        $first_cdata = reset($data['cdatas']);
        
        //文書情報
        $data['documents'] = $this->Document_model->getDocumentsByEdinetId($data['edinet']->id);

        //sns用URL
        $data['sns_url'] = '/income/show/'.$presenter_name_key;

        //set header title
        if($download_string == 'xlsx'){
            $download_name = 'エクセルファイル';
        }elseif($download_string == 'csv'){
            $download_name = 'CSVファイル';
        }
        $header_string = strftime($this->lang->line('setting_date_format'), $first_cdata->col_disclosure).'提出 ' .$data['edinet']->presenter_name.'の年収'.$download_name.'をダウンロード';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $header_string);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $header_string);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $header_string);
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/start/jquery-ui-1.9.2.custom.css','/css/tabulous.css')));
        $this->load->view('income/download', array_merge($this->data,$data));
    }

    function prepare($company_id,$download_string)
    {
        $index = array_search($download_string,$this->config->item('allowed_download_file_type'));

        if(empty($download_string) || $index === FALSE) show_404();
        $company = $this->Tenmono_model->getCompanyByCompanyId($company_id);
        if(empty($company))  show_404();

        $cdatas = $this->Tenmono_model->getCdatasByCompanyId($company_id);
        if(empty($cdatas))  show_404();

        $data = array(
            'company_id' => $company->_id,
            'type' => $index,
            'ip' => $_SERVER['REMOTE_ADDR'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert('company_downloads', $data);
        $this->db->insert_id();
        $filename = $company->col_code.'_'.strftime($this->lang->line('setting_date_under_score_format'), reset($cdatas)->col_disclosure).'.'.$download_string;
        if($download_string == 'csv'){
            $data = "公開日,年収,従業員数,平均年齢,平均勤続年数\n";
            foreach ($cdatas as $cdata){
                $data .= strftime($this->lang->line('setting_date_format'), $cdata->col_disclosure).','.$cdata->col_income.','.$cdata->col_person.','.$cdata->col_age.','.$cdata->col_employ."\n";
            }
            $data = mb_convert_encoding($data,"SJIS-win","UTF-8");
            $this->load->helper('download');
            force_download($filename, $data);
        }elseif($download_string == 'xlsx'){
            //format合わせ
            $excel_map[0] = 0;
            $csv_datas[0][] = array('公開日','年収','従業員数','平均年齢','平均勤続年数');
            foreach ($cdatas as $cdata){
                $csv_datas[0][] = array(strftime($this->lang->line('setting_date_format'), $cdata->col_disclosure),$cdata->col_income,$cdata->col_person,$cdata->col_age,$cdata->col_employ);
            }
            $objWriter = $this->xbrl_lib->put_excel(null,$csv_datas,$filename,$excel_map);
            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Content-Type: application/force-download");
            header("Content-Type: application/octet-stream");
            header("Content-Type: application/download");
            header("Content-Disposition: attachment;filename=".$filename);
            header("Content-Transfer-Encoding: binary ");
            $objWriter->save('php://output');
        }else{
            show_404();
        }
    }
    function _set_order($order){
        if ($order == "disclosure") {
            $orderExpression = "col_disclosure DESC";//公開日
        } else if ($order == "disclosureRev") {
            $orderExpression = "col_disclosure ASC";
        } else if ($order == "income") {//年収
            $orderExpression = "col_income ASC";
        } else if ($order == "incomeRev") {//年収
            $orderExpression = "col_income ASC";
        }else{
            $order = "disclosure";
            $orderExpression = "col_disclosure DESC";//公開日
        }
        return array($order,$orderExpression);
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */