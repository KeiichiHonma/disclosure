<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Zip_upload
 *
 * zip file upload library
 *
 * @package        Zip_upload
 * @author        Yoichiro Sakurai
 * @version        1.0
 */
class Zip_upload
{
    var $CI;
    private $archiver;
    private $extractFiles;
    private $encryptFilename;

    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('upload_folder');
        $this->CI->load->library('image_user_lib');
        $this->archiver = new ZipArchive();
        $this->extractFiles = array();
        $this->encryptFilename = FALSE;
    }

    function setConfig($config)
    {
        foreach($config as $key => $value) {
            $this->$key = $value;
        }
    }

    function validate($zipfile) {
        if ($zipfile['size'] > $this->CI->config->config['upload_issue_max_filesize']) {
            return $this->CI->lang->language['issue_incorrect_filesize'];
        }

        if ($this->archiver->open($zipfile['tmp_name'])) {
            for($i=0; $i < $this->archiver->numFiles; $i++) {
                if ( TRUE === ( $validateResult = $this->isValidImageFilename($this->archiver->getNameIndex($i)) ) ) {
                    // if there is folder in zip file, then throw error forcibly.
                    //extractFilesのkeyに意図的に$iを入れている。まぜかmacで作成したであろうzipの解凍でゴミフォルダが入り、indexがずれるため
                    $this->extractFiles[$i] = $this->archiver->getNameIndex($i);
                }elseif($validateResult == 'continue'){
                    //no exe blacklist or folder
                }else{
                    return $this->CI->lang->language['issue_incorrect_types'];
                }
            }
        }

        $result = count($this->extractFiles) > 0 ? true : $this->CI->lang->language['issue_no_file'];
        return $result;
    }

    function extract($destination_dir = '') {
        $folderPath = $this->CI->upload_folder->getTemporaryFolder($destination_dir);
        $index = 1;
        $is_remove_directory = false;
        natsort($this->extractFiles);//81 wrote 自然順ソート。pixivに準拠
        $i = 0;
        //解凍
        foreach ($this->extractFiles as $key => $extractFile) {
            //解凍順である$keyと意図したリネーム番号$iは異なる
            $newFilename = sprintf('%04d.', $i) . substr(strrchr($this->archiver->getNameIndex($key), '.'), 1);
            if($i == 0) $first_filename = strtolower($newFilename);
            
            $this->archiver->renameIndex($key,$newFilename);
            $this->archiver->extractTo($folderPath,$newFilename);
            if ($this->encryptFilename) {
                //$newFilename = sprintf('%s%04d.%s', $destination_dir, $index++, array_pop(explode('.', $newFilename)));
                //@rename($folderPath . $newFilename, $folderPath . $newFilename);
            }
            $i++;
        }
        //check image file
        if( $_handle = @opendir($folderPath) ) {
            while (false !== ($_entry = readdir($_handle))) {
                if ($_entry != '.' && $_entry != '..') {
                    if (@is_file($folderPath . DIRECTORY_SEPARATOR . $_entry)) {
                        $result = $this->check_file_size_rect($folderPath . DIRECTORY_SEPARATOR . $_entry);
                        if($result !== true){
                            $is_remove_directory = true;
                            break;
                        }else{
                            //小文字へrename
                            @rename($folderPath . DIRECTORY_SEPARATOR . $_entry, $folderPath . DIRECTORY_SEPARATOR . strtolower($_entry));
                        }
                    }
                }
            }
            closedir($_handle);
        }
        if($is_remove_directory){
            $this->remove_directory($folderPath);
            return $result;//error
        }

        //thumb mangahack only
        if ($this->CI->image_user_lib->mangahack_create_thumb)
        {
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
            $first_file = $folderPath . $first_filename;

            // Set the Image Properties
            if ( ! $this->CI->image_user_lib->get_image_properties($first_file))
            {
                return FALSE;
            }
            $array = pathinfo($first_filename);
            $thumb_file = $folderPath . DIRECTORY_SEPARATOR . 'thumb.'.$array['extension'];
            if (@copy($first_file, $thumb_file))
            {
                @chmod($thumb_file, FILE_WRITE_MODE);
            }
            if ( ! ($src_thumb_img = $this->CI->image_user_lib->image_create_gd($thumb_file)))
            {
                return FALSE;
            }
            $this->CI->image_user_lib->mangahack_thumb_image_reproportion();
            $dst_thumb_img = $create($this->CI->image_user_lib->mangahack_thumb_width, $this->CI->image_user_lib->mangahack_thumb_height);

            if ($this->CI->image_user_lib->image_type == 3) // png we can actually preserve transparency
            {
                imagealphablending($dst_thumb_img, FALSE);
                imagesavealpha($dst_thumb_img, TRUE);
            }
            $copy($dst_thumb_img, $src_thumb_img, 0, 0, $this->CI->image_user_lib->x_axis, $this->CI->image_user_lib->y_axis, $this->CI->image_user_lib->mangahack_thumb_width, $this->CI->image_user_lib->mangahack_thumb_height, $this->CI->image_user_lib->width, $this->CI->image_user_lib->height);
            // Or save it
            if ( ! $this->CI->image_user_lib->image_save_gd($dst_thumb_img,$thumb_file))
            {
                return FALSE;
            }
            //  Kill the file handles
            imagedestroy($dst_thumb_img);
            imagedestroy($src_thumb_img);
        }

        return true;
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

    private function isValidImageFilename($filename) {
        $blackList = array('__MACOSX');
        foreach ($blackList as $blackListFilename) {
            if (preg_match("/.*{$blackListFilename}.*/", $filename)) {
                $this->CI->logger->debug(sprintf('black list matches:%s', $filename));
                return 'continue';
            }
        }
        if ( preg_match("/\/$/", $filename) === 1) {
            $this->CI->logger->debug(sprintf('folder matches:%s', $filename));
            return 'continue';
        }else{
            return $this->CI->upload_folder->isImageFile($filename);
        }
    }

    function __destruct()
    {
        $this->archiver->close();
    }

    private function remove_directory($dir) {
        if ($handle = opendir("$dir")) {
        while (false !== ($item = readdir($handle))) {
            if ($item != "." && $item != "..") {
                if (is_dir("$dir/$item")) {
                    remove_directory("$dir/$item");
                } else {
                    unlink("$dir/$item");
                }
            }
        }
        closedir($handle);
        rmdir($dir);
        }
    }

    /**
     * @param rebuilded & filtered array
     */
    private function check_file_size_rect($file_path)
    {
        $result = true;
        if (function_exists('getimagesize')) {
            $D = @getimagesize($file_path);
            $image_width        = $D['0'];
            $image_height        = $D['1'];
        }

        if ($image_width < $this->CI->config->config['upload_issue_image_min_width'] && $image_height < $this->CI->config->config['upload_issue_image_min_height']) {
            $result = $this->CI->lang->language['issue_incorrect_minimum'];
        }

        if ($image_width > $this->CI->config->config['upload_issue_image_max_width'] || $image_height > $this->CI->config->config['upload_issue_image_max_height']) {
            $result = $this->CI->lang->language['issue_incorrect_maximum'];
        }
        return $result;
    }
}

/* End of file Zip_upload.php */
/* Location: ./application/libraries/Zip_upload.php */
