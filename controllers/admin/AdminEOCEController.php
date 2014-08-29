<?php

require_once(_PS_MODULE_DIR_ . 'extendedorderconfirmationemail/controllers/admin/AdminEOCEPaymentController.php');
require_once(_PS_MODULE_DIR_ . 'extendedorderconfirmationemail/controllers/admin/AdminEOCEShippingController.php');

class AdminEOCEController extends ModuleAdminController {

    public function __construct() {
        $this->bootstrap = true;
        $this->show_toolbar = true;
        $this->show_toolbar_options = true;
        $this->show_page_header_toolbar = true;

        $this->table = 'extended_order_confirmation_email';
        $this->className = 'AdminEOCE';


        $this->admin_eoce_payment = new EOCEPaymentController();
        $this->admin_eoce_payment->init();

        $this->admin_eoce_shipping = new EOCEShippingController();
        $this->admin_eoce_shipping->init();

        $this->lang = true;
        parent::__construct();
    }

    public function postProcess() {
        $this->admin_eoce_payment->postProcess();
        $this->admin_eoce_shipping->postProcess();
    }

    public function initContent() {

        $this->renderPageHeaderToolbar();
        $this->admin_eoce_payment->token = $this->token;
        $this->admin_eoce_shipping->token = $this->token;

        if (Tools::getIsset('add' . $this->admin_eoce_payment->table) || Tools::getIsset('update' . $this->admin_eoce_payment->table))
            $this->content.= $this->admin_eoce_payment->renderForm();
        else if (Tools::getIsset('add' . $this->admin_eoce_shipping->table) || Tools::getIsset('update' . $this->admin_eoce_shipping->table))
            $this->content.= $this->admin_eoce_shipping->renderForm();
        else {

            $this->content .= $this->admin_eoce_payment->renderList();
            $this->content .= $this->admin_eoce_shipping->renderList();
        }

        $this->context->smarty->assign(array(
            'content' => $this->content
        ));
    }

    public function getShipping($echo, $row) {
        $carriers = Carrier::getCarriers($this->context->language->id, true);
        foreach($carriers as $carrier)
            if($row['id_of_type'] == $carrier['id_carrier'])
                return $carrier['name'];
    }

    public function getPayment($echo, $row) {
        $modules = Module::getModulesOnDisk(true);
        foreach ($modules as $module) {
            if ($module->tab == 'payments_gateways' && $module->active && $module->name == $row['id_of_type'])
                return $module->displayName;
        }
    }

    public function getBlock1($echo, $row) {
        return Tools::substr(strip_tags($row['block_1']), 0, 150);
    }

    public function getBlock2($echo, $row) {
        return Tools::substr(strip_tags($row['block_2']), 0, 150);
    }

    public function renderPageHeaderToolbar() {

        $carriers = Carrier::getCarriers($this->context->language->id);
        $inputs = array();
        foreach ($carriers as $carrier) {
            $inputs[] = array(
                'type' => 'text',
                'label' => $carrier['name'],
                'name' => 'carriers[' . $carrier['id_carrier'] . ']'
            );
        }

        $this->admin_eoce_payment->toolbar_btn['new'] = array(
            'href' => self::$currentIndex . '&add' . $this->admin_eoce_payment->table . '&token=' . $this->token,
            'desc' => $this->l('Add/edit blocks')
        );

        $this->admin_eoce_shipping->toolbar_btn['new'] = array(
            'href' => self::$currentIndex . '&add' . $this->admin_eoce_shipping->table . '&token=' . $this->token,
            'desc' => $this->l('Add/edit blocks')
        );

        if (is_array($this->page_header_toolbar_btn) && $this->page_header_toolbar_btn instanceof Traversable || trim($this->page_header_toolbar_title) != '')
            $this->show_page_header_toolbar = true;

        $this->context->smarty->createTemplate(
                $this->context->smarty->getTemplateDir(0) . DIRECTORY_SEPARATOR
                . 'page_header_toolbar.tpl', $this->context->smarty);

        $this->context->smarty->assign(array(
            'show_page_header_toolbar' => $this->show_page_header_toolbar,
            'title' => $this->page_header_toolbar_title,
            'toolbar_btn' => $this->page_header_toolbar_btn,
            'page_header_toolbar_btn' => $this->page_header_toolbar_btn,
            'page_header_toolbar_title' => $this->toolbar_title,
        ));
    }

}
