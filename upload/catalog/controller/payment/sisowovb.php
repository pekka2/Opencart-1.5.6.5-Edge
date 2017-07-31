<?php

include 'sisow.php';

class ControllerPaymentSisowOvb extends ControllerPaymentSisow {
	protected function index() {
		$this->_index('sisowovb');
	}

	public function notify() {
		$this->_notify('sisowovb');
	}

	public function redirectbank() {
		$this->_redirectbank('sisowovb');
	}
}
?>
