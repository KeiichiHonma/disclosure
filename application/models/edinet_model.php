<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Edinets
 *
 * @author    kh
 */
class Edinet_model extends CI_Model
{
    var $CI;
    private $table_name            = 'edinets';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getEdinetById($edinet_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($edinet_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getEdinetByEdinetCode($edinet_code)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.edinet_code = ?"
        , array($edinet_code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getEdinetByPresenterNameKey($presenter_name_key)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.presenter_name_key = ?"
        , array($presenter_name_key)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getAllEdinets()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );
        if ($query->num_rows() != 0) return $query->result('flip','key');
        return array();
    }

    function getEdinetsOrder($order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
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

    /**
     * get keyword promotions
     *
     * @param    array keywords
     * @return    array
     */
    function getEdinetsByKeywords($keywords, $page, $orderExpression = 'tab_job_cdata.col_disclosure DESC')
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $queryParameter = array();

        $queryString = "SELECT SQL_CALC_FOUND_ROWS *,{$this->table_name}.id AS id
                                    FROM {$this->table_name}
                                    INNER JOIN documents ON {$this->table_name}.id = documents.edinet_id
                                    INNER JOIN tab_job_company ON {$this->table_name}.security_code = tab_job_company.col_code
                                    INNER JOIN tab_job_cdata ON ( tab_job_company._id = tab_job_cdata.col_cid AND tab_job_cdata.col_edition = 1 )
                                    WHERE (";
        for ($index = 0; $index < count($keywords); $index++) {
            if ($index != 0) $queryString .= "OR ";
            $queryString .= "(";
            $queryString .= "{$this->table_name}.presenter_name LIKE ? OR {$this->table_name}.presenter_name_en LIKE ? OR {$this->table_name}.presenter_name_kana LIKE ? OR {$this->table_name}.security_code LIKE ?";//presenter_name
            $queryString .= ")";
            for ($i=0;$i<4;$i++){
                $queryParameter[] = "%{$keywords[$index]}%";
            }
        }
        $queryString .= ")  GROUP BY {$this->table_name}.id ORDER BY {$orderExpression} LIMIT {$offset}, {$perPageCount}";
        
        $query = $this->db->query($queryString, $queryParameter);

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
}

/* End of file edinet_model.php */
/* Location: ./application/models/edinet_model.php */