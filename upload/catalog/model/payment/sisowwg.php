<?php 

class ModelPaymentSisowWG extends Model {
	public function getMethod($address = false, $total = false) {
		if (!$this->config->get('sisowwg_status')) {
			return false;
		}
		
		/*if ($this->currency->getCode() != 'EUR') {
			return false;
		}*/
		
		if ($this->config->get('sisowwg_geo_zone_id')) {
      		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('sisowwg_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
			if (!$query->num_rows) {
     	  		return false;
			}	
		}

		if ($total) {
			if ($this->config->get('sisowwg_total') && $total < $this->config->get('sisowwg_total')) {
				return false;
			}
			if ($this->config->get('sisowwg_totalmax') && $total > $this->config->get('sisowwg_totalmax')) {
				return false;
			}
		}
		else if ($total < 0.45) {
                return false;
		}

		$this->language->load('payment/sisowwg');
		$data = array(
			'id'		=> 'sisowwg', // tbv 1.4.x
			'code'		=> 'sisowwg',
			'title'		=> $this->language->get('text_title'),
			'sort_order'	=> $this->config->get('sisowwg_sort_order')
			);
		return $data;
	}
}
?>
