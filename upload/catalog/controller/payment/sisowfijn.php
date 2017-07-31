<?php

include 'sisow.php';

class ControllerPaymentSisowFijn extends ControllerPaymentSisow {
	protected function index() {
		$this->_index('sisowfijn');
	}

	public function notify() {
		$this->_notify('sisowfijn');
	}

	public function redirectbank() {
		$this->_redirectbank('sisowfijn');
	}
}
?>
