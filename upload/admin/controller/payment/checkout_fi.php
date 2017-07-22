<?php
class ControllerPaymentCheckoutFi extends Controller {
	private $error = array(); 

	public function index() {
		$this->data = array();
		$this->data = array_merge($this->data,$this->load->language('payment/checkout_fi'));
		$this->document->setTitle($this->language->get('heading_title'));

		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			
			$this->model_setting_setting->editSetting('checkout_fi', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment','token=' . $this->session->data['token'],'SSL'));
		}

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

 		if (isset($this->error['security'])) {
			$this->data['error_security'] = $this->error['security'];
		} else {
			$this->data['error_security'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('common/home','token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('extension/payment','token=' . $this->session->data['token'],'SSL'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'href'      => $this->url->link('payment/checkout_fi','token=' . $this->session->data['token'],'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/checkout_fi','token=' . $this->session->data['token'],'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'],'SSL');
		
		if (isset($this->request->post['checkout_fi_merchant'])) {
			$this->data['checkout_fi_merchant'] = $this->request->post['checkout_fi_merchant'];
		} else {
			$this->data['checkout_fi_merchant'] = $this->config->get('checkout_fi_merchant');
		}

		if (isset($this->request->post['checkout_fi_security'])) {
			$this->data['checkout_fi_security'] = $this->request->post['checkout_fi_security'];
		} else {
			$this->data['checkout_fi_security'] = $this->config->get('checkout_fi_security');
		}
		
		if (isset($this->request->post['checkout_fi_order_status_id'])) {
			$this->data['checkout_fi_order_status_id'] = $this->request->post['checkout_fi_order_status_id'];
		} else {
			$this->data['checkout_fi_order_status_id'] = $this->config->get('checkout_fi_order_status_id'); 
		} 
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['checkout_fi_geo_zone_id'])) {
			$this->data['checkout_fi_geo_zone_id'] = $this->request->post['checkout_fi_geo_zone_id'];
		} else {
			$this->data['checkout_fi_geo_zone_id'] = $this->config->get('checkout_fi_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['checkout_fi_status'])) {
			$this->data['checkout_fi_status'] = $this->request->post['checkout_fi_status'];
		} else {
			$this->data['checkout_fi_status'] = $this->config->get('checkout_fi_status');
		}
		
		if (isset($this->request->post['checkout_fi_sort_order'])) {
			$this->data['checkout_fi_sort_order'] = $this->request->post['checkout_fi_sort_order'];
		} else {
			$this->data['checkout_fi_sort_order'] = $this->config->get('checkout_fi_sort_order');
		}
		$this->template = 'payment/checkout_fi.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/checkout_fi')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['checkout_fi_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['checkout_fi_security']) {
			$this->error['security'] = $this->language->get('error_security');
		}

		return !$this->error;
	}
}
?>