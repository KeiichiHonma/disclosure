<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Contexts
 *
 * @author    kh
 */
class Context_model extends CI_Model
{
    var $CI;
    private $table_name            = 'contexts';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getContextById($Context_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($Context_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getContextByContextName($context_name)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.context_name = ?"
        , array($context_name)
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getAllContexts()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );
        if ($query->num_rows() != 0) return $query->result('flip','key');
        return array();
    }

    function getContextsOrder($order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->Context('paging_count_per_manage_page');
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

    function getContextStringByContexts($Contexts)
    {
        $result = array();
        foreach($Contexts as $Context) {
            $result[] = $Context->Context_name;
        }

        return implode($result, ' ');
    }

    function insertContext($ContextData) {
        $data = array(
            'name_ja' => $ContextData['name_ja'],
            'name_en' => $ContextData['name_en'],
            'name_th' => $ContextData['name_th'],
            'order' => $ContextData['order'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateContext($Context_id,$ContextData) {
        $data = array(
            'name_ja' => $ContextData['name_ja'],
            'name_en' => $ContextData['name_en'],
            'name_th' => $ContextData['name_th'],
            'order' => $ContextData['order'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $Context_id);
        return $this->db->update($this->table_name, $data);
    }

    function getContextId($Context_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.Context_name = ?"
            , array(trim($Context_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteContext($Context_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $Context_id);
        $this->db->delete($this->table_name);

        if ($needTransaction) {
            // check transaction succeeded.
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return true;
            }
        }
    }
}

/* End of file Context_model.php */
/* Location: ./application/models/Context_model.php */