<?php

include 'sisow.php';

class ControllerPaymentSisowEcare extends ControllerPaymentSisow {
	protected function index() {
		$this->_index('sisowecare');
	}

	public function notify() {
		$this->_notify('sisowecare');
	}

	public function redirectbank() {
		$this->_redirectbank('sisowecare');
	}
}
?>
