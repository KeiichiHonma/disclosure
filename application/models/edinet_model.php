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
}

/* End of file edinet_model.php */
/* Location: ./application/models/edinet_model.php */