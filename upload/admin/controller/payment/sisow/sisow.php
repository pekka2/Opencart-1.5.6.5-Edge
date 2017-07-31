<?php 

class ControllerPaymentSisow extends Controller {
	public $error = array(); 

	public function _index($payment) {
		$this->language->load('payment/sisow'); // . $payment);
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title_' . $payment));

		// Sisow ecare payment fee tonen bij checkout
		if ($payment == 'sisowecare') {
			$do = false;
			$this->load->model('setting/extension');
			$totals = $this->model_setting_extension->getInstalled('total');
			foreach ($totals as $total) {
				if ($total == 'sisowecarefee') {
					$do = true;
					break;
				}
			}
			if (!$do) {
				$this->model_setting_extension->install('total', 'sisowecarefee');
				//$this->load->model('setting/setting');
				$post['sisowecarefee_sort_order'] = 4;
				$post['sisowecarefee_status'] = 1;
				$this->model_setting_setting->editSetting('sisowecarefee', $post);
			}
		}

		// Sisow PayPal payment fee tonen bij checkout
		if ($payment == 'sisowpp') {
			$do = false;
			$this->load->model('setting/extension');
			$totals = $this->model_setting_extension->getInstalled('total');
			foreach ($totals as $total) {
				if ($total == 'sisowppfee') {
					$do = true;
					break;
				}
			}
			if (!$do) {
				$this->model_setting_extension->install('total', 'sisowppfee');
				//$this->load->model('setting/setting');
				$post['sisowppfee_sort_order'] = 4;
				$post['sisowppfee_status'] = 1;
				$this->model_setting_setting->editSetting('sisowppfee', $post);
			}
		}

		if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
			$this->model_setting_setting->editSetting($payment, $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			if (substr(VERSION, 0, 3) == '1.4') {
				if (substr(VERSION, 0, 5) == '1.4.8' || substr(VERSION, 0, 5) == '1.4.9') {
					$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
				}
				else {
					$this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment');
				}
			}
			else {
				$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}

		$this->data['heading_title'] = $this->language->get('heading_title_' . $payment);

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_none'] = $this->language->get('text_none');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_version'] = $this->language->get('text_version');
		$this->data['text_author'] = $this->language->get('text_author');
		$this->data['text_partner'] = $this->language->get('text_partner');

		$this->data['entry_merchantid'] = $this->language->get('entry_merchantid');
		$this->data['entry_merchantkey'] = $this->language->get('entry_merchantkey');
		$this->data['entry_success'] = $this->language->get('entry_success');
		$this->data['entry_testmode'] = $this->language->get('entry_testmode');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_totalmax'] = $this->language->get('entry_totalmax');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_author'] = $this->language->get('entry_author');
		$this->data['entry_partner'] = $this->language->get('entry_partner');
		$this->data['entry_version_status'] = $this->language->get('entry_version_status');
		if ($payment == 'sisowecare') {
			$this->data['entry_makeinvoice'] = $this->language->get('entry_makeinvoice');
			$this->data['entry_mailinvoice'] = $this->language->get('entry_mailinvoice');
			$this->data['entry_paymentfee'] = $this->language->get('entry_paymentfee');
			$this->data['entry_sisowecarefee_tax'] = $this->language->get('entry_sisowecarefee_tax');
			$this->data['text_none'] = 'Geen'; //$this->language->get('text_none');
			$this->load->model('localisation/tax_class');
			$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		}
		else if ($payment == 'sisowovb') {
			$this->data['entry_businessonly'] = $this->language->get('entry_businessonly');
			$this->data['entry_days'] = $this->language->get('entry_days');
			$this->data['entry_paylink'] = $this->language->get('entry_paylink');
		}
		else if ($payment == 'sisowpp') {
			$this->data['entry_paymentfee'] = $this->language->get('entry_paymentfee');
			$this->data['entry_sisowppfee_tax'] = $this->language->get('entry_sisowppfee_tax');
			$this->data['text_none'] = 'Geen'; //$this->language->get('text_none');
			$this->load->model('localisation/tax_class');
			$this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();
		}

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		}
		else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$this->data['error_username'] = $this->error['username'];
		}
		else {
			$this->data['error_username'] = '';
		}

		$this->data['breadcrumbs'] = array();

		if (substr(VERSION, 0, 3) == '1.4') {
			if (substr(VERSION, 0, 5) == '1.4.8' || substr(VERSION, 0, 5) == '1.4.9') {
				$this->data['breadcrumbs'][] = array(
					'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
					'text' => $this->language->get('text_home'),
					'separator' => false
					);

				$this->data['breadcrumbs'][] = array(
					'href' => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
					'text' => $this->language->get('text_payment'),
					'separator' => ' :: '
					);

				$this->data['breadcrumbs'][] = array(
					'href' => HTTPS_SERVER . "index.php?route=payment/$payment&token=" . $this->session->data['token'],
					'text' => $this->language->get('heading_title_' . $payment),
					'separator' => ' :: '
					);
						
				$this->data['action'] = HTTPS_SERVER . "index.php?route=payment/$payment&token=" . $this->session->data['token'];

				$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];
			}
			else {
				$this->data['breadcrumbs'][] = array(
					'href' => HTTPS_SERVER . 'index.php?route=common/home',
					'text' => $this->language->get('text_home'),
					'separator' => false
					);

				$this->data['breadcrumbs'][] = array(
					'href' => HTTPS_SERVER . 'index.php?route=extension/payment',
					'text' => $this->language->get('text_payment'),
					'separator' => ' :: '
					);

				$this->data['breadcrumbs'][] = array(
					'href' => HTTPS_SERVER . 'index.php?route=payment/' . $payment,
					'text' => $this->language->get('heading_title_' . $payment),
					'separator' => ' :: '
					);
						
				$this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/' . $payment;

				$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment';
			}
		}
		else {
			$this->data['breadcrumbs'][] = array(
				'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'text' => $this->language->get('text_home'),
				'separator' => false
				);

			$this->data['breadcrumbs'][] = array(
				'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
				'text' => $this->language->get('text_payment'),
				'separator' => ' :: '
				);

			$this->data['breadcrumbs'][] = array(
				'href' => $this->url->link("payment/$payment", 'token=' . $this->session->data['token'], 'SSL'),
				'text' => $this->language->get('heading_title_' . $payment),
				'separator' => ' :: '
				);
					
			$this->data['action'] = $this->url->link("payment/$payment", 'token=' . $this->session->data['token'], 'SSL');

			$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		}

		// Merchant ID
		if (isset($this->request->post[$payment . '_merchantid'])) {
			$this->data[$payment . '_merchantid'] = $this->request->post[$payment . '_merchantid'];
		}
		else {
			$this->data[$payment . '_merchantid'] = $this->config->get($payment . '_merchantid');
		}

		// Order status
		if (isset($this->request->post[$payment . '_status_success'])) {
			$this->data[$payment . '_status_success'] = $this->request->post[$payment . '_status_success'];
		}
		else {
			$this->data[$payment . '_status_success'] = $this->config->get($payment . '_status_success'); 
		} 

		// Test mode
		if (isset($this->request->post[$payment . '_testmode'])) {
			$this->data[$payment . '_testmode'] = $this->request->post[$payment . '_testmode'];
		}
		else {
			$this->data[$payment . '_testmode'] = $this->config->get($payment . '_testmode');
		} 

		// Minimum
		if (isset($this->request->post[$payment . '_total'])) {
			$this->data[$payment . '_total'] = $this->request->post[$payment . '_total'];
		} else {
			$this->data[$payment . '_total'] = $this->config->get($payment . '_total'); 
		} 

		// Maximum
		if (isset($this->request->post[$payment . '_totalmax'])) {
			$this->data[$payment . '_totalmax'] = $this->request->post[$payment . '_totalmax'];
		} else {
			$this->data[$payment . '_totalmax'] = $this->config->get($payment . '_totalmax'); 
		} 

		// Merchant Key
		if (isset($this->request->post[$payment . '_merchantkey'])) {
			$this->data[$payment . '_merchantkey'] = $this->request->post[$payment . '_merchantkey'];
		}
		else {
			$this->data[$payment . '_merchantkey'] = $this->config->get($payment . '_merchantkey'); 
		} 
		
		// Geo Zone
		$this->load->model('localisation/geo_zone');
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post[$payment . '_geo_zone_id'])) {
			$this->data[$payment . '_geo_zone_id'] = $this->request->post[$payment . '_geo_zone_id'];
		} else {
			$this->data[$payment . '_geo_zone_id'] = $this->config->get($payment . '_geo_zone_id'); 
		} 

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		// Active y/n
		if (isset($this->request->post[$payment . '_status'])) {
			$this->data[$payment . '_status'] = $this->request->post[$payment . '_status'];
		}
		else {
			$this->data[$payment . '_status'] = $this->config->get($payment . '_status');
		}

		// Sort order
		if (isset($this->request->post[$payment . '_sort_order'])) {
			$this->data[$payment . '_sort_order'] = $this->request->post[$payment . '_sort_order'];
		}
		else {
			$this->data[$payment . '_sort_order'] = $this->config->get($payment . '_sort_order');
		}
		
		if ($payment == 'sisowecare') {
			// Make invoice
			if (isset($this->request->post[$payment . '_makeinvoice'])) {
				$this->data[$payment . '_makeinvoice'] = $this->request->post[$payment . '_makeinvoice'];
			}
			else {
				$this->data[$payment . '_makeinvoice'] = $this->config->get($payment . '_makeinvoice');
			}
			// Mail invoice
			if (isset($this->request->post[$payment . '_mailinvoice'])) {
				$this->data[$payment . '_mailinvoice'] = $this->request->post[$payment . '_mailinvoice'];
			}
			else {
				$this->data[$payment . '_mailinvoice'] = $this->config->get($payment . '_mailinvoice');
			}
			// Payment fee
			if (isset($this->request->post[$payment . '_paymentfee'])) {
				$this->data[$payment . '_paymentfee'] = $this->request->post[$payment . '_paymentfee'];
			}
			else {
				$this->data[$payment . '_paymentfee'] = $this->config->get($payment . '_paymentfee');
			}
			// Payment fee tax class
			if (isset($this->request->post[$payment . 'fee_tax'])) {
				$this->data[$payment . 'fee_tax'] = $this->request->post[$payment . 'fee_tax'];
			}
			else {
				$this->data[$payment . 'fee_tax'] = $this->config->get($payment . 'fee_tax');
			}
		}
		else if ($payment == 'sisowovb') {
			// Business only
			if (isset($this->request->post[$payment . '_businessonly'])) {
				$this->data[$payment . '_businessonly'] = $this->request->post[$payment . '_businessonly'];
			}
			else {
				$this->data[$payment . '_businessonly'] = $this->config->get($payment . '_businessonly');
			}
			// Dagen
			if (isset($this->request->post[$payment . '_days'])) {
				$this->data[$payment . '_days'] = $this->request->post[$payment . '_days'];
			}
			else {
				$this->data[$payment . '_days'] = $this->config->get($payment . '_days');
			}
			// Paylink
			if (isset($this->request->post[$payment . '_paylink'])) {
				$this->data[$payment . '_paylink'] = $this->request->post[$payment . '_paylink'];
			}
			else {
				$this->data[$payment . '_paylink'] = $this->config->get($payment . '_paylink');
			}
		}
		else if ($payment == 'sisowpp') {
			// Payment fee
			if (isset($this->request->post[$payment . '_paymentfee'])) {
				$this->data[$payment . '_paymentfee'] = $this->request->post[$payment . '_paymentfee'];
			}
			else {
				$this->data[$payment . '_paymentfee'] = $this->config->get($payment . '_paymentfee');
			}
			// Payment fee tax class
			if (isset($this->request->post[$payment . 'fee_tax'])) {
				$this->data[$payment . 'fee_tax'] = $this->request->post[$payment . 'fee_tax'];
			}
			else {
				$this->data[$payment . 'fee_tax'] = $this->config->get($payment . 'fee_tax');
			}
		}
		
		$this->template = "payment/$payment.tpl";
		$this->children = array(
			'common/header',
			'common/footer'
		); 

		$this->response->setOutput($this->render(true), $this->config->get('config_compression')); //v1.3.3+
	}

	public function _validate($payment) {
		if (!$this->user->hasPermission('modify', "payment/$payment")) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!@$this->request->post[$payment . '_merchantid']) {
			$this->error['merchantid'] = $this->language->get('error_merchantid');
		}

		if (!@$this->request->post[$payment . '_merchantkey']) {
			$this->error['merchantkey'] = $this->language->get('error_merchantkey');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>
