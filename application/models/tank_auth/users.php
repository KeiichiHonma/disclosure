<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package    Tank_auth
 * @author    Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model
{
    var $ci;
    private $table_name            = 'users';            // user accounts
    private $profile_table_name    = 'user_profiles';    // user profiles
    private $filtered_profile_table_name    = 'filtered_user_profiles';    // filtered user profiles

    function __construct()
    {
        parent::__construct();

        $this->ci =& get_instance();
        $this->table_name            = $this->ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
        $this->profile_table_name    = $this->ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
    }

    /**
     * Get user record by Id
     *
     * @param    int
     * @param    bool
     * @return    object
     */
    function get_user_by_id($user_id, $activated)
    {
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row();
        return NULL;
    }

    /**
     * Get user record by login (username or email)
     *
     * @param    string
     * @return    object
     */
    function get_user_by_login($login)
    {
        //$this->db->where('LOWER(username)=', strtolower($login));
        //$this->db->or_where('LOWER(email)=', strtolower($login));
        $this->db->where('LOWER(email)=', strtolower($login));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row();
        return NULL;
    }

    /**
     * Get user record by username
     *
     * @param    string
     * @return    object
     */
    function get_user_by_username($username)
    {
        $this->db->where('LOWER(username)=', strtolower($username));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row();
        return NULL;
    }

    /**
     * Get user record by email
     *
     * @param    string
     * @return    object
     */
    function get_user_by_email($email)
    {
        $this->db->where('LOWER(email)=', strtolower($email));

        $query = $this->db->get($this->table_name);
        if ($query->num_rows() == 1) return $query->row();
        return NULL;
    }

    //console only
    function getUsersOrder($order, $page)
    {
        $result = array();
        $perPageCount = $this->ci->config->item('paging_count_per_page');
        $offset = $perPageCount * ($page - 1);
        $query = $this->db->query("SELECT SQL_CALC_FOUND_ROWS *
                                    FROM {$this->table_name}
                                    ORDER BY {$this->table_name}.{$order}
                                    LIMIT {$offset},{$perPageCount}"
        );

        if ($query->num_rows() != 0) {
            $result['data'] = $query->result();
            $query = $this->db->query("SELECT FOUND_ROWS() as count");
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

    //console only
    function getUsersCSVByCreated($where_from_created,$where_to_created)
    {
        $result = array();
        $query = $this->db->query("SELECT {$this->table_name}.id AS 'ユーザーID', {$this->table_name}.created AS '登録日時', {$this->table_name}.username AS 'ユーザー名', {$this->table_name}.email AS 'メールアドレス', {$this->table_name}.address1 AS '住所1', {$this->table_name}.address2 AS '住所2', {$this->table_name}.zip AS 'Zip', {$this->table_name}.phone AS '電話番号', {$this->table_name}.birthday_year AS '生まれた年', {$this->table_name}.sex AS '性別', {$this->table_name}.need_notify AS 'Balloooooon!からのお知らせを受け取る', {$this->table_name}.source AS '何を見てBalloooooon!をお知りになりましたか?', {$this->table_name}.language AS '言語', {$this->table_name}.last_ip AS 'アクセスIP', {$this->table_name}.last_login AS '最終ログイン日時', {$this->table_name}.activated AS 'アクティベート', {$this->table_name}.banned AS '有効/無効'
                                    FROM {$this->table_name}
                                    WHERE {$this->table_name}.created >= '{$where_from_created}  00:00:00' AND {$this->table_name}.created <= '{$where_to_created}  23:59:59'
                                    ORDER BY {$this->table_name}.created"
        );
        $this->load->dbutil();
        return mb_convert_encoding($this->dbutil->user_csv_from_result($query, ",", "\n"), 'Shift-JIS', 'UTF-8');
    }

    /**
     * Get user record by Id
     *
     * @param    int
     * @param    bool
     * @return    object
     */
    function get_user_magazine_by_condition($condition)
    {
        //必須条件
        $this->db->where('need_notify', 0);
        $this->db->where('banned', 0);
        
        //・使用言語
        if(is_numeric($condition['where_language']) && $condition['where_language'] >= 0 && $condition['where_language'] < 3){
            $this->db->where('language', intval($condition['where_language']));
        }

        //性別
        if(is_numeric($condition['where_sex']) && $condition['where_sex'] >= 0 && $condition['where_sex'] < 2){
            $this->db->where('sex', intval($condition['where_sex']));
        }
        
        //・年代の幅
        if( is_numeric($condition['where_from_birthday_year']) && $condition['where_from_birthday_year'] > 0 && $condition['where_from_birthday_year'] <= date("Y",time()) ){
            $this->db->where('birthday_year >= '.$condition['where_from_birthday_year']);
        }

        if( $condition['where_to_birthday_year'] > 0 && $condition['where_to_birthday_year'] <= date("Y",time()) ){
            $this->db->where('birthday_year <= '.$condition['where_to_birthday_year']);
        }
        //・ユーザー登録した日時 2013/08/15
        if( !is_null($condition['where_from_created']) ){
            $this->db->where('created >= \''.$condition['where_from_created'].' 00:00:00\'');
        }

        if( !is_null($condition['where_to_created']) ){
            $this->db->where('created <= \''.$condition['where_to_created'].' 23:59:59\'');
        }

        $query = $this->db->get($this->table_name);
        //return $this->db->last_query();
        if ($query->num_rows() != 0) return $query->result();
        return array();
    }

    /**
     * Update user info
     *
     * @param    int, array
     * @return    object
     */
    function update_user_info($userId, $userinfo)
    {
        //$this->db->where('user_id', intval($userId));
        //$this->db->update($this->profile_table_name, $userinfo);
        $this->db->where('id', intval($userId));
        $this->db->update($this->table_name, $userinfo);
    }

    /**
     * Check if username available for registering
     *
     * @param    string
     * @return    bool
     */
    function is_username_available($username)
    {
        $this->db->select('1', FALSE);
        $this->db->where('LOWER(username)=', strtolower($username));

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 0;
    }

    /**
     * Check if email available for registering
     *
     * @param    string
     * @return    bool
     */
    function is_email_available($email)
    {
        $this->db->select('1', FALSE);
        $this->db->where('LOWER(email)=', strtolower($email));
        //$this->db->or_where('LOWER(new_email)=', strtolower($email));

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 0;
    }

    /**
     * Create new user record
     *
     * @param    array
     * @param    bool
     * @return    array
     */
    function create_user($data, $activated = TRUE)
    {
        $data['created'] = date('Y-m-d H:i:s');
        $data['activated'] = $activated ? 1 : 0;

        if ($this->db->insert($this->table_name, $data)) {
            return array('user_id' => $this->db->insert_id());
        }
        return NULL;
    }

    /**
     * Activate user if activation key is valid.
     * Can be called for not activated users only.
     *
     * @param    int
     * @param    string
     * @param    bool
     * @return    bool
     */
    function activate_user($user_id, $activation_key, $activate_by_email)
    {
        $this->db->select('1', FALSE);
        $this->db->where('id', $user_id);
        if ($activate_by_email) {
            $this->db->where('new_email_key', $activation_key);
        } else {
            $this->db->where('new_password_key', $activation_key);
        }
        $this->db->where('activated', 0);
        $query = $this->db->get($this->table_name);

        if ($query->num_rows() == 1) {

            $this->db->set('activated', 1);
            $this->db->set('new_email_key', NULL);
            $this->db->where('id', $user_id);
            $this->db->update($this->table_name);

            //create profile already
            //$this->create_profile($user_id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Purge table of non-activated users
     *
     * @param    int
     * @return    void
     */
    function purge_na($expire_period = 172800)
    {
        $this->db->where('activated', 0);
        $this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
        $this->db->delete($this->table_name);
    }

    /**
     * Delete user record
     *
     * @param    int
     * @return    bool
     */
    function delete_user($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->delete($this->table_name);
        if ($this->db->affected_rows() > 0) {
            $this->delete_profile($user_id);
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Set new password key for user.
     * This key can be used for authentication when resetting user's password.
     *
     * @param    int
     * @param    string
     * @return    bool
     */
    function set_password_key($user_id, $new_pass_key)
    {
        $this->db->set('new_password_key', $new_pass_key);
        $this->db->set('new_password_requested', date('Y-m-d H:i:s'));
        $this->db->where('id', $user_id);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Check if given password key is valid and user is authenticated.
     *
     * @param    int
     * @param    string
     * @param    int
     * @return    void
     */
    function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
    {
        $this->db->select('1', FALSE);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

        $query = $this->db->get($this->table_name);
        return $query->num_rows() == 1;
    }

    /**
     * Change user password if password key is valid and user is authenticated.
     *
     * @param    int
     * @param    string
     * @param    string
     * @param    int
     * @return    bool
     */
    function reset_password($user_id, $new_pass, $new_pass_key, $expire_period = 900)
    {
        $this->db->set('password', $new_pass);
        $this->db->set('new_password_key', NULL);
        $this->db->set('new_password_requested', NULL);
        $this->db->where('id', $user_id);
        $this->db->where('new_password_key', $new_pass_key);
        $this->db->where('UNIX_TIMESTAMP(new_password_requested) >=', time() - $expire_period);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Change user password
     *
     * @param    int
     * @param    string
     * @return    bool
     */
    function change_password($user_id, $new_pass)
    {
        $this->db->set('password', $new_pass);
        $this->db->where('id', $user_id);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Set new email for user (may be activated or not).
     * The new email cannot be used for login or notification before it is activated.
     *
     * @param    int
     * @param    string
     * @param    string
     * @param    bool
     * @return    bool
     */
    function set_new_email($user_id, $new_email, $new_email_key, $activated)
    {
        $this->db->set($activated ? 'new_email' : 'email', $new_email);
        $this->db->set('new_email_key', $new_email_key);
        $this->db->where('id', $user_id);
        $this->db->where('activated', $activated ? 1 : 0);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Activate new email (replace old email with new one) if activation key is valid.
     *
     * @param    int
     * @param    string
     * @return    bool
     */
    function activate_new_email($user_id, $new_email_key)
    {
        $this->db->set('email', 'new_email', FALSE);
        $this->db->set('new_email', NULL);
        $this->db->set('new_email_key', NULL);
        $this->db->where('id', $user_id);
        $this->db->where('new_email_key', $new_email_key);

        $this->db->update($this->table_name);
        return $this->db->affected_rows() > 0;
    }

    /**
     * Update user login info, such as IP-address or login time, and
     * clear previously generated (but not activated) passwords.
     *
     * @param    int
     * @param    bool
     * @param    bool
     * @return    void
     */
    function update_login_info($user_id, $record_ip, $record_time)
    {
        $this->db->set('new_password_key', NULL);
        $this->db->set('new_password_requested', NULL);

        if ($record_ip)        $this->db->set('last_ip', $this->input->ip_address());
        if ($record_time)    $this->db->set('last_login', date('Y-m-d H:i:s'));

        $this->db->where('id', $user_id);
        $this->db->update($this->table_name);
    }

    /**
     * Ban user
     *
     * @param    int
     * @param    string
     * @return    void
     */
    function ban_user($user_id, $reason = NULL)
    {
        $this->db->where('id', $user_id);
        $this->db->update($this->table_name, array(
            'banned'        => 1,
            'ban_reason'    => $reason,
        ));
    }

    /**
     * Unban user
     *
     * @param    int
     * @return    void
     */
    function unban_user($user_id)
    {
        $this->db->where('id', $user_id);
        $this->db->update($this->table_name, array(
            'banned'        => 0,
            'ban_reason'    => NULL,
        ));
    }

    function get_user_profile($user_id) {
        //$this->db->where('user_id', $user_id);
        $this->db->where('id', $user_id);
        
        $query = $this->db->get($this->table_name);
        //$query = $this->db->get($this->filtered_profile_table_name);
        if ($query->num_rows() == 1) return $query->row();
        return NULL;
    }

    /**
     * Create an empty profile for a new user
     *
     * @param    int
     * @return    bool
     */
    private function create_profile($user_id)
    {
        $this->db->set('user_id', $user_id);
        return $this->db->insert($this->profile_table_name);
    }

    /**
     * Create an profile with data
     *
     * @param    int
     * @param    array
     * @return    bool
     */
    private function create_profile_with_data($user_id, $data)
    {
        $data['user_id'] = $user_id;
        return $this->db->insert($this->profile_table_name, $data);
    }

    /**
     * Delete user profile
     *
     * @param    int
     * @return    void
     */
    private function delete_profile($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->profile_table_name);
    }
}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */