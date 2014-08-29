<?php
/**
 * Module Extended Order confirmation email 
 * 
 * @author 	kuzmany.biz
 * @copyright 	kuzmany.biz/prestashop
 * @license 	kuzmany.biz/prestashop
 * Reminder: You own a single production license. It would only be installed on one online store (or multistore)
 */
class EOCEPayment extends ObjectModel {

    public $id_extended_order_confirmation_email_payment;
    public $id_of_type;
    public $block_1;
    public $block_2;

    public function __construct($id = null, $id_lang = null, $id_shop = null) {
        self::_init();
        parent::__construct($id, $id_lang, $id_shop);
    }

    private static function _init() {
        if (Shop::isFeatureActive())
            Shop::addTableAssociation(self::$definition['table'], array('type' => 'shop'));
    }

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'extended_order_confirmation_email_payment',
        'primary' => 'id_extended_order_confirmation_email_payment',
        'multilang' => TRUE,
        'fields' => array(
            'id_of_type' => array('type' => self::TYPE_STRING),
            'block_1' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'block_2' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString')
        )
    );

    public static function getAll($parms = array()) {
        self::_init();
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from(self::$definition['table'], 'c');
        $sql->leftJoin(self::$definition['table'] . '_lang', 'l', 'c.' . self::$definition['primary'] . ' = l.' . self::$definition['primary'] . ' AND l.id_lang = ' . (int) Context::getContext()->language->id);
        if (Shop::isFeatureActive())
            $sql->innerJoin(self::$definition['table'] . '_shop', 's', 'c.' . self::$definition['primary'] . ' = s.' . self::$definition['primary'] . ' AND s.id_shop = ' . (int) Context::getContext()->shop->id);
        if (empty($parms) == false)
            foreach ($parms as $k => $p)
                $sql->where('' . $k . ' =\'' . $p . '\'');
        echo $sql->build();
        return Db::getInstance()->executeS($sql);
    }

}

?>