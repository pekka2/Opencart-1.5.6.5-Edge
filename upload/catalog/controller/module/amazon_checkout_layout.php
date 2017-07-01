<?php
class ControllerModuleAmazonCheckoutLayout extends Controller {
	public function index($setting) {
		static $module = 0;

		$status = $this->config->get('amazon_checkout_status');

		if ($this->config->get('amazon_checkout_allowed_ips') && !in_array($this->request->server['REMOTE_ADDR'], $this->config->get('amazon_checkout_allowed_ips'))) {
			$status = false;

		} elseif (!$this->cart->hasProducts() || !empty($this->session->data['vouchers']) || $this->cart->hasRecurringProducts()) {
			$status = false;
		}

		if ($status) {
			if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
				if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js');
				} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/de/sandbox/PaymentWidgets.js');
				}
			} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
				if ($this->config->get('amazon_checkout_marketplace') == 'uk') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js');
				} elseif ($this->config->get('amazon_checkout_marketplace') == 'de') {
					$this->document->addScript('https://static-eu.payments-amazon.com/cba/js/de/PaymentWidgets.js');
				}
			}

			$this->data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');

			$this->data['amazon_checkout'] = $this->url->link('payment/amazon_checkout/address', '', 'SSL');

			$this->data['button_colour'] = $this->config->get('amazon_checkout_button_colour');
			$this->data['button_background'] = $this->config->get('amazon_checkout_button_background');
			$this->data['button_size'] = $this->config->get('amazon_checkout_button_size');

			$this->data['align'] = '';

			$this->data['module'] = $module++;

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/amazon_button.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/amazon_button.tpl';

				$this->render();
				
			} else {
			
			// basic 1.5.6 version theme
			
			if ($this->config->get('amazon_checkout_mode') == 'sandbox') {
					$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/sandbox/PaymentWidgets.js';
				} elseif ($this->config->get('amazon_checkout_mode') == 'live') {
					$amazon_payment_js = 'https://static-eu.payments-amazon.com/cba/js/gb/PaymentWidgets.js';
				}

				$this->document->addScript($amazon_payment_js);

				$this->data['amazon_checkout'] = $this->url->link('payment/amazon_checkout/address', '', 'SSL');
				$this->data['amazon_checkout_status'] = true;
				$this->data['merchant_id'] = $this->config->get('amazon_checkout_merchant_id');
				$this->data['button_colour'] = $this->config->get('amazon_checkout_button_colour');
				$this->data['button_background'] = $this->config->get('amazon_checkout_button_background');
				$this->data['button_size'] = $this->config->get('amazon_checkout_button_size');

				$this->data['layout_id'] = $setting['layout_id'];
				$this->data['position'] = $setting['position'];
				
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/amazon_checkout_layout.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/module/amazon_checkout_layout.tpl';
				} else {
					$this->template = 'default/template/module/amazon_checkout_layout.tpl';
				}

				$this->render();
			}
		}
}
?>