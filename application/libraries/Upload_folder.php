<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Upload_folder
 *
 * upload folder name utility
 *
 * @package		Upload_folder
 * @author		Yoichiro Sakurai
 * @version		1.0
 */
class Upload_folder
{
	var $CI;

	function __construct()
	{
		$this->CI =& get_instance();
	}

	function getUploadFolderTop() {
		return $this->CI->config->item('upload_folder_top') . '/';
	}

	function getTemporaryFolder($child_dir = '') {
		$folderPath = 	array(
							$this->CI->config->item('upload_folder_top'),
							$this->CI->config->item('upload_temporary_folder'),
						);
		if (! empty($child_dir)) $folderPath[] = $child_dir;

		return implode('/', $folderPath) . '/';
	}

	function getComicsFolder($user_id = 0) {
		$folderPath = 	array(
							$this->CI->config->item('upload_folder_top'),
							$this->CI->config->item('upload_comics_folder'),
						);
		if (intval($user_id) > 0) $folderPath[] = $user_id;
		$folderPath = implode('/', $folderPath) . '/';
		if (!file_exists($folderPath)) $this->createFolder($folderPath);

		return $folderPath;
	}

	function getProfileFolder() {
		return implode('/',
						array(
							$this->CI->config->item('upload_folder_top'),
							$this->CI->config->item('upload_profile_folder'),
						)
					) . '/';
	}

	function createFolder($folderPath) {
		mkdir($folderPath);
		return chmod($folderPath, 0777);
	}

	function isImageFile($filename) {
        //$ext = pathinfo($filename,PATHINFO_EXTENSION);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return in_array($ext, array('png', 'jpg', 'jpeg', 'gif'));
		//return in_array(array_pop(explode('.', $filename)), array('png', 'jpg', 'jpeg', 'gif'));
	}
}

/* End of file Upload_folder.php */
/* Location: ./application/libraries/Upload_Folder.php */
