<?php

include 'sisow.php';

class ControllerPaymentSisowWG extends ControllerPaymentSisow {
	protected function index() {
		$this->_index('sisowwg');
	}

	public function notify() {
		$this->_notify('sisowwg');
	}

	public function redirectbank() {
		$this->_redirectbank('sisowwg');
	}
}
?>
