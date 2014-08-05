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
        $this->load->model('Security_model');
        $this->load->model('Presenter_model');
        $this->load->model('Xbrl_model');
        $this->load->library('Xbrl_lib');
        $this->categories = $this->Category_model->getAllcategories();
        $this->data = array();
    }

    function startElement ($parser, $name, $attribs) {
        array_push($this->tags, $name);
    }
    function endElement ($parser, $name) {
        $tag = array_pop($this->tags);
//var_dump($tag);
        if ($tag == "TITLE") {
        //print "<tr><th>$this->cdata</th>\n";
        }
        if ($tag == "AUTHOR") {
        //print "<td>$this->cdata</td></tr>\n";
        }
    }
    function characterData ($parser, $data) {
        $data = trim($data);
        if(!empty($data)) $this->values[] = $data;
    }

    /**
     * search area action
     *
     */
    function show($document_id)
    {
        $data['bodyId'] = 'ind';
        $order = "created";
        $orderExpression = "created DESC";//作成新しい
        $page = 1;
        $data['xbrl'] = $this->Xbrl_model->getXbrlById($document_id);
        
        for ($i=0;$i<$data['xbrl']->xbrl_count;$i++){
            $add_path = '';
            if($i > 0) $add_path = '_'.$i;
            $data['xbrls'][$i] = $this->xbrl_lib->_read_form_item_csv($data['xbrl']->format_path.$add_path.'.base',TRUE);
        }

        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
        
        $this->load->view('document/show', array_merge($this->data,$data));
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