<?php

include 'sisow.php';

class ControllerPaymentSisowMC extends ControllerPaymentSisow {
	protected function index() {
		$this->_index('sisowmc');
	}

	public function notify() {
		$this->_notify('sisowmc');
	}

	public function redirectbank() {
		$this->_redirectbank('sisowmc');
	}
}
?>
