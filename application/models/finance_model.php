<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Finances
 *
 * @author    kh
 */
class Finance_model extends CI_Model
{
    var $CI;
    private $table_name            = 'document_finances';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getFinanceById($document_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($document_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getFinancesByEdinetId($edinet_id,$from_year,$orderExpression = 'date DESC')
    {
        $from_date = $from_year.'-01-01 00:00:00';
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    INNER JOIN documents ON documents.id = document_finances.document_id
                                    INNER JOIN edinets ON edinets.id = documents.edinet_id
                                    WHERE edinets.id = ? && documents.date > ?
                                    ORDER BY {$orderExpression}"
        , array(intval($edinet_id),$from_date)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getFinancesOrder($year, $orderExpression, $page)
    {
        $result = array();
        $from_date = $year.'-01-01 00:00:00';
        $to_date = $year.'-12-31 23:59:59';
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    INNER JOIN documents ON documents.id = document_finances.document_id
                                    INNER JOIN edinets ON edinets.id = documents.edinet_id
                                    WHERE documents.date >= ? AND documents.date <= ?
                                    ORDER BY {$orderExpression}
                                    LIMIT {$offset},{$perPageCount}"
        , array($from_date,$to_date)
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

    function getFinancesOrderByCategoryId($category_id, $year, $orderExpression, $page)
    {
        $result = array();
        $from_date = $year.'-01-01 00:00:00';
        $to_date = $year.'-12-31 23:59:59';
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    INNER JOIN documents ON documents.id = document_finances.document_id
                                    INNER JOIN edinets ON edinets.id = documents.edinet_id
                                    WHERE documents.category_id = ? AND documents.date >= ? AND documents.date <= ?
                                    ORDER BY {$orderExpression}
                                    LIMIT {$offset},{$perPageCount}"
        , array(intval($category_id),$from_date,$to_date)
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

    function getFinancesOrderByMarketId($market_id, $year, $orderExpression, $page)
    {
        $result = array();
        $from_date = $year.'-01-01 00:00:00';
        $to_date = $year.'-12-31 23:59:59';
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    INNER JOIN documents ON documents.id = document_finances.document_id
                                    INNER JOIN edinets ON edinets.id = documents.edinet_id
                                    WHERE edinets.market_id = ? AND documents.date >= ? AND documents.date <= ?
                                    ORDER BY {$orderExpression}
                                    LIMIT {$offset},{$perPageCount}"
        , array(intval($market_id),$from_date,$to_date)
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

    //tools only
    function getAllFinancesByYear($year)
    {
        $from_date = $year.'-01-01 00:00:00';
        $to_date = $year.'-12-31 23:59:59';
        $orderExpression = "date DESC";//作成新しい
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    INNER JOIN documents ON documents.id = document_finances.document_id
                                    INNER JOIN edinets ON edinets.id = documents.edinet_id
                                    WHERE documents.date >= ? AND documents.date <= ?
                                    ORDER BY {$orderExpression}"
        , array($from_date,$to_date)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
    //tools only
    function getFinancesAndCdatasByRecent()
    {
        $resent_date = date("Y-m-d H:i:s", time() - 86400);//1日前
        $orderExpression = "net_sales DESC,col_income DESC";
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    INNER JOIN documents ON documents.id = document_finances.document_id
                                    INNER JOIN edinets ON edinets.id = documents.edinet_id
                                    INNER JOIN tab_job_company ON tab_job_company.col_edinet_code = edinets.edinet_code
                                    INNER JOIN tab_job_cdata ON tab_job_company._id = tab_job_cdata.col_cid
                                    WHERE documents.created >= '{$resent_date}' AND tab_job_cdata.created >= '{$resent_date}'
                                    ORDER BY {$orderExpression}"
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
}

/* End of file document_model.php */
/* Location: ./application/models/document_model.php */