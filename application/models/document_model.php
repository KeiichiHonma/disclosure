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

    function getDocumentsByEdinetId($edinet_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.edinet_id = ?"
        , array(intval($edinet_id))
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
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.date >= ?
                                    GROUP BY {$this->table_name}.category_id"
                                    
        , array($date)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getDocumentsByEdinetIdByIsHtml($edinet_id,$is_html = 0)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.edinet_id = ? AND {$this->table_name}.is_html = ?
                                    ORDER BY {$this->table_name}.date ASC"
        , array(intval($edinet_id),intval($is_html))
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getDocumentDataByDocumentId($document_id)
    {
        $query = $this->db->query("SELECT *,document_datas.element_name AS element_name
                                    FROM document_datas
                                    LEFT JOIN items ON items.id = document_datas.item_id
                                    WHERE document_datas.document_id = ?"
        , array(intval($document_id))
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getDocumentHtmlByDocumentId($document_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM document_htmls
                                    WHERE document_htmls.document_id = ?
                                    ORDER BY document_htmls.id ASC"
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

    function getDocumentsOrder($year, $order, $page)
    {
        $result = array();
        $from_date = $year.'-01-01 00:00:00';
        $to_date = $year.'-12-31 23:59:59';
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.document_name = '有価証券報告書' AND documents.date >= ? AND documents.date <= ?
                                    ORDER BY {$this->table_name}.{$order}
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

    function getDocumentsByCategoryIdOrder($category_id, $year, $order, $page)
    {
        $result = array();
        $from_date = $year.'-01-01 00:00:00';
        $to_date = $year.'-12-31 23:59:59';
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.category_id = ? AND {$this->table_name}.document_name = '有価証券報告書' AND documents.date >= ? AND documents.date <= ?
                                    ORDER BY {$this->table_name}.{$order}
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

    function getDocumentsByMarketIdOrder($market_id, $year, $order, $page)
    {
        $result = array();
        $from_date = $year.'-01-01 00:00:00';
        $to_date = $year.'-12-31 23:59:59';
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    INNER JOIN edinets ON edinets.id = documents.edinet_id
                                    WHERE edinets.market_id = ? AND {$this->table_name}.document_name = '有価証券報告書' AND documents.date >= ? AND documents.date <= ?
                                    ORDER BY {$this->table_name}.{$order}
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

    function getDocumentsByEdinetIdOrder($edinet_id,$order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.edinet_id = ?
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
        , array(intval($edinet_id))
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
    //function getAllDocuments($offset,$perPageCount)
    function getAllDocuments()
    {
        $result = array();
/*
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.document_name = '有価証券報告書'
                                    LIMIT {$offset},{$perPageCount}"
        );
*/
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.document_name = '有価証券報告書'"
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getAllDocumentDataByItemId($item_id,$offset,$perPageCount,$context_period = '当期末')
    {
        $query = $this->db->query("SELECT *
                                    FROM document_datas
                                    INNER JOIN documents ON documents.id = document_datas.document_id
                                    WHERE document_datas.item_id = ? AND document_datas.context_period = ? AND documents.document_name = '有価証券報告書'
                                    GROUP BY document_datas.document_id
                                    LIMIT {$offset},{$perPageCount}"
        , array(intval($item_id),$context_period)
        );
        if ($query->num_rows() != 0) return $query->result('flip','document_id');
        return array();
    }

    function getDocumentDataByDocumentIdByTarget($document_id,$item_id,$context_period = '当期末',$context_consolidated = '連結')
    {
        $query = $this->db->query("SELECT *
                                    FROM document_datas
                                    WHERE document_datas.document_id = ? AND document_datas.item_id = ? AND document_datas.context_period = ? AND document_datas.context_consolidated = ?"
        , array(intval($document_id),intval($item_id),$context_period,$context_consolidated)
        );
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }
}

/* End of file document_model.php */
/* Location: ./application/models/document_model.php */