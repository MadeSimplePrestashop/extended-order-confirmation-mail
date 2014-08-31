<?php

/**
 * Module Extended Order confirmation email 
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
if (!defined('_PS_VERSION_'))
    exit;

require_once(dirname(__FILE__) . '/models/EOCEPayment.php');
require_once(dirname(__FILE__) . '/models/EOCEShipping.php');

class extendedorderconfirmationemail extends Module {

    public function __construct() {
        $this->name = 'extendedorderconfirmationemail';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'kuzmany.biz/prestashop';
        $this->need_instance = 0;
        $this->module_key = 'f881e7e331cc4f1c314de7f70fe72cd4';

        parent::__construct();

        $this->displayName = $this->l('Extended Order confirmation email');
        $this->description = $this->l('Extend confirmation email for customers. New information can depend on shipping and payment.');

        Shop::addTableAssociation(EOCEPayment::$definition['table'], array('type' => 'shop'));
        Shop::addTableAssociation(EOCEShipping::$definition['table'], array('type' => 'shop'));
    }

    public function install() {

        if (!parent::install() || !$this->registerHook('actionCarrierUpdate'))
            return false;

        include_once(dirname(__FILE__) . '/init/install_sql.php');

// Install Tabs
        $this->context->controller->getLanguages();
        $lang_array = array();
        $id_parent = Tab::getIdFromClassName('AdminParentLocalization');
        foreach ($this->context->controller->_languages as $language)
            $lang_array[(int) $language['id_lang']] = $this->displayName;
        $tab = $this->installAdminTab($lang_array, 'AdminEOCE', $id_parent);
        $id_parent = $tab->id;
//payment controller
        $lang_array = array();
        foreach ($this->context->controller->_languages as $language)
            $lang_array[(int) $language['id_lang']] = $this->displayName . ' payment';
        $this->installAdminTab($lang_array, 'EOCEPayment', $id_parent);
//shipping controller
        $lang_array = array();
        foreach ($this->context->controller->_languages as $language)
            $lang_array[(int) $language['id_lang']] = $this->displayName . ' shipping';
        $tab = $this->installAdminTab($lang_array, 'EOCEShipping', $id_parent);

        return true;
    }

    public function uninstall() {
        if (!parent::uninstall() || !$this->unregisterHook('actionCarrierUpdate'))
            return false;

        include_once(dirname(__FILE__) . '/init/uninstall_sql.php');

        $this->uninstallAdminTab('AdminEOCE');
        $this->uninstallAdminTab('EOCEPayment');
        $this->uninstallAdminTab('EOCEShipping');

        return true;
    }

    public function getContent() {
        Tools::redirectAdmin('index.php?controller=AdminEOCE&token=' . Tools::getAdminTokenLite('AdminEOCE'));
    }

    private function installAdminTab($name, $className, $parent) {
        $tab = new Tab();
        $tab->name = $name;
        $tab->class_name = $className;
        $tab->id_parent = $parent;
        $tab->module = $this->name;
        $tab->add();
        return $tab;
    }

    private function uninstallAdminTab($className) {
        $tab = new Tab((int) Tab::getIdFromClassName($className));
        $tab->delete();
    }

    // set new carrier id
    public function hookActionCarrierUpdate($params) {
        $parms = array();
        $parms['id_of_type'] = $params['id_carrier'];
        $shippings = EOCEShipping::getAll($parms);
        foreach ($shippings as $shipping) {
            $parms = array();
            $parms['id_of_type'] = (int) $params['carrier']->id;
            Db::getInstance()->update(EOCEShipping::$definition['table'], $parms, 'id_of_type=' . (int) $shipping['id_of_type']);
        }
    }

}
