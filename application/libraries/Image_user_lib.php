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
class Image_user_lib
{
    var $CI;
    public $image_library           = 'gd2';    // Can be:  imagemagick, netpbm, gd, gd2
    public $balloooooon_create_thumb  = FALSE;//mangahack only
    public $master_dim              = 'height';
    public $balloooooon_thumb_marker  = '_m_thumb';//mangahack only
    public $balloooooon_thumb_width   = 150;
    public $balloooooon_thumb_height  = 150;
    public $full_src_path           = '';
    public $orig_width              = '';
    public $orig_height             = '';
    public $width              = '';
    public $height             = '';
    public $image_type              = '';
    public $size_str                = '';
    public $mime_type               = '';
    public $x_axis                  = 0;
    public $y_axis                  = 0;
    function __construct()
    {
        $this->CI =& get_instance();
        $this->balloooooon_thumb_width   = $this->CI->config->config['upload_thumb_image_resize_width'];
        $this->balloooooon_thumb_height  = $this->CI->config->config['upload_thumb_image_resize_height'];
    }

    /**
     * Create Image - GD
     *
     * This simply creates an image resource handle
     * based on the type of image being processed
     *
     * @access    public
     * @param    string
     * @return    resource
     */
    function image_create_gd($path, $image_type = '')
    {
        if ($image_type == '')
            $image_type = $this->image_type;


        switch ($image_type)
        {
            case     1 :
                        if ( ! function_exists('imagecreatefromgif'))
                        {
                            $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
                            return FALSE;
                        }

                        return imagecreatefromgif($path);
                break;
            case 2 :
                        if ( ! function_exists('imagecreatefromjpeg'))
                        {
                            $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
                            return FALSE;
                        }

                        return imagecreatefromjpeg($path);
                break;
            case 3 :
                        if ( ! function_exists('imagecreatefrompng'))
                        {
                            $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
                            return FALSE;
                        }

                        return imagecreatefrompng($path);
                break;

        }

        $this->set_error(array('imglib_unsupported_imagecreate'));
        return FALSE;
    }

    /**
     * Get image properties
     *
     * A helper function that gets info about the file
     *
     * @access    public
     * @param    string
     * @return    mixed
     */
    function get_image_properties($path = '', $return = FALSE)
    {
        // For now we require GD but we should
        // find a way to determine this using IM or NetPBM

        if ($path == '')
            $path = $this->full_src_path;
        if ( ! file_exists($path))
        {
            $this->set_error('imglib_invalid_path');
            return FALSE;
        }

        $vals = @getimagesize($path);

        $types = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');

        $mime = (isset($types[$vals['2']])) ? 'image/'.$types[$vals['2']] : 'image/jpg';

        if ($return == TRUE)
        {
            $v['width']            = $vals['0'];
            $v['height']        = $vals['1'];
            $v['image_type']    = $vals['2'];
            $v['size_str']        = $vals['3'];
            $v['mime_type']        = $mime;

            return $v;
        }

        $this->orig_width    = $vals['0'];
        $this->orig_height    = $vals['1'];
        if ($this->width == '')
            $this->width = $this->orig_width;

        if ($this->height == '')
            $this->height = $this->orig_height;
        $this->image_type    = $vals['2'];
        $this->size_str        = $vals['3'];
        $this->mime_type    = $mime;

        return TRUE;
    }

    function balloooooon_thumb_image_reproportion()
    {
        if ( ! is_numeric($this->balloooooon_thumb_width) OR ! is_numeric($this->balloooooon_thumb_height) OR $this->balloooooon_thumb_width == 0 OR $this->balloooooon_thumb_height == 0)
            return;

        if ( ! is_numeric($this->orig_width) OR ! is_numeric($this->orig_height) OR $this->orig_width == 0 OR $this->orig_height == 0)
            return;

        $new_width    = ceil($this->orig_width*$this->balloooooon_thumb_height/$this->orig_height);
        $new_height    = ceil($this->balloooooon_thumb_width*$this->orig_height/$this->orig_width);

        $ratio = (($this->orig_height/$this->orig_width) - ($this->balloooooon_thumb_height/$this->balloooooon_thumb_width));

        if ($this->master_dim != 'width' AND $this->master_dim != 'height')
        {
            $this->master_dim = ($ratio < 0) ? 'width' : 'height';
        }

        if (($this->balloooooon_thumb_width != $new_width) AND ($this->balloooooon_thumb_height != $new_height))
        {
            if ($this->master_dim == 'height')
            {
                $this->balloooooon_thumb_width = $new_width;
            }
            else
            {
                $this->balloooooon_thumb_height = $new_height;
            }
        }
    }

    function image_save_gd($resource,$file_path)
    {
        switch ($this->image_type)
        {
            case 1 :
                        if ( ! function_exists('imagegif'))
                        {
                            $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_gif_not_supported'));
                            return FALSE;
                        }

                        if ( ! @imagegif($resource, $file_path))
                        {
                            $this->set_error('imglib_save_failed');
                            return FALSE;
                        }
                break;
            case 2    :
                        if ( ! function_exists('imagejpeg'))
                        {
                            $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_jpg_not_supported'));
                            return FALSE;
                        }

                        if ( ! @imagejpeg($resource, $file_path))
                        {
                            $this->set_error('imglib_save_failed');
                            return FALSE;
                        }
                break;
            case 3    :
                        if ( ! function_exists('imagepng'))
                        {
                            $this->set_error(array('imglib_unsupported_imagecreate', 'imglib_png_not_supported'));
                            return FALSE;
                        }

                        if ( ! @imagepng($resource, $file_path))
                        {
                            $this->set_error('imglib_save_failed');
                            return FALSE;
                        }
                break;
            default        :
                            $this->set_error(array('imglib_unsupported_imagecreate'));
                            return FALSE;
                break;
        }

        return TRUE;
    }
}

/* End of file Multiple_upload.php */
/* Location: ./application/libraries/Multiple_upload.php */
