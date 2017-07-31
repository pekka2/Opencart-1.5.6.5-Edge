<?php

require_once('sisow.cls5.php');

class ControllerPaymentSisow extends Controller {
	public function _index($payment) {
		$this->language->load('payment/' . $payment);
		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
		if ($payment == 'sisowideal') {
			$this->data['text_bank'] = $this->language->get('text_bank');
			$sisow = new Sisow($this->config->get($payment . '_merchantid'), $this->config->get($payment . '_merchantkey'));
			$sisow->DirectoryRequest($arr, false, $this->config->get($payment . '_testmode') == 'true');
			$this->data['banks'] = $arr;
		}
		else if ($payment == 'sisowecare') {
			$this->data['text_description'] = $this->language->get('text_description');
			$this->data['text_initial'] = substr($order_info['payment_firstname'] . ' ', 0, 1);
			if (isset($this->session->data['sisowecarefee']['fee'])) {
				$fee = $this->session->data['sisowecarefee']['fee'];
				if (isset($this->session->data['sisowecarefee']['feetax'])) {
					$fee += $this->session->data['sisowecarefee']['feetax'];
				}
				$this->data['text_paymentfee'] = str_replace('{fee}', $this->currency->format($fee), $this->language->get('text_paymentfee'));
			}
			else {
				$this->data['text_paymentfee'] = '';
			}
		}
		else if ($payment == 'sisowovb') {
			$this->data['text_ovb'] = $this->language->get('text_ovb');
		}
		
		$this->data['text_header'] = $this->language->get('text_header');
		$this->data['text_redirect'] = $this->language->get('text_redirect');
		$this->data['button_confirm'] = $this->language->get('button_confirm');

		if (substr(VERSION, 0, 3) == '1.4') {
			$this->data['button_back'] = $this->language->get('button_back');
			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
			}
			else {
				$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
			}
			$this->id = 'payment';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/' . $payment . '.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/' . $payment . '.tpl';
		}
		else {
			$this->template = 'default/template/payment/' . $payment . '.tpl';
		}
		$this->render();
	}

	public function callback() {
		if ($this->request->get['status'] == 'Success') {
			if (substr(VERSION, 0, 3) == '1.4') {
				header("Location: " . HTTPS_SERVER . 'index.php?route=checkout/success');
			}
			else {
				header("Location: " . $this->url->link('checkout/success'));
			}
		} 
		else {
			if (substr(VERSION, 0, 3) == '1.4') {
				header("Location: " . HTTPS_SERVER . 'index.php?route=checkout/guest_step_2'); // or payment??
			}
			else {
				header("Location: " . $this->url->link('checkout/checkout'));
			}
		}
	}

	public function _notify($payment) {
		if (isset($this->request->get['trxid'])) {
			$this->load->model('payment/' . $payment);

			$trxid = $this->request->get['trxid'];
			$order_id = $this->request->get['ec'];

			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($order_id);
			
			if ($payment == 'sisowecare' && $this->request->get['action']) {
				if ($this->request->get['action'] == 'invoice') {
					$this->model_checkout_order->confirm($order_id, $order_info['order_status_id']);
					$message = 'Transactie ' . $trxid . ' gecontroleerd door Sisow.<br />';
					$message .= 'Sisow ecare invoice created.';
					$this->model_checkout_order->update($order_id, $order_info['order_status_id'], $message, false);
					echo 'OK';
				}
				else if ($this->request->get['action'] == 'creditinvoice') {
					$this->model_checkout_order->confirm($order_id, 7);
					$message = 'Transactie ' . $trxid . ' gecontroleerd door Sisow.<br />';
					$message .= 'Sisow ecare credit invoice created.';
					$this->model_checkout_order->update($order_id, 7, $message, false);
					echo 'OK';
				}
				else if ($this->request->get['action'] == 'cancelreservation') {
					$this->model_checkout_order->confirm($order_id, 7);
					$message = 'Transactie ' . $trxid . ' gecontroleerd door Sisow.<br />';
					$message .= 'Sisow ecare reservation cancelled.';
					$this->model_checkout_order->update($order_id, 7, $message, false);
					echo 'OK';
				}
				return;
			}

			$sisow = new Sisow($this->config->get($payment . '_merchantid'), $this->config->get($payment . '_merchantkey'));
			if (($ex = $sisow->StatusRequest($trxid)) < 0) {
				$this->log->write($payment . ': StatusRequest ' . $ex . ' ' . $sisow->errorMessage);
				header("Status: 404 Not Found");
				echo 'NOK ' . $ex;
			}
			else {
				if ($sisow->status == 'Success') {
					$this->model_checkout_order->confirm($order_id, $this->config->get($payment . '_status_success'));
					$message = 'Transactie ' . $trxid . ' gecontroleerd door Sisow.<br />';
					if ($payment == 'sisowideal') {
						$message .= 'Bankrekening: ' . $sisow->consumerAccount . '.<br />';
						$message .= 'Ten name van: ' . $sisow->consumerName . '.<br />';
						$message .= 'Plaats: ' . $sisow->consumerCity . '.<br />';
					}
					$this->model_checkout_order->update($order_id, $this->config->get($payment . '_status_success'), $message, false);
					echo 'OK';
				}
			}
		}
	}
	
	private function setOut($json) {
		if (function_exists('json_encode'))
			$this->response->setOutput(json_encode($json));
		else {
			$this->load->library('json');
			$this->response->setOutput(Json::encode($json));
		}
	}

	public function _redirectbank($payment) {
		// controle bankkeuze
		if ($payment == 'sisowideal') {
			if (!$this->request->post['sisowbank']) {
				$json['error'] = 'U heeft geen bank gekozen';
				$this->setOut($json);
				return;
			}
		}
		
		// controle geboortedatum
		if ($payment == 'sisowecare') {
			if (!isset($this->request->post['sisowgender']) || $this->request->post['sisowgender'] == '') {
				$json['error'] = 'U heeft geen aanhef gekozen';
				$this->setOut($json);
				return;
			}
			if (!isset($this->request->post['sisowinitials']) || $this->request->post['sisowinitials'] == '') {
				$json['error'] = 'U heeft geen voorletter(s) ingevuld';
				$this->setOut($json);
				return;
			}
			$day = (int)$this->request->post['sisowday'];
			$month = (int)$this->request->post['sisowmonth'];
			$year = (int)$this->request->post['sisowyear'];
			if ($day < 1 || $day > 31 || $month < 1 || $month > 12 || $year < 0) {
				$json['error'] = 'Geboortedatum niet (correct) ingevuld';
				$this->setOut($json);
				return;
			}
			if ($year < 100) {
				$year += 1900;
			}
			$posts['birthdate'] = sprintf('%02d%02d%04d', $day, $month, $year);
			$posts['gender'] = $this->request->post['sisowgender'];
			$posts['initials'] = $this->request->post['sisowinitials'];
		}
		
		$this->language->load('payment/' . $payment);
		$this->load->model('payment/'. $payment);

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		// Bepaal bedrag
		$amt = $this->_getAmount($order_info, $order_info['total']);
		
		$sisow = new Sisow($this->config->get($payment . '_merchantid'), $this->config->get($payment . '_merchantkey'));
		
		switch ($payment) {
			case 'sisowideal':
				$sisow->issuerId = $this->request->post['sisowbank'];
				break;
			case 'sisowecare':
				$sisow->payment = 'ecare';
				$fee = $feetax = $feetaxrate = 0;
				if (isset($this->session->data['sisowecarefee']['fee'])) {
					$fee = $this->_getAmount($order_info, $this->session->data['sisowecarefee']['fee']);
					if (isset($this->session->data['sisowecarefee']['feetax'])) {
						$feetax = $this->_getAmount($order_info, $this->session->data['sisowecarefee']['feetax']);
					}
					/*if (isset($this->session->data['sisowecarefee']['feetaxrate'])) {
						$feetaxrate = $this->session->data['sisowecarefee']['feetaxrate'];
					}*/
				}
				break;
			case 'sisowovb':
				$sisow->payment = 'overboeking';
				break;
			case 'sisowmc':
				$sisow->payment = 'mistercash';
				break;
			case 'sisowde':
				$sisow->payment = 'sofort';
				break;
			case 'sisowfijn':
				$sisow->payment = 'fijncadeau';
				break;
			case 'sisowwg':
				$sisow->payment = 'webshop';
				break;
			case 'sisowpp':
				$sisow->payment = 'paypalec';
				$fee = $feetax = $feetaxrate = 0;
				if (isset($this->session->data['sisowppfee']['fee'])) {
					$fee = $this->_getAmount($order_info, $this->session->data['sisowppfee']['fee']);
					if (isset($this->session->data['sisowppfee']['feetax'])) {
						$feetax = $this->_getAmount($order_info, $this->session->data['sisowppfee']['feetax']);
					}
				}
				break;
		}
		$sisow->purchaseId = $order_info['order_id'];
		$sisow->description = $this->config->get('config_name') . " order " . $order_info['order_id'];
		$sisow->amount = $amt;
		if (substr(VERSION, 0, 3) == '1.4') {
			//$sisow->amount = $this->currency->format($order_info['total'], $order_info['currency'], $order_info['value'], false);
			$sisow->returnUrl = HTTPS_SERVER . 'index.php?route=payment/' . $payment . '/callback';
			$sisow->notifyUrl = HTTPS_SERVER . 'index.php?route=payment/' . $payment . '/notify';
		}
		else {
			//$sisow->amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
			$sisow->returnUrl = $this->url->link('payment/' . $payment . '/callback');
			$sisow->notifyUrl = $this->url->link('payment/' . $payment . '/notify');
		}
		if ($this->config->get($payment . '_testmode') == 'true') {
			$posts['testmode'] = 'true';
		}
		if ($payment == 'sisowecare') {
			$posts['makeinvoice'] = $this->config->get($payment . '_makeinvoice'); // == 'true';
			$posts['mailinvoice'] = $this->config->get($payment . '_mailinvoice'); // == 'true';
		}
		if ($order_info['customer_id']) {
			$posts['customer'] = $order_info['customer_id'];
		}
		$posts['ipaddress'] = $this->request->server['REMOTE_ADDR'];
		// billing
		$posts['billing_company']	= $order_info['payment_company'];
		$posts['billing_firstname']	= $order_info['payment_firstname'];
		$posts['billing_lastname']	= $order_info['payment_lastname'];
		$posts['billing_address1']	= $order_info['payment_address_1'];
		if (!empty($order_info['payment_address_2']))
			$posts['billing_address2'] = $order_info['payment_address_2'];
		$posts['billing_zip']		= $order_info['payment_postcode'];
		$posts['billing_city']		= $order_info['payment_city'];
		$posts['billing_mail']		= $order_info['email'];
		$posts['billing_phone']		= $order_info['telephone'];
		//$posts['billing_country']	= $order_info['payment_city'];
		// shipping
		$posts['shipping_company']	= $order_info['shipping_company'];
		$posts['shipping_firstname']	= $order_info['shipping_firstname'];
		$posts['shipping_lastname']	= $order_info['shipping_lastname'];
		$posts['shipping_address1']	= $order_info['shipping_address_1'];
		if (!empty($order_info['shipping_address_2']))
			$posts['shipping_address2'] = $order_info['shipping_address_2'];
		$posts['shipping_zip']		= $order_info['shipping_postcode'];
		$posts['shipping_city']		= $order_info['shipping_city'];
		$posts['shipping_mail']		= $order_info['email'];
		$posts['shipping_phone']	= $order_info['telephone'];
		//$posts['shipping_country']	= $order_info['shipping_city'];
		// products
		$products_info = $this->cart->getProducts();
		$i = 1;
		foreach ($products_info as $key => $data) {
			$taxrate = $this->_getRate($data['tax_class_id']);
			$posts['product_id_' . $i] = $data['product_id'];
			$posts['product_description_' . $i] = $data['name'];
			$posts['product_quantity_' . $i] = $data['quantity'];
			$posts['product_weight_' . $i] = round($data['weight'] * 1000, 0);
			$posts['product_netprice_' . $i] = round($this->_getAmount($order_info, $data['price']) * 100, 0);
			$posts['product_nettotal_' . $i] = round($this->_getAmount($order_info, $data['total']) * 100, 0);
			if ($taxrate) {
				$posts['product_taxrate_' . $i] = round($taxrate * 100, 0);
				$posts['product_tax_' . $i] = round($this->_getAmount($order_info, $data['price']) * $taxrate, 0);
				$posts['product_price_' . $i] = round($this->_getAmount($order_info, $data['price']) * ($taxrate / 100 + 1) * 100, 0);
				$posts['product_total_' . $i] = round($this->_getAmount($order_info, $data['total']) * ($taxrate / 100 + 1) * 100, 0);
			}
			$i++;
		}
		// Shipping
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method']) && $this->config->get('shipping_status')) {
			$shipping = $this->_getAmount($order_info, $this->session->data['shipping_method']['cost']);
			$shiptax = $shiptaxrate = 0;
			if ($this->session->data['shipping_method']['tax_class_id']) {
				$shiptaxrate = $this->_getRate($this->session->data['shipping_method']['tax_class_id']);
				$shiptax = round($shipping * $shiptaxrate / 100, 2);
			}
			$posts['product_id_' . $i] = 'shipping';
			$posts['product_description_' . $i] = 'Verzendkosten';
			$posts['product_quantity_' . $i] = 1;
			//$posts['product_weight_' . $i] = round($data['weight'] * 1000, 0);
			$posts['product_netprice_' . $i] = round($shipping * 100, 0);
			$posts['product_nettotal_' . $i] = round($shipping * 100, 0);
			if ($shiptaxrate) {
				$posts['product_taxrate_' . $i] = round($shiptaxrate * 100, 0);
				$posts['product_tax_' . $i] = round($shiptax * 100, 0);
				$posts['product_price_' . $i] = round(($shipping + $shiptax) * 100, 0);
				$posts['product_total_' . $i] = round(($shipping + $shiptax) * 100, 0);
			}
			$i++;
		}
		// Payment fee
		if (($payment == 'sisowecare' || $payment == 'sisowpp') && $fee) {
			$feetaxrate = $this->_getRate($this->config->get($payment . 'fee_tax'));
			$posts['product_id_' . $i] = 'fee';
			$posts['product_description_' . $i] = 'Payment fee';
			$posts['product_quantity_' . $i] = 1;
			//$posts['product_weight_' . $i] = round($data['weight'] * 1000, 0);
			$posts['product_netprice_' . $i] = round($fee * 100, 0);
			$posts['product_nettotal_' . $i] = round($fee * 100, 0);
			if ($feetaxrate) {
				$posts['product_taxrate_' . $i] = round($feetaxrate * 100, 0);
				$posts['product_tax_' . $i] = round($feetax * 100, 0);
				$posts['product_price_' . $i] = round(($fee + $feetax) * 100, 0);
				$posts['product_total_' . $i] = round(($fee + $feetax) * 100, 0);
			}
			$i++;
		}
		
		// Request
		$json = array();
		if (($ex = $sisow->TransactionRequest($posts)) < 0) {
			$this->log->write($payment . ': TransactionRequest ' . $ex . ' ' . $sisow->errorMessage);
			if ($payment == 'sisowecare') {
				$json['error'] = 'Betalen met Sisow ecare is (nu) niet mogelijk.';
			}
			else {
				$json['error'] = $ex . ' ' . $sisow->errorMessage;
			}
		}
		else if ($payment == 'sisowecare') {
			$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('sisowecare_status_success'));
			$message = 'Transactie ' . $sisow->trxId . ' gecontroleerd door Sisow.';
			if ($sisow->invoiceNo) {
				$message .= '<br/>Sisow ecare invoice ' . $sisow->invoiceNo . '';
			}
			else {
				$message .= '<br/><br/><a href="https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/CancelReservationRequest?report=true&merchantid=' . $this->config->get('sisowecare_merchantid') . '&trxid=' . $sisow->trxId . '&sha1=' . sha1($sisow->trxId . $this->config->get('sisowecare_merchantid') . $this->config->get('sisowecare_merchantkey')) . '" target="_blank" onclick="return confirm(\'Bent u zeker? De Sisow ecare reservering wordt geannuleerd!\');">Annuleer Sisow ecare reservering</a>';
			}
			$message .= '<br/><br/><a href="https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/InvoiceRequest?report=true&returnpdf=true&merchantid=' . $this->config->get('sisowecare_merchantid') . '&trxid=' . $sisow->trxId . '&sha1=' . sha1($sisow->trxId . $this->config->get('sisowecare_merchantid') . $this->config->get('sisowecare_merchantkey')) . '" target="_blank" onclick="return confirm(\'Bent u zeker? De Sisow ecare factuur wordt gegenereerd!\');">Maak of open Sisow ecare factuur</a>';
			$message .= '<br/><br/><a href="https://www.sisow.nl/Sisow/iDeal/RestHandler.ashx/CreditInvoiceRequest?report=true&returnpdf=true&merchantid=' . $this->config->get('sisowecare_merchantid') . '&trxid=' . $sisow->trxId . '&sha1=' . sha1($sisow->trxId . $this->config->get('sisowecare_merchantid') . $this->config->get('sisowecare_merchantkey')) . '" target="_blank" onclick="return confirm(\'Bent u zeker? De Sisow ecare factuur wordt gecrediteerd!\');">Maak of open Sisow ecare creditnota</a>';
			$message .= '<br/><br/>';
			$this->model_checkout_order->update($order_info['order_id'], $this->config->get('sisowecare_status_success'), $message, false);
			if (substr(VERSION, 0, 3) == '1.4') {
				$reurl = HTTPS_SERVER . 'index.php?route=checkout/success';
			}
			else {
				$reurl = $this->url->link('checkout/success');
			}
			if (!$reurl) $reurl = 'http://www.sulaco.nl';
			$json['success'] = $reurl;
		}
		else if ($payment == 'sisowmob') {
			$this->model_checkout_order->confirm($order_info['order_id'], $this->config->get('sisowmob_status_success'));
			$message = 'Transactie ' . $sisow->trxId . ' gecontroleerd door Sisow.<br />';
			$this->model_checkout_order->update($order_info['order_id'], 1, $message, false);
			if (substr(VERSION, 0, 3) == '1.4') {
				$reurl = HTTPS_SERVER . 'index.php?route=checkout/success';
			}
			else {
				$reurl = $this->url->link('checkout/success');
			}
			if (!$reurl) $reurl = 'http://www.sulaco.nl';
			$json['success'] = $reurl;
		}
		else if ($payment == 'sisowovb') {
			$this->model_checkout_order->confirm($order_info['order_id'], 1);
			$message = 'Transactie ' . $sisow->trxId . ' gecontroleerd door Sisow.<br />';
			$this->model_checkout_order->update($order_info['order_id'], 1, $message, false);
			if (substr(VERSION, 0, 3) == '1.4') {
				$reurl = HTTPS_SERVER . 'index.php?route=checkout/success';
			}
			else {
				$reurl = $this->url->link('checkout/success');
			}
			if (!$reurl) $reurl = 'http://www.sulaco.nl';
			$json['success'] = $reurl;
		}
		else {
			$json['success'] = $sisow->issuerUrl;
		}
		$this->setOut($json);
	}
	
	private function _getAmount($order_info, $amount) {
		if (substr(VERSION, 0, 3) == '1.4') {
			$amt = $this->currency->format($amount, $order_info['currency'], $order_info['value'], false);
		}
		else {
			$amt = $this->currency->format($amount, $order_info['currency_code'], $order_info['currency_value'], false);
		}
		if ($this->currency->getCode() != 'EUR') {
			$amt = $this->currency->convert($amt, $this->currency->getCode(), 'EUR');
		}
		return $amt;
	}
	
	private function _getRate($tax_class_id) {
		if (method_exists($this->tax, 'getRate')) {
			return $this->tax->getRate($tax_class_id);
		}
		else {
			$tax_rates = $this->tax->getRates(100, $tax_class_id);
			foreach ($tax_rates as $tax_rate) {
				return $tax_rate['amount'];
			}
		}
	}
}
?>
