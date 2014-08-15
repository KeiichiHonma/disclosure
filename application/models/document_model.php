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

    function getDocumentsBySecurityCode($code)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    INNER JOIN presenters ON {$this->table_name}.presenter_id = presenters.id
                                    WHERE presenters.securities_code = ?"
        , array($code)
        );
        if ($query->num_rows() != 0) return $query->result();
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

    function getDocumentsCategoryByDateGroupByCategory($date)
    {
        $query = $this->db->query("SELECT date
                                    FROM {$this->table_name}
                                    GROUP BY {$this->table_name}.category_id
                                    ORDER BY {$this->table_name}.date ASC
                                    WHERE {$this->table_name}.date >= ?"
        , array($date)
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

    function getDocumentsOrder($order, $page)
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

    function getAllDocuments()
    {
        $result = array();
        $query = $this->db->query("SELECT *,{$this->table_name}.id AS id,tab_job_variety._id AS vid
                                    FROM {$this->table_name}
                                    INNER JOIN presenters ON {$this->table_name}.presenter_id = presenters.id
                                    INNER JOIN securities ON presenters.securities_code = securities.id
                                    INNER JOIN tab_job_variety ON tab_job_variety.col_name = securities.category_name"
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
}

/* End of file document_model.php */
/* Location: ./application/models/document_model.php */