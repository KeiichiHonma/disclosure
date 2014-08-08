<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Documents
 *
 * @author    kh
 */
class Document_model extends CI_Model
{
    var $CI;
    private $table_name            = 'documents';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getDocumentById($document_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($document_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getDocumentDateGroupByDate()
    {
        $query = $this->db->query("SELECT date
                                    FROM {$this->table_name}
                                    GROUP BY {$this->table_name}.date
                                    ORDER BY {$this->table_name}.date ASC
                                    LIMIT 0,7"
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getDocumentDataByDocumentId($document_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM document_datas
                                    WHERE document_datas.document_id = ?"
        , array(intval($document_id))
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getDocumentByCode($code)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.code = ?"
        , array($code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getDocumentsByDateOrder($date,$order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.date = ?
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
        , array($date)
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

    function getAllDocumentsByDate($date,$order)
    {
        $result = array();
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.date = ?
                                    ORDER BY {$this->table_name}.{$order}"
        , array($date)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getDocumentsByPresenterIdOrder($presenter_id,$order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.presenter_id = ?
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
        , array(intval($presenter_id))
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

/* End of file document_model.php */
/* Location: ./application/models/document_model.php */