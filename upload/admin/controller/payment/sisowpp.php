<?php 

include 'sisow/sisow.php';

class ControllerPaymentSisowPP extends ControllerPaymentSisow {
	public function index() {
		$this->_index('sisowpp');
	}

	public function validate() {
		return $this->_validate('sisowpp');
	}
}
?>
