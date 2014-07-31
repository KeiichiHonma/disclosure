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
$this->load->library('PHPExcel');
// 新規作成の場合
$objPHPExcel = new PHPExcel();

$objPHPExcel->getDefaultStyle()->getFont()->setName( 'ＭＳ ゴシック' )->setSize( 11 );

// 0番目のシートをアクティブにする（シートは左から順に、0、1，2・・・）
$objPHPExcel->setActiveSheetIndex(0);
// アクティブにしたシートの情報を取得
$objSheet = $objPHPExcel->getActiveSheet();

// シート名を変更する
$objSheet->setTitle("シート1");

// セル「A1」に「タイトル」という文字を挿入
$objSheet->setCellValue("A1", "タイトル");
// セル「B2」に今日の日付を挿入
$objSheet->setCellValue("B2", date("Y/m/d"));
// セル「C3」に計算結果を挿入
$objSheet->setCellValue("C3", 5000*5);
// セル「D4」に変数同士の計算結果を挿入
//$objSheet->setCellValue("D4", $price * $num);

// IOFactory.phpを利用する場合
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save("/usr/local/apache2/htdocs/disclosure/tmp/sample2.xlsx");
// Excel2007.phpを利用する場合
//$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
//$objWriter->save("sample2.xlsx");

die();


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
$xbrl_datas = $this->xbrl_lib->_parseXml($data['xbrl']->xbrl_path);
$data['csv_datas'] = $this->xbrl_lib->_makeCsv($xbrl_datas);
$csv_paths[0] = '/usr/local/apache2/htdocs/disclosure/tmp/test.csv';
$this->put_csv($csv_paths,$data['csv_datas']);
die();
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