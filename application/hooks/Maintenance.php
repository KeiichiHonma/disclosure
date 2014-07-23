<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Maintenance {
    public function maintenance() {
        // IPアドレスを取得して変数にセットする
        $ipAddress = $_SERVER["REMOTE_ADDR"];
        if($ipAddress != '210.173.251.227'){//渋谷オフィスのみOK
            $config = load_class('Config', 'core');
            if ($config->item('maintenance') === TRUE) {
                include APPPATH . 'views/' . $config->item('maintenance_view') . '.php';
                exit();
            }
        }
    }
}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */