<?php
/**
 * Created by PhpStorm.
 * User: clever
 * Date: 14/12/18
 * Time: 11:03
 */

class Customer extends CustomerCore
{
    /** @var string phone */
    public $phone;
    
    public function __construct($idStore = null, $idLang = null)
    {
        Self::$definition['fields']['phone']=array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => false, 'size' => 64);
        parent::__construct($idStore, $idLang);
    }


}