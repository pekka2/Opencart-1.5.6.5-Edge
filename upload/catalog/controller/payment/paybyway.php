<?php
class ControllerPaymentPaybyway extends Controller {
	protected function index() {

        $this->load->model('checkout/order');
        $this->load->model('payment/paybyway');

        $this->language->load('payment/paybyway');
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['text_loading'] = $this->language->get('text_loading');
		$this->data['continue'] = $this->url->link('checkout/success');
		$this->data['auth'] = $this->url->link('payment/paybyway','action=auth-payment&method=button');
		$this->data['card'] = $this->url->link('payment/paybyway','action=auth-payment&method=card-payment');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $this->data['amount'] = $this->rounder($order_info['total']);
        $this->data['text_card_payment'] = $this->language->get('text_card_payment');
        $this->data['text_success'] = $this->language->get('text_success');
        $this->data['text_pay_button'] = $this->language->get('text_pay_button');
        $this->data['text_failed'] = $this->language->get('text_failed');
        $this->data['text_charging'] = $this->language->get('text_charging');
        $this->data['text_checking'] = $this->language->get('text_checking');
        $this->data['text_token'] = $this->language->get('text_token');
        
        $this->data['entry_card_number'] = $this->language->get('entry_card_number');
        $this->data['entry_month'] = $this->language->get('entry_month');
        $this->data['entry_year'] = $this->language->get('entry_year');
        $this->data['entry_cvv'] = $this->language->get('entry_cvv');
        $this->data['help_cvv'] = $this->language->get('help_cvv');
        $this->data['help_card_number'] = $this->language->get('help_card_number');
        
        $this->data['button_pay'] = $this->language->get('button_pay');
    
	    $this->data['error_create_payment'] = $this->language->get('error_create_payment');
        
        $this->data['action'] = $this->url->link('payment/paybyway','action=check-payment-status&token=', 'SSL');
        $this->data['payment_form'] = false;
        
	    require(DIR_SYSTEM . 'library/bambora.php');

        $payForm = new Bambora\Payform($this->config->get('paybyway_api_key'), $this->config->get('paybyway_private_key'));
        // Start to Actions
         if(isset($this->request->get['action'])){
        	if($this->request->get['action'] == 'auth-payment'){

                $returnUrl = $this->url->link('payment/paybyway/confirm','SSL');

		        $method = isset($this->request->get['method']) ? $this->request->get['method'] : '';
                $amount = $this->rounder($order_info['total']);

		        $payForm->addCharge(array(
			        'order_number' => $this->session->data['order_id'],
			        'amount' => $amount,
			         'currency' => 'EUR'
		        ));

		        $payForm->addCustomer(array(
			       'firstname' => $order_info['firstname'], 
			       'lastname' => $order_info['lastname'], 
			       'address_street' => $order_info['payment_address_1'] ,
			       'address_city' => $order_info['payment_city'],
			       'address_zip' => $order_info['payment_postcode']
		        ));

                $products = $this->cart->getProducts();

                foreach($products as $product){
                    $tax_rate = $this->model_payment_paybyway->taxRate($product['tax_class_id']);
		            $payForm->addProduct(array(
			          'id' => $product['product_id'], 
			          'title' => $product['name'],
			          'count' => $product['quantity'],
			          'pretax_price' => $this->rounder($product['price']),
			          'tax' => $tax_rate,
			          'price' => $this->rounder($this->tax->calculate($product['total'],$product['tax_class_id'])),
			          'type' => 1
		            ));
		  	   }

		       if($this->cart->hasShipping()){
                   $shipping = $this->model_payment_paybyway->getShipping($this->session->data['order_id']);
                  if(!empty($shipping['title'])){
            	     $shipping['title'] = str_replace('<br/>', ', ', $shipping['title']);
            	     $shipping['title'] = str_replace('<br>', ', ', $shipping['title']);

                     $tax_info = $this->model_payment_paybyway->taxShipping($this->session->data['order_id']);

		             $payForm->addProduct(array(
			           'id' => 1, 
			           'title' => $shipping['title'],
			           'count' => 1,
			           'pretax_price' => $this->rounder($shipping['value']),
			           'tax' => $tax_info['tax_rate'],
			           'price' => $this->rounder($this->tax->calculate($shipping['value'], $tax_info['tax_class_id'], true)),
			           'type' => 2
		             ));
		 	      }
		       }
          
               $handling = $this->model_payment_paybyway->getHandling($this->session->data['order_id']);

               if(!empty($handling['title'])){

            	   $handling['title'] = str_replace('<br/>', ', ', $handling['title']);
            	   $handling['title'] = str_replace('<br>', ', ', $handling['title']);

                   $handling_price = $handling['value'];
                   if($this->config->get('handling_status')){
                      $handling_tax_class_id = $this->config->get('handling_tax_class_id');
                   } elseif ($this->config->get('low_order_fee_status')){
                     $handling_tax_class_id = $this->config->get('low_order_fee_tax_class_id');
                   }
                   $handling_tax_percent =  $this->model_payment_paybyway->taxRate($handling_tax_class_id);

		           $payForm->addProduct(array(
			           'id' => 1, 
			           'title' => $handling['title'],
			           'count' => 1,
			           'pretax_price' => $this->rounder($handling['value']),
			           'tax' => $handling_tax_percent,
			           'price' => $this->rounder($this->tax->calculate($handling['value'], $handling_tax_class_id, true)),
			           'type' => 3
		            ));
			    }
          
               $fee = $this->model_payment_paybyway->getLowOrderFee($this->session->data['order_id']);

               if(!empty($fee['title'])){

            	   $fee['title'] = str_replace('<br/>', ', ', $fee['title']);
            	   $fee['title'] = str_replace('<br>', ', ', $fee['title']);

                   $fee_price = $fee['value'];
                   $fee_tax_class_id = $this->config->get('low_order_fee_tax_class_id');
                   $fee_tax_percent =  $this->model_payment_paybyway->taxRate($fee_tax_class_id);

		           $payForm->addProduct(array(
			           'id' => 1, 
			           'title' => $fee['title'],
			           'count' => 1,
			           'pretax_price' => $this->rounder($fee['value']),
			           'tax' => $handling_tax_percent,
			           'price' => $this->rounder($this->tax->calculate($fee['value'], $fee_tax_class_id, true)),
			           'type' => 3
		            ));
			    }

	            if($method === 'card-payment'){
			         $paymentMethod = array(
			    	      'type' => 'card', 
				          'register_card_token' => 0
			         );
	     	    } else {
			         $paymentMethod = array(
			  	         'type' => 'e-payment', 
				         'return_url' => $returnUrl,
				         'notify_url' => $returnUrl,
				         'lang' => 'fi'
			         );

			         if(isset($this->request->get['selected'])){
				          $paymentMethod['selected'] = array(strip_tags($this->request->get['selected']));
			         }
		        }

		        $payForm->addPaymentMethod($paymentMethod);

	 	        try{
		  	       $result = $payForm->createCharge();

			       if($result->result == 0){
				       if($method === 'card-payment'){
					     echo json_encode(array(
						     'token' => $result->token,
						     'url' => $payForm::API_URL . '/charge'
					     ));
				       }
				       else {
					       header('Location: ' . $payForm::API_URL . '/token/' . $result->token);
				      }
			      }else{
				      $error_msg = $this->language->get('error_payment');

				      if(isset($result->errors) && !empty($result->errors)){
					       $error_msg .= $this->language->get('error_validation') . print_r($result->errors, true);
				      } else {
				  	      $error_msg .= $this->language->get('error_keys');
				      }

				      exit($error_msg);
			      }
		      }
		      catch(Bambora\PayformException $e){
			      exit($this->language->get('error_exception') . ' ' . $e->getMessage());
		      }
	      } else if($_GET['action'] === 'check-payment-status'){
	          // Start action from Card Payment form
	     	  try{
		    	$result = $payForm->checkStatusWithToken($this->request->get['token']);

		    	echo $result->result == 0 ? 'success' : 'failed';
	      	  }
	    	  catch(Bambora\PayformException $e){
		    	exit($this->language->get('error_exception') . ' ' . $e->getMessage());
	    	  }
    	  }

	      exit();
        } else if(isset($this->request->get['return-from-pay-page'])){
    	     try{
	        	$result = $payForm->checkReturn($this->request->get);

		        if($result->RETURN_CODE == 0){
			        exit($this->language->get('text_succeeded') . ', <a href="'. $this->url->link('common/home') .'">'.$this->language->get('text_continue').'</a>');	
		         } else {
			        exit($this->language->get('text_is_failed') . ' (RETURN_CODE: ' . $result->RETURN_CODE . '), <a href="'. $this->url->link('common/home') .'">' . $this->language->get('text_continue') . '</a>');
		         }
	         }
	         catch(Bambora\PayformException $e){
		           exit($this->language->get('error_exception') . ' ' . $e->getMessage());
	        }
         }
         $this->data['merchantPaymentMethods'] = array();
         try{
    	    $merchantPaymentMethods = $payForm->getMerchantPaymentMethods();

	        if($merchantPaymentMethods->result != 0){
		        exit($this->language->get('error_create_payment'));
	         }
	         $this->data['merchantPaymentMethods'] = $merchantPaymentMethods;
         }
         catch(Bambora\PayformException $e){
	          exit($this->language->get('error_exception') . ' ' . $e->getMessage());
         }


			$this->children = array(
				'common/column_left',
				'common/column_right'
			);

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paybyway.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/paybyway.tpl';
		} else {
			$this->template = 'default/template/payment/paybyway.tpl';
		}

		$this->render();
	}

	public function confirm() {
         $this->language->load('payment/paybyway');
         $status = $this->request->get['RETURN_CODE'];
         $authcode = $this->request->get['AUTHCODE'];
         $settled = $this->request->get['SETTLED'];
         $order_number = $this->request->get['ORDER_NUMBER'];
         $checking = strtoupper(hash_hmac('sha256', $status . '|' . $order_number .'|' . $settled, $this->config->get('paybyway_private_key')));
		 
			$this->load->model('checkout/order');
            $log = new Log("paybyway.log");
          if($authcode == $checking){
              if($status == 0){
		           $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('paybyway_order_status_id'),$this->language->get('text_success'));
                   $log->write($this->language->get('text_failed_' . $status));
              }else{
                if($status == 1 || $status == 4 || $staus == 10){
		             $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('paybyway_failed_status_id'), $this->language->get('text_failed_' . $status));
                     $log->write($this->language->get('text_failed_' . $status)); 
                  } else {
		             $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('paybyway_failed_status_id'), $this->language->get('text_unknown_failed'));
                    $log->write($this->language->get('text_unknow_failed')); 
                  }
              }
          }
			
			$this->redirect($this->url->link('checkout/success'));
	}

    public function rounder($sum){
      $round = round($sum,2);
       if(strpos($round,'.')){
           $parts = explode('.',$round);
           if(strlen($parts[1]) == 1){
            return str_replace('.','',$round . '0');
           }
           return str_replace('.','',$round);
        } else {
           return $round . '00';
        }
    }
}
