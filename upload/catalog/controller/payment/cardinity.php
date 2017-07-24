<?php

/* Cardinity payment for OpenCart
** Cardinity API 1.0v
** Author: ELPAS LT, Ltd, Gintas Korsakas
** http://open-cart.lt
*/

require_once(DIR_PAYMENT.'cardinity/OAuthStore.php');
require_once(DIR_PAYMENT.'cardinity/OAuthRequester.php');


class ControllerPaymentCardinity extends Controller {
	
	private $error = array();

	protected function index() {
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		
		$this->data['action'] = $this->url->link('payment/cardinity/process');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cardinity.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cardinity.tpl';
		} else {
			$this->template = 'default/template/payment/cardinity.tpl';
		}

		$this->render();
	}

	public function process() {

		$this->load->language('payment/cardinity');

		if ( ($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() ) {
			if(isset($this->session->data['order_id'])) {

				$response = $this->pay();
					
					if($response->code == 500) {
						$this->error['warning'] = $response->title;
					}
					
					if( $response->code == 202 || $response->code == 201) {
						$this->db->query("INSERT INTO `".DB_PREFIX."cardinity` SET order_id='".(int)$response->order_id."', payment_id='".$this->db->escape($response->id)."', store_id='".(int)$this->config->get('config_store_id')."', date_added=NOW()");
					}

					if($response->code == 400) {
						
						
						if(isset($response->errors[0]->field)) {
							$this->error['warning'] = $this->language->get($response->errors[0]->field);
						} else {
							$this->error['warning'] = $this->language->get('unknown_error');	
						}
					} else if ( $response->code == 202 ) { //3dsec
						$callback = $this->url->link('payment/cardinity/callback');
						$data = $response->authorization_information->data;
						$url = $response->authorization_information->url;
						$message = $this->language->get('3dMsg');
						echo '<html>
							   <head>
							      <title>3-D Secure</title>
							   </head>
							   <script type="text/javascript">
							      function OnLoadEvent()
							      {
							         document.getElementById("ThreeDForm").submit();
							      }
							   </script>
							   <body onload="OnLoadEvent();">
							      <p>
							        '.$message.'
							      </p>
							      <form name="ThreeDForm" id="ThreeDForm" method="POST" action="'.$url.'">
							         <button type=submit>OK</button>
							         <input type="hidden" name="PaReq" value="'.$data.'" />
							         <input type="hidden" name="TermUrl" value="'.$callback.'" />
							         <input type="hidden" name="MD" value="'.$this->session->data['order_id'].'" />
							      </form>
							   </body>
							</html>
							';
							die;	
					} else if ( $response->code == 201 ) { 
						if($response->status == 'approved') {
							$this->confirm($response->order_id,$response->id);
							$this->redirect($this->url->link('checkout/success'));
						}	
							
					}
			} else {
				$this->error['warning'] = $this->language->get('error_order_not_found');
			}
		}

		$this->getProcessForm();
	}

	public function callback() {
		$this->load->language('payment/cardinity');

		if(isset($_POST['MD']) && $_POST['MD'] != '') {
			$data = array('authorize_data' => $_POST['PaRes']);
			$this->finalizePayment((int)$_POST['MD'], $data);
		} else {
			$this->errorPage( $this->language->get('error_no_data_to_finalize_payment'));
		}
	}
	
	private function errorPage($error) { 
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$this->data['base'] = $server;

		$this->load->language('payment/cardinity');

		$this->data['error_warning'] = $error;
		$this->data['heading_title'] = $this->language->get('error_title');
			
		$this->data['button_back_to_shop'] = $this->language->get('button_back_to_shop');
		$this->data['back_to_shop_url'] = $this->url->link('common/home');
		
		$this->data['template'] = $this->config->get('config_template');	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cardinity_fail.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cardinity_fail.tpl';
		} else {
			$this->template = 'default/template/payment/cardinity_fail.tpl';
		}

		$this->response->setOutput($this->render());

	}


	private function finalizePayment($order_id, $data) {
			$query = $this->db->query("SELECT payment_id FROM `".DB_PREFIX."cardinity` WHERE order_id = '".$order_id."' LIMIT 1");
			$payment_id = $query->row['payment_id'];
			
			if($payment_id != '') {
				$url = 'https://api.cardinity.com/v1/payments/'.$payment_id;
				$response = $this->makeRequest($data, $url, $method = 'PATCH');	

				if ( $response->code == 201 && $response->status == 'approved' ) {
					$this->confirm($response->order_id,$response->id);
					$this->redirect($this->url->link('checkout/success'));
				} else if ($response->code == 402) {
					$this->errorPage( $this->language->get('error_request_failed') );
				} else if ($response->code == 400) {
					$this->errorPage( $this->language->get('error_bad_request') );
				}
			}

	}


	protected function validateForm() {

		$this->error = array();

		if ($this->request->post['holder'] == '') { 
			$this->error['holder'] = $this->language->get('error_required_field');
		}
		
		if ($this->request->post['pan'] == '') { 
			$this->error['pan'] = $this->language->get('error_required_field');
		}

		if ($this->request->post['cvc'] == '') { 
			$this->error['cvc'] = $this->language->get('error_required_field');
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}


	private function getProcessForm() {

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$this->data['base'] = $server;

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}


		if (isset($this->error['holder'])) {
			$this->data['error_holder'] = $this->error['holder'];
		}

		if (isset($this->error['pan'])) {
			$this->data['error_pan'] = $this->error['pan'];
		}

		if (isset($this->error['cvc'])) {
			$this->data['error_cvc'] = $this->error['cvc'];
		}

		$this->data['heading_title'] = $this->language->get('cardinity_heading_title');
		$this->data['button_make_payment'] = $this->language->get('button_make_payment');
		$this->data['cvc_heading'] = $this->language->get('cvc_heading');
		$this->data['cvc_1'] = $this->language->get('cvc_1');
		$this->data['cvc_2'] = $this->language->get('cvc_2');
		$this->data['entry_holder'] = $this->language->get('entry_holder');
		$this->data['entry_pan'] = $this->language->get('entry_pan');
		$this->data['entry_cvc'] = $this->language->get('entry_cvc');
		$this->data['entry_expiry_date'] = $this->language->get('entry_expiry_date');
		$this->data['entry_order_total'] = $this->language->get('entry_order_total');
		
		$this->data['action'] = $this->url->link('payment/cardinity/process');

		if (isset($this->session->data['error']) && !empty($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} else {
			$this->data['error'] = '';
		}

		if (isset($this->request->post['holder'])) {
			$this->data['holder'] = $this->request->post['holder'];
		} else {
			$this->data['holder'] = '';
		}

		if (isset($this->request->post['year'])) {
			$this->data['pyear'] = $this->request->post['year'];
		} else {
			$this->data['pyear'] = '';
		}

		if (isset($this->request->post['month'])) {
			$this->data['pmonth'] = $this->request->post['month'];
		} else {
			$this->data['pmonth'] = '';
		}

		if (isset($this->request->post['pan'])) {
			$this->data['pan'] = $this->request->post['pan'];
		} else {
			$this->data['pan'] = '';
		}

		if (isset($this->request->post['cvc'])) {
			$this->data['cvc'] = $this->request->post['cvc'];
		} else {
			$this->data['cvc'] = '';
		}

		$this->data['order_total'] = '';
		
		if(isset($this->session->data['order_id'])) {
			$this->load->model('checkout/order');
			$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
			$amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);
			$amount = number_format(round($amount, 2), 2, '.', '');
			$this->data['order_total'] = $amount.' '.$order_info['currency_code'];	
		} 

		$this->data['template'] = $this->config->get('config_template');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/cardinity_process.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/cardinity_process.tpl';
		} else {
			$this->template = 'default/template/payment/cardinity_process.tpl';
		}

		$this->response->setOutput($this->render());

	}

	private function pay() {

		$order_id = $this->session->data['order_id'];
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);
		
		if($order_info) {
			
			$currency = $order_info['currency_code'];
			$amount = $this->currency->format($order_info['total'], $currency, $order_info['currency_value'], false);
			$amount = number_format(round($amount, 2), 2, '.', '');
			$country = $order_info['payment_iso_code_2'];
			
			$data = array(
				'amount'             => $amount,
				'currency'           => $currency,
				'order_id'           => 'ID'.$order_id,
				'country'            => $country,
				'payment_method'     => 'card',
				//'description'        => '3d-pass',
				'payment_instrument' => array(
					'pan'       => $this->request->post['pan'],
					'exp_year'  => $this->request->post['year'],
					'exp_month' => $this->request->post['month'],
					'cvc'       => $this->request->post['cvc'],
					'holder'    => $this->request->post['holder']
				)
			);

		return $this->makeRequest($data);

		}
	}


	private function makeRequest($data, $url = 'https://api.cardinity.com/v1/payments', $method = 'POST') {

	$options = array(
			'consumer_key'    => $this->config->get('cardinity_key'),
			'consumer_secret' =>  $this->config->get('cardinity_secret')
		);

		OAuthStore::instance('2Leg', $options);

		$request = new OAuthRequester($url, $method, null);

		$oaheader = $request->getAuthorizationHeader();
		$headers = array('Content-Type: application/json', 'Authorization: '.$oaheader);

		$curl_options = array(
			CURLOPT_URL            => $url,
			CURLOPT_HTTPHEADER     => $headers,
			CURLOPT_POST           => 1,
			CURLOPT_POSTFIELDS     => json_encode($data),
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_SSL_VERIFYPEER => false
		);

		$response = $request->doRequest(0, $curl_options);

		/*$this->load->library('log');
		$log = new log('cardinity'.date('_Y_m_d').'.txt');
		$log->write('--------'.PHP_EOL.print_r($data, true).PHP_EOL.print_r($curl_options, true).PHP_EOL.print_r($response, true).PHP_EOL.'---------');*/

		$response_data  = json_decode($response['body']);
		if(isset($response_data->order_id)) {
			$response_data->order_id = substr($response_data->order_id, 2);
		}
		$response_data->code = $response['code'];	

		return $response_data;
	}


	private function confirm($order_id, $payment_id) {
		$this->load->model('checkout/order');
		$this->model_checkout_order->confirm($order_id, $this->config->get('cardinity_order_status_id'));
		$comment = $payment_id;
		$this->db->query("INSERT INTO ".DB_PREFIX."order_history SET order_id='".(int)$order_id."',order_status_id='".(int)$this->config->get('cardinity_order_status_id')."',notify='0', comment='".$this->db->escape($comment)."', date_added=NOW()");
	}
}
?>