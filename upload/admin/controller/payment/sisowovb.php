<?php 

include 'sisow/sisow.php';

class ControllerPaymentSisowOvb extends ControllerPaymentSisow {
	public function index() {
		$this->_index('sisowovb');
	}

	public function validate() {
		return $this->_validate('sisowovb');
	}
}
?>
