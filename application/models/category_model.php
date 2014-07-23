<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Categories
 *
 * @author    kh
 */
class category_model extends CI_Model
{
    var $CI;
    private $table_name            = 'categories';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getcategoryById($category_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($category_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
    
    function getAllCategories()
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );
        if ($query->num_rows() != 0) return $query->result('flip','name');
        return array();
    }

    function getCategoriesOrder($order, $page)
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

    function getcategoryStringByCategories($categories)
    {
        $result = array();
        foreach($categories as $category) {
            $result[] = $category->category_name;
        }

        return implode($result, ' ');
    }

    function insertcategory($categoryData) {
        $data = array(
            'name_ja' => $categoryData['name_ja'],
            'name_en' => $categoryData['name_en'],
            'name_th' => $categoryData['name_th'],
            'order' => $categoryData['order'],
            'created' => date("Y-m-d H:i:s", time()),
        );
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    function updatecategory($category_id,$categoryData) {
        $data = array(
            'name_ja' => $categoryData['name_ja'],
            'name_en' => $categoryData['name_en'],
            'name_th' => $categoryData['name_th'],
            'order' => $categoryData['order'],
            'modified' => date("Y-m-d H:i:s", time()),
        );
        $this->db->where('id', $category_id);
        return $this->db->update($this->table_name, $data);
    }

    function getcategoryId($category_name) {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.category_name = ?"
            , array(trim($category_name))
        );

        if ($query->num_rows() == 1) return $query->row()->id;
        return 0;
    }

    function deletecategory($category_id, $needTransaction = true)
    {
        if ($needTransaction) {
            //start transaction manually
            $this->db->trans_begin();
        }
        $this->db->where('id', $category_id);
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

/* End of file category_model.php */
/* Location: ./application/models/category_model.php */