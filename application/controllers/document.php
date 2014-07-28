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




/*
$xml = xml_parser_create();
xml_set_element_handler ($xml, array(&$this,"startElement"), array(&$this,"endElement"));
xml_set_character_data_handler ($xml, array(&$this,"characterData"));
$fp = fopen($data['xbrl']->xbrl_path, "r");


while ($read = fread($fp, 4096)) {
    if (!xml_parse ($xml, $read, feof($fp))) {
        die(sprintf ("error: %s at line %d\n",
        xml_error_string(xml_get_error_code($xml)),
        xml_get_current_line_number($xml)));
    }
}
var_dump($this->values);
die();
var_dump($this->tags);
die();
*/
$content=file_get_contents($data['xbrl']->xbrl_path);
$xml_parser=xml_parser_create();
xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,FALSE);
xml_parse_into_struct($xml_parser,$content,$xbrl_datas);
xml_parser_free($xml_parser);
$arr = array();
foreach ($xbrl_datas as $key => $xbrl_data){
    preg_match('/^xbrl/', $xbrl_data['tag'], $matches);
    if(empty($matches)){
        preg_match('/^link/', $xbrl_data['tag'], $matches);
        if(empty($matches)){
            $arr[] = $xbrl_data;
            list($namespace,$index) = explode(':',$xbrl_data['tag']);
            $element = $this->Item_model->getItemByElementName($index);
            if(!empty($element)){
                $data['csv_datas'][$key][] = $xbrl_data['tag'];
                $data['csv_datas'][$key][] = $element[0]->style_tree != '' ? $element[0]->style_tree : $element[0]->detail_tree;
                $data['csv_datas'][$key][] = isset($xbrl_data['attributes']['contextRef']) ? $xbrl_data['attributes']['contextRef'] : '';
                $data['csv_datas'][$key][] = isset($xbrl_data['attributes']['unitRef']) ? $xbrl_data['attributes']['unitRef'] : '';
                $data['csv_datas'][$key][] = isset($xbrl_data['attributes']['decimals']) ? $xbrl_data['attributes']['decimals'] : '';
                $value = isset($xbrl_data['value']) ? trim($xbrl_data['value']) : '';
                $data['csv_datas'][$key][] = $value;
            }else{
    var_dump($xbrl_data['tag']);
    die();
            }
        }
    }
}
var_dump($data['csv_datas']);
die();



        $parse = $this->xbrl_lib->_parseXml($data['xbrl']->xbrl_path);
var_dump($parse);
die();
        if($parse){
            $csv_line = 0;
            foreach ($parse as $xbrl){
                foreach ($xbrl as $namespace => $children){
                    if($namespace == '_namespace'){
                    
                    }elseif($namespace == 'xbrli'){

                    }elseif($namespace == 'link'){

                    }else{
                        //ex $index PurchaseOfInvestmentsInAssociatedCompaniesInvCF
                        foreach ($children as $index => $items){
                            $element = $this->Item_model->getItemByElementName($index);
                            //ex $number 項目の数分存在 前期 前前期
                            foreach ($items as $number => $child){
                                if(!empty($element)){
                                    $data['csv_datas'][$csv_line][] = $namespace;
                                    $data['csv_datas'][$csv_line][] = $element[0]->style_tree != '' ? $element[0]->style_tree : $element[0]->detail_tree;
                                    //ex $type _attributes or _value
                                    foreach ($child as $type => $item_value){
                                        if(is_array($item_value)){
                                            //ex $item_index contextRef unitRef decimals
                                            foreach ($item_value as $item_index => $value){
                                                $data['csv_datas'][$csv_line][] = $value;
                                            }
                                        }else{
                                            //ex _value 実際の値はここ
                                            $data['csv_datas'][$csv_line][] = $item_value;
                                        }
                                    }
                                }else{
                                    //項目がない場合、無効なもの
var_dump($index);
die();
                                }
                                $csv_line++;
                            }
                        }
                        
                    }
                }
            }
        }
var_dump($data['csv_datas']);
die();
        //set header title
        $data['header_title'] = $this->lang->line('common_header_title');
        $data['header_keywords'] = $this->lang->line('common_header_keywords');
        $data['header_description'] = $this->lang->line('common_header_description');
        
        $this->load->view('document/show', array_merge($this->data,$data));
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