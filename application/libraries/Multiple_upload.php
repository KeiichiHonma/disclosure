<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Multiple_upload
 *
 * Multiple image file upload library
 *
 * @package        Multiple_upload
 * @author        Yoichiro Sakurai
 * @version        1.0
 */
class Multiple_upload
{
    var $CI;
    public $upload_path             = "";
    public $uploadFiles             = array();
    public $encrypt_name            = TRUE;
    public $is_image                = TRUE;
    public $image_width             = '';
    public $image_height            = '';
    public $image_type              = '';
    public $error_msg               = array();

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('upload_folder');
        $this->CI->load->library('image_user_lib');
    }

    /**
     * @return    bool
     */
    function validate($files)
    {
        if (count($files) < 1) {
            $error_msg[] = array("There are no files uploaded.");
            return $error_msg;
        }
        return $this->check_file_size_rect($files);
    }

    /**
     * @return    rebuild array
     */
    private function rebuild($files)
    {
        $result = array();
        $count = count($files['name']);
        for($i = 0; $i < $count; $i++) {
            $item = array();
            foreach($files as $key => $value) {
                if($count == 1){
                    $item[$key] = $value;
                }else{
                    $item[$key] = $value[$i];
                }
            }
            $result[] = $item;
        }

        return $result;
    }

    /**
     * @param rebuilded array
     * @return    filtered array
     */
    function filter($files)
    {
        $result = array();
        $files = $this->rebuild($files);
        foreach($files as $file) {
            if (empty($file['name']) || empty($file['tmp_name'])) continue;
            if ($this->is_image) {
                if (! in_array($file['type'], $this->allowed_types)) continue;
            }
            $result[] = $file;
        }

        return $result;
    }

    /**
     * @param upload files array
     * @return    boolean
     */
    function upload($files,$create_coupon_files, $page_array = array('action'=>null))
    {
        //$create_coupon_files  array('galleryData'=>$galleryData,'thumb_file'=>null,'filesArray'=>$filesArray);
        if ($this->CI->image_user_lib->image_library == 'gd2' AND function_exists('imagecreatetruecolor'))
        {
            $create    = 'imagecreatetruecolor';
            $copy    = 'imagecopyresampled';
        }
        else
        {
            $create    = 'imagecreate';
            $copy    = 'imagecopyresized';
        }
        
        //$folderPath = $this->CI->upload_folder->getTemporaryFolder($destination_dir);
        $index = 1;
        $first_file = '';
        $thumb_file = '';
        foreach ($files as $key=> $file) {
            //add
            if($page_array['action'] == 'addGallery'){
                @move_uploaded_file($file['tmp_name'], $create_coupon_files['filesArray'][0]['image_filepath']);
                if(!is_file($create_coupon_files['filesArray'][0]['image_filepath'])) return FALSE;
                return TRUE;
            //update
            }elseif($page_array['action'] == 'updateGallery'){
                if($create_coupon_files['is_difference_ext']){
                    @move_uploaded_file($file['tmp_name'], $create_coupon_files['filesArray'][0]['image_filepath']);
                    if(!is_file($create_coupon_files['filesArray'][0]['image_filepath'])) return FALSE;
                    @unlink($create_coupon_files['galleryData']['old_image_filepath']);
                }else{
                    @move_uploaded_file($file['tmp_name'], $create_coupon_files['filesArray'][0]['image_filepath']);
                }

                //thumbnail
                if(strcasecmp($create_coupon_files['galleryData']['face'],1) == 0){
                    $this->CI->image_user_lib->balloooooon_create_thumb = TRUE;
                    $first_file = $create_coupon_files['filesArray'][0]['image_filepath'];
                    $thumb_file = $create_coupon_files['thumb_file'];
                }else{
                    return TRUE;
                }
            //coupon add
            }else{
                if($key == 0 ) {
                    $this->CI->image_user_lib->balloooooon_create_thumb = TRUE;
                    $first_file = $create_coupon_files['filesArray'][0]['image_filepath'];
                    $thumb_file = $create_coupon_files['thumb_file'];
                }
                @move_uploaded_file($file['tmp_name'], $create_coupon_files['filesArray'][$key]['image_filepath']);
                if(!is_file($create_coupon_files['filesArray'][$key]['image_filepath'])) return FALSE;
            }
        }

        //balloooooon only
        if ($this->CI->image_user_lib->balloooooon_create_thumb)
        {
            // Set the Image Properties
            if ( ! $this->CI->image_user_lib->get_image_properties($first_file))
            {
                return FALSE;
            }
            if (@copy($first_file, $thumb_file))
            {
                @chmod($thumb_file, FILE_WRITE_MODE);
            }
            if ( ! ($src_thumb_img = $this->CI->image_user_lib->image_create_gd($thumb_file)))
            {
                return FALSE;
            }
            $this->CI->image_user_lib->balloooooon_thumb_image_reproportion();
            $dst_thumb_img = $create($this->CI->image_user_lib->balloooooon_thumb_width, $this->CI->image_user_lib->balloooooon_thumb_height);

            if ($this->CI->image_user_lib->image_type == 3) // png we can actually preserve transparency
            {
                imagealphablending($dst_thumb_img, FALSE);
                imagesavealpha($dst_thumb_img, TRUE);
            }
            $copy($dst_thumb_img, $src_thumb_img, 0, 0, $this->CI->image_user_lib->x_axis, $this->CI->image_user_lib->y_axis, $this->CI->image_user_lib->balloooooon_thumb_width, $this->CI->image_user_lib->balloooooon_thumb_height, $this->CI->image_user_lib->width, $this->CI->image_user_lib->height);
            // Or save it
            if ( ! $this->CI->image_user_lib->image_save_gd($dst_thumb_img,$thumb_file))
            {
                return FALSE;
            }
            if(isset($create_coupon_files['galleryData']['face']) && strcasecmp($create_coupon_files['galleryData']['face'],1) == 0 && $create_coupon_files['is_difference_ext']) @unlink($create_coupon_files['galleryData']['old_thumbnail_filename']);
            //  Kill the file handles
            imagedestroy($dst_thumb_img);
            imagedestroy($src_thumb_img);
        }

        //return $folderPath;
        return TRUE;
    }
    
    function delete($filepath)
    {
        if(is_file($filepath)) @unlink($filepath);
    }
    
    function createTemporaryFolder() {
        $folderName = date('YmdHis') . substr(md5(uniqid(mt_rand())), 0, 4);
        if ($this->CI->upload_folder->createFolder($this->CI->upload_folder->getTemporaryFolder($folderName))) {
            return $folderName;
        } else {
            $this->CI->logger->emerg(sprintf('failed to create temporary folder:%s', $folderName));
            return false;
        }
    }

    /**
     * @param rebuilded & filtered array
     */
    private function check_file_size_rect($files)
    {
        $files = $this->rebuild($files);
        $result = true;
        $size = 0;
        foreach($files as $key =>  $file) {
            if (empty($file['name']) || empty($file['tmp_name'])) continue;
            //size
            $size = $size + $file['size'];
            //type
            if ($this->is_image && ! in_array($file['type'], $this->CI->config->config['upload_coupon_image_allowed_types'])) {
                $result = '['.$file['name'].']'.$this->CI->lang->language['coupon_incorrect_types'];
                break;
            }
            
            //width height
            if (function_exists('getimagesize')) {
                $D = @getimagesize($file['tmp_name']);
                $image_width        = $D['0'];
                $image_height        = $D['1'];
                
                if ($image_width < $this->CI->config->config['upload_coupon_image_min_width'] && $image_height < $this->CI->config->config['upload_coupon_image_min_height']) {
                    $result = '['.$file['name'].']'.$this->CI->lang->language['coupon_incorrect_minimum'];
                    break;
                }

                if ($image_width > $this->CI->config->config['upload_coupon_image_max_width'] || $image_height > $this->CI->config->config['upload_coupon_image_max_height']) {
                    $result = '['.$file['name'].']'.$this->CI->lang->language['coupon_incorrect_maximum'];
                    break;
                }
            }else{
                $result = $this->CI->lang->language['coupon_unclear'];
                break;
            }
            $this->uploadFiles[] = $file;
        }
        if ($size > $this->CI->config->config['upload_coupon_max_filesize']) {
            $result = $this->CI->lang->language['coupon_incorrect_filesize'];
        }
        return $result;
    }
}

/* End of file Multiple_upload.php */
/* Location: ./application/libraries/Multiple_upload.php */
