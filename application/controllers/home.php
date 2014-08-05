<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->lang->load('setting');
        $this->load->database();
        $this->load->model('Category_model');
        $this->load->model('Presenter_model');
        $this->load->model('Xbrl_model');
        $this->categories = $this->Category_model->getAllcategories();
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
        $xbrlsResult =$this->Xbrl_model->getXbrlsOrder($orderExpression,$page);
        $data['xbrls'] = $xbrlsResult['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        
        $data['pageFormat'] = "{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($xbrlsResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');
        
        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');

        //$this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/jquery.bxslider.css','/css/add.css','css/start/jquery-ui-1.9.2.custom.css','css/datepicker.css')));
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/add.css','css/start/jquery-ui-1.9.2.custom.css','/css/tabulous.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/jquery-1.8.3.js','js/jquery-ui-1.9.2.custom.js')));
        $this->load->view('home/index', array_merge($this->data,$data));
    }

    /**
     * Send email message of given type (activate, forgot_password, etc.)
     *
     * @param    string
     * @param    string
     * @param    array
     * @return    void
     */
    function _send_email($type,$email, &$data)
    {
        $config = array(
            'charset' => 'utf-8',
            'mailtype' => 'text'
        );
        $this->load->library('email',$config);
        $this->email->initialize($config);
        $data['site_name'] = $this->config->item('website_name', 'tank_auth');
        
        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($email);
        $this->email->bcc($this->config->item('webmaster_email', 'tank_auth'));
        $subject = sprintf($this->lang->line($type.'_subject'), $this->config->item('website_name', 'tank_auth'));
        $this->email->subject($subject);
        $this->email->message($this->load->view('email/'.$type.'-txt', $data, TRUE));
        $this->email->send();
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */