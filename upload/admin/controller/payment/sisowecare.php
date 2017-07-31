<?php 

include 'sisow/sisow.php';

class ControllerPaymentSisowEcare extends ControllerPaymentSisow {
	public function index() {
		$this->_index('sisowecare');
	}

	public function validate() {
		return $this->_validate('sisowecare');
	}
}
?>
