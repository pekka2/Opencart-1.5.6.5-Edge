<?php 

include 'sisow/sisow.php';

class ControllerPaymentSisowIdeal extends ControllerPaymentSisow {
	public function index() {
		$this->_index('sisowideal');
	}

	public function validate() {
		return $this->_validate('sisowideal');
	}
}
?>
