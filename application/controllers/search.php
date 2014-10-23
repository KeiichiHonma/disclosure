<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller
{
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
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        $this->data['income_categories'] = $this->Tenmono_model->getAllTenmonoCategories();
        $this->data['categories'] = $this->Category_model->getAllCategories();
    }

    /**
     * search keyword action
     *
     */
    function keyword($order = "modified", $page = 1)
    {
        $data['bodyId'] = 'ind';
        $keywords = $this->input->get('keyword');
        $keywords = preg_replace('/@+/', ' ', mb_convert_kana($keywords, 's'));
        $keywords = array_filter(explode(' ', $keywords), 'strlen');
        $page = intval($page);
        $orderExpression = "tab_job_cdata.col_disclosure DESC";//作成新しい

        $this->load->model('Edinet_model');
        $edinetsResult = $this->Edinet_model->getEdinetsByKeywords($keywords, $page, $orderExpression);

        $data['search_keywords'] = implode($keywords, ' ');
        $data['edinets'] = $edinetsResult['data'];
        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "search/keyword/{$order}/%d";
        $data['searchPageFormat'] = '?keyword='.urlencode($data['search_keywords']);
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($edinetsResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');

        //set header title
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['search_keywords']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['search_keywords']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['search_keywords']);

        $this->load->view('search/keyword', array_merge($this->data,$data));
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */