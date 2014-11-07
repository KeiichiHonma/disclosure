<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Xbrl_lib
{
    var $ATTR = "_attributes";
    var $NS = "_namespace";
    var $VAL = "_value";
    
    //データとして出力しない
    var $ng_tag =array
    (
    'NonConsolidated',//<xbrli:scenario><jpfr-oe:NonConsolidated/></xbrli:scenario>
    'NonconsolidatedInterimFinancialStatementsXBRLDocumentInformation',//<jpfr-di:NonconsolidatedInterimFinancialStatementsXBRLDocumentInformation contextRef="DocumentInfo">true</jpfr-di:NonconsolidatedInterimFinancialStatementsXBRLDocumentInformation>
    'ContextIDBeginningOfPeriodFCNonconsolidatedInterimPL',//<jpfr-di:ContextIDBeginningOfPeriodFCNonconsolidatedInterimPL contextRef="DocumentInfo">Prior1YearNonConsolidatedInstant</jpfr-di:ContextIDBeginningOfPeriodFCNonconsolidatedInterimPL>
    
    );
    
    //データとしては出力するがログにださない
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
        'SupplementaryReferenceInformationAboutMotherFundFinancialStatementsTextBlock',
        'ApplicationFeeOverviewOfFundNA',
        'NotesChangesInScopeOfConsolidationOrEquityMethodNATextBlock',
        'NotesChangesInAccountingPoliciesQuarterlyConsolidatedFinancialStatementsNATextBlock',
        'NotesAccountingTreatmentsSpecificToQuarterlyFinancialStatementsQuarterlyConsolidatedFinancialStatementsNATextBlock',
        'DescriptionAboutImportantInformationInfluencingJudgingCompanyFinancialConditionOperatingResultsAndCashFlowOverviewNATextBlock',
        'NotesQuarterlyConsolidatedBalanceSheetNATextBlock',
        'NotesRegardingLossOnBusinessWithdrawalTextBlock',
        'NotesShareholdersEquityNATextBlock',
        'NotesDerivativesQuarterlyConsolidatedFinancialStatementsNATextBlock',
        'DescriptionOfFactThatDisclosureIsOmittedBasedOnArticle172OfRegulationOfQuarterlyConsolidatedFinancialStatementNotesFinancialInstrumentsQuarterlyConsolidatedFinancialStatementsTextBlock',
        'DescriptionOfFactThatDisclosureIsOmittedBasedOnArticle172OfRegulationOfQuarterlyConsolidatedFinancialStatementNotesSecuritiesQuarterlyConsolidatedFinancialStatementsTextBlock',
        'NotesDerivativesQuarterlyConsolidatedFinancialStatementsNATextBlock',
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
        $this->ci->load->library('PHPExcel');
    }
    
    public function _parseXml($file="") {
        $content=file_get_contents($file);
        $xml_parser=xml_parser_create();
        xml_parser_set_option($xml_parser,XML_OPTION_CASE_FOLDING,FALSE);
        xml_parse_into_struct($xml_parser,$content,$xbrl_datas);
        xml_parser_free($xml_parser);
        return $xbrl_datas;
    }

    public function _listedXbrlHtml($xbrl_datas,$move_xbrl_path) {
        $htmls = array();
        foreach ($xbrl_datas as $line => $xbrl_data){
            if($xbrl_data['tag'] == 'ixbrl' && !preg_match('/^0000000/', $xbrl_data['value'])){
                $htmls[] = $move_xbrl_path.$xbrl_data['value'];
            }
        }
        return $htmls;
    }
    
    private $h_tags = array('h1','h2','h3','h4','h5');
    
    public function _makeHtmlData($document_id,$html_file,&$html_index,$file_number,$move_ymd_path) {
        require_once('application/libraries/simple_html_dom.php');
        $simple_html_dom = file_get_html($html_file);
        $data = array();

        //hタグリスト
        foreach ($this->h_tags as $tag){
            foreach($simple_html_dom->find($tag) as $key => $element){
                $html_index[$file_number][$tag][] = $element->plaintext;
                if($tag == 'h2' || $tag == 'h3'){
                    $element->id = $element->id != '' ? $element->id.' '.$tag.'_'.$file_number.'_'.$key : $tag.'_'.$file_number.'_'.$key;
                }
            }
        }

        //img
        foreach($simple_html_dom->find('img') as $element){
                if(isset($element->src)){
                    $res_image_path = $element->src;
                    if($ex = explode('/',$res_image_path)){
                        $res_image_path = end($ex);
                    }
                    $paths = explode('/',$html_file);
                    array_pop($paths);
                    $image_filepath = implode('/',$paths).'/'.$element->src;
                    $move_image_path = $move_ymd_path.'/'.$document_id.'_'.$res_image_path;
                    rename($image_filepath,$move_image_path);
                    $html_imagepath = explode('disclosure/',$move_image_path);
                    $element->src = '/'.$html_imagepath[1];//上書き
                }
            
        }
        $html = '';
        $style = $simple_html_dom->find('style',0)->innertext;
        if(strlen($style) > 0){
            $html .=  '<style type="text/css">'.$style.'</style>';
        }
        $html .= $simple_html_dom->find('body',0)->innertext;
/*
        $html = '';
        $index = array();
        $html_go = FALSE;
        foreach ($html_datas as $key => $html_data){
            
            //index
            if(preg_match('/^<h1/', $html_data)){
                $html_indexs_file_number['h1'][] = strip_tags($html_data);
            }elseif(preg_match('/^<h2/', $html_data)){
                $html_indexs_file_number['h2'][] = strip_tags($html_data);
            }elseif(preg_match('/^<h3/', $html_data)){
                $html_indexs_file_number['h3'][] = strip_tags($html_data);
            }elseif(preg_match('/^<h4/', $html_data)){
                $html_indexs_file_number['h4'][] = strip_tags($html_data);
            }elseif(preg_match('/^<h5/', $html_data)){
                $html_indexs_file_number['h5'][] = strip_tags($html_data);
            }elseif(preg_match('/<img/', $html_data)){
                //$paths = explode('/',$html_file);
                preg_match_all('/<img.*?src=(["\'])(.+?)\1.*?>/i', $html_data, $res);
                //preg_match_all('/<img[^src]+src=[\'"]*((?:(?!hogefuga).)*?(jpg|gif|png))[^>]+>/i',$html_data,$res);
                if(isset($res[2][0])){
                    $res_image_path = $res[2][0];
                    if($ex = explode('/',$res_image_path)){
                        $res_image_path = end($ex);
                    }
                    $paths = explode('/',$html_file);
                    array_pop($paths);
                    $image_filepath = implode('/',$paths).'/'.$res[2][0];
                    $move_image_path = $move_ymd_path.'/'.$document_id.'_'.$res_image_path;
                    rename($image_filepath,$move_image_path);
                    //$html_images[] = $move_image_path;
                    //str_replace($res[0][0],$html_datas,)
                    $html_imagepath = explode('disclosure/',$move_image_path);
                    $html_data = preg_replace('!(?<=src\=\").+(?=\"(\s|\/\>))!', '/'.$html_imagepath[1],$html_data );
                }
            }
            
            //html
            if($html_go) $html .= $html_data;
            if(preg_match('/^<body/', $html_data)) $html_go = TRUE;
            if(preg_match('/^<\/body/', $html_data)) $html_go = FALSE;
        }
*/
        $simple_html_dom->clear();
        return $html;
    }
    public function _makeCsvSqlData($xbrl_datas,$file,&$insert_document_data,$edinet_code,&$tenmono_datas) {
        require_once('application/libraries/simple_html_dom.php');
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

                        //tenmono
                        if(isset($tenmono_datas['companies'][$edinet_code])){
                            //住所
                            if($namespace == 'jpcrp_cor' && $index == 'AddressOfRegisteredHeadquarterCoverPage'){
                                $tenmono_datas['companies'][$edinet_code]['col_address'] = trim($xbrl_data_value);
                                $tenmono_datas['companies'][$edinet_code]['col_map'] = $tenmono_datas['companies'][$edinet_code]['col_address'];
                            }
                            //公開日
                            if($namespace == 'jpcrp_cor' && $index == 'FilingDateCoverPage'){
                                $tenmono_datas['cdatas'][$edinet_code]['col_disclosure'] = strtotime(trim($xbrl_data_value));
                            }
                            //期
                            if($namespace == 'jpcrp_cor' && $index == 'FiscalYearCoverPage'){
                                $tenmono_datas['cdatas'][$edinet_code]['col_fiscalyear'] = trim($xbrl_data_value);
                            }
                            //各値
                            if($namespace == 'jpcrp_cor' && $index == 'InformationAboutEmployeesTextBlock'){
                                $bl = $this->_makeTenmonoData($xbrl_data_value,$tenmono_datas,$edinet_code);
                            }
                        }

                        if(!in_array($index,$this->ng_tag)){
                            $element = $this->ci->Item_model->getItemByElementName($index);
                            if(!empty($element) || $is_all_int === 1){
                                
                                //if(empty($element) && $is_all_int === 1 && !in_array($index,$this->ng_log_tag)) log_message('error','all int none index '.$xbrl_data['tag'].':'.$file);
                                
                                //context
                                $contextRef = isset($xbrl_data['attributes']['contextRef']) ? $xbrl_data['attributes']['contextRef'] : '';
                                if($contextRef != ''){
                                    if(preg_match('/^FilingDateInstant/', $contextRef) || preg_match('/^DocumentInfo/', $contextRef)){
                                        $context = '提出日時点';
                                    }elseif(preg_match('/^Current/', $contextRef)){
                                        if(preg_match('/^CurrentYear/', $contextRef) && preg_match('/Instant/', $contextRef)){
                                            $context = '当期末';
                                        }elseif(preg_match('/^CurrentYear/', $contextRef) && preg_match('/Duration/', $contextRef)){
                                            $context = '当期';
                                        }elseif(preg_match('/^CurrentYTD/', $contextRef) && preg_match('/Duration/', $contextRef)){
                                            $context = '当四半期累計期間';
                                        }elseif(preg_match('/^CurrentQuarter/', $contextRef) && preg_match('/Instant/', $contextRef)){
                                            $context = '当四半期会計期間末';
                                        }else{
                                            $context_data = $this->ci->Context_model->getContextByContextTag($contextRef);
                                            if(empty($context_data)){
                                                $context = $contextRef;
                                                $this->_insert_log_message(array('error','none context tag '.$contextRef.':'.$file));
                                            }else{
                                                $context = $context_data->context_name;
                                            }
                                        }
                                    //Prior2YearConsolidatedInstant
                                    }elseif(preg_match('/^Prior1/', $contextRef)){
                                        if(preg_match('/^Prior1Year/', $contextRef) && preg_match('/Instant/', $contextRef)){
                                            $context = '前期末';
                                        }elseif(preg_match('/^Prior1Year/', $contextRef) && preg_match('/Duration/', $contextRef)){
                                            $context = '前期';
                                        }elseif(preg_match('/^Prior1YTD/', $contextRef) && preg_match('/Duration/', $contextRef)){
                                            $context = '前年度同四半期累計期間';
                                        }elseif(preg_match('/^Prior1Quarter/', $contextRef) && preg_match('/Instant/', $contextRef)){
                                            $context = '前年度同四半期会計期間末';
                                        }else{
                                            $context_data = $this->ci->Context_model->getContextByContextTag($contextRef);
                                            if(empty($context_data)){
                                                $context = $contextRef;
                                                $this->_insert_log_message(array('error','none context tag '.$contextRef.':'.$file));
                                            }else{
                                                $context = $context_data->context_name;
                                            }
                                        }
                                    }elseif(preg_match('/^Prior2/', $contextRef) === 1){
                                        if(preg_match('/^Prior2Year/', $contextRef) && preg_match('/Instant/', $contextRef)){
                                            $context = '前々期末';
                                        }elseif(preg_match('/^Prior2YearDuration/', $contextRef)){
                                            $context = '前々期';
                                        }else{
                                            $context_data = $this->ci->Context_model->getContextByContextTag($contextRef);
                                            if(empty($context_data)){
                                                $context = $contextRef;
                                                $this->_insert_log_message(array('error','none context tag '.$contextRef.':'.$file));
                                            }else{
                                                $context = $context_data->context_name;
                                            }
                                        }
                                    }elseif(preg_match('/^Prior3/', $contextRef) === 1){
                                        if(preg_match('/^Prior3Year/', $contextRef) && preg_match('/Instant/', $contextRef)){
                                            $context = '三期前時点';
                                        }elseif(preg_match('/^Prior3YearDuration/', $contextRef)){
                                            $context = '三期前';
                                        }else{
                                            $context_data = $this->ci->Context_model->getContextByContextTag($contextRef);
                                            if(empty($context_data)){
                                                $context = $contextRef;
                                                $this->_insert_log_message(array('error','none context tag '.$contextRef.':'.$file));
                                            }else{
                                                $context = $context_data->context_name;
                                            }
                                        }
                                    }elseif(preg_match('/^Prior4/', $contextRef) === 1){
                                        if(preg_match('/^Prior4Year/', $contextRef) && preg_match('/Instant/', $contextRef)){
                                            $context = '四期前時点';
                                        }elseif(preg_match('/^Prior4YearDuration/', $contextRef)){
                                            $context = '四期前';
                                        }else{
                                            $context_data = $this->ci->Context_model->getContextByContextTag($contextRef);
                                            if(empty($context_data)){
                                                $context = $contextRef;
                                                $this->_insert_log_message(array('error','none context tag '.$contextRef.':'.$file));
                                            }else{
                                                $context = $context_data->context_name;
                                            }
                                        }
                                    }elseif(preg_match('/^Interim/', $contextRef) === 1){
                                        if(preg_match('/^InterimNonConsolidatedDuration/', $contextRef)){
                                            $context = '当中間期';
                                        }elseif(preg_match('/^InterimNonConsolidatedInstant/', $contextRef)){
                                            $context = '当中間期末';
                                        }else{
                                            $context_data = $this->ci->Context_model->getContextByContextTag($contextRef);
                                            if(empty($context_data)){
                                                $context = $contextRef;
                                                $this->_insert_log_message(array('error','none context tag '.$contextRef.':'.$file));
                                            }else{
                                                $context = $context_data->context_name;
                                            }
                                        }
                                    }else{
                                        $this->_insert_log_message(array('error','none context tag '.$contextRef.':'.$file));
                                        $context = $contextRef;
                                    }
                                }else{
                                    $context = '';
                                }

                                //連結・個別
                                if($namespace == 'jpcrp_cor'){
                                    $Consolidated_NonConsolidated_etc = 'その他';
                                }elseif(preg_match('/NonConsolidatedMember$/', $contextRef)){//NonConsolidatedMemberで終わる
                                    $Consolidated_NonConsolidated_etc = '個別';
                                }elseif(!preg_match('/NonConsolidated/', $contextRef) && preg_match('/Consolidated/', $contextRef)){//NonConsolidatedがなくて、Consolidatedがある場合は個別
                                    $Consolidated_NonConsolidated_etc = '連結';
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
                                
                                $insert_document_data[$line]['item_id'] = !empty($element) ? $element[0]->id : 0;
                                $insert_document_data[$line]['prefix'] = $namespace;
                                $insert_document_data[$line]['element_name'] = $index;
                                //$insert_document_data[$line]['element_title'] = !empty($element) ? $element[0]->style_tree != '' ? $element[0]->style_tree : $element[0]->detail_tree : $index;
                                $insert_document_data[$line]['context_period'] = $context;//コンテキストID
                                $insert_document_data[$line]['context_consolidated'] = $Consolidated_NonConsolidated_etc;//連結・個別
                                $insert_document_data[$line]['context_term'] = $duration_instant;//期間・時点
                                $insert_document_data[$line]['unit'] = isset($xbrl_data['attributes']['unitRef']) ? $xbrl_data['attributes']['unitRef'] : '';

                                $text = htmlspecialchars($xbrl_data_value,ENT_NOQUOTES);
                                $is_judge_length = isset($text[$this->ci->config->item('string_max_length')]);
                                if($is_judge_length){
                                    //一応DBには入れておく→メモリエラーになるので、入れない
                                    $insert_document_data[$line]['int_data'] = 0;
                                    $insert_document_data[$line]['text_data'] = '';
                                    //$insert_document_data[$line]['mediumtext_data'] = $text;
                                    $insert_document_data[$line]['mediumtext_data'] = 'over';

                                }else{
                                    if(is_numeric($text)){
                                        $insert_document_data[$line]['int_data'] = $text;
                                        $insert_document_data[$line]['text_data'] = '';
                                        $insert_document_data[$line]['mediumtext_data'] = '';
                                    }else{
                                        $insert_document_data[$line]['int_data'] = '';
                                        $insert_document_data[$line]['text_data'] = $text;
                                        $insert_document_data[$line]['mediumtext_data'] = '';
                                    }
                                }
/*
長文廃止
                                $is_judge_length = isset($text[$this->ci->config->item('string_max_length')]);
                                if($is_judge_length){

                                    $insert_document_data[$line]['int_data'] = 0;
                                    $insert_document_data[$line]['text_data'] = '';
                                    $insert_document_data[$line]['mediumtext_data'] = $text;
                                    //csv用
                                    $ret = $this->_mb_str_split($text, $this->ci->config->item('string_max_length') * 2);//16,384
                                    foreach ($ret as $split_text){
                                        $csv_datas[$line][] = $split_text;
                                    }

                                }elseif(isset($text[1000])){//htmlを完全除去したい
                                    $insert_document_data[$line]['int_data'] = 0;
                                    $insert_document_data[$line]['text_data'] = '';
                                    $insert_document_data[$line]['mediumtext_data'] = $text;
                                    //何もしない
                                }else{
                                    if(is_numeric($text)){
                                        $insert_document_data[$line]['int_data'] = $text;
                                        $insert_document_data[$line]['text_data'] = '';
                                        $insert_document_data[$line]['mediumtext_data'] = '';
                                    }else{
                                        $insert_document_data[$line]['int_data'] = $text;
                                        $insert_document_data[$line]['text_data'] = $text;
                                        $insert_document_data[$line]['mediumtext_data'] = '';
                                    }
                                    //csv
                                    $csv_datas[$line][] = $text;
                                }
*/
                            }else{
                                //if(!in_array($index,$this->ng_log_tag)) log_message('error','none index '.$xbrl_data['tag'].':'.$file);
                            }
                        }
                    }
                }
            }
        }
    }
    
    public function _makeTenmonoData($xbrl_data_value,&$tenmono_datas,$edinet_code) {
        $html = str_get_html(str_replace(array("\r\n","\n","\r"),array('','',''),$xbrl_data_value));
        foreach($html->find('p') as $key => $element){
            $innertext = strip_tags($element->innertext);
            //if ( preg_match("/^平均年間給与/", $element->innertext) ) {
            if ( preg_match("/^平均年間給与/", $innertext) ) {
                $pos = mb_strpos($html, '>平均年間給与');//>はあえてつけてる
                $rest = mb_substr($html, $pos, 2500, 'UTF-8');
                $implode_html = str_get_html($rest);
                foreach($implode_html->find('p') as $key2 => $element2){
                    $text = trim($element2->innertext);
                    if($text != ' '){
                        $text = strip_tags($text);//pタグの中に<span style="font-size: 12px">とかあったりする
                        $text = mb_convert_kana($text, "a", "UTF-8");
                        $text = str_replace(array(',','、',' ','　','名','人',' '),array('','','','','','',''),$text);//不思議な空白 「 」 がある。。
                        //カッコ対策　16〔10〕等
                        $kakkos = array('（','〔','(','[','［','【');
                        foreach ($kakkos as $kakko){
                            if( FALSE !== strstr($text,$kakko) ){
                                $string = explode($kakko,$text);
                                $text = $string[0];
                            }
                        }

                        //ヶ月対策　35歳   11ヶ月　8年  10ヶ月 ６年10ヵ月等
                        if( FALSE !== strstr($text,'ヶ月') && FALSE !== strstr($text,'歳') ){
                            $string = explode('歳',$text);
                            $string2 = explode('ヶ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'ヶ月') && FALSE !== strstr($text,'才') ){
                            $string = explode('才',$text);
                            $string2 = explode('ヶ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'ヶ月') && FALSE !== strstr($text,'年') ){
                            $string = explode('年',$text);
                            $string2 = explode('ヶ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'ヵ月') && FALSE !== strstr($text,'歳') ){
                            $string = explode('歳',$text);
                            $string2 = explode('ヵ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'ヵ月') && FALSE !== strstr($text,'才') ){
                            $string = explode('才',$text);
                            $string2 = explode('ヵ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'ヵ月') && FALSE !== strstr($text,'年') ){
                            $string = explode('年',$text);
                            $string2 = explode('ヵ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'か月') && FALSE !== strstr($text,'歳') ){
                            $string = explode('歳',$text);
                            $string2 = explode('か月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'か月') && FALSE !== strstr($text,'才') ){
                            $string = explode('才',$text);
                            $string2 = explode('か月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'か月') && FALSE !== strstr($text,'年') ){
                            $string = explode('年',$text);
                            $string2 = explode('か月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'カ月') && FALSE !== strstr($text,'歳') ){
                            $string = explode('歳',$text);
                            $string2 = explode('カ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'カ月') && FALSE !== strstr($text,'才') ){
                            $string = explode('才',$text);
                            $string2 = explode('カ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'カ月') && FALSE !== strstr($text,'年') ){
                            $string = explode('年',$text);
                            $string2 = explode('カ月',$string[1]);
                            (int)$text = $string[0] + round($string2[0] / 12,1);
                        }elseif( FALSE !== strstr($text,'歳') ){
                            $string = explode('歳',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== strstr($text,'才') ){
                            $string = explode('才',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== strstr($text,'年') ){
                            $string = explode('年',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== strstr($text,'千円') ){
                            $string = explode('千円',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== strstr($text,'万円') ){
                            $string = explode('万円',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== strstr($text,'円') ){
                            $string = explode('円',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== @strstr($text,'ヶ月') ){
                            $string = explode('ヶ月',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== @strstr($text,'ヵ月')){
                            $string = explode('ヵ月',$text);
                            (int)$text = $string[0];
                        }elseif( FALSE !== @strstr($text,'か月') ){
                            $string = explode('か月',$text);
                            (int)$text = $string[0];
                        }
                        if( is_numeric($text) ){
                            $result[] = $text;
                        }
                    }
                }
                if(!isset($result)){
                    unset($tenmono_datas['cdatas'][$edinet_code]);
                    $this->_insert_log_message(array('error','cdata none income data:edinet_code'.$edinet_code));
                    return FALSE;
                }else{
                    $count = count($result);
                    if($count < 4){
                        unset($tenmono_datas['cdatas'][$edinet_code]);
                        $this->_insert_log_message(array('error','cdata count '.$count.':edinet_code'.$edinet_code));
                        return FALSE;
                    }elseif($count > 4){
                        $this->_insert_log_message(array('error','cdata count over 4'.$count.':edinet_code'.$edinet_code));
                    }
                }

                //順番で判定
                $tenmono_datas['cdatas'][$edinet_code]['col_person'] = $result[0];
                $tenmono_datas['cdatas'][$edinet_code]['col_age']    = $result[1];
                $tenmono_datas['cdatas'][$edinet_code]['col_employ'] = $result[2];
                $tenmono_datas['cdatas'][$edinet_code]['col_income'] = $this->_makeIncomeLength($result[3],$edinet_code);
            }
        }
    }
    
    public function _makeIncomeLength($income,$edinet_code){
        //=IF(LEN(H840)=7,ROUND(H840/1000,0)/10,IF(LEN(H840)=4,H840/10,IF(LEN(H840)=8,ROUND(H840/1000,0)/10,IF(LEN(H840)=5,H840/10))))
        $len = strlen($income);
        if($len == 8){
            return (round($income / 10000)) / 10;//年収一千万超え
        }elseif($len == 7){
            return (round($income / 1000)) / 10;//7654321 単位が円
        }elseif($len == 5){
            return $income / 10;//年収一千万超え
        }elseif($len == 4){
            return $income / 10;//単位が千円
        }elseif($len == 3){//たぶんよくある誤記３桁で記載している場合は０の記載漏れ
            return $income;//そのまま
        }elseif($len == 2){//たぶんよくある誤記
            return $income;//そのまま
        }elseif($len == 1){
            return $income * 100;//単位が百万円
        }else{
            $this->_insert_log_message(array('error','income length '.$income.':edinet_code'.$edinet_code));
            return $income;//そのまま
        }
    }
    
    //error log insert
    function _insert_log_message($insert_data){
        $data['type'] = $insert_data[0];
        $data['log'] = $insert_data[1];
        $data['created'] = date('Y-m-d H:i:s');
        $this->ci->db->insert('logs', $data);//myisam
    }
    
    // ----------------------------------------------------------------
    // EXCEL出力 
    // ----------------------------------------------------------------
    var $alphabet = array('A','B','C','D','E','F','G','H','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ');
    function put_excel($excel_path,$csv_data,$excel_sheet_name) {
        $objPHPExcel = null;
        // 新規作成の場合
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getDefaultStyle()->getFont()->setName( 'ＭＳ Ｐゴシック' )->setSize( 11 );
        

        // 0番目のシートをアクティブにする（シートは左から順に、0、1，2・・・）
        $objPHPExcel->setActiveSheetIndex(0);
        // アクティブにしたシートの情報を取得
        $objSheet = $objPHPExcel->getActiveSheet();
        // シート名を変更する
        $objSheet->setTitle($excel_sheet_name);
        $excel_tate = 0;
        $line = 0;
        foreach ($csv_data as $values){
            $excel_tate = $line + 1;
            foreach ($values as $value_number => $col) {
                if(!isset($this->alphabet[$value_number])){
                    $this->_insert_log_message(array('error','none alphabet '.$value_number.':'.$excel_path));
                }
                $excel_yoko = $this->alphabet[$value_number];
                $excel_column_name = $excel_yoko.$excel_tate;
                
                if (is_numeric($col)) {
                    $objSheet->setCellValue($excel_column_name, $col);
                } else {
                    if(is_array($col)){
                        var_dump($col);
                        die();
                    }
                    $col = str_replace('"', '""', $col);
                    $objSheet->setCellValue($excel_column_name, $col);
                }
            }
            $line++;
        }
        //$objPHPExcel->setActiveSheetIndex(0);//sheet選択
        // IOFactory.phpを利用する場合
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        if(is_null($excel_path)){
            return $objWriter;
            //$objWriter->save('php://output');
        }else{
            $objWriter->save($excel_path);
            unset($objWriter);
            unset($objPHPExcel);
        }
    }
    function put_excel_bak($excel_path,$csv_datas,$excel_sheet_name,$excel_map) {
        $objPHPExcel = null;
        // 新規作成の場合
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getDefaultStyle()->getFont()->setName( 'ＭＳ Ｐゴシック' )->setSize( 11 );
        
        foreach ($excel_map as $xbrl_count => $xbrl_path_loop_number){
            if($xbrl_path_loop_number > 0) $objPHPExcel->createSheet();
            // 0番目のシートをアクティブにする（シートは左から順に、0、1，2・・・）
            $objPHPExcel->setActiveSheetIndex($xbrl_path_loop_number);
            // アクティブにしたシートの情報を取得
            $objSheet = $objPHPExcel->getActiveSheet();
            // シート名を変更する
            $objSheet->setTitle($excel_sheet_name[$xbrl_path_loop_number]);
            $excel_tate = 0;
            $line = 0;
            foreach ($csv_datas[$xbrl_count] as $values){
                $excel_tate = $line + 1;
                foreach ($values as $value_number => $col) {
                    if(!isset($this->alphabet[$value_number])){
                        $this->_insert_log_message(array('error','none alphabet '.$value_number.':'.$excel_path));
                    }
                    $excel_yoko = $this->alphabet[$value_number];
                    $excel_column_name = $excel_yoko.$excel_tate;
                    
                    if (is_numeric($col)) {
                        $data[$xbrl_path_loop_number][$excel_column_name] = $col;
                        $objSheet->setCellValue($excel_column_name, $col);
                    } else {
                        if(is_array($col)){
                            var_dump($col);
                            die();
                        }
                        //$col = mb_convert_encoding($col, $toEncoding, $srcEncoding);
                        $col = str_replace('"', '""', $col);
                        $data[$xbrl_path_loop_number][$excel_column_name] = $col;
                        $objSheet->setCellValue($excel_column_name, $col);
                    }
                }
                $line++;
            }
            $objPHPExcel->setActiveSheetIndex(0);//sheet選択
            // IOFactory.phpを利用する場合
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            if(is_null($excel_path)){
                return $objWriter;
                //$objWriter->save('php://output');
            }else{
                $objWriter->save($excel_path);
                unset($objWriter);
                unset($objPHPExcel);
            }
            
        }
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

    // ----------------------------------------------------------------
    // CSV入力
    // ----------------------------------------------------------------
    function _fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = "";
        $eof = false; // Added for PHP Warning.
        while ( $eof != true ) {
        $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
        $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
        if ($itemcnt % 2 == 0) $eof = true;
        }
        $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';

        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);

        $_csv_data = $_csv_matches[1];

        for ( $_csv_i=0; $_csv_i<count($_csv_data); $_csv_i++ ) {
        $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
        $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }

    function _read_form_item_csv($csv_file,$is_base = FALSE,$skip = TRUE){
        $fp=@fopen($csv_file,"r");
        $line = 0;
        $csv = array();
        while ($CSVRow = @$this->_fgetcsv_reg($fp,1024)){//ファイルを一行ずつ読み込む
            //XbrlSearchDlInfo.csvは上部2行が不要
            if($skip && $line === 0){
                $skip = TRUE;
            }elseif($skip && $line === 1){
                $skip = FALSE;
            }else{
                $csv[$line]['element'] = $is_base ? $CSVRow[0] : mb_convert_encoding($CSVRow[0],"UTF-8","SJIS-win");//jpdei_cor:NumberOfSubmissionDEI
                $csv[$line]['element_name'] = $is_base ? $CSVRow[1] : mb_convert_encoding($CSVRow[1],"UTF-8","SJIS-win");//提出回数
                $csv[$line]['context_period'] = $is_base ? $CSVRow[2] : mb_convert_encoding($CSVRow[2],"UTF-8","SJIS-win");//提出日時点
                $csv[$line]['context_consolidated'] = $is_base ? $CSVRow[3] : mb_convert_encoding($CSVRow[3],"UTF-8","SJIS-win");//連結
                $csv[$line]['context_term'] = $is_base ? $CSVRow[4] : mb_convert_encoding($CSVRow[4],"UTF-8","SJIS-win");//時点
                $csv[$line]['unit'] = $is_base ? $CSVRow[5] : mb_convert_encoding($CSVRow[5],"UTF-8","SJIS-win");//JPY
                $csv[$line]['value'] = $is_base ? $CSVRow[6] : mb_convert_encoding($CSVRow[6],"UTF-8","SJIS-win");//値
            }
            $line++;
        }
        fclose( $fp );
        return $csv;
    }

}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */