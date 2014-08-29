<?php
/**
 * Module Extended Order confirmation email 
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */

require_once(_PS_MODULE_DIR_ . 'extendedorderconfirmationemail/models/EOCEPayment.php');

class EOCEPaymentController extends ModuleAdminController {

    public function __construct() {
        $this->bootstrap = true;

        $this->table = 'extended_order_confirmation_email_payment';
        $this->className = 'EOCEPayment';

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->lang = true;
        parent::__construct();
    }

    public function renderForm() {

        if (!$this->loadObject(true))
            return;


        $this->fields_form = array(
            'legend' => array(
                'tinymce' => true,
                'title' => $this->l('Add new block depend on payment'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_extended_order_confirmation_email',
                ),
                array(
                    'type' => 'textarea',
                    'wysiwyg' => 1,
                    'lang' => true,
                    'label' => $this->l('Block 1'),
                    'name' => 'block_1',
                    'autoload_rte' => true
                ),
                array(
                    'type' => 'textarea',
                    'wysiwyg' => 1,
                    'lang' => true,
                    'label' => $this->l('Block 2'),
                    'name' => 'block_2',
                    'autoload_rte' => true
                ),
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right',
                'name' => 'submitEditCriterion',
            )
        );

        // load payments
        $payments = array();
        $modules = Module::getModulesOnDisk(true);
        foreach ($modules as $module) {
            if ($module->tab == 'payments_gateways' && $module->active) {
                $payments[] = array(
                    'id_payment' => $module->name,
                    'name' => $module->displayName
                );
            }
        }
        
        $this->fields_form['input'][] = array(
            'type' => 'select',
            'label' => $this->l('Payment'),
            'name' => 'id_of_type',
            'options' => array(
                'query' => $payments,
                'id' => 'id_payment',
                'name' => 'name'
            )
        );


        if (Shop::isFeatureActive()) {
            $this->fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->l('Shop association:'),
                'name' => 'checkBoxShopAsso',
            );
        }

        return parent::renderForm();
    }

    public function renderList() {

        $this->fields_list = array(
            'id_of_type' => array(
                'title' => $this->l('Payment method'),
                'type' => 'text',
                'orderby' => false,
                'search' => false,
                'callback' => 'getPayment'
            ),
            'block_1' => array(
                'title' => $this->l('Block 1'),
                'type' => 'text',
                'orderby' => false,
                'search' => false,
                'callback' => 'getBlock1'
            ),
            'block_2' => array(
                'title' => $this->l('Block 2'),
                'type' => 'text',
                'orderby' => false,
                'search' => false,
                'callback' => 'getBlock2'
            )
        );
        return parent::renderList();
    }

}
