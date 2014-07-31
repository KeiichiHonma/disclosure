<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Xbrl_lib
{
    var $ATTR = "_attributes";
    var $NS = "_namespace";
    var $VAL = "_value";
    
    var $ng_tag =array
    (
    'NonConsolidated',//<xbrli:scenario><jpfr-oe:NonConsolidated/></xbrli:scenario>
    'NonconsolidatedInterimFinancialStatementsXBRLDocumentInformation',//<jpfr-di:NonconsolidatedInterimFinancialStatementsXBRLDocumentInformation contextRef="DocumentInfo">true</jpfr-di:NonconsolidatedInterimFinancialStatementsXBRLDocumentInformation>
    'ContextIDBeginningOfPeriodFCNonconsolidatedInterimPL',//<jpfr-di:ContextIDBeginningOfPeriodFCNonconsolidatedInterimPL contextRef="DocumentInfo">Prior1YearNonConsolidatedInstant</jpfr-di:ContextIDBeginningOfPeriodFCNonconsolidatedInterimPL>
    );

    var $ng_log_tag =array
    (
        'IncomeLossBeforeIncomeTaxesAndMinorityInterestsSummaryOfBusinessResults',
        'IncomeLossBeforeIncomeTaxesSummaryOfBusinessResults',
        'LossOnSalesReturnsSGA',
        'PensionCostOfSubsidiaries',
        'CumulativeEffectsOfChangesInAccountingPolicies',
        'RestatedBalance',
        'IncreaseDecreaseInNetDefinedBenefitLiabilityOpeCF',
        'IncreaseDecreaseInNetDefinedBenefitAssetOpeCF',
        'AmortizationOfGoodwillAmortizationAndUnamortizedBalanceOfGoodwillForEachReportableSegment',
        'PayrollsAndBonusesSGA',
        'DescriptionOfFactThatFinancialStatementsHaveBeenPreparedInAccordanceWithSpecialProvisionFinancialInformation',
        'AccountingPolicyForRetirementBenefitsTextBlock',
        'NotesRegardingCivilLitigationInTheUnitedStatesAndElsewhereTextBlock',
        'NotesRegardingPensionCostOfSubsidiariesTextBlock',
        'DescriptionOfFactAndReasonWhyComponentsOfMajorAssetsAndLiabilitiesAreNotPresented',

    );

    var $ng_string =array
    (
    'Prior',
    );
    
    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->model('Item_model');
        $this->ci->load->model('Context_model');
    }
    
    public function _parseXml($file="") {
        $content=file_get_contents($file);
        $xml_parser=xml_parser_create();
        xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,FALSE);
        xml_parse_into_struct($xml_parser,$content,$xbrl_datas);
        xml_parser_free($xml_parser);
        return $xbrl_datas;
    }
    
    public function _makeCsv($xbrl_datas,$file) {
        foreach ($xbrl_datas as $line => $xbrl_data){
            if(preg_match('/^xbrl/', $xbrl_data['tag']) === 0 && preg_match('/^link/', $xbrl_data['tag']) === 0){
                $xbrl_data_value = isset($xbrl_data['value']) ? trim($xbrl_data['value']) : '';
                $is_all_int = preg_match("/^[0-9]+$/", $xbrl_data_value);
                if( ( preg_match('/^http/', $xbrl_data_value) === 0 && $xbrl_data_value != '' ) || $is_all_int === 1){//全て数字はOK
                    $all_english = preg_match("/^[a-zA-Z]+$/", $xbrl_data_value);
                    $all_int_english = preg_match("/^[a-zA-Z0-9]+$/", $xbrl_data_value);
                    
                    foreach ($this->ng_string as $ng_string){
                        $is_start_ng_string = preg_match("/^$ng_string/", $xbrl_data_value);
                        if($is_start_ng_string === 1) break;
                    }
                    
                    
                    
                    $is_in_space = strpos($xbrl_data_value, " ") === FALSE ? FALSE : TRUE;
                    $is_ng = FALSE;
                    
                    if($all_english === 1 && !$is_in_space) $is_ng = TRUE;
                    if($all_int_english === 1 && $is_start_ng_string === 1) $is_ng = TRUE;
                    
                    if(!$is_ng){
                        list($namespace,$index) = explode(':',$xbrl_data['tag']);
                        
                        if(!in_array($index,$this->ng_tag)){
                            $element = $this->ci->Item_model->getItemByElementName($index);
                            if(!empty($element) || $is_all_int === 1){
                                
                                if(empty($element) && $is_all_int === 1 && !in_array($index,$this->ng_log_tag)) log_message('error','all int none index '.$xbrl_data['tag'].':'.$file);
                                
                                //context
                                $contextRef = isset($xbrl_data['attributes']['contextRef']) ? $xbrl_data['attributes']['contextRef'] : '';
                                if($contextRef != ''){
                                    $context = $this->ci->Context_model->getContextByContextName($contextRef);
                                    if(empty($context)) $context = $contextRef;
                                }else{
                                    $context = '';
                                }
                                //連結・個別
                                if($namespace == 'jpcrp_cor'){
                                    $Consolidated_NonConsolidated_etc = 'その他';
                                }elseif(preg_match('/NonConsolidatedMember$/', $contextRef)){//NonConsolidatedMemberで終わる
                                    $Consolidated_NonConsolidated_etc = '個別';
                                }else{
                                    $Consolidated_NonConsolidated_etc = '連結';
                                }
                                //期間・時点
                                if(preg_match('/Duration/', $contextRef)){//Duration含まれる
                                    $duration_instant = '期間';
                                }elseif(preg_match('/Instant/', $contextRef)){//Instant含まれる
                                    $duration_instant = '時点';
                                }else{
                                    $duration_instant = '';
                                }
                                $csv_datas[$line][] = $xbrl_data['tag'];
                                $csv_datas[$line][] = !empty($element) ? $element[0]->style_tree != '' ? $element[0]->style_tree : $element[0]->detail_tree : $index;
                                $csv_datas[$line][] = $context;//コンテキストID
                                $csv_datas[$line][] = $Consolidated_NonConsolidated_etc;//連結・個別
                                $csv_datas[$line][] = $duration_instant;//期間・時点
                                $csv_datas[$line][] = isset($xbrl_data['attributes']['unitRef']) ? $xbrl_data['attributes']['unitRef'] : '';
                                //$csv_datas[$line][] = isset($xbrl_data['attributes']['decimals']) ? $xbrl_data['attributes']['decimals'] : '';
                                
                                $text = htmlspecialchars($xbrl_data_value,ENT_NOQUOTES);
                                if(mb_strlen($text) > 20000){
                                    $ret = $this->_mb_str_split($text, 20000);//16,384
                                    foreach ($ret as $split_text){
                                        $csv_datas[$line][] = $split_text;
                                    }
                                }else{
                                    $csv_datas[$line][] = $text;
                                }

                            }else{
                                if(!in_array($index,$this->ng_log_tag)) log_message('error','none index '.$xbrl_data['tag'].':'.$file);
                            }
                        }
                    }
                }
            }
        }
        return $csv_datas;
    }

    function _mb_str_split($str, $split_len = 1) {
        mb_internal_encoding('UTF-8');
        mb_regex_encoding('UTF-8');
        if ($split_len <= 0) {
            $split_len = 1;
        }

        $strlen = mb_strlen($str, 'UTF-8');
        $ret    = array();

        for ($i = 0; $i < $strlen; $i += $split_len) {
            $ret[ ] = mb_substr($str, $i, $split_len);
        }
        return $ret;
    }

    public function bk_parseXml($file="") {
        libxml_use_internal_errors(true);
        $doc = @simplexml_load_file($file, null, LIBXML_COMPACT|LIBXML_NOCDATA|LIBXML_NOBLANKS|LIBXML_NOENT);
var_dump($doc->xbrli);
die();
        if(!is_object($doc)) {
         $err["status"] = "Failed loading XML.";
         foreach(libxml_get_errors() as $error) {
            $err["error"][] = $error->message;
         }
         return $err;
        }
        $test = $doc->getDocNamespaces();
var_dump($test);
die();
        $ns = $data[$this->NS] = $doc->getDocNamespaces();
var_dump($ns);
die();
        if(!count($ns)) {
            $body = $this->_parseXmlLoop($doc, "", 1);
        }else {
            $ns = $this->_nameSpace($ns, 1);
            //_namespaceセット
            foreach($ns as $key=>$val) {
                $obj_attr = ($val) ? $doc->attributes($val) : $doc->attributes();
                if($obj_attr) {
                    $data[$this->ATTR.$key] = $this->_perseAttributes($obj_attr);
                }
            }

            $root = $doc->getName();

            $body[$root] = $data;
            $i = 0;
            foreach($ns as $key=>$val) {
                if($obj_data = $this->_parseXmlLoop($doc, $val)) {
var_dump($obj_data);
die();
                    $body[$root][$key] = $obj_data;
/*
                    if($key != 'xbrli' && $key != 'link'){
                        $body[$i][$root][$key] = $obj_data;
                        $i++;
                    }else{
                        $body[$root][$key] = $obj_data;
                    }
*/
                }
            }
        }
        return $body;
    }
/**********************************************************
Name Space.
*********************************************************/
    private function _nameSpace($array="") {
    if(!array_key_exists("", $array) || !in_array("", $array)) {
     $array[""] = "";
    }
    return $array;
 }
/**********************************************************
Perse Xml Attributes.
*********************************************************/
    private function _perseAttributes($attributes="") {
    foreach($attributes as $attr) {
     $data[(string)$attr->getName()] = (string)$attr;
    }
    return $data;
 }
/**********************************************************
Perse Xml Loop.
*********************************************************/
    private function _parseXmlLoop($doc="", $vals="", $not="") {
    if(!is_object($doc)) {
     return;
    }
    $ns = $this->_nameSpace($doc->getNamespaces(true));
    $vals_ = (!$vals) ? $doc->children() : $doc->children($vals);
    foreach($vals_ as $obj_key=>$obj_val) {
     if($not===1) {
        if($obj_attr = $doc->attributes()) {
         $body[$obj_key][$this->ATTR] = $this->_perseAttributes($obj_attr);
        }
     }
     $data = "";
     foreach($ns as $key=>$val) {
        if($obj_loop = $this->_parseXmlLoop($obj_val, $val)) {
         $data[$key] = $obj_loop;
        }
     }
     if(count($ns)===1) {
        $data = $data[""];
     }
     foreach($ns as $key=>$val) {
        $obj_attr = ($val) ? $obj_val->attributes($val) : $obj_val->attributes();
        if($obj_attr) {
         $data[$this->ATTR.$key] = $this->_perseAttributes($obj_attr);
        }
     }
     if((string)$obj_val && !is_array($obj_val)) {
        $data[$this->VAL] = (string)$obj_val;
     }
     $body[$obj_key][] = $data;
    }
    if(isset($body)) return $body;
 }


}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */