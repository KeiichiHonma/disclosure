<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Items
 *
 * @author    kh
 */
class Item_model extends CI_Model
{
    var $CI;
    private $table_name            = 'items';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getItemById($item_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($item_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getItemByElementName($element_name)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.element_name = ?"
        , array($element_name)
        );

        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    function getAllItems()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );
        if ($query->num_rows() != 0) return $query->result('flip','key');
        return array();
    }

    function getItemsOrder($order, $page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('paging_count_per_manage_page');
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

    function getItemStringByItems($items)
    {
        $result = array();
        foreach($items as $item) {
            $result[] = $item->item_name;
        }

        return implode($result, ' ');
    }

    function insertItem($itemData) {
        $data = array(
            'name_ja' => $itemData['name_ja'],
            'name_en' => $itemData['name_en'],
            'name_th' => $itemData['name_th'],
            'order' => $itemData['order'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updateItem($item_id,$itemData) {
        $data = array(
            'name_ja' => $itemData['name_ja'],
            'name_en' => $itemData['name_en'],
            'name_th' => $itemData['name_th'],
            'order' => $itemData['order'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $item_id);
        return $this->db->update($this->table_name, $data);
    }

    function getItemId($item_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.item_name = ?"
            , array(trim($item_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deleteItem($item_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $item_id);
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

/* End of file item_model.php */
/* Location: ./application/models/item_model.php */