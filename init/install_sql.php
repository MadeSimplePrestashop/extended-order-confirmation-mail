<?php

$sql = array();
$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extended_order_confirmation_email_payment` (
  `id_extended_order_confirmation_email_payment` int(11) NOT NULL AUTO_INCREMENT,
  `id_of_type` varchar(50),
  PRIMARY KEY (`id_extended_order_confirmation_email_payment`)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';

$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extended_order_confirmation_email_payment_lang` (
  `id_extended_order_confirmation_email_payment` int(11),
  `id_lang` int(3) NOT NULL,
  `block_1` TEXT NOT NULL,
  `block_2` TEXT NOT NULL,
  PRIMARY KEY (`id_extended_order_confirmation_email_payment`,id_lang)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';

$sql[] = ''
        . 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extended_order_confirmation_email_payment_shop` (
      `id_extended_order_confirmation_email_payment` int(10)  NOT NULL,
      `id_shop` int(10) unsigned NOT NULL,
      PRIMARY KEY (`id_extended_order_confirmation_email_payment`, `id_shop`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
        . '';
$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extended_order_confirmation_email_shipping` (
  `id_extended_order_confirmation_email_shipping` int(11) NOT NULL AUTO_INCREMENT,
  `id_of_type` int(11) NOT NULL,
  PRIMARY KEY (`id_extended_order_confirmation_email_shipping`)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';

$sql[] = '
CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extended_order_confirmation_email_shipping_lang` (
  `id_extended_order_confirmation_email_shipping` int(11),
  `id_lang` int(3) NOT NULL,
  `block_1` TEXT NOT NULL,
  `block_2` TEXT NOT NULL,
  PRIMARY KEY (`id_extended_order_confirmation_email_shipping`,id_lang)
) ENGINE = ' . _MYSQL_ENGINE_ . '  ';

$sql[] = ''
        . 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'extended_order_confirmation_email_shipping_shop` (
      `id_extended_order_confirmation_email_shipping` int(10)  NOT NULL,
      `id_shop` int(10) unsigned NOT NULL,
      PRIMARY KEY (`id_extended_order_confirmation_email_shipping`, `id_shop`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
        . '';

foreach ($sql as $s) {
    if (!Db::getInstance()->Execute($s)) {
        return false;
    }
}
?>