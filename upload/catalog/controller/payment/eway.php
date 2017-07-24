<?php
class ControllerPaymentEway extends Controller {

    protected function index() {
        $this->id = 'payment';
        $this->load->language('payment/eway');
        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $this->data['item_name'] = html_entity_decode($this->config->get('config_store'), ENT_QUOTES, 'UTF-8');
        $amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

        if ($this->config->get('eway_test')) {
            $this->data['text_testing'] = $this->language->get('text_testing');
        }

//        error_reporting(E_ALL);
//        ini_set("display_errors", 1);

	if (defined("JPATH_MIJOSHOP_OC")) {
	    require_once(JPATH_MIJOSHOP_OC . '/catalog/controller/payment/lib/eWAY/RapidAPI.php');
	} else {
	    require_once(DIR_APPLICATION . '/controller/payment/lib/eWAY/RapidAPI.php');
	}

        // Create Access Code Request Object
        $request = new eWAY\CreateAccessCodeRequest();

        $request->Customer->Title = 'Mr.';
        $request->Customer->FirstName = strval($order_info['payment_firstname']);
        $request->Customer->LastName = strval($order_info['payment_lastname']);
        $request->Customer->CompanyName = strval($order_info['payment_company']);
        $request->Customer->JobDescription = '';
        $request->Customer->Street1 = strval($order_info['payment_address_1']);
        $request->Customer->Street2 = strval($order_info['payment_address_2']);
        $request->Customer->City = strval($order_info['payment_city']);
        $request->Customer->State = strval($order_info['payment_zone']);
        $request->Customer->PostalCode = strval($order_info['payment_postcode']);
        $request->Customer->Country = strtolower($order_info['payment_iso_code_2']);
        $request->Customer->Email = $order_info['email'];
        $request->Customer->Phone = $order_info['telephone'];

        // require field
        $request->ShippingAddress->FirstName = strval($order_info['shipping_firstname']);
        $request->ShippingAddress->LastName = strval($order_info['shipping_lastname']);
        $request->ShippingAddress->Street1 = strval($order_info['shipping_address_1']);
        $request->ShippingAddress->Street2 = strval($order_info['shipping_address_2']);
        $request->ShippingAddress->City = strval($order_info['shipping_city']);
        $request->ShippingAddress->State = strval($order_info['shipping_zone']);
        $request->ShippingAddress->PostalCode = strval($order_info['shipping_postcode']);
        $request->ShippingAddress->Country = strtolower($order_info['shipping_iso_code_2']);
        $request->ShippingAddress->Email = $order_info['email'];
        $request->ShippingAddress->Phone = $order_info['telephone'];
        $request->ShippingAddress->ShippingMethod = "Unknown";

        $invoiceDesc = '';
        $products = $this->cart->getProducts();
        foreach ($products as $product) {
	    $item_price = $this->currency->format($product['price'], $order_info['currency_code'], false, false);
	    $item_total = $this->currency->format($product['total'], $order_info['currency_code'], false, false);
            $item = new eWAY\LineItem();
            $item->SKU = $product['product_id'];
            $item->Description = $product['name'];
            $item->Quantity = $product['quantity'];
            $item->UnitCost = $item_price * 100;
            $item->Total = $item_total * 100;
            $request->Items->LineItem[] = $item;
            $invoiceDesc .= $product['name'] . ', ';
        }
        $invoiceDesc = substr($invoiceDesc, 0, -2);
        if (strlen($invoiceDesc) > 64)
            $invoiceDesc = substr($invoiceDesc, 0, 61) . '...';

	$shipping = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $order_info['currency_code'], false, false);

	if ($shipping > 0) {
	    $item = new eWAY\LineItem();
            $item->SKU = '';
            $item->Description = $this->language->get('text_shipping');
            $item->Quantity = 1;
            $item->UnitCost = $shipping * 100;
            $item->Total = $shipping * 100;
            $request->Items->LineItem[] = $item;
	}

        $opt1 = new eWAY\Option();
        $opt1->Value = $this->session->data['order_id'];
        $request->Options->Option[0] = $opt1;

        $request->Payment->TotalAmount = number_format($amount, 2, '.', '') * 100;
        $request->Payment->InvoiceNumber = $this->session->data['order_id'];
        $request->Payment->InvoiceDescription = $invoiceDesc;
        $request->Payment->InvoiceReference = '#' . $order_info['order_id'];
        $request->Payment->CurrencyCode = $order_info['currency_code'];

        $request->RedirectUrl = $this->url->link('payment/eway/callback', '', 'SSL');
        $request->Method = 'ProcessPayment';
        $request->TransactionType = 'Purchase';
	$request->DeviceID = 'opencart-'.VERSION.' eway-trans-1.1.4';
	$request->CustomerIP = $this->request->server['REMOTE_ADDR'];

        // Call RapidAPI
        $__username = html_entity_decode($this->config->get('eway_username'), ENT_QUOTES, 'UTF-8');
        $__password = html_entity_decode($this->config->get('eway_password'), ENT_QUOTES, 'UTF-8');

        $eway_params = array();
        if ($this->config->get('eway_test'))
            $eway_params['sandbox'] = true;
        $service = new eWAY\RapidAPI($__username, $__password, $eway_params);
        $result = $service->CreateAccessCode($request);
        $this->data['payment_type'] = $this->config->get('eway_payment_type');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/eway.tpl')) {
	    $this->template = $this->config->get('config_template') . '/template/payment/eway.tpl';
	} else {
	    $this->template = 'default/template/payment/eway.tpl';
	}

        // Check if any error returns
        if (isset($result->Errors)) {
            // Get Error Messages from Error Code. Error Code Mappings are in the Config.ini file
            $ErrorArray = explode(",", $result->Errors);
            $lblError = "";
            foreach ($ErrorArray as $error) {
                $error = $service->getMessage($error);
                $lblError .= $error . "<br />\n";
	    }
	    $this->log->write('eWAY GetAccessCode Error: '.$lblError);
        }

        if (isset($lblError)) {
            $this->data['error'] = $lblError;
        } else {
            $this->data['action'] = $result->FormActionURL;
            $this->data['AccessCode'] = $result->AccessCode;
        }
        $this->response->setOutput($this->render());
    }

    public function callback() {
        if (isset($this->request->get['AccessCode']) || isset($this->request->get['amp;AccessCode'])) {

	    // Mijoshop
	    if (defined("JPATH_MIJOSHOP_OC")) {
		require_once(JPATH_MIJOSHOP_OC . '/catalog/controller/payment/lib/eWAY/RapidAPI.php');
	    } else {
		require_once(DIR_APPLICATION . '/controller/payment/lib/eWAY/RapidAPI.php');
	    }

            $__username = html_entity_decode($this->config->get('eway_username'), ENT_QUOTES, 'UTF-8');
	    $__password = html_entity_decode($this->config->get('eway_password'), ENT_QUOTES, 'UTF-8');
            $eway_params = array();
            if ($this->config->get('eway_test'))
                $eway_params['sandbox'] = true;
            $service = new eWAY\RapidAPI($__username, $__password, $eway_params);

            $request = new eWAY\GetAccessCodeResultRequest();
            if (isset($this->request->get['amp;AccessCode'])) {
                $request->AccessCode = $this->request->get['amp;AccessCode'];
            } else {
                $request->AccessCode = $this->request->get['AccessCode'];
            }

            // Call RapidAPI to get the result
            $result = $service->GetAccessCodeResult($request);

            $isError = false;
            // Check if any error returns
            if (isset($result->Errors)) {
                $ErrorArray = explode(",", $result->Errors);
                $lblError = "Sorry, there was a problem processing your payment:";
                $isError = true;
                foreach ($ErrorArray as $error) {
                    $error = $service->getMessage($error);
                    $lblError .= "<li>" . $error . "</li>\n";
                }
            }
            if (!$isError) {
                if (!$result->TransactionStatus) {
		    $error_array = explode(", ", $result->ResponseMessage);
		    $lbl_error = '';
		    $log_error = '';
		    foreach ($error_array as $error) {
			// Don't show fraud issues to customers
			if (stripos($error, 'F') === false) {
			    $lbl_error .= $service->getMessage($error);
			} else {
			    $fraud = true;
			}
			$log_error .= $service->getMessage($error) . ", ";
		    }
                    $isError = true;
		    $log_error = substr($log_error, 0, -2);
		    $this->log->write('eWAY payment failed: ' . $log_error);
                    $lblError = "Sorry, your payment was declined: " . $lbl_error;
                }
            }

            if ($isError) {
                $this->language->load('payment/eway');

                $this->data['breadcrumbs'] = array();
                $this->data['breadcrumbs'][] = array(
                    'href' => $this->url->link('common/home'),
                    'text' => $this->language->get('text_home'),
                    'separator' => false
                );
                $this->data['breadcrumbs'][] = array(
                    'href' => $this->url->link('checkout/cart'),
                    'text' => $this->language->get('text_basket'),
                    'separator' => $this->language->get('text_separator')
                );
                $this->data['breadcrumbs'][] = array(
                    'href' => $this->url->link('checkout/checkout', '', 'SSL'),
                    'text' => $this->language->get('text_checkout'),
                    'separator' => $this->language->get('text_separator')
                );

                $this->data['heading_title'] = 'Transaction Failed';
                $this->data['text_message'] = '<div class="content">' . $lblError . '</div>';
                $this->data['button_continue'] = $this->language->get('button_continue');
                $this->data['continue'] = $this->url->link('checkout/checkout&quickconfirm=1');

                if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
                    $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
                } else {
                    $this->template = 'default/template/common/success.tpl';
                }

                $this->children = array(
                    'common/column_left',
                    'common/column_right',
                    'common/content_top',
                    'common/content_bottom',
                    'common/footer',
                    'common/header'
                );

                $this->response->setOutput($this->render());

		// @todo: redirect back to checkout on payment error.
		//$this->session->data['error'] = $lblError;
		//$this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
            } else {
                $order_id = $result->Options[0]->Value;
                $this->load->model('checkout/order');
                $order_info = $this->model_checkout_order->getOrder($order_id);

                $this->load->model('payment/eway');
                $eway_order_data = array(
                    'order_id' => $order_id,
                    'transaction_id' => $result->TransactionID,
                    'amount' => $result->TotalAmount / 100,
                    'debug_data' => json_encode($result),
                );
                $this->model_payment_eway->addOrder($eway_order_data);

		$message = 'Transaction ID: '.$result->TransactionID."\n";
		$message .= 'Authorisation Code: '.$result->AuthorisationCode."\n";
		$message .= 'Card Response Code: '.$result->ResponseCode."\n";

                $this->model_checkout_order->confirm($order_id, $this->config->get('eway_order_status_id'), $message);
                $this->redirect($this->url->link('checkout/success'));
            }
        }
    }

}

?>