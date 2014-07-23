<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Uploadfile_validate
{
    var $CI;
    
    function __construct()
    {
        $this->CI =& get_instance();
    }
    
    function validate($field = 'userfile')
    {

    // Is $_FILES[$field] set? If not, no reason to continue.
        //post_max_size error
        if (empty($_POST) && $_SERVER["REQUEST_METHOD"] === "POST") {
            return $this->CI->lang->language['upload_file_post_limit'];
        }
        
        if ( ! isset($_FILES[$field]))
        {
            return $this->CI->lang->language['upload_no_file_selected'];
        }
        // Was the file able to be uploaded? If not, determine the reason why.
        if ( ! @is_uploaded_file($_FILES[$field]['tmp_name']))
        {
            $result = $this->check_file($_FILES[$field]['error']);
            if($result !== TRUE) return $result;
        }
        return TRUE;
    }

    function multiple_validate($field = 'userfile')
    {

    // Is $_FILES[$field] set? If not, no reason to continue.
        //post_max_size error
        if (empty($_POST) && $_SERVER["REQUEST_METHOD"] === "POST") {
            return $this->CI->lang->language['upload_file_post_limit'];
        }
        
        if ( ! isset($_FILES[$field]))
        {
            return $this->CI->lang->language['upload_no_file_selected'];
        }
        $count = count($_FILES[$field]['tmp_name']);
        for($i = 0; $i < $count; $i++) {
            // Was the file able to be uploaded? If not, determine the reason why.
            if ( ! @is_uploaded_file($_FILES[$field]['tmp_name'][$i]))
            {
                if($_FILES[$field]['tmp_name'][$i] == '') continue;
                $result = $this->check_file($_FILES[$field]['error'][$i]);
                if($result !== TRUE) return $result;
            }
        }
        return TRUE;
    }
    
    private function check_file($files_error){
        $result = TRUE;
        $error = ( ! isset($files_error) ) ? 4 : $files_error;

        switch($error)
        {
            case 1:    // UPLOAD_ERR_INI_SIZE
                $result = $this->CI->lang->language['upload_userfile_not_set'];
                break;
            case 2: // UPLOAD_ERR_FORM_SIZE
                $result = $this->CI->lang->language['upload_file_exceeds_form_limit'];
                break;
            case 3: // UPLOAD_ERR_PARTIAL
                $result = $this->CI->lang->language['upload_file_partial'];
                break;
            case 4: // UPLOAD_ERR_NO_FILE
                $result = $this->CI->lang->language['upload_no_file_selected'];
                break;
            case 6: // UPLOAD_ERR_NO_TMP_DIR
                $result = $this->CI->lang->language['upload_no_temp_directory'];
                break;
            case 7: // UPLOAD_ERR_CANT_WRITE
                $result = $this->CI->lang->language['upload_unable_to_write_file'];
                break;
            case 8: // UPLOAD_ERR_EXTENSION
                $result = $this->CI->lang->language['upload_stopped_by_extension'];
                break;
            default :   $result = $this->CI->lang->language['upload_no_file_selected'];
                break;
        }
        return $result;
    }
}
