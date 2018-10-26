<?php
class ControllerPaymentPaybyway extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/paybyway');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('paybyway', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_edit'] = $this->language->get('text_edit');
		$this->data['text_clear'] = $this->language->get('text_clear');
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');

		$this->data['entry_failed_status'] = $this->language->get('entry_failed_status');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_total'] = $this->language->get('entry_total');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_api_key'] = $this->language->get('entry_api_key');
		$this->data['entry_private_key'] = $this->language->get('entry_private_key');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_log'] = $this->language->get('entry_log');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

	   $this->document->addStyle('view/stylesheet/bootstrap.css');
	   $this->document->addStyle('view/stylesheet/font-awesome/css/font-awesome.css');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['api_key'])) {
			$this->data['error_api_id'] = $this->error['api_key'];
		} else {
			$this->data['error_api_key'] = '';
		}

 		if (isset($this->error['private_key'])) {
			$this->data['error_private_key'] = $this->error['private_key'];
		} else {
			$this->data['error_private_key'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/paybyway', 'token=' . $this->session->data['token'], 'SSL')
		);

		$this->data['action'] = $this->url->link('payment/paybyway', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['clear'] = $this->url->link('payment/paytrail/clear','token=' . $this->session->data['token'],'SSL');
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['paybyway_api_key'])) {
			$this->data['paybyway_api_key'] = $this->request->post['paybyway_api_key'];
		} else {
			$this->data['paybyway_api_key'] = $this->config->get('paybyway_api_key');
		}

		if (isset($this->request->post['paybyway_order_status_id'])) {
			$this->data['paybyway_order_status_id'] = $this->request->post['paybyway_order_status_id'];
		} else {
			$this->data['paybyway_order_status_id'] = $this->config->get('paybyway_order_status_id');
		}

		if (isset($this->request->post['paybyway_failed_status_id'])) {
			$this->data['paybyway_failed_status_id'] = $this->request->post['paybyway_failed_status_id'];
		} else {
			$this->data['paybyway_failed_status_id'] = $this->config->get('paybyway_failed_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['paybyway_geo_zone_id'])) {
			$this->data['paybyway_geo_zone_id'] = $this->request->post['paybyway_geo_zone_id'];
		} else {
			$this->data['paybyway_geo_zone_id'] = $this->config->get('paybyway_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['paybyway_status'])) {
			$this->data['paybyway_status'] = $this->request->post['paybyway_status'];
		} else {
			$this->data['paybyway_status'] = $this->config->get('paybyway_status');
		}

		if (isset($this->request->post['paybyway_private_key'])) {
			$this->data['paybyway_private_key'] = $this->request->post['paybyway_private_key'];
		} else {
			$this->data['paybyway_private_key'] = $this->config->get('paybyway_private_key');
		}

		if (isset($this->request->post['paybyway_sort_order'])) {
			$this->data['paybyway_sort_order'] = $this->request->post['paybyway_sort_order'];
		} else {
			$this->data['paybyway_sort_order'] = $this->config->get('paybyway_sort_order');
		}

		$file = DIR_LOGS . 'paybyway.log';

		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

		$this->template = 'payment/paybyway.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function clear() {
		$this->language->load('payment/paybyway');

		$file = DIR_LOGS . 'paybyway.log';
        if (file_exists($file) && $this->validateClear()) {
		   $handle = fopen($file, 'w+'); 

	    	fclose($handle); 			

		    $this->session->data['success'] = $this->language->get('text_clear_success');
	   }

		$this->response->redirect($this->url->link('payment/paybyway', 'token=' . $this->session->data['token'], true));		
	}

	protected function validateClear() {
		if (!$this->user->hasPermission('modify', 'payment/paybyway')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paybyway')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paybyway_api_key']) {
			$this->error['api_key'] = $this->language->get('error_api_key');
		}

		if (!$this->request->post['paybyway_private_key']) {
			$this->error['private_key'] = $this->language->get('error_private_key');
		}

		return !$this->error;
	}
}