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
        //$this->load->model('Category_model');
        $this->load->model('Security_model');
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        $this->load->library('Xbrl_lib');
        //$this->categories = $this->Category_model->getAllcategories();
        $this->income_categories = $this->Tenmono_model->getAllTenmonoCategories();
        $this->data = array();
    }

    /**
     * search area action
     *
     */
    function index($page = 1)
    {
        
        $data['bodyId'] = 'ind';
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $cdatas = $this->Tenmono_model->getCdataOrderDisclosure($page);
        $data['cdatas'] = $cdatas['data'];

        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array(
        'css/income.css'
        )));
        
        //$data['categories'] = $this->categories;
        $data['income_categories'] = $this->income_categories;
        $this->load->view('income/index', array_merge($this->data,$data));
    }

    function show($company_id)
    {
        $data['bodyId'] = 'ind';
        $data['income_side_current'] = 'income_show';
        
        $data['company'] = $this->Tenmono_model->getCompanyByCompanyId($company_id);
        if(empty($data['company']))  show_404();
        
        $data['cdatas'] = $this->Tenmono_model->getCdatasByCompanyId($company_id);
        $first_cdata = reset($data['cdatas']);
        //Higher
        $higher_cdatas = $this->Tenmono_model->getCdatasNotInCompanyIdHighAndLowIncomeByVarietyid($company_id,$first_cdata->col_income,$data['company']->col_vid,'>=','ASC');

        //Lower
        $lower_cdatas = $this->Tenmono_model->getCdatasNotInCompanyIdHighAndLowIncomeByVarietyid($company_id,$first_cdata->col_income,$data['company']->col_vid,'<=','DESC');
        if(!empty($higher_cdatas) || !empty($lower_cdatas)){
            $data['high_and_low_cdatas'] = array_merge(array_reverse($higher_cdatas),$lower_cdatas);
        }

        //文書情報
        $data['documents'] = $this->Document_model->getDocumentsBySecurityCode($data['company']->col_code);

        $data['categories'] = $this->categories;
        $data['income_categories'] = $this->income_categories;
        
        //sns用URL
        $data['sns_url'] = '/income/show/'.$data['company']->_id;
        $data['cdata_download'] = TRUE;
        
        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/income.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/Chart.js')));
        $this->load->view('income/show', array_merge($this->data,$data));
    }

    function download($company_id,$download_string)
    {
        $data['bodyId'] = 'ind';
        if(empty($download_string) || !in_array($download_string,$this->config->item('allowed_download_file_type'))) show_404();
        
        $data['download_string'] = $download_string;
        $data['income_side_current'] = 'income_dl_'.$download_string;
        
        $data['company'] = $this->Tenmono_model->getCompanyByCompanyId($company_id);
        if(empty($data['company']))  show_404();
        
        $data['cdatas'] = $this->Tenmono_model->getCdatasByCompanyId($company_id);
        if(empty($data['cdatas']))  show_404();

        //文書情報
        $data['documents'] = $this->Document_model->getDocumentsBySecurityCode($data['company']->col_code);

        $data['categories'] = $this->categories;
        $data['income_categories'] = $this->income_categories;

        //sns用URL
        $data['sns_url'] = '/income/show/'.$data['company']->_id;

        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
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
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */