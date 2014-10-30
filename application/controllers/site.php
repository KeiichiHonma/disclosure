<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->lang->load('setting');
        $this->load->config('tank_auth', TRUE);
        $this->load->database();
        $this->load->model('Category_model');
        $this->load->model('Market_model');
        $this->load->model('Tenmono_model');
        $this->load->model('Presenter_model');
        $this->load->model('Document_model');
        $this->load->model('Edinet_model');
        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['markets'] = $this->Market_model->getAllMarkets();
    }

    function contact()
    {
        if(strcasecmp($_SERVER['REQUEST_METHOD'],'POST') === 0){
            $contactData['contact'] = $this->input->post('contact');
            if($contactData['contact'] != ''){
                $this->_send_email('contact', $contactData);
                $this->session->set_flashdata('notify', $this->lang->line('contact_message'));
            }
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * ad page
     *
     */
    function ad()
    {
        $data['bodyId'] = 'ind';

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/site/ad',$this->lang->line('common_title_ad'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_ad'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_ad'));
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $this->lang->line('common_title_ad'));

        $this->load->view('site/ad', array_merge($this->data,$data));
    }

    /**
     * asp page
     *
     */
    function api()
    {
        $data['bodyId'] = 'ind';

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/site/ad',$this->lang->line('common_title_api'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_api'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_api'));
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $this->lang->line('common_title_api'));

        $this->load->view('site/api', array_merge($this->data,$data));
    }

    /**
     * about page
     *
     */
    function about()
    {
        $data['bodyId'] = 'area';

        //確率
        $this->load->model('Odds_model');
        $data['odds'] = $this->Odds_model->getOddsByMaxId();

        $data['topicpaths'][] = array('/',$this->lang->line('topicpath_home'));
        $data['topicpaths'][] = array('/area/',$this->lang->line('topicpath_about'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('topicpath_about'), $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('topicpath_about'));
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $this->lang->line('topicpath_about'));

        $this->load->view('site/about', array_merge($this->data,$data));
    }

    /**
     * issues page
     *
     */
    function issues($page = 1)
    {
        $data['bodyId'] = 'ind';
        $page = intval($page);
        $edinetsResult = $this->Edinet_model->getEdinetsSecuritiesOrder($page*1000);
        $data['chunk_edinets'] = array_chunk($edinetsResult['data'],8);
        $data['page'] = $page;
        $data['pageFormat'] = "site/issues/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($edinetsResult['count']) / intval($this->config->item('paging_count_per_page')));

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/site/ad',$this->lang->line('common_title_issues'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $this->lang->line('common_title_issues'), $this->lang->line('header_website_name'));
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $this->lang->line('common_title_issues'));
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $this->lang->line('common_title_issues'));

        $this->load->view('site/issues', array_merge($this->data,$data));
    }

    function not_found()
    {
        $data['bodyId'] = 'ind';
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_404_error'), $this->lang->line('header_title'));

        $data['topicpaths'][] = array('/',$this->lang->line('common_topicpath_home'));
        $data['topicpaths'][] = array('/',$this->lang->line('common_title_404'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), '404 error', $this->lang->line('header_website_name'));
        $this->output->set_status_header('404');
        $this->load->view('site/error_404', array_merge($this->data,$data));
    }
    function _send_email($type, &$data)
    {
        $data['site_name'] = $this->config->item('website_name', 'tank_auth');
        $config = array(
            'charset' => 'utf-8',
            'mailtype' => 'text'
        );
        $this->load->library('email',$config);
        $this->load->library('email');
        $this->email->from($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->reply_to($this->config->item('webmaster_email', 'tank_auth'), $this->config->item('website_name', 'tank_auth'));
        $this->email->to($this->config->item('webmaster_email', 'tank_auth'));
        $this->email->subject('opendataご意見');
        $this->email->message($this->load->view('email/'.$type.'-txt', $data, TRUE));
        $this->email->send();
    }
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */