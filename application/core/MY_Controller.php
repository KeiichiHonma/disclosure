<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        // use validation whole page
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="errorMessage">', '</p>');

        $this->zend->load('Zend/Log');
        $this->zend->load('Zend/Log/Writer/Stream');
        $filepath = $this->config->item('zend_log_filename');
        $writer = new Zend_Log_Writer_Stream($filepath);
        $this->logger = new Zend_Log($writer);
        $writer->addFilter(new Zend_Log_Filter_Priority(intval($this->config->item('zend_start_log_level'))));
        $writer->addFilter(new Zend_Log_Filter_Priority(intval($this->config->item('zend_end_log_level')),">="));
    }

}

/* End of file Auth_Controller.php */
/* Location: ./application/libraries/Auth_Controller.php */