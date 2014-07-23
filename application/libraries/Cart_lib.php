<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cart_lib
{
    var $CI;
    public $cart;
    public $cart_promotion_ids;
    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->lang->load('cart');
        $this->cart = $this->CI->session->userdata('cart');
        $this->cart_promotion_ids = $this->CI->session->userdata('cart_promotion_ids');
    }

    function promotion_check($code)
    {
        if(!$this->cart){
            unset($this->CI->session->userdata['cart']);
            if(isset($this->CI->session->userdata['cart_promotion_ids']))unset($this->CI->session->userdata['cart_promotion_ids']);
            $this->save_cart_message($this->CI->lang->line('cart_message_not_buy_stock'));
        }
        $cart_coupon_ids = array_keys($this->cart);
        
        //codeあるか？有効期間内か？
        $promotion = $this->CI->Promotion_model->getValidatePromotionsByCodeByTime($code,time());
        if(empty($promotion)) $this->_show_message( $this->CI->lang->line('cart_message_not_exist_promotion'));
        
        //クーポン指定の場合、カートにあるクーポンと関連したプロモーションコードか？
        $promotion_coupons =array();
        if($promotion->type != 9){
            $promotion_coupons = $this->CI->Promotion_model->getPromotionCouponsByPromotionIdByCouponIds($promotion->id,$cart_coupon_ids);
            if(empty($promotion_coupons)) $this->_show_message( $this->CI->lang->line('cart_message_not_exist_promotion'));
        }

        //同じプロモーションコードが再度入力された
        if($this->cart_promotion_ids !== FALSE && in_array($promotion->id,$this->cart_promotion_ids)) $this->_show_message( $this->CI->lang->line('cart_message_not_reuse_promotion'));

        //トータルの使用制限はOKか？
        if($promotion->stock == 0){
            $this->_show_message( $this->CI->lang->line('cart_message_not_use_stock_promotion'));
        }
        
        //1ユーザーの使用制限はOKか？
        $user_id = $this->CI->tank_auth->get_user_id();
        $purchase_promotions = $this->CI->Purchase_model->getPurchasesByPromotionIdAndUserId($promotion->id,$user_id);
        if($promotion->user_use_limit <= count($purchase_promotions)) $this->_show_message( $this->CI->lang->line('cart_message_not_use_user_limit_promotion'));
        
        return array('promotion'=>$promotion,'promotion_coupons'=>$promotion_coupons);
    }

    function check($coupons)
    {
        if(!$this->cart){
            unset($this->CI->session->userdata['cart']);
            if(isset($this->CI->session->userdata['cart_promotion_ids']))unset($this->CI->session->userdata['cart_promotion_ids']);
            $this->save_cart_message($this->CI->lang->line('cart_message_not_buy_stock'));
        }
        
        //クーポンチェック
        //カート上でクーポンが空というのはありえない。途中でクーポンが無効となった
        if(empty($coupons)){
            unset($this->CI->session->userdata['cart']);
            if(isset($this->CI->session->userdata['cart_promotion_ids'])) unset($this->CI->session->userdata['cart_promotion_ids']);
            $this->save_cart_message($this->CI->lang->line('cart_message_not_buy_stock'));
        }
        
        $cart_coupon_ids = array_keys($this->cart);
        $coupon_ids = array_keys($coupons);
        $diff = array_diff($cart_coupon_ids, $coupon_ids);
        //購入クーポンの数量チェック
        if(count($diff) > 0){
            //購入前に売り切れ、又はクーポンが非公開になった
            foreach ($diff as $diff_coupon_id){
                //$error['coupons'][$diff_coupon_id] = $this->CI->lang->line('cart_message_not_buy_stock');
                unset($this->CI->session->userdata['cart'][$coupon_id]);
               if(isset($this->CI->session->userdata['cart_promotion_ids'][$coupon_id]))unset($this->CI->session->userdata['cart_promotion_ids'][$coupon_id]);
            }
            $this->save_cart_message($this->CI->lang->line('cart_message_not_buy_stock'));
        }
        

        //プロモーションコードチェック
        $promotions = array();
        $promotions_coupons = array();
        if($this->cart_promotion_ids !== FALSE){
            foreach ($this->cart_promotion_ids as $cart_promotion_id){
                //codeあるか？有効期間内か？
                $promotion = $this->CI->Promotion_model->getValidatePromotionsByIdByTime($cart_promotion_id,time());
                if(empty($promotion)) $this->delete_cart_promotion($promotion->id,$this->CI->lang->line('cart_message_not_exist_after_input_promotion'));

                //クーポン指定の場合、カートにあるクーポンと関連したプロモーションコードか？
                $promotion_coupons =array();
                if($promotion->type != 9){
                    $promotion_coupons = $this->CI->Promotion_model->getPromotionCouponsByPromotionIdByCouponIds($promotion->id,$cart_coupon_ids);
                     if(empty($promotion_coupons)) $this->delete_cart_promotion($promotion->id,$this->CI->lang->line('cart_message_not_exist_after_input_promotion'));
                     $promotions_coupons[$promotion->id] = $promotion_coupons;
                }

                //トータルの使用制限はOKか？
                if($promotion->stock == 0) $this->delete_cart_promotion($promotion->id,$this->CI->lang->line('cart_message_not_use_stock_promotion'));
                
                //1ユーザーの使用制限はOKか？
                $user_id = $this->CI->tank_auth->get_user_id();
                $purchase_promotions = $this->CI->Purchase_model->getPurchasesByPromotionIdAndUserId($promotion->id,$user_id);
                if($promotion->user_use_limit <= count($purchase_promotions)) $this->delete_cart_promotion($promotion->id,$this->CI->lang->line('cart_message_not_use_user_limit_promotion'));
                $promotions[$promotion->id] = $promotion;
            }
        }

        //在庫チェック
        foreach ($this->cart as $coupon_id => $cart_coupon){
            //在庫チェック
            if($coupons[$coupon_id]->stock < $cart_coupon['number']){
                $error['coupons'][$coupon_id] = $this->CI->lang->line('cart_message_not_edit_number');
                //強制的に戻す
                $cart[$coupon_id]['number'] = 1;
                $this->CI->session->set_userdata('cart', $cart);
            }
        }

        if(isset($error)){
            $this->CI->session->set_flashdata('error',$error);
            redirect("cart/manage");
        }
        return array('promotions'=>$promotions,'promotions_coupons'=>$promotions_coupons);
    }

    function get_payment($price,$number,$save){
        $total = $price * $number;
        if($save > 0){
            $total = floor( $total - ($total * $save / 100) );
        }
        return $total;
    }

    function old_check($cart,$coupons,$cart_promotion_ids,$promotions)
    {
        if(!$cart){
            unset($this->CI->session->userdata['cart']);
            if(isset($this->CI->session->userdata['cart_promotion_ids']))unset($this->CI->session->userdata['cart_promotion_ids']);
            $this->CI->session->sess_write();
            $this->_show_message($this->CI->lang->line('cart_message_not_buy_stock'));
        }
        //クーポンチェック
        //カート上でクーポンが空というのはありえない。途中でクーポンが無効となった
        if(empty($coupons)){
            unset($this->CI->session->userdata['cart']);
            if(isset($this->CI->session->userdata['cart_promotion_ids'])) unset($this->CI->session->userdata['cart_promotion_ids']);
            $this->CI->session->sess_write();
            $this->_show_message($this->CI->lang->line('cart_message_not_buy_stock'));
        }
        
        $cart_coupon_ids = array_keys($cart);
        $coupon_ids = array_keys($coupons);
        $diff = array_diff($cart_coupon_ids, $coupon_ids);
        //購入クーポンの数量チェック
        if(count($diff) > 0){
            //購入前に売り切れ、又はクーポンが非公開になった
            foreach ($diff as $diff_coupon_id){
                //$error['coupons'][$diff_coupon_id] = $this->CI->lang->line('cart_message_not_buy_stock');
                unset($this->CI->session->userdata['cart'][$coupon_id]);
               if(isset($this->CI->session->userdata['cart_promotion_ids'][$coupon_id]))unset($this->CI->session->userdata['cart_promotion_ids'][$coupon_id]);
            }
            $this->CI->session->sess_write();
            $this->_show_message($this->CI->lang->line('cart_message_not_buy_stock'));
        }
        
        //プロモーションコードチェック
        if($cart_promotion_ids !== FALSE){
            //カートでは指定していたのに、誰かにそのIDがを使用された
            if(empty($promotions)){
                if(isset($this->CI->session->userdata['cart_promotion_ids'])) unset($this->CI->session->userdata['cart_promotion_ids']);
                $this->CI->session->sess_write();
                $this->_show_message($this->CI->lang->line('cart_message_not_reuse_promotion'));
            }
            foreach ($promotions as $promotion){
                $promotion_ids[] = $promotion->id;
            }
            
            $diff = array_diff($cart_promotion_ids, $promotion_ids);
            //プロモーションコードの有効、使用済みチェク
            if(count($diff) > 0){
                foreach ($diff as $diff_promotion_id){
                    if($index = array_search($diff_promotion_id,$cart_promotion_ids)) unset($this->CI->session->userdata['cart_promotion_ids'][$index]);
                }
                $this->CI->session->sess_write();
                $this->_show_message($this->CI->lang->line('cart_message_not_reuse_promotion'));
            }
        }

        //在庫チェック
        foreach ($cart as $coupon_id => $cart_coupon){
            //在庫チェック
            if($coupons[$coupon_id]->stock < $cart_coupon['number']){
                $error['coupons'][$coupon_id] = $this->CI->lang->line('cart_message_not_edit_number');
                //強制的に戻す
                $cart[$coupon_id]['number'] = 1;
                $this->CI->session->set_userdata('cart', $cart);
            }
        }

        if(isset($error)){
            $this->CI->session->set_flashdata('error',$error);
            redirect("cart/manage");
        }
        return;
    }
    function delete_cart_promotion($promotion_id,$message){
        if($index = array_search($promotion_id,$this->cart_promotion_ids)) unset($this->CI->session->userdata['cart_promotion_ids'][$index]);
        $this->save_cart_message($message);
    }

    function save_cart_message($message){
        $this->CI->session->sess_write();
        $this->_show_message($message);
    }

    /**
     * Show info message
     *
     * @param    string
     * @return    void
     */
    function _show_message($message)
    {
        $this->CI->session->set_flashdata('message', $message);
        redirect($this->CI->config->item('show_message_cart_url'));
    }
}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */