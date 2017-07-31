<?php

include 'sisow.php';

class ControllerPaymentSisowPP extends ControllerPaymentSisow {
	protected function index() {
		$this->_index('sisowpp');
	}

	public function notify() {
		$this->_notify('sisowpp');
	}

	public function redirectbank() {
		$this->_redirectbank('sisowpp');
	}
}
?>
