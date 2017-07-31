<?php 

include 'sisow/sisow.php';

class ControllerPaymentSisowMC extends ControllerPaymentSisow {
	public function index() {
		$this->_index('sisowmc');
	}

	public function validate() {
		return $this->_validate('sisowmc');
	}
}
?>
