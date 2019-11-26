<?php
/**
*  @author Taoufiq Ait Ali
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Regphonefield extends Module
{
    public function __construct()
    {
        $this->name          = 'regphonefield';
        $this->tab           = 'front_office_features';
        $this->version       = '1.0.0';
        $this->author        = 'Taoufiq Ait Ali';
        $this->need_instance = 0;
        $this->bootstrap     = true;
        
        parent::__construct();
        
        $this->displayName = $this->l('phone field for registration');
        $this->description = $this->l('Add phone field to registration form.');
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }
    public function install()
    {
        $result = true;
        if (!parent::install()
            || !$this->registerHook('additionalCustomerFormFields')
            || !$this->registerHook('actionCustomerAccountAdd')
            || !$this->registerHook('actionAdminCustomersListingFieldsModifier')
        ) {
             $result = false;
        }

        $res =(bool)Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'customer`  ADD `phone` varchar(64) NULL'
        );
        
        return $result;
    }
    
    public function uninstall()
    {
        if (!parent::uninstall()
        ) {
            return false;
        }
        $res =(bool)Db::getInstance()->execute(
            'ALTER TABLE `'._DB_PREFIX_.'customer` DROP `phone`'
        );
        return true;
    }

    public function hookAdditionalCustomerFormFields($params)
    {
        $formField = new FormField();
        $formField->setName('phone');
        $formField->setType('text');
        $formField->setLabel($this->l('Phone'));
        $formField->setRequired(true);
        return array($formField);
    }
    
    public function hookActionCustomerAccountAdd($params)
    {   
        $customerId =$params['newCustomer']->id;
        $phone= Tools::getValue('phone','');
        return (bool) Db::getInstance()->execute('update '._DB_PREFIX_.'customer set phone=\''.pSQL($phone)."' WHERE id_customer=".(int) $customerId);
    }
    public function hookActionAdminCustomersListingFieldsModifier($params)
    {
        $params['fields']['phone'] = array(
            'title' => $this->l('Phone'),
            'align' => 'center',
        );
    }
}
