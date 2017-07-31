<?php 

include 'sisow/sisow.php';

class ControllerPaymentSisowFijn extends ControllerPaymentSisow {
	public function index() {
		$this->_index('sisowfijn');
	}

	public function validate() {
		return $this->_validate('sisowfijn');
	}
}
?>
