<?php
class ModelPaymentPaybyway extends Model {
	public function getMethod($address, $total) {
		$this->load->language('payment/paybyway');

		if ($this->config->get('paybyway_status')) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('paybyway_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (!$this->config->get('paybyway_geo_zone_id')) {
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
				'code'       => 'paybyway',
				'title'      => $this->language->get('text_title'),
				'terms'      => '',
				'sort_order' => $this->config->get('paybyway_sort_order')
			);
		}

		return $method_data;
	}
    public function taxRate($tax_class_id){
        $tax_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "tax_class tc INNER JOIN " . DB_PREFIX . "tax_rule tr ON(tc.tax_class_id = tr.tax_class_id) INNER JOIN " . DB_PREFIX . "tax_rate tt ON(tr.tax_rate_id = tt.tax_rate_id) WHERE tc.tax_class_id = '" . (int)$tax_class_id . "'");
        if(isset($tax_query->row['rate'])){
         return round($tax_query->row['rate'],2);
        }
    }
    public function taxShipping($order_id){
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE `order_id` = '" . $order_id . "'");
        if($query->num_rows){
           $code = explode('.',$query->row['shipping_code']);

           if($code[0] == 'xshipping'){

               $number = str_replace('xshipping','', $code[1]);

               $tax_class_id = $this->config->get('xshipping_tax_class_id' . $number);

           } else {
              $tax_class_id = $this->config->get($code[0] . '_tax_class_id');
           }

           $result = array("tax_rate" => $this->taxRate($tax_class_id), "tax_class_id" => $tax_class_id);

           return $result;
        }
    }

    public function getShipping($order_id){
      $result = array();
      $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND `code` = 'shipping'");
      if(isset($query->row['value']) && $query->row['value'] > 0){
         $result = $query->row;
      }
      return $result;
    }
    public function getHandling($order_id){
      $result = array();
      $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order_total` WHERE `order_id` = '" . $order_id . "' AND `code` = 'handling' OR `order_id` = '" . $order_id . "' AND `code` = 'low_order_fee'");
      if(isset($query->row['value']) && $query->row['value'] > 0){
         $result = $query->row;
      }
      return $result;
    }
}
