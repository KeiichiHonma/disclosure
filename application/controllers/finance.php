<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Finance extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->helper('html');
        $this->load->helper(array('form', 'url'));
        $this->load->helper('image');
        $this->load->helper('chart');
        $this->lang->load('setting');
        $this->load->database();
        $this->load->model('Edinet_model');
        $this->load->model('Category_model');
        $this->load->model('Market_model');
        $this->load->model('Document_model');
        $this->load->model('Tenmono_model');
        $this->load->model('Finance_model');
        $this->data['income_categories'] = $this->Tenmono_model->getAllTenmonoCategories();
        $this->data['categories'] = $this->Category_model->getAllCategories();
        $this->data['markets'] = $this->Market_model->getAllMarkets();
    }
    private $types = array('pl','bs','cf');
    /**
     * ranking action
     *
     */
    function ranking($type='bs', $year = null, $order = null, $page = 1)
    {
        if(!in_array($type,$this->types)) show_404();
        $data['bodyId'] = 'ind';
        $page = intval($page);
        
        if(is_null($year) && is_null($order)){
            $order = "date";
            $orderExpression = "date DESC";//作成新しい
        }else{
            list($order,$orderExpression) = $this->_set_order($type,$order);
        }

        $data['type'] = $type;
        $data['year'] = $year;
        $financesResult = $this->Finance_model->getFinancesOrder($data['year'], $orderExpression, $page);

        $data['finances'] = $financesResult['data'];

        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "finance/ranking/{$type}/{$year}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($financesResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');

        //set header title
        $data['page_title'] = $data['year'].'年'.$this->lang->line('common_title_'.$type);
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);

        $this->load->view('finance/ranking', array_merge($this->data,$data));
    }

    /**
     * ranking action
     *
     */
    function category($category_id,$type='bs', $year = null, $order = "date", $page = 1)
    {
        if(!in_array($type,$this->types)) show_404();
        $data['bodyId'] = 'ind';
        $page = intval($page);
        if ($order == "date") {
            $order = "date";
            $orderExpression = "date DESC";//作成新しい
        } else if ($order == "modifiedRev") {
            $orderExpression = "modified ASC";
        } else if ($order == "created") {
            $orderExpression = "created DESC";
        } else if ($order == "createdRev") {
            $orderExpression = "created ASC";
        } else {
            $order = "modified";
            $orderExpression = "date DESC";//作成新しい
        }
        
        $data['category_id'] = $category_id;
        $data['type'] = $type;
        $data['year'] = $year;
        $financesResult = $this->Finance_model->getFinancesOrderByCategoryId($category_id,$data['year'], $orderExpression, $page);

        $data['finances'] = $financesResult['data'];

        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "finance/category/{$category_id}/{$type}/{$year}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($financesResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');

        //set header title
        $data['page_title'] = $this->data['categories'][$category_id]->name.' - '.$data['year'].'年'.$this->lang->line('common_title_'.$type);
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);

        $this->load->view('finance/ranking', array_merge($this->data,$data));
    }

    /**
     * market action
     *
     */
    function market($market_id,$type='bs', $year = null, $order = "date", $page = 1)
    {
        if(!in_array($type,$this->types)) show_404();
        $data['bodyId'] = 'ind';
        $page = intval($page);
        if ($order == "date") {
            $order = "date";
            $orderExpression = "date DESC";//作成新しい
        } else if ($order == "modifiedRev") {
            $orderExpression = "modified ASC";
        } else if ($order == "created") {
            $orderExpression = "created DESC";
        } else if ($order == "createdRev") {
            $orderExpression = "created ASC";
        } else {
            $order = "modified";
            $orderExpression = "date DESC";//作成新しい
        }
        $data['category_id'] = $category_id;
        $data['type'] = $type;
        $data['year'] = $year;
        $financesResult = $this->Finance_model->getFinancesOrderByMarketId($market_id,$data['year'], $orderExpression, $page);

        $data['finances'] = $financesResult['data'];

        $data['page'] = $page;
        $data['order'] = $order;
        $data['pageFormat'] = "finance/market/{$market_id}/{$type}/{$year}/{$order}/%d";
        $data['rowCount'] = intval($this->config->item('paging_row_count'));
        $data['columnCount'] = intval($this->config->item('paging_column_count'));
        $data['pageLinkNumber'] = intval($this->config->item('page_link_number'));//表示するリンクの数 < 2,3,4,5,6 >
        $data['maxPageCount'] = (int) ceil(intval($financesResult['count']) / intval($this->config->item('paging_count_per_page')));
        $data['orderSelects'] = $this->lang->line('order_select');

        //set header title
        $data['page_title'] = $this->data['markets'][$market_id]->name.' - '.$data['year'].'年'.$this->lang->line('common_title_'.$type);
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $data['page_title']);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $data['page_title']);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $data['page_title']);

        $this->load->view('finance/ranking', array_merge($this->data,$data));
    }

    function show($presenter_name_key = '',$type='top')
    {
        $data['bodyId'] = 'ind';
        $data['switch_side_current'] = 'finance_show';
        $data['finance_tab_current'] = $type;
        $data['type'] = $type;
        
        if(empty($presenter_name_key))  show_404();
        $data['edinet'] = $this->Edinet_model->getEdinetByPresenterNameKey($presenter_name_key);
        if(empty($data['edinet']))  show_404();
        
        $desc_asc = 'ASC';
        $data['from_year'] = date("Y",time()) - 5;//5年分
        $data['finances'] = $this->Finance_model->getFinancesByEdinetId($data['edinet']->id,$data['from_year'],'date '.$desc_asc);

        //その他の文書
        $orderExpression = "created DESC";//作成新しい
        $etc_documents = $this->Document_model->getDocumentsByEdinetIdOrder($data['edinet']->id,$orderExpression,1);
        $data['etc_documents'] = $etc_documents['data'];
        
        //tenmonoデータ
        if($data['edinet']->security_code > 0) $data['company'] = $this->Tenmono_model->getCompanyBySecurityCode($data['edinet']->security_code);

        //sns用URL
        $data['sns_url'] = '/finance/show/'.$presenter_name_key;

        //グラフデータ
        if($type != 'top'){
            $reverse_finances = $data['finances'];
            $data['graphs'] = array
            (
                'bs'=>array('assets','liabilities'),
                'pl'=>array('net_sales','net_income'),
                'cf'=>array('depreciation_and_amortization','net_increase_decrease_in_cash_and_cash_equivalents'),
            );
            $data['target_values'] = array();
            $data['target_years'] = array();
            foreach ($data['graphs'][$type] as $target){
                foreach ($reverse_finances as $reverse_finance){
                    $data['target_values'][$target][] = $reverse_finance->$target;
                    $data['target_years'][$target][] = strftime("%Y", strtotime($reverse_finance->date));
                }
            }
        }


        //set header title
        $header_string = $data['edinet']->presenter_name.'のファイナンス情報';
        $data['header_title'] = sprintf($this->lang->line('common_header_title'), $header_string);
        $data['header_keywords'] = sprintf($this->lang->line('common_header_keywords'), $header_string);
        $data['header_description'] = sprintf($this->lang->line('common_header_description'), $header_string);
        
        $this->config->set_item('javascripts', array_merge($this->config->item('javascripts'), array('js/Chart.js')));
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('/css/tab.css')));
        $this->load->view('finance/show', array_merge($this->data,$data));
    }
    
    function _set_order($type,$order){
        if ($order == "date") {
            $orderExpression = "date DESC";//作成新しい
        }elseif ($order == "dateRev") {
            $orderExpression = "date ASC";
        }
        if($type == 'pl'){
            if ($order == "net-sales") {
                $orderExpression = "net_sales DESC";//売上高
            } else if ($order == "net-salesRev") {
                $orderExpression = "net_sales ASC";
            } else if ($order == "cost-of-sales") {
                $orderExpression = "cost_of_sales DESC";//売上原価
            } else if ($order == "cost-of-salesRev") {
                $orderExpression = "cost_of_sales ASC";
            } else if ($order == "gross-profit") {
                $orderExpression = "gross_profit DESC";//売上総利益
            } else if ($order == "gross-profitRev") {
                $orderExpression = "gross_profit ASC";
            } else if ($order == "operating-income") {
                $orderExpression = "operating_income DESC";//営業利益
            } else if ($order == "operating-incomeRev") {
                $orderExpression = "operating_income ASC";
            } else if ($order == "ordinary-income") {
                $orderExpression = "ordinary_income DESC";//経常利益
            } else if ($order == "ordinary-incomeErv") {
                $orderExpression = "ordinary_income ASC";
            } else if ($order == "extraordinary-total") {
                $orderExpression = "extraordinary_total DESC";//特別損益収支
            } else if ($order == "extraordinary-totalRev") {
                $orderExpression = "extraordinary_total ASC";
            } else if ($order == "net-income") {
                $orderExpression = "net_income DESC";//当期純利益
            } else if ($order == "net-incomeRev") {
                $orderExpression = "net_income ASC";
            }else{
                $order = "net-sales";
                $orderExpression = "net_sales DESC";//売上高
            }
        }elseif($type == 'bs'){
            if ($order == "assets") {
                $orderExpression = "assets DESC";//資産
            } else if ($order == "assetsRev") {
                $orderExpression = "assets ASC";
            } else if ($order == "liabilities") {
                $orderExpression = "liabilities DESC";//負債
            } else if ($order == "liabilitiesRev") {
                $orderExpression = "liabilities ASC";
            } else if ($order == "capital-stock") {
                $orderExpression = "capital_stock DESC";//資本金
            } else if ($order == "capital-stockRev") {
                $orderExpression = "capital_stock ASC";
            } else if ($order == "shareholders-equity") {
                $orderExpression = "shareholders_equity DESC";//株主資本
            } else if ($order == "shareholders-equityRev") {
                $orderExpression = "shareholders_equity ASC";
            }else{
                $order = "assets";
                $orderExpression = "assets DESC";//資産
            }
        }elseif($type == 'cf'){
            if ($order == "net-income") {
                $orderExpression = "net_income DESC";//当期純利益
            } else if ($order == "net-incomeRev") {
                $orderExpression = "net_income ASC";
            } else if ($order == "depreciation-and-amortization") {
                $orderExpression = "depreciation_and_amortization DESC";//減価償却費
            } else if ($order == "depreciation-and-amortizationRev") {
                $orderExpression = "depreciation_and_amortization ASC";
            } else if ($order == "net-cash-provided-by-used-in-operating-activities") {
                $orderExpression = "net_cash_provided_by_used_in_operating_activities DESC";//営業活動によるキャッシュ・フロー
            } else if ($order == "net-cash-provided-by-used-in-operating-activitiesRev") {
                $orderExpression = "net_cash_provided_by_used_in_operating_activities ASC";
            } else if ($order == "net-cash-provided-by-used-in-investing-activities") {
                $orderExpression = "net_cash_provided_by_used_in_investing_activities DESC";//投資活動によるキャッシュ・フロー
            } else if ($order == "net-cash-provided-by-used-in-investing-activitiesRev") {
                $orderExpression = "net_cash_provided_by_used_in_investing_activities ASC";
            } else if ($order == "net-cash-provided-by-used-in-financing-activitiesRev") {
                $orderExpression = "net_cash_provided_by_used_in_financing_activities DESC";//財務活動によるキャッシュ・フロー
            } else if ($order == "net-cash-provided-by-used-in-financing-activitiesRevRev") {
                $orderExpression = "net_cash_provided_by_used_in_financing_activities ASC";
            } else if ($order == "net-increase-decrease-in-cash-and-cash-equivalents") {
                $orderExpression = "net_increase_decrease_in_cash_and_cash_equivalents DESC";//キャッシュ・フロー
            } else if ($order == "net-increase-decrease-in-cash-and-cash-equivalentsRev") {
                $orderExpression = "net_increase_decrease_in_cash_and_cash_equivalents ASC";
            }else{
                $order = "net-income";
                $orderExpression = "net_income DESC";//当期純利益
            }
        }
        return array($order,$orderExpression);
    }
}

/* End of file search.php */
/* Location: ./application/controllers/search.php */