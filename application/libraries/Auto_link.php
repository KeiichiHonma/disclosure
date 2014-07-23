<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Auto_link
{
    private $url_pattern = '(https?://[a-zA-Z0-9/_.?#&;=$+:@%~,\\-]+)';
    private $email_pattern = '([a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*@[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+(\\.[a-zA-Z0-9!#$%&\'*+\\-/=^_`{|}~]+)*)';
    private $error = array();

    function __construct()
    {
        $this->ci =& get_instance();
    }
    
    function autolink( $string, $popup = FALSE)
    {
        if( 0 == strlen( $string ) )
        {
            return $string;
        }
        $popup_string = $popup ? ' target="_blank"' : '';
        
        $matches = array();
        
        mb_ereg_search_init( $string, $this->url_pattern );
        while ( ( $ret = mb_ereg_search_pos() ) !== FALSE ) {
            $regs = mb_ereg_search_getregs();
            $matches[$ret[0]] = array( 'type'=>1, 'len'=>$ret[1], 'regs'=>$regs );
        }
        ksort($matches);
        $matches[strlen($string)] = array( 'type'=>3, 'len'=>0, 'regs'=>NULL );
        $ptr = 0;
        $ret_string = '';
        foreach ( $matches as $start => $info ) {
            if ( $start < $ptr ) continue;
            
            $type = $info['type'];
            $len = $info['len'];
            $regs = $info['regs'];
            $ret_string .= htmlspecialchars( substr( $string, $ptr, $start - $ptr ) );

            //文字の丸め
            if(strlen($regs[0]) > 70){
                $url_name = mb_strimwidth($regs[0],0,70,'...','UTF-8');
            }else{
                $url_name = $regs[0];
            }
            
            switch ( $info['type'] ) {
            case 0:
                if( $regs[4] || $regs[5] ) $link = substr( $link,0,  strlen( $link ) - 1 );
                $link = "\t".'<a href="' . $link . $regs[4] . $regs[5] . '">' .htmlspecialchars( $url_name ) . '</a>'."\t";
                $ret_string .= $link;
                break;
            case 1:
                $link = "\t".'<a href="' . $regs[0] . '"' . $popup_string . '>' .htmlspecialchars( $url_name ) . '</a>'."\t";
                $ret_string .= $link;
                break;

            }
            
            $ptr = $start + $len;
        }
        
        return $ret_string;
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */