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
        $this->load->model('Tenmono_model');
        $this->load->model('Presenter_model');
        $this->load->model('Document_model');
        $this->categories = $this->Category_model->getAllCategories();
        $this->data = array();
    }

    /**
     * search area action
     *
     */
    function index()
    {
        $data['bodyId'] = 'ind';
        $data['new_categories'] = $this->Document_model->getDocumentsCategoryByDateGroupByCategory(date("Y-m-d H:i:s",strtotime("-7 day")));

        $order = "date";
        $orderExpression = "date DESC";//作成新しい
        $xbrls =$this->Document_model->getDocumentsOrder($orderExpression,1);
        $data['xbrls'] = $xbrls['data'];
        
        $data['categories'] = $this->categories;
        //set header title
        $data['header_title'] = $this->lang->line('header_title');
        $data['header_keywords'] = $this->lang->line('header_keywords');
        $data['header_description'] = $this->lang->line('header_description');
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css','/css/tabulous.css')));
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('http://code.jquery.com/ui/1.9.2/jquery-ui.js','http://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js')));

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