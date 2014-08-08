<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Tenmonos
 *
 * @author    kh
 */
class Tenmono_model extends CI_Model
{
    var $CI;
    var $db2;
    private $table_name            = 'documents';
    
    function __construct()
    {
        parent::__construct();
        $this->CI =& get_instance();
        $this->db2 = $this->load->database('database2', TRUE);
    }

    function getCompanyBySecurityCode($security_code)
    {
        $query = $this->db2->query("SELECT *
                                    FROM tab_job_company
                                    WHERE tab_job_company.col_code = ?"
        , array($security_code)
        );

        if ($query->num_rows() == 1) return $query->row();
        return array();
    }

    function getCdataOrderDisclosure($page)
    {
        $result = array();
        $perPageCount = $this->CI->config->item('cdata_paging_count_per_page');

        $offset = $perPageCount * ($page - 1);
        $query = $this->db2->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM tab_job_cdata
                                    INNER JOIN tab_job_company ON tab_job_company._id = tab_job_cdata.col_cid
                                    ORDER BY tab_job_cdata.col_disclosure DESC
                                    LIMIT {$offset},{$perPageCount}"
        );

        if ($query->num_rows() != 0) {
            $result['data'] = $query->result();
            $query = $this->db2->query("SELECT FOUND_ROWS() as count");
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