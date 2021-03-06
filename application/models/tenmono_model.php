<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tenmonos
 *
 * @author    kh
 */
class Tenmono_model extends CI_Model
{
    var $CI;
    //var $db2;
    private $table_name            = 'documents';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
        //$this->db2 = $this->load->database('database2', TRUE);
    }

    function getVarietyByVarietyName($variety_name)
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_variety
                                    WHERE tab_job_variety.col_name = ?"
        , array($variety_name)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllTenmonoCategories()
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_variety
                                    ORDER BY tab_job_variety._id ASC"
        );
        if ($query->num_rows() != 0) return $query->result('flip','_id');
        return array();
    }

    function getAllCompany()
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_company"
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
    //tools only
    function getAllCdataByYear($year)
    {
        $result = array();
        $from_time = mktime(0,0,0,1,1,$year);
        $to_time = mktime(23,59,59,12,31,$year);
        $orderExpression = "col_disclosure DESC";//公開日
        $query = $this->db->query("SELECT *
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    INNER JOIN edinets ON edinets.edinet_code = tab_job_company.col_edinet_code
                                    WHERE tab_job_cdata.col_disclosure >= ? AND tab_job_cdata.col_disclosure <= ?
                                    ORDER BY {$orderExpression}"
        ,array(intval($from_time),intval($to_time))
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    //tools only
    function getCdatasByRecent()
    {
        $result = array();
        $resent_date = date("Y-m-d H:i:s", time() - 86400);//1日前
        $orderExpression = "col_income DESC";
        $query = $this->db->query("SELECT *
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    INNER JOIN edinets ON edinets.edinet_code = tab_job_company.col_edinet_code
                                    WHERE tab_job_cdata.created >= '{$resent_date}'
                                    ORDER BY {$orderExpression}"
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getCompanyByCompanyId($company_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_company
                                    WHERE tab_job_company._id = ?"
        , array(intval($company_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getCompanyBySecurityCode($security_code)
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_company
                                    WHERE tab_job_company.col_code = ?"
        , array($security_code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getCompanyByEdinetCode($edinet_code)
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_company
                                    INNER JOIN edinets ON edinets.edinet_code = tab_job_company.col_edinet_code
                                    WHERE tab_job_company.col_edinet_code = ?"
        , array($edinet_code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getCdataByYear($company_id,$year)
    {
        $from_time = mktime(0,0,0,1,1,$year);
        $to_time = mktime(23,59,59,12,31,$year);
        $query = $this->db->query("SELECT *
                                    FROM tab_job_cdata
                                    WHERE tab_job_cdata.col_cid = ? AND tab_job_cdata.col_disclosure >= ? AND tab_job_cdata.col_disclosure <= ?"
        , array(intval($company_id),intval($from_time),intval($to_time))
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getCdatasByCompanyId($company_id,$order = 'tab_job_cdata.col_disclosure DESC')
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_cdata
                                    WHERE tab_job_cdata.col_cid = ?
                                    ORDER BY {$order}"
        , array(intval($company_id))
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getCdataByCode($code)
    {
        $query = $this->db->query("SELECT *
                                    FROM tab_job_cdata
                                    WHERE tab_job_cdata.col_code = ?"
        , array($code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getCdataOrder($year,$orderExpression,$page)
    {
        $result = array();
        $from_time = mktime(0,0,0,1,1,$year);
        $to_time = mktime(23,59,59,12,31,$year);
        $perPageCount = $this->CI->config->item('paging_count_per_page');

        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    INNER JOIN edinets ON edinets.edinet_code = tab_job_company.col_edinet_code
                                    WHERE tab_job_cdata.col_disclosure >= ? AND tab_job_cdata.col_disclosure <= ?
                                    ORDER BY {$orderExpression}
                                    LIMIT {$offset},{$perPageCount}"
        ,array(intval($from_time),intval($to_time))
        );

        if ($query->num_rows() != 0) {
            $result['data'] = $query->result();
            $query = $this->db->query("SELECT FOUND_ROWS() as count");
            if($query->num_rows() == 1) {
                foreach ($query->result() as $row)
                $result['count'] = $row->count;
            }
        } else {
            $result['data'] = array();
            $result['count'] = 0;
        }

        return $result;
    }

    function getCdataByCategoryIdOrderDisclosure($category_id,$year,$orderExpression, $page)
    {
        $result = array();
        $from_time = mktime(0,0,0,1,1,$year);
        $to_time = mktime(23,59,59,12,31,$year);
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    INNER JOIN edinets ON edinets.edinet_code = tab_job_company.col_edinet_code
                                    WHERE edinets.category_id = ? AND tab_job_cdata.col_disclosure >= ? AND tab_job_cdata.col_disclosure <= ?
                                    ORDER BY {$orderExpression}
                                    LIMIT {$offset},{$perPageCount}"
        , array(intval($category_id),intval($from_time),intval($to_time))
        );
        if ($query->num_rows() != 0) {
            $result['data'] = $query->result();
            $query = $this->db->query("SELECT FOUND_ROWS() as count");
            if($query->num_rows() == 1) {
                foreach ($query->result() as $row)
                $result['count'] = $row->count;
            }
        } else {
            $result['data'] = array();
            $result['count'] = 0;
        }

        return $result;
    }

    function getCdataByMarketIdOrderDisclosure($market_id,$year,$orderExpression, $page)
    {
        $result = array();
        $from_time = mktime(0,0,0,1,1,$year);
        $to_time = mktime(23,59,59,12,31,$year);
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    INNER JOIN edinets ON edinets.edinet_code = tab_job_company.col_edinet_code
                                    WHERE edinets.market_id = ? AND tab_job_cdata.col_disclosure >= ? AND tab_job_cdata.col_disclosure <= ?
                                    ORDER BY {$orderExpression}
                                    LIMIT {$offset},{$perPageCount}"
        , array(intval($market_id),intval($from_time),intval($to_time))
        );

        if ($query->num_rows() != 0) {
            $result['data'] = $query->result();
            $query = $this->db->query("SELECT FOUND_ROWS() as count");
            if($query->num_rows() == 1) {
                foreach ($query->result() as $row)
                $result['count'] = $row->count;
            }
        } else {
            $result['data'] = array();
            $result['count'] = 0;
        }

        return $result;
    }

    function getCdatasNotInCompanyIdHighAndLowIncomeByVarietyid($company_id,$income,$vid,$sign = '>=',$order = 'ASC',$year)
    {
        $from_time = mktime(0,0,0,1,1,$year);
        $to_time = mktime(23,59,59,12,31,$year);
        $query = $this->db->query("SELECT *
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    INNER JOIN edinets ON edinets.edinet_code = tab_job_company.col_edinet_code
                                    WHERE tab_job_company._id != ? AND tab_job_cdata.col_income {$sign} ? AND tab_job_company.col_vid = ? AND tab_job_cdata.col_disclosure >= ? AND tab_job_cdata.col_disclosure <= ?
                                    ORDER BY tab_job_cdata.col_income {$order}
                                    LIMIT 0,5"
        , array(intval($company_id),intval($income),intval($vid),intval($from_time),intval($to_time))
        );

        if ($query->num_rows() != 0) return $query->result('flip','col_cid');
        return array();
    }

    function getCompanyCountByVarietyid($vid)
    {
        $query = $this->db->query("SELECT COUNT(tab_job_cdata.col_cid) as count
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    WHERE tab_job_company.col_vid = ? AND tab_job_cdata.col_edition = 1 AND tab_job_company.col_validate = 0"
        , array(intval($vid))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getCdatasRankByVariety_id($vid,$col_income,$year)
    {
        $from_time = mktime(0,0,0,1,1,$year);
        $to_time = mktime(23,59,59,12,31,$year);
        $query = $this->db->query("SELECT COUNT(tab_job_cdata.col_cid) as count
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    WHERE tab_job_cdata.col_vid = ? AND tab_job_cdata.col_income > ? AND tab_job_company.col_validate = 0 AND tab_job_cdata.col_disclosure >= ? AND tab_job_cdata.col_disclosure <= ?"
        , array(intval($vid),$col_income,intval($from_time),intval($to_time))
        );
        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
}

/* End of file document_model.php */
/* Location: ./application/models/document_model.php */