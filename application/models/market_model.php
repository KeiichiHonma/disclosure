<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Markets
 *
 * @author    kh
 */
class market_model extends CI_Model
{
    var $CI;
    private $table_name            = 'markets';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
    }

    function getmarketById($market_id)
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.id = ?"
        , array(intval($market_id))
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }
    
    function getAllMarkets($flip_key = 'id')
    {
        $query = $this->db->query("SELECT *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.id ASC"
        );
        if ($query->num_rows() != 0) return $query->result('flip',$flip_key);
        return array();
    }
}

/* End of file market_model.php */
/* Location: ./application/models/market_model.php */