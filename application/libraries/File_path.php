<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class File_path
{
    function __construct()
    {
        $this->CI =& get_instance();
    }
    
    public $dir_file;
    
    public function getFilePath($fid,$ext){
        $this->dir_file = '';//初期化．しないと前のパスを記憶してまう
        //ディレクトリとファイル名
        $dir1 = '0';
        $dir2 = '0';
        $file = '';
        
        $len = strlen( $fid );
        if ( $len > 6 ) {
            $limit = $len - 6;
            $dir1 = substr( $fid, 0, $limit );
            $dir2 = substr( $fid, $limit, 3 );
            $file = substr( $fid, $limit + 3, 3 );
        } else if ( $len > 3 ) {
            $limit = $len - 3;
            $dir2 = substr( $fid, 0, $limit );
            $file = substr( $fid, $limit, 3 );
        } else {
            $file = $fid;
        }
        $t = $_SERVER['DOCUMENT_ROOT'].'/files';
        if ( ! is_dir( $t ) )
        {
            mkdir( $t , 755);
            //return FALSE;
        }
        $t .= "/${dir1}";
        if ( ! is_dir( $t ) )
        {
            mkdir( $t , 755);
            //return FALSE;
        }
        $t .= "/${dir2}";
        if ( ! is_dir( $t ) )
        {
            mkdir( $t , 755);
            //return FALSE;
        }
        $this->dir_file = "/${dir1}/${dir2}/${file}".'.'.$ext;
        return 'files'.$this->dir_file;
    }

    //保存ディレクトリ命名ロジック+作成
    function formatPath( $fid,$mkdir = FALSE){
        $blnStatus = true;
        //ディレクトリとファイル名
        $dir1 = '0';
        $dir2 = '0';
        $file = '';

        $len = strlen( $fid );
        if ( $len > 6 ) {
            $limit = $len - 6;
            $dir1 = substr( $fid, 0, $limit );
            $dir2 = substr( $fid, $limit, 3 );
            $file = substr( $fid, $limit + 3, 3 );
        } else if ( $len > 3 ) {
            $limit = $len - 3;
            $dir2 = substr( $fid, 0, $limit );
            $file = substr( $fid, $limit, 3 );
        } else {
            $file = $fid;
        }

        //登録処理、TRUEでくる
        if ( $mkdir ) {
            //$t = $this->file_path;
            //$t = PATH_FILES;
            $t = 'files';
            if ( ! is_dir( $t ) )
            {
                mkdir( $t );
            }
            $t .= "/${dir1}";
            if ( ! is_dir( $t ) )
            {
                mkdir( $t );
            }
            $t .= "/${dir2}";
            if ( ! is_dir( $t ) )
            {
                mkdir( $t );
            }
            //is_writable -- ファイルが書き込み可能かどうかを調べる
            if( !is_writable( $t ) )
            {
                print 'deny';
                die();
            }
        }
        return $blnStatus;
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */