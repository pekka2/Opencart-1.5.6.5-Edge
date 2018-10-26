<?php 
class ModelPaymentPaytrail extends Model {
  	public function getMethod($address) {
		$this->load->language('payment/paytrail');
		
		if ($this->config->get('paytrail_status')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('paytrail_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			
			if (!$this->config->get('paytrail_geo_zone_id')) {
        		$status = true;
      		} elseif ($query->num_rows) {
      		  	$status = true;
      		} else {
     	  		$status = false;
			}	
      	} else {
			$status = false;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'paytrail',
        		'title'      => $this->language->get('text_title'),
				    'terms'      => '',
				    'sort_order' => $this->config->get('paytrail_sort_order')
      		);
    	}
   
    	return $method_data;
   }
	
    public function taxRate($tax_class_id){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class tc INNER JOIN " . DB_PREFIX . "tax_rule tr ON(tc.tax_class_id = tr.tax_class_id) INNER JOIN " . DB_PREFIX . "tax_rate tt ON(tr.tax_rate_id = tt.tax_rate_id) WHERE tc.tax_class_id = '" . (int)$tax_class_id . "'");
          if(!empty($query->row['rate'])){
             return round($query->row['rate'],2);
          }
    }

    public function findShipping($order_id){
        $result = array();
        $q = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND `code` = 'shipping'");

        if(isset($q->row['value']) && $q->row['value'] > 0){
           $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . $order_id . "'");

           $code = explode('.',$query->row['shipping_code']);

           $tax_class_id = $this->config->get($code[0] . '_tax_class_id');

           $result['shipping'] = array('title' => $q->row['title'],
                                       'price' => $q->row['value'],
                                       'tax_class_id' => $tax_class_id,
                                       'tax_rate' => $this->taxRate($tax_class_id)
                                );
         } else {
            $result['shipping'] = array();
         }

        $query2 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND `code` = 'handling'");
         if(isset($query2->row['value']) && $query2->row['value'] > 0){

             $result['handling'] = array('title' => $query2->row['title'],
                                         'price' => $query2->row['value'],
                                         'tax_class_id' => $this->config->get('handling_tax_class_id'),
                                         'tax_rate' => $this->taxRate($this->config->get('handling_tax_class_id'))
                                   );
             } else {
                 $result['handling'] = array();
             }

         $query3 = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND `code` = 'low_order_fee'");
         if(isset($query3->row['value']) && $query3->row['value'] > 0){
              $result['fee'] = array('title' => $query3->row['title'],
                                     'price' => $query3->row['value'],
                                     'tax_class_id' => $this->config->get('low_order_fee_tax_class_id'),
                                     'tax_rate' => $this->taxRate($this->config->get('low_order_fee_tax_class_id'))
                                );
           } else {
               $result['fee'] = array();
           }

          return $result;
    }
}
?>
