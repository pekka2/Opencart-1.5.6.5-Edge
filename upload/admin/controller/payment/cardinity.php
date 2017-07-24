<?php 

/* Cardinity payment for OpenCart
** Cardinity API 1.0v
** Author: ELPAS LT, Ltd, Gintas Korsakas
** http://open-cart.lt
*/

class ControllerPaymentCardinity extends Controller {
	private $error = array(); 

	public function index() { 
		$this->language->load('payment/cardinity');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('cardinity', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');

		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_key'] = $this->language->get('entry_key');	
		$this->data['entry_secret'] = $this->language->get('entry_secret');	
		$this->data['entry_test_mode'] = $this->language->get('entry_test_mode');	

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/cardinity', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/cardinity', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');	

		
		if (isset($this->request->post['cardinity_key'])) {
			$this->data['cardinity_key'] = $this->request->post['cardinity_key'];
		} else {
			$this->data['cardinity_key'] = $this->config->get('cardinity_key'); 
		}

		if (isset($this->request->post['cardinity_secret'])) {
			$this->data['cardinity_secret'] = $this->request->post['cardinity_secret'];
		} else {
			$this->data['cardinity_secret'] = $this->config->get('cardinity_secret'); 
		}

		if (isset($this->request->post['cardinity_total'])) {
			$this->data['cardinity_total'] = $this->request->post['cardinity_total'];
		} else {
			$this->data['cardinity_total'] = $this->config->get('cardinity_total'); 
		}

		if (isset($this->request->post['cardinity_order_status_id'])) {
			$this->data['cardinity_order_status_id'] = $this->request->post['cardinity_order_status_id'];
		} else {
			$this->data['cardinity_order_status_id'] = $this->config->get('cardinity_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['cardinity_geo_zone_id'])) {
			$this->data['cardinity_geo_zone_id'] = $this->request->post['cardinity_geo_zone_id'];
		} else {
			$this->data['cardinity_geo_zone_id'] = $this->config->get('cardinity_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');						

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['cardinity_status'])) {
			$this->data['cardinity_status'] = $this->request->post['cardinity_status'];
		} else {
			$this->data['cardinity_status'] = $this->config->get('cardinity_status');
		}

		if (isset($this->request->post['cardinity_sort_order'])) {
			$this->data['cardinity_sort_order'] = $this->request->post['cardinity_sort_order'];
		} else {
			$this->data['cardinity_sort_order'] = $this->config->get('cardinity_sort_order');
		}

		$this->template = 'payment/cardinity.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/cardinity')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."cardinity` (
			 `cardinity_id` bigint(20) NOT NULL AUTO_INCREMENT,
			 `order_id` bigint(20) NOT NULL,
			 `payment_id` varchar(255) NOT NULL,
			 `store_id` int(11) NOT NULL DEFAULT '0',
			 `date_added` datetime NOT NULL,
			 PRIMARY KEY (`cardinity_id`),
			 KEY `order_id` (`order_id`)
			) DEFAULT CHARSET=utf8
		");
	}


}
?>