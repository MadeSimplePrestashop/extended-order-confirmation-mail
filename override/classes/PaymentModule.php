<?php

/*
 * 2007-2014 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2014 PrestaShop SA
 *  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

require_once(_PS_MODULE_DIR_ . '/extendedorderconfirmationemail/models/AdminEOCEPayment.php');
require_once(_PS_MODULE_DIR_ . '/extendedorderconfirmationemail/models/AdminEOCEShipping.php');

class PaymentModule extends PaymentModuleCore {

    public function validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null) {

        $cart = new Cart($id_cart);
        $parms = array('id_of_type' => (string) $this->name);
        $payment_blocks = AdminEOCEPayment::getAll($parms);
        $extra_vars['{block_1_payment}'] = '';
        $extra_vars['{block_2_payment}'] = '';
        foreach ($payment_blocks as $pb) {
            $extra_vars['{block_1_payment}'] .= $pb['block_1'];
            $extra_vars['{block_2_payment}'] .= $pb['block_2'];
        }

        $parms = array('id_of_type' => $cart->id_carrier);
        $shipping_blocks = AdminEOCEShipping::getAll($parms);
        $extra_vars['{block_1_shipping}'] = '';
        $extra_vars['{block_2_shipping}'] = '';
        foreach ($shipping_blocks as $sb) {
            $extra_vars['{block_1_shipping}'] .= $sb['block_1'];
            $extra_vars['{block_2_shipping}'] .= $sb['block_2'];
        }

        parent::validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method, $message, $extra_vars, $currency_special, $dont_touch_amount, $secure_key, $shop);
    }

}
