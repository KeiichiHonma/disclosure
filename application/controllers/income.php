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
        $this->load->model('Category_model');
        $this->load->model('Security_model');
        $this->load->model('Tenmono_model');
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
        $cdatas = $this->Tenmono_model->getCdataOrderDisclosure($page);
        $data['cdatas'] = $cdatas['data'];

        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
        
        $this->config->set_item('stylesheets', array_merge($this->config->item('stylesheets'), array('css/income.css')));
        
        $this->load->view('income/index', array_merge($this->data,$data));
    }
    // ----------------------------------------------------------------
    // CSV出力 
    // ----------------------------------------------------------------
    function put_csv($csv_paths,$csv_datas) {
        foreach ($csv_datas as $key => $csv_data){
            // ファイル書き込み
            $fp = fopen($csv_paths[$key], 'w+');
            foreach ($csv_data as $line => $value){
                $byte = $this->_fputcsv($fp, $value);
            }
            fclose($fp);
            //@chown($csv_paths[$key], 'apache');
            //@chmod($csv_paths[$key],0770);
        }

        return $byte;
    }
    function _fputcsv($fp, $data, $toEncoding='SJIS-WIN', $srcEncoding='UTF-8') {
        //require_once 'mb_str_replace.php';
        $csv = '';
        $toPutComma = FALSE;
        foreach ($data as $col) {
            if ( $toPutComma ) {
                $csv .= ',';
            } else {
                $toPutComma = TRUE;
            }
            if (is_numeric($col)) {
                $csv .= $col;
            } else {
                if(is_array($col)){
                    var_dump($col);
                    die();
                }
                $col = mb_convert_encoding($col, $toEncoding, $srcEncoding);
                //カンマ対応
                //$col = str_replace(',', '","', $col);
                //改行対応
                //$col = str_replace("\n", chr(10), $col);
                //$col = mb_str_replace('"', '""', $col, $toEncoding);
                $col = str_replace('"', '""', $col);
                if(empty($col)){
                    $csv .= '';
                }else{
                    $csv .= '"' . $col . '"';
                }
                
            }
        }
        fwrite($fp, $csv);
        fwrite($fp, "\r\n");
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