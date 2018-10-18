<?php
class ControllerPaymentCheckoutFi extends Controller {
  public function index() {
		$this->data = array();
		$this->data = array_merge($this->data,$this->load->language('payment/checkout_fi'));
		$this->document->setTitle($this->language->get('heading_title'));
	
		$this->data['back'] = $this->url->link('checkout/cart');

    if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/checkout_fi.tpl')) {
      $this->template = $this->config->get('config_template') . '/template/payment/checkout_fi.tpl';
    } else {
      $this->template = 'default/template/payment/checkout_fi.tpl';
    } 

    $this->render(); 
  }
  public function complete(){
        $json = '';

        $co = new CheckoutFi($this->config->get('checkout_fi_merchant'), $this->config->get('checkout_fi_security'));

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        // Order information
        $coData				= array();
        $coData["stamp"]		= time();
        $coData["reference"]	= $this->session->data['order_id'] . $order_info['customer_id'];
        $coData["return"]		= $this->url->link('payment/checkout_fi/callback');
        $coData["delayed"]		= $this->url->link('payment/checkout_fi/callback');
        $coData["reject"]		= $this->url->link('common/home');
        $coData['cancel']       = $this->url->link('checkout/cart');
        $coData["amount"]		= $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], FALSE) * 100; // price in cents
        $coData["delivery_date"] = date( "Ymd", strtotime("+3 day") );
        $coData["message"]       = $this->config->get('config_store') . ' - #' . $this->session->data['order_id'];
        $coData["firstname"]	 = $order_info['firstname'];
        $coData["familyname"]	 = $order_info['lastname'];
        $coData["address"]		= $order_info['shipping_address_1'];
        $coData["postcode"]		= $order_info['shipping_postcode'];
        $coData["postoffice"]	= $order_info['shipping_city'];
        $coData["email"]		= $order_info['email'];
        $coData["phone"]		= $order_info['telephone'];

        $coObject = $co->getCheckoutObject($coData);

        $data = '';
        foreach($coObject as $key=>$value){
            $data .= '&' . $key . '=' . $value;
        }

        $json = $co->sendPost($data);
        $tulos = '';
    
        if(!$json){
            $tulos = 'Yhteys verkkoon ei onnistunut. Ota yhteyttä verkkokaupan ylläpitoon.';
        } else {
             if(strpos($json, 'luonti ei onnistunut')){
                 $tulos = htmlspecialchars($json);
             } else {
                  $pankit = strpos($json,'nordea');
              if($pankit){
                $array = explode("\n",$json);
                for($i=5;$i<count($array);$i++){
                     $tulos .= $array[$i] . "\r\n";
                 }
               } else {
                  $tulos = 'Odottamaton virhe: ' . htmlspecialchars($json);
               }
             }
           $json = $tulos;
        }
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput($json);
  }
  public function callback() {
            $this->load->model('checkout/order');
         
	     $this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('checkout_fi_order_status_id'));
             
             $this->response->redirect($this->url->link('checkout/success'));
  }
}
?>
