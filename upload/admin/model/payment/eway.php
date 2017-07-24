<?php
class ModelPaymentEway extends Model {
	public function install() {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "eway_order` (
              `eway_order_id` int(11) NOT NULL AUTO_INCREMENT,
              `order_id` int(11) NOT NULL,
              `created` DATETIME NOT NULL,
              `modified` DATETIME NOT NULL,
              `amount` DECIMAL( 10, 2 ) NOT NULL,
              `transaction_id` VARCHAR(24) NOT NULL,
              `debug_data` TEXT,

              `refund_amount` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0',
              `refund_transaction_id` TEXT,

              PRIMARY KEY (`eway_order_id`)
            ) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;");
    }

    public function uninstall() {
        $this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "eway_order`;");
    }

    public function getOrder($order_id) {
        $qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "eway_order` WHERE `order_id` = '" . (int) $order_id . "' LIMIT 1");
        if ($qry->num_rows) {
            $order = $qry->row;
            return $order;
        } else {
            return false;
        }
    }

    public function AddRefundRecord($order, $result) {
        $TransactionID = $result->TransactionID;
        $TotalAmount = $result->Refund->TotalAmount / 100;
        $refund_amount = $order['refund_amount'] + $TotalAmount;

        if (strlen($order['refund_transaction_id'])) $order['refund_transaction_id'] .= ',';
        $order['refund_transaction_id'] .= $TransactionID;

        $this->db->query("UPDATE `" . DB_PREFIX . "eway_order` SET `modified` = NOW(), refund_amount = '" . (double) $refund_amount . "', `refund_transaction_id` = '" . $this->db->escape($order['refund_transaction_id']) . "' WHERE eway_order_id = " . $order['eway_order_id']);
    }
}
?>