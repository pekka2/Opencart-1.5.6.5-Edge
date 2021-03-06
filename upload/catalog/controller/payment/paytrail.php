<?php
class ControllerPaymentPaytrail extends Controller {
	private $order_info = array();
	public function index() {

       $this->load->language('payment/paytrail');

    $this->data['text_paid_success']     =  $this->language->get('text_paid_success');
    $this->data['text_help_to_continue']  =  $this->language->get('text_help_to_continue');
    $this->data['text_loading'] = $this->language->get('text_loading');
    $this->data['button_confirm'] = $this->language->get('button_confirm');

    $this->data['journal_checkout'] = false;

        if ($this->config->get('config_template') == 'journal2' && $this->journal2->settings->get('journal_checkout')) {
          $this->data['journal_checkout'] = true;
        }

    $this->data["PAYER_COMPANY_NAME"] = "";

    $this->data['products'] = array();

    $this->data['continue'] = $this->url->link('checkout/success');

    $this->load->model('checkout/order');
        $total = 0;
        $cart = array();
    if(isset($this->session->data['order_id'])){
       $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
       if($order_info){
         $total = $order_info['total'];
       }
    }
    if(!$total){
        $cart = $this->cart->getProducts();
        foreach($cart as $order){
          $total = $order['total'];
        } 
    }

    $this->data['action'] = 'https://payment.paytrail.com/e2';

    if(!isset($_COOKIE['paytrail'])){
       $this->data['paid'] = false;
       if($this->config->get('paytrail_merchant_id')){
          $this->data["MERCHANT_ID"] = $this->config->get('paytrail_merchant_id');
          $this->data["CURRENCY"] = "EUR";
          $this->data["ORDER_NUMBER"] = $this->language->get('text_order') . $this->session->data['order_id'];
          $this->data["URL_SUCCESS"] = $this->url->link('payment/paytrail/callback');
          $this->data["URL_CANCEL"] = $this->url->link('payment/paytrail/cancel');
          $this->data["URL_NOTIFY"] = $this->url->link('checkout/cart');
          $this->data["AMOUNT"] = round($total,2);
          $this->data["REFERENCE_NUMBER"] = "";
          $this->data["PAYMENT_METHODS"] = "";
          $this->data["LOCALE"] = "fi_FI";
          $this->data["ALG"] = 1;
          $this->data["MSG_UI_MERCHANT_PANEL"] = "Tilausnumero " . $this->session->data['order_id'];

        if(!$this->data['journal_checkout']){
            $order_info['telephone'] = str_replace('-','',$order_info['telephone']);
            $order_info['telephone'] = str_replace(' ','',$order_info['telephone']);
            $this->data["VAT_IS_INCLUDED"] = 1;
            $this->data["PAYER_PERSON_FIRSTNAME"] = $order_info['firstname'];
            $this->data["PAYER_PERSON_LASTNAME"] = $order_info['lastname'];
            $this->data["PAYER_PERSON_STREET"] = $order_info['payment_address_1'];
            $this->data["PAYER_PERSON_ADDR_POSTAL_CODE"] = $order_info['payment_postcode'];
            $this->data["PAYER_PERSON_ADDR_TOWN"] = $order_info['payment_city'];
            $this->data["PAYER_PERSON_PHONE"] =  $order_info['telephone'];
            $this->data["PAYER_PERSON_EMAIL"] = $order_info['email'];
            $this->data["PAYER_PERSON_ADDR_COUNTRY"] = $order_info['payment_country'];
            if($order_info['payment_company']){
               $this->data["PAYER_COMPANY_NAME"] = $order_info['payment_company'];
               $company_data = '|' . $order_info['payment_company'];
               $company_field = ',PAYER_COMPANY_NAME';
            } else {
                $this->data["PAYER_COMPANY_NAME"] = "";
                $company_data = "";
                $company_field = "";
            }

            $this->load->model("payment/paytrail");

            $payer_data =  '|' . $this->data["PAYER_PERSON_PHONE"].'|' . $this->data["PAYER_PERSON_EMAIL"].'|' . $this->data["PAYER_PERSON_FIRSTNAME"].'|' . $this->data["PAYER_PERSON_LASTNAME"] . $company_data  . '|' . $this->data["PAYER_PERSON_STREET"] . '|' . $this->data["PAYER_PERSON_ADDR_POSTAL_CODE"] . '|' . $this->data["PAYER_PERSON_ADDR_TOWN"] . '|' . $this->data["PAYER_PERSON_ADDR_COUNTRY"] . '|' . $this->data["VAT_IS_INCLUDED"];

             $products_field = "";
             $products_data = "";
             $products = $this->cart->getProducts();
             $item = 0;
             foreach($products as $product){
                // name
                $this->data['products'][$item]["ITEM_TITLE"] = strip_tags($product['name']);
                $products_data .= '|' . strip_tags($product['name']);
                // productid
                $this->data['products'][$item]["ITEM_ID"] = $product['product_id'];
                $products_data .= '|' . $product['product_id'];
                // quantity
                $this->data['products'][$item]["ITEM_QUANTITY"] = $product['quantity'];
                $products_data .= '|' . $product['quantity'];
                // price
                $price_query = $this->tax->calculate($product['price'],$product['tax_class_id'],true);
                $price = $this->rounder($price_query);
                $this->data['products'][$item]["ITEM_UNIT_PRICE"] = $price;
                $products_data .= '|' . $price;
                // tax percent
                $tax_rate = $this->model_payment_paytrail->taxRate($product['tax_class_id']);
                $this->data['products'][$item]["ITEM_VAT_PERCENT"] = $tax_rate;
                $products_data .= '|' .$tax_rate;

                $this->data['products'][$item]["ITEM_DISCOUNT_PERCENT"] = 0;
                $products_data .= '|0';

                $this->data['products'][$item]["ITEM_TYPE"] = 1;
                $products_data .= '|1';

                $products_field .= ",ITEM_TITLE[$item]";
                $products_field .= ",ITEM_ID[$item]";
                $products_field .= ",ITEM_QUANTITY[$item]";
                $products_field .= ",ITEM_UNIT_PRICE[$item]";
                $products_field .= ",ITEM_VAT_PERCENT[$item]";
                $products_field .= ",ITEM_DISCOUNT_PERCENT[$item]";
                $products_field .= ",ITEM_TYPE[$item]";
                $item++;
            }

            $shipping_method = $this->model_payment_paytrail->findShipping($this->session->data['order_id']);

            if(!empty($shipping_method['shipping'])){
               $shipping = $shipping_method['shipping'];
                  $this->data['products'][$item]["ITEM_TITLE"] = str_replace('<br/>', ', ', $shipping['title']);
                  $products_data .= '|' . str_replace('<br/>', ', ', $shipping['title']);
                  // productid
                  $this->data['products'][$item]["ITEM_ID"] = $this->session->data['order_id'];
                  $products_data .= '|' . $this->session->data['order_id'];

                  $this->data['products'][$item]["ITEM_QUANTITY"] = 1;
                  $products_data .= '|1';
                  $price_query_2 = $this->tax->calculate($shipping['price'], $shipping['tax_class_id'], true);

                  $shipping_price = $this->rounder($price_query_2);
                  $this->data['products'][$item]["ITEM_UNIT_PRICE"] = $shipping_price;
                  $products_data .= '|' . $shipping_price;
                  $this->data['products'][$item]["ITEM_VAT_PERCENT"] = $shipping['tax_rate'];
                  $products_data .= '|' . $shipping['tax_rate'];

                  $this->data['products'][$item]["ITEM_DISCOUNT_PERCENT"] = 0;
                  $products_data .= '|0';

                  $this->data['products'][$item]["ITEM_TYPE"] = 2;
                  $products_data .= '|2';

                  $products_field .= ",ITEM_TITLE[$item]";
                  $products_field .= ",ITEM_ID[$item]";
                  $products_field .= ",ITEM_QUANTITY[$item]";
                  $products_field .= ",ITEM_UNIT_PRICE[$item]";
                  $products_field .= ",ITEM_VAT_PERCENT[$item]";
                  $products_field .= ",ITEM_DISCOUNT_PERCENT[$item]";
                  $products_field .= ",ITEM_TYPE[$item]";
                  $item++;
            }
 
            if(!empty($shipping_method['handling'])){
               $handling = $shipping_method['handling'];

                  $this->data['products'][$item]["ITEM_TITLE"] = str_replace('<br/>', ', ', $handling['title']);
                  $products_data .= '|' . str_replace('<br/>', ', ', $handling['title']);
                  $this->data['products'][$item]["ITEM_ID"] = $this->session->data['order_id'];
                  $products_data .= '|' . $this->session->data['order_id'];

                  $this->data['products'][$item]["ITEM_QUANTITY"] = 1;
                  $products_data .= '|1';
                  $price_query_3 = $this->tax->calculate($handling['price'], $handling['tax_class_id'], true);

                  $handling_price = $this->rounder($price_query_3);
                  $this->data['products'][$item]["ITEM_UNIT_PRICE"] = $handling_price;
                  $products_data .= '|' . $handling_price;
                  $this->data['products'][$item]["ITEM_VAT_PERCENT"] = $handling['tax_rate'];
                  $products_data .= '|' . $handling['tax_rate'];

                  $this->data['products'][$item]["ITEM_DISCOUNT_PERCENT"] = 0;
                  $products_data .= '|0';

                  $this->data['products'][$item]["ITEM_TYPE"] = 3;
                  $products_data .= '|3';

                  $products_field .= ",ITEM_TITLE[$item]";
                  $products_field .= ",ITEM_ID[$item]";
                  $products_field .= ",ITEM_QUANTITY[$item]";
                  $products_field .= ",ITEM_UNIT_PRICE[$item]";
                  $products_field .= ",ITEM_VAT_PERCENT[$item]";
                  $products_field .= ",ITEM_DISCOUNT_PERCENT[$item]";
                  $products_field .= ",ITEM_TYPE[$item]";
                  $item++;
              }
 
            if(!empty($shipping_method['fee'])){
               $fee = $shipping_method['fee'];

                  $this->data['products'][$item]["ITEM_TITLE"] = str_replace('<br/>', ', ', $fee['title']);
                  $products_data .= '|' . str_replace('<br/>', ', ', $fee['title']);
                  $this->data['products'][$item]["ITEM_ID"] = $this->session->data['order_id'];
                  $products_data .= '|' . $this->session->data['order_id'];

                  $this->data['products'][$item]["ITEM_QUANTITY"] = 1;
                  $products_data .= '|1';
                  $price_query_4 = $this->tax->calculate($fee['price'], $fee['tax_class_id'], true);

                  $fee_price = $this->rounder($price_query_4);
                  $this->data['products'][$item]["ITEM_UNIT_PRICE"] = $fee_price;
                  $products_data .= '|' . $fee_price;
                  $this->data['products'][$item]["ITEM_VAT_PERCENT"] = $fee['tax_rate'];
                  $products_data .= '|' . $fee['tax_rate'];

                  $this->data['products'][$item]["ITEM_DISCOUNT_PERCENT"] = 0;
                  $products_data .= '|0';

                  $this->data['products'][$item]["ITEM_TYPE"] = 3;
                  $products_data .= '|3';

                  $products_field .= ",ITEM_TITLE[$item]";
                  $products_field .= ",ITEM_ID[$item]";
                  $products_field .= ",ITEM_QUANTITY[$item]";
                  $products_field .= ",ITEM_UNIT_PRICE[$item]";
                  $products_field .= ",ITEM_VAT_PERCENT[$item]";
                  $products_field .= ",ITEM_DISCOUNT_PERCENT[$item]";
                  $products_field .= ",ITEM_TYPE[$item]";
              }

            $payer_field = ",PAYER_PERSON_PHONE,PAYER_PERSON_EMAIL,PAYER_PERSON_FIRSTNAME,PAYER_PERSON_LASTNAME" . $company_field .",PAYER_PERSON_ADDR_STREET,PAYER_PERSON_ADDR_POSTAL_CODE,PAYER_PERSON_ADDR_TOWN,PAYER_PERSON_ADDR_COUNTRY,VAT_IS_INCLUDED";

        } else {
            $products_field = "";
            $payer_field = "";
            $products_data = "";
            $payer_data = "";
        }

          $this->data["PARAMS_IN"] = "MERCHANT_ID,URL_SUCCESS,URL_CANCEL,ORDER_NUMBER,PARAMS_IN,PARAMS_OUT" . $products_field . ",MSG_UI_MERCHANT_PANEL,URL_NOTIFY,LOCALE,CURRENCY,REFERENCE_NUMBER,PAYMENT_METHODS" . $payer_field . ",AMOUNT,ALG";
          $this->data["PARAMS_OUT"] = "ORDER_NUMBER,PAYMENT_ID,AMOUNT,CURRENCY,PAYMENT_METHOD,TIMESTAMP,STATUS";

          $signature = $this->config->get('paytrail_merchant_secret');    

           $this->data["AUTHCODE"] = strtoupper(hash('sha256', $signature . '|' . $this->data["MERCHANT_ID"] . '|' . $this->data["URL_SUCCESS"] . '|' . $this->data["URL_CANCEL"] . '|' . $this->data["ORDER_NUMBER"] . '|' . $this->data["PARAMS_IN"] . '|' . $this->data["PARAMS_OUT"] . $products_data . '|' . $this->data["MSG_UI_MERCHANT_PANEL"]   . '|' . $this->data["URL_NOTIFY"] . '|' . $this->data["LOCALE"] . '|' . $this->data["CURRENCY"] . '|' . $this->data["REFERENCE_NUMBER"] . '|' . $this->data["PAYMENT_METHODS"] . $payer_data . '|' . $this->data["AMOUNT"]  . '|' . $this->data["ALG"]));
          } else {
              $this->data['paid'] = true;
          }
        }  
			$this->children = array(
				'common/column_left',
				'common/column_right'
			);   
      
          if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paytrail.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/paytrail.tpl';
          } else {
             $this->template = 'default/template/payment/paytrail.tpl';
          }

            $this->render();
	}
	public function callback() {
          $this->language->load('payment/paytrail');

           // Ladataan paluuarvot
           $payment_id = $this->request->get['PAYMENT_ID'];
           $status = $this->request->get['STATUS']; 
           $amount = $this->request->get['AMOUNT'];
           $method = $this->request->get['PAYMENT_METHOD'];
           $return_authcode = $this->request->get['RETURN_AUTHCODE'];

            $this->load->model('checkout/order');
         if(isset($this->session->data['order_id']) && strlen($return_authcode) > 50 && $amount > 0){
            $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
    
                $this->db->query("UPDATE `" . DB_PREFIX . "order` SET `payment_method` = REPLACE(`payment_method`,'". $order_info['payment_method'] ."','" . $order_info['payment_method'] . " (" . $this->language->get('text_method_' . $method) . ")'), date_modified = NOW() WHERE order_id = '" . (int)$this->session->data['order_id'] . "'"); 

                $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('paytrail_order_status_id'));    
        	$log = new Log("paytrail_log.txt");
        	$log->write($this->language->get('text_paid_success') . $this->language->get('text_method_' . $method) . ', Payment ID: ' . $payment_id);
           }
            $this->redirect($this->url->link('checkout/success'));
	}
        public function confirm() {
         if ($this->session->data['payment_method']['code'] == 'paytrail') {
           $this->load->model('checkout/order');
           $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('paytrail_order_status_id'));
        }
       }
	public function cancel() {
            $this->language->load('payment/paytrail');
            $this->load->model('checkout/order');
         if(isset($this->session->data['order_id'])){
            $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('paytrail_order_cancel_status_id'));

            $log = new Log("paytrail_log.txt");
            $log->write($this->language->get('text_paid_cancel'));
	 }
            $this->redirect($this->url->link('common/home'));
	}
       public function rounder($sum){
          $round = round($sum,2);
          if(strpos($round,'.')){
              $parts = explode('.',$round);
              if(strlen($parts[1]) == 1){
                 return $round . '0';
              }
              return $round;
          } else {
              return $round . '.00';
          }
      }
}
?>
