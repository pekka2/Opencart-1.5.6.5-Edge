<?php

include 'sisow.php';

class ControllerPaymentSisowIdeal extends ControllerPaymentSisow {
	protected function index() {
		$this->_index('sisowideal');
	}

	public function notify() {
		$this->_notify('sisowideal');
	}

	public function redirectbank() {
		$this->_redirectbank('sisowideal');
	}
}
?>
