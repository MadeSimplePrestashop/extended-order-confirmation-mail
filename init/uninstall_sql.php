<?php

$sql = array();
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'extended_order_confirmation_email_payment`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'extended_order_confirmation_email_payment_lang`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'extended_order_confirmation_email_payment_shop`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'extended_order_confirmation_email_shipping`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'extended_order_confirmation_email_shipping_lang`';
$sql[] = 'DROP TABLE `' . _DB_PREFIX_ . 'extended_order_confirmation_email_shipping_shop`';

foreach ($sql as $s) {
    if (!Db::getInstance()->Execute($s)) {
        return false;
    }
}
?>