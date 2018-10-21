<?php
class ControllerPaymentPaytrail extends Controller {
	private $error = array(); 

	public function index() {
		$this->data = array();
		$this->data = array_merge($this->data,$this->load->language('payment/paytrail'));

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {	
			$this->model_setting_setting->editSetting('paytrail', $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect($this->url->link('extension/payment','token=' . $this->session->data['token'],'SSL'));
		}

	   $this->document->addStyle('view/stylesheet/bootstrap.css');
	   $this->document->addStyle('view/stylesheet/font-awesome/css/font-awesome.css');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant_id'])) {
			$this->data['error_merchant_id'] = $this->error['merchant_id'];
		} else {
			$this->data['error_merchant_id'] = '';
		}

 		if (isset($this->error['merchant_secret'])) {
			$this->data['error_secret'] = $this->error['merchant_secret'];
		} else {
			$this->data['error_secret'] = '';
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
       		'href'      => $this->url->link('payment/paytrail','token=' . $this->session->data['token'],'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/paytrail','token=' . $this->session->data['token'],'SSL');		
		$this->data['clear'] = $this->url->link('payment/paytrail/clear','token=' . $this->session->data['token'],'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'],'SSL');
		
		if (isset($this->request->post['paytrail_merchant_id'])) {
			$this->data['paytrail_merchant_id'] = $this->request->post['paytrail_merchant_id'];
		} else {
			$this->data['paytrail_merchant_id'] = $this->config->get('paytrail_merchant_id');
		}

		if (isset($this->request->post['paytrail_merchant_secret'])) {
			$this->data['paytrail_merchant_secret'] = $this->request->post['paytrail_merchant_secret'];
		} else {
			$this->data['paytrail_merchant_secret'] = $this->config->get('paytrail_merchant_secret');
		}

		
		if (isset($this->request->post['paytrail_order_failed_status_id'])) {
			$this->data['paytrail_order_failed_status_id'] = $this->request->post['paytrail_order_failed_status_id'];
		} else {
			$this->data['paytrail_order_failed_status_id'] = $this->config->get('paytrail_order_failed_status_id'); 
		}
		
		if (isset($this->request->post['paytrail_order_status_id'])) {
			$this->data['paytrail_order_status_id'] = $this->request->post['paytrail_order_status_id'];
		} else {
			$this->data['paytrail_order_status_id'] = $this->config->get('paytrail_order_status_id'); 
		}
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['paytrail_geo_zone_id'])) {
			$this->data['paytrail_geo_zone_id'] = $this->request->post['paytrail_geo_zone_id'];
		} else {
			$this->data['paytrail_geo_zone_id'] = $this->config->get('paytrail_geo_zone_id'); 
		} 

		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['paytrail_status'])) {
			$this->data['paytrail_status'] = $this->request->post['paytrail_status'];
		} else {
			$this->data['paytrail_status'] = $this->config->get('paytrail_status');
		}
		
		if (isset($this->request->post['paytrail_sort_order'])) {
			$this->data['paytrail_sort_order'] = $this->request->post['paytrail_sort_order'];
		} else {
			$this->data['paytrail_sort_order'] = $this->config->get('paytrail_sort_order');
		}

		$file = DIR_LOGS . 'paytrail_log.txt';

		if (file_exists($file)) {
			$this->data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		} else {
			$this->data['log'] = '';
		}

	
		$this->template = 'payment/paytrail.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	public function clear() {
		$this->language->load('payment/paytrail');

		$file = DIR_LOGS . 'paytrail_log.txt';

		$handle = fopen($file, 'w+'); 

		fclose($handle); 			

		$this->session->data['success'] = $this->language->get('text_clear_success');

		$this->redirect($this->url->link('payment/paytrail', 'token=' . $this->session->data['token'], 'SSL'));		
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/paytrail')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['paytrail_merchant_id']) {
			$this->error['merchant_id'] = $this->language->get('error_merchant_id');
		}

		if (!$this->request->post['paytrail_merchant_secret']) {
			$this->error['merchant_secret'] = $this->language->get('error_secret');
		}

		return !$this->error;
	}
}
?>
