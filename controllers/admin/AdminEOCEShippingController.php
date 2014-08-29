<?php

require_once(_PS_MODULE_DIR_ . 'extendedorderconfirmationemail/models/EOCEShipping.php');

class EOCEShippingController extends ModuleAdminController {

    public function __construct() {
        $this->bootstrap = true;

        $this->table = 'extended_order_confirmation_email_shipping';
        $this->className = 'EOCEShipping';

        $this->addRowAction('edit');
        $this->addRowAction('delete');
        $this->lang = true;
        parent::__construct();
    }

    public function renderForm() {

        if (!($obj = $this->loadObject(true)))
            return;

        $this->fields_form = array(
            'legend' => array(
                'tinymce' => true,
                'title' => $this->l('Add new block depend on shipping'),
                'icon' => 'icon-cogs'
            ),
            'input' => array(
                array(
                    'type' => 'hidden',
                    'name' => 'id_extended_order_confirmation_email_shipping',
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

        // load carriers

        $this->fields_form['input'][] = array(
            'type' => 'select',
            'label' => $this->l('Shipping method'),
            'name' => 'id_of_type',
            'options' => array(
                'query' => Carrier::getCarriers($this->context->language->id),
                'id' => 'id_carrier',
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
                'title' => $this->l('Shipping method'),
                'type' => 'text',
                'orderby' => false,
                'search' => false,
                'callback' => 'getShipping'
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
