<?php

if (!defined('_PS_VERSION_'))
    exit;

/**
 * Module Order confirmation email extended
 * @copyright 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
require_once(dirname(__FILE__) . '/models/AdminEOCEPayment.php');
require_once(dirname(__FILE__) . '/models/AdminEOCEShipping.php');

class extendedorderconfirmationemail extends Module {

    public function __construct() {
        $this->name = 'extendedorderconfirmationemail';
        $this->tab = 'front_office_features';
        $this->version = '1.0';
        $this->author = 'kuzmany.biz/prestashop';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->l('Extended order confirmation email');
        $this->description = $this->l('Extend confirmation email for customers. New information can depend on shipping and payment.');
        
        Shop::addTableAssociation(AdminEOCEPayment::$definition['table'], array('type' => 'shop'));
        Shop::addTableAssociation(AdminEOCEShipping::$definition['table'], array('type' => 'shop'));
    }

    public function install() {

        if (!parent::install() || !$this->registerHook('actionCarrierUpdate'))
            return false;

        include_once(dirname(__FILE__) . '/init/install_sql.php');
        $this->runSql($sql);

        // Install Tabs
        $this->context->controller->getLanguages();
        $lang_array = array();
        foreach ($this->context->controller->_languages as $language)
            $lang_array[(int) $language['id_lang']] = $this->displayName;
        $id_parent = Tab::getIdFromClassName('AdminParentModules');
        $tab = $this->installAdminTab($lang_array, 'AdminEOCE', $id_parent);
        $id_parent = $tab->id;
        foreach ($this->context->controller->_languages as $language)
            $lang_array[(int) $language['id_lang']] = $this->displayName . ' payment';
        $this->installAdminTab($lang_array, 'AdminEOCEPayment', $id_parent);
        foreach ($this->context->controller->_languages as $language)
            $lang_array[(int) $language['id_lang']] = $this->displayName . ' shipping';
        $tab = $this->installAdminTab($lang_array, 'AdminEOCEShipping', $id_parent);

        return true;
    }

    public function uninstall() {
        if (!parent::uninstall() || !$this->unregisterHook('actionCarrierUpdate'))
            return false;

        include_once(dirname(__FILE__) . '/init/uninstall_sql.php');
        $this->runSql($sql);

        $this->uninstallAdminTab('AdminEOCE');
        $this->uninstallAdminTab('AdminEOCEPayment');
        $this->uninstallAdminTab('AdminEOCEShipping');

        return true;
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
        $shippings = AdminEOCEShipping::getAll($parms);
        foreach ($shippings as $shipping) {
            $parms = array();
            $parms['id_of_type'] = (int) $params['carrier']->id;
            Db::getInstance()->update(AdminEOCEShipping::$definition['table'], $parms, 'id_of_type=' . (int) $shipping['id_of_type']);
        }
    }

    // mass sql runner
    private function runSql($sql) {
        foreach ($sql as $s) {
            if (!Db::getInstance()->Execute($s)) {
                return false;
            }
        }
    }

}
