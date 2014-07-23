<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Site extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->load->helper('weather');
        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
        $this->lang->load('common');
        $this->lang->load('setting');
        $this->load->model('Region_model');
        $this->load->model('Area_model');
        $this->load->model('Spring_model');
        $this->load->model('Future_model');
        $this->load->library('weather_lib');
        $this->load->library('yahoo_lib');
        $this->data['all_regions'] = $this->Region_model->getAllregions();
        $this->data['all_areas'] = $this->Area_model->getAllAreas();
        $this->data['all_holidays'] = $this->weather_lib->get_holidays_this_month(date("Y",time()));
        $this->data['all_springs'] = $this->Spring_model->getAllSpringsOrderSpringAreaId();
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

    function not_found()
    {
        $data['bodyId'] = 'ind';
        //set header title
        $data['header_title'] = sprintf('%s [%s]', $this->lang->line('common_title_404_error'), $this->lang->line('header_title'));

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), '404 error', $this->lang->line('header_website_name'));

        $this->load->view('site/error_404', array_merge($this->data,$data));
    }
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */